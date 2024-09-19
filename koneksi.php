<?php
date_default_timezone_set('Asia/Jakarta');

//$host="10.0.0.4";
//$username="timdit";
//$password="4dm1n";
//$db_name="TM";
//$connInfo = array( "Database"=>$db_name, "UID"=>$username, "PWD"=>$password);
//$conn     = sqlsrv_connect( $host, $connInfo);

$hostname = "10.0.0.21";
$database = "NOWPRD";
$user = "db2admin";
$passworddb2 = "Sunkam@24809";
$port = "25000";
$conn_string = "DRIVER={IBM ODBC DB2 DRIVER}; HOSTNAME=$hostname; PORT=$port; PROTOCOL=TCPIP; UID=$user; PWD=$passworddb2; DATABASE=$database;";
$conn1 = db2_connect($conn_string, '', '');

if ($conn1) {
} else {
    exit("DB2 Connection failed");
}

// $con1=mysqli_connect("10.0.0.10","dit","4dm1n","dbknitt");
// //$cond=mysqli_connect("10.0.0.10","dit","4dm1n","db_qc");
// $con=mysqli_connect("10.0.0.10","dit","4dm1n","dbnow_gkg");
// if (mysqli_connect_errno()) {
// printf("Connect failed: %s\n", mysqli_connect_error());
// exit();
// } 


$hostSVR19 = "10.0.0.221";
$usernameSVR19 = "sa";
$passwordSVR19 = "Ind@taichen2024";

// dbknitt belum ada jadi dimatikan koneksinya :)
$dbknitt = array("Database" => "dbknitt", "UID" => $usernameSVR19, "PWD" => $passwordSVR19);
$dbnow_gkg = array("Database" => "dbnow_gkg", "UID" => $usernameSVR19, "PWD" => $passwordSVR19);

$con1 = sqlsrv_connect($hostSVR19, $dbknitt);
$con = sqlsrv_connect($hostSVR19, $dbnow_gkg);

if (!$con1) {
    exit("SQLSVR19 Connection failed con1");
}
if (!$con) {
    exit("SQLSVR19 Connection failed con");
}
