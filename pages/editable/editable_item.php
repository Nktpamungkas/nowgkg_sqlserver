<?PHP
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
$ip_num = $_SERVER['REMOTE_ADDR'];
$os= $_SERVER['HTTP_USER_AGENT'];

sqlsrv_query($con,"UPDATE dbnow_gkg.tbl_stdmcdye SET no_item = '$_POST[value]' where id = '".$_POST['pk']."'");
sqlsrv_query($con, "INSERT INTO dbnow_gkg.tbl_log (what, what_do, do_by, do_at, ip, os, remark, foto, project)
									VALUES('Edit Data Item', 'Edit Data Item $_POST[value]', 'admin', GETDATE(), '$ip_num', '$os', NULL, NULL, NULL);");
	
echo json_encode('success');
