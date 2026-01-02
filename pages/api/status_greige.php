<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../koneksi.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);
if (!isset($data['items']) || !is_array($data['items']) || empty($data['items'])) {
    echo json_encode(['data' => []]);
    exit;
}

// Dedup input
$items = [];
foreach ($data['items'] as $row) {
    if (!isset($row['ord']) || !isset($row['line'])) {
        continue;
    }
    $ord = trim($row['ord']);
    $line = trim($row['line']);
    if ($ord === '' || $line === '') {
        continue;
    }
    $items[$ord . '|' . $line] = ['ord' => $ord, 'line' => $line];
}

if (empty($items)) {
    echo json_encode(['data' => []]);
    exit;
}

// Build VALUES list for DB2
$values = [];
foreach ($items as $it) {
    $ordEsc = str_replace("'", "''", $it['ord']);
    $lineEsc = str_replace("'", "''", $it['line']);
    $values[] = "('{$ordEsc}','{$lineEsc}')";
}
$valuesSql = implode(',', $values);

$sql = "
WITH PAIRS(ORDERCODE, ORDERLINE) AS (VALUES {$valuesSql}),
ELS AS (
  SELECT DISTINCT p.ORDERCODE, p.ORDERLINE, st.ITEMELEMENTCODE
  FROM PAIRS p
  JOIN STOCKTRANSACTION st ON st.ORDERCODE = p.ORDERCODE AND st.ORDERLINE = p.ORDERLINE
  WHERE st.LOGICALWAREHOUSECODE = 'M021'
    AND st.TEMPLATECODE = '204'
),
INBAL AS (
  SELECT e.ORDERCODE, e.ORDERLINE, b.ELEMENTSCODE
  FROM BALANCE b
  JOIN ELS e ON e.ITEMELEMENTCODE = b.ELEMENTSCODE
),
LAST302 AS (
  SELECT ITEMELEMENTCODE,
         CREATIONUSER,
         ROW_NUMBER() OVER(PARTITION BY ITEMELEMENTCODE ORDER BY TRANSACTIONDATE DESC) AS RN
  FROM STOCKTRANSACTION
  WHERE TEMPLATECODE = '302'
    AND ITEMELEMENTCODE IN (SELECT ELEMENTSCODE FROM INBAL)
)
SELECT ib.ORDERCODE, ib.ORDERLINE,
       COUNT(DISTINCT ib.ELEMENTSCODE) AS CNT_BALANCE,
       SUM(CASE WHEN l.ITEMELEMENTCODE IS NULL THEN 1 ELSE 0 END) AS CNT_MISS302,
       MAX(CASE WHEN l.RN = 1 THEN l.CREATIONUSER END) AS LAST_USER
FROM INBAL ib
LEFT JOIN LAST302 l ON l.ITEMELEMENTCODE = ib.ELEMENTSCODE AND l.RN = 1
GROUP BY ib.ORDERCODE, ib.ORDERLINE";

$stmt = db2_prepare($conn1, $sql);
db2_execute($stmt);
$result = [];
while ($r = db2_fetch_assoc($stmt)) {
    $key = $r['ORDERCODE'] . '|' . $r['ORDERLINE'];
    $result[$key] = [
        'balance' => (int)$r['CNT_BALANCE'],
        'missing' => (int)$r['CNT_MISS302'],
        'user' => $r['LAST_USER'],
    ];
}

echo json_encode(['data' => $result]);
