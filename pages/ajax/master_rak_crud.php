<?php
// pages/ajax/master_rak_crud.php
header('Content-Type: application/json; charset=utf-8');
require_once '../../koneksi.php';

$conn = $con ?? null;

if (!$conn) {
  http_response_code(500);
  echo json_encode(['success'=>false, 'message'=>'SQL Server connection ($con) tidak tersedia.']);
  exit;
}

$action = $_GET['action'] ?? '';

function json_ok($data = [], $extra = []) {
  echo json_encode(array_merge(['success'=>true,'data'=>$data], $extra)); exit;
}
function json_err($msg = 'Error') {
  echo json_encode(['success'=>false,'message'=>$msg]); exit;
}

function num($v){ return is_numeric($v) ? (0 + $v) : null; }
function strv($v){ return trim((string)$v); }

/** Normalisasi desimal toleran koma/titik (defense in depth) */
if (!function_exists('dec')) {
  function dec($v){
    $s = trim((string)$v);
    if ($s === '') return null;
    $s = str_replace(' ', '', $s);
    $hasComma = strpos($s, ',') !== false;
    $hasDot   = strpos($s, '.') !== false;

    if ($hasComma && $hasDot) {
      if (strrpos($s, ',') > strrpos($s, '.')) { // 1.234,56
        $s = str_replace('.', '', $s);
        $s = str_replace(',', '.', $s);
      } else {                                   // 1,234.56
        $s = str_replace(',', '', $s);
      }
    } else {
      $s = str_replace(',', '.', $s); // 123,45 -> 123.45
    }
    return is_numeric($s) ? (float)$s : null;
  }
}

/* ===== LIST ===== */
if ($action === 'list') {
  $sql = "SELECT id, RTRIM(location) AS location, max_capacity
          FROM dbnow_gkg.dbnow_gkg.tbl_master_rak WITH (NOLOCK)
          ORDER BY id DESC";
  $stmt = sqlsrv_query($conn, $sql);
  if ($stmt === false) {
    json_err('Gagal load data');
  }

  $rows = [];
  while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    // Normalisasi max_capacity jika perlu
    if (is_array($r['max_capacity'])) { $r['max_capacity'] = $r['max_capacity'][0] ?? 0; }
    $rows[] = $r;
  }
  json_ok($rows);
}

/* ===== GET ===== */
if ($action === 'get') {
  $id = num($_POST['id'] ?? null);
  if (!$id) json_err('ID tidak valid');

  $sql = "SELECT id, RTRIM(location) AS location, max_capacity
          FROM dbnow_gkg.dbnow_gkg.tbl_master_rak WITH (NOLOCK)
          WHERE id = ?";
  $stmt = sqlsrv_query($conn, $sql, [$id]);
  if ($stmt === false) json_err('Gagal mengambil data');

  $d = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
  if (!$d) json_err('Data tidak ditemukan');

  if (is_array($d['max_capacity'])) { $d['max_capacity'] = $d['max_capacity'][0] ?? 0; }

  json_ok($d);
}

/* ===== CREATE ===== */
if ($action === 'create') {
  $location = strtoupper(substr(strv($_POST['location'] ?? ''), 0, 10));
  $max_capacity = dec($_POST['max_capacity'] ?? 900);

  if (!$location) json_err('Location wajib diisi');
  if ($max_capacity === null) $max_capacity = 900;

  // Cek duplikat by location (ignoring padding)
  $cek = sqlsrv_query($conn,
    "SELECT 1 FROM dbnow_gkg.dbnow_gkg.tbl_master_rak WITH (NOLOCK) WHERE RTRIM(location) = ?",
    [$location]
  );
  if ($cek && sqlsrv_fetch($cek)) json_err('Location sudah ada');

  $sql = "INSERT INTO dbnow_gkg.dbnow_gkg.tbl_master_rak (location, max_capacity) VALUES (?, ?)";
  $stmt = sqlsrv_query($conn, $sql, [$location, $max_capacity]);
  if ($stmt === false) {
    $e = sqlsrv_errors();
    json_err('Gagal menyimpan' . ($e ? ' : '.$e[0]['message'] : ''));
  }

  json_ok();
}

/* ===== UPDATE ===== */
if ($action === 'update') {
  $id = num($_POST['id'] ?? null);
  $location = strtoupper(substr(strv($_POST['location'] ?? ''), 0, 10));
  $max_capacity = dec($_POST['max_capacity'] ?? 900);

  if (!$id) json_err('ID tidak valid');
  if (!$location) json_err('Location wajib diisi');
  if ($max_capacity === null) $max_capacity = 900;

  // Cek duplikat selain dirinya
  $cek = sqlsrv_query($conn,
    "SELECT 1 FROM dbnow_gkg.dbnow_gkg.tbl_master_rak WITH (NOLOCK) WHERE RTRIM(location) = ? AND id <> ?",
    [$location, $id]
  );
  if ($cek && sqlsrv_fetch($cek)) json_err('Location sudah terpakai rak lain');

  $sql = "UPDATE dbnow_gkg.dbnow_gkg.tbl_master_rak
          SET location = ?, max_capacity = ?
          WHERE id = ?";
  $stmt = sqlsrv_query($conn, $sql, [$location, $max_capacity, $id]);
  if ($stmt === false) {
    $e = sqlsrv_errors();
    json_err('Gagal mengubah' . ($e ? ' : '.$e[0]['message'] : ''));
  }

  json_ok();
}

/* ===== DELETE ===== */
if ($action === 'delete') {
  $id = num($_POST['id'] ?? null);
  if (!$id) json_err('ID tidak valid');

  $sql = "DELETE FROM dbnow_gkg.dbnow_gkg.tbl_master_rak WHERE id = ?";
  $stmt = sqlsrv_query($conn, $sql, [$id]);
  if ($stmt === false) {
    $e = sqlsrv_errors();
    json_err('Gagal menghapus' . ($e ? ' : '.$e[0]['message'] : ''));
  }

  json_ok();
}

/* ===== BULK UPDATE: set semua max_capacity ===== */
if ($action === 'bulk_update_capacity') {
  $max_capacity = dec($_POST['max_capacity'] ?? '');
  if ($max_capacity === null) json_err('Nilai max_capacity tidak valid');
  if ($max_capacity < 0) json_err('Max Capacity harus >= 0');

  $sql = "UPDATE dbnow_gkg.dbnow_gkg.tbl_master_rak SET max_capacity = ?";
  $stmt = sqlsrv_query($conn, $sql, [$max_capacity]);
  if ($stmt === false) {
    $e = sqlsrv_errors();
    json_err('Gagal mengubah semua data' . ($e ? ' : '.$e[0]['message'] : ''));
  }

  $affected = sqlsrv_rows_affected($stmt);
  json_ok([], ['affected' => $affected]);
}

json_err('Action tidak dikenali');
