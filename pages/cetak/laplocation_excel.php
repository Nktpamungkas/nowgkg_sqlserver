<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=change_location".date($_GET['awal']).".xls");//ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda

?>
<?php
$Awal	= isset($_GET['awal']) ? $_GET['awal'] : '';
$Akhir	= isset($_GET['akhir']) ? $_GET['akhir'] : '';
?>
<?php
ini_set("error_reporting", 1);
include"../../koneksi.php";
$data = [];
$no = 1;
$sqlDB21 = "SELECT
	*
FROM
	(
	SELECT
    s.LOTCODE,
    s.TRANSACTIONDATE,
    s.TRANSACTIONTIME,
		TIMESTAMP(s.TRANSACTIONDATE,
		s.TRANSACTIONTIME) AS DATE_TRANSACTION,
		s.ITEMTYPECODE,
		s.ITEMELEMENTCODE,
		TRIM(s.DECOSUBCODE01) AS DECOSUBCODE01,
		TRIM(s.DECOSUBCODE02) AS DECOSUBCODE02,
		TRIM(s.DECOSUBCODE03) AS DECOSUBCODE03,
		TRIM(s.DECOSUBCODE04) AS DECOSUBCODE04,
		s.LOGICALWAREHOUSECODE,
		s.WAREHOUSELOCATIONCODE AS LOCATION_AFTER,
		b.WAREHOUSELOCATIONCODE AS LOCATION_BEFORE,
		s.CREATIONUSER,
    s.USERPRIMARYQUANTITY,
		s.USERPRIMARYUOMCODE,
		ROW_NUMBER() OVER ( PARTITION BY s.ITEMELEMENTCODE
	ORDER BY
		TIMESTAMP(s.TRANSACTIONDATE,
		s.TRANSACTIONTIME) DESC ) AS rn
	FROM
		STOCKTRANSACTION s
	LEFT JOIN (
		SELECT
			TRANSACTIONNUMBER,
			ITEMTYPECODE,
			ITEMELEMENTCODE,
			DECOSUBCODE01,
			DECOSUBCODE02,
			DECOSUBCODE03,
			DECOSUBCODE04,
			WAREHOUSELOCATIONCODE
		FROM
			STOCKTRANSACTION s
		WHERE
			s.TEMPLATECODE = '301'
			AND s.ITEMTYPECODE = 'KGF'
			AND s.LOGICALWAREHOUSECODE = 'M021') b ON
		b.ITEMELEMENTCODE = s.ITEMELEMENTCODE
		AND b.TRANSACTIONNUMBER = s.TRANSACTIONNUMBER
	WHERE
		(TIMESTAMP(s.TRANSACTIONDATE,
		s.TRANSACTIONTIME) BETWEEN TIMESTAMP('$Awal') AND TIMESTAMP('$Akhir'))
		AND s.TEMPLATECODE = '302'
		AND s.ITEMTYPECODE = 'KGF'
		AND s.LOGICALWAREHOUSECODE = 'M021'
	ORDER BY
		TIMESTAMP(s.TRANSACTIONDATE,
		s.TRANSACTIONTIME) DESC
) x
WHERE
	x.rn = 1";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){
      $sql2 = "SELECT
                ITEMELEMENTCODE,
                TRIM(ORDERCODE) || ' - ' || TRIM(ORDERLINE) AS BON,
                DATE_TRANSACTION
              FROM
                (
                SELECT
                  s.*,
                  (s.TRANSACTIONDATE) AS DATE_TRANSACTION, 
                  ROW_NUMBER() OVER ( PARTITION BY s.ITEMELEMENTCODE
                ORDER BY
                  TIMESTAMP(s.TRANSACTIONDATE,
                  s.TRANSACTIONTIME) DESC ) AS rn
                FROM
                  STOCKTRANSACTION s
                WHERE
                  s.TEMPLATECODE = '204' ) x
              WHERE
                rn = 1
                AND x.ITEMELEMENTCODE ='$rowdb21[ITEMELEMENTCODE]'";
	$stmt2   = db2_exec($conn1,$sql2, array('cursor'=>DB2_SCROLLABLE));
  $rowdb22 = db2_fetch_assoc($stmt2);

  $sql3 = "SELECT
              ad.VALUESTRING AS NO_MESIN
            FROM
              PRODUCTIONDEMAND pd
            LEFT OUTER JOIN ADSTORAGE ad ON
              ad.UNIQUEID = pd.ABSUNIQUEID
              AND ad.NAMENAME = 'MachineNo'
            WHERE
              pd.CODE = '$rowdb21[LOTCODE]'
            GROUP BY
              ad.VALUESTRING";
	$stmt3   = db2_exec($conn1,$sql3, array('cursor'=>DB2_SCROLLABLE));
  $rowdb23 = db2_fetch_assoc($stmt3);
  // $no ++;

  if($rowdb21['TRANSACTIONTIME']>='07:00:00' && $rowdb21['TRANSACTIONTIME']<='15:00:00'){
    $shift = 'Shift 1';
  }else if($rowdb21['TRANSACTIONTIME']>='15:00:00' && $rowdb21['TRANSACTIONTIME']<='23:00:00'){
    $shift = 'Shift 2';
  } else {
    $shift = 'Shift 3';
  }

   $nobon = $rowdb22['BON'];

  $data = [
    'date'          => $rowdb22['DATE_TRANSACTION'],
    'nobon'         => $nobon,
    'code'          => $rowdb21['DECOSUBCODE02'] . $rowdb21['DECOSUBCODE03'] .' '. $rowdb21['DECOSUBCODE04'],
    'shift'         => $shift,
    'mesin'         => $rowdb23['NO_MESIN'],
    'qty'           => (float)$rowdb21['USERPRIMARYQUANTITY'],
    'lokasi_after'  => $rowdb21['LOCATION_AFTER'],
    'lokasi_before' => $rowdb21['LOCATION_BEFORE'],
    'user'          => $rowdb21['CREATIONUSER'],
  ];

  // --- PROSES GROUPING BERDASARKAN NOBON ---
  if (!isset($group_data[$nobon])) {
    $group_data[$nobon] = [
      'nobon'         => $data['nobon'],
      'date'          => $data['date'],
      'code'          => $data['code'],
      'shift'         => $data['shift'],
      'mesin'         => [$data['mesin']],
      'qty'           => $data['qty'],
      'roll'          => 1,
      'lokasi_after'  => [$data['lokasi_after']],
      'user'          => [$data['user']],
    ];
  } else {

    $group_data[$nobon]['qty'] += $data['qty'];
    $group_data[$nobon]['roll'] += 1;

    if (!in_array($data['lokasi_after'], $group_data[$nobon]['lokasi_after'])) {
      $group_data[$nobon]['lokasi_after'][] = $data['lokasi_after'];
    }

    if (!in_array($data['mesin'], $group_data[$nobon]['mesin'])) {
      $group_data[$nobon]['mesin'][] = $data['mesin'];
    }
    
    if (!in_array($data['user'], $group_data[$nobon]['user'])) {
      $group_data[$nobon]['user'][] = $data['user'];
    }
  }
}

foreach ($group_data as &$item) {
  $item['lokasi_after'] = implode(', ', $item['lokasi_after']);
  $item['user']         = implode(', ', $item['user']);
  $item['mesin']         = implode(', ', $item['mesin']);
}
unset($item);

// print_r($group_data);
?>

<div style="display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
    <!-- Logo -->
    <div style="margin-right: 20px;">
    </div>
    <!-- Judul -->
    <div style="text-align: center;">
        <h1 style="margin: 0;">LAPORAN CHANGE LOCATION GKG</h1>
        <!-- <p>NO. REVISI:<br/>TGL Terbit:</p> -->
    </div>
</div>

<div align="LEFT">TGL : <?php echo date($_GET['awal']); ?></div>
<table width="125%" border="1" align="center">
  <tr align="center">
    <td><b>No</b></td>
    <td><b>Tanggal</b></td>
    <td><b>No Bon</b></td>
    <td><b>Code</b></td>
    <td><b>Shift</b></td>
    <td><b>Mesin Rajut</b></td>
    <td><b>Roll</b></td>
    <td><b>Berat (Kg)</b></td>
    <td><b>Lokasi</b></td>
    <td><b>User Change Lokasi</b></td>
  </tr>

  <?php 
  $no = 1;
  $total_qty = 0;
  $total_roll = 0;

  foreach ($group_data as $row) {
    $total_qty  += $row['qty'];
    $total_roll += $row['roll'];
  ?>
    <tr align="center">
      <td><?= $no++; ?></td>
      <td><?= htmlspecialchars($row['date']); ?></td>
      <td><?= htmlspecialchars($row['nobon']); ?></td>
      <td><?= htmlspecialchars($row['code']); ?></td>
      <td><?= htmlspecialchars($row['shift']); ?></td>
      <td><?= htmlspecialchars($row['mesin']); ?></td>
      <td><?= htmlspecialchars($row['roll']); ?></td>
      <td><?= number_format($row['qty'], 2); ?></td>
      <td><?= htmlspecialchars($row['lokasi_after']); ?></td>
      <td><?= htmlspecialchars($row['user']); ?></td>
    </tr>
  <?php } ?>

  <tr align="right">
    <td colspan="6"><b>Total</b></td>
    <td align="center"><b><?= $total_roll; ?></b></td>
    <td align="center"><b><?= number_format($total_qty, 2); ?></b></td>
    <td colspan="2"></td>
  </tr>
</table>


        


