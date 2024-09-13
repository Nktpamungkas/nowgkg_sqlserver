<?PHP
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
$ip_num = $_SERVER['REMOTE_ADDR'];
$os= $_SERVER['HTTP_USER_AGENT'];
$loading = str_replace("'","''",$_POST['value']);
mysqli_query($con,"UPDATE tbl_stdmcdye SET `loading` = '$loading' where id = '".$_POST['pk']."'");
mysqli_query($con,"INSERT into tbl_log SET
	`what` = 'Edit Data Loading',
	`what_do` = 'Edit Data Loading $loading',
	`project` = '',
	`do_by` = 'admin',
	`do_at` = '$time',
	`ip` = '$ip_num',
	`os` = '$os',
	`foto` = '',
	`remark`=''");
echo json_encode('success');
