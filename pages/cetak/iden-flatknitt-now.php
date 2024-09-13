<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
//--
$IntDoc = $_GET['intdoc'];
$Orderline = $_GET['orderline'];
//-
$sqlDB2 = "SELECT
            INTERNALDOCUMENT.PROVISIONALDOCUMENTDATE,
            INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE,
            INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
            INTERNALDOCUMENTLINE.ITEMTYPEAFICODE,
            INTERNALDOCUMENTLINE.SUBCODE01,
            INTERNALDOCUMENTLINE.SUBCODE02,
            INTERNALDOCUMENTLINE.SUBCODE03,
            INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
            INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
            INTERNALDOCUMENTLINE.PROJECTCODE,
            INTERNALDOCUMENTLINE.INTERNALREFERENCEDATE,
            ITXVIEWBUKMUTGKGKNTFLAT.RECEIVINGDATE,
            COUNT(ITXVIEWBUKMUTGKGKNTFLAT.ITEMELEMENTCODE) AS ROLL,
            SUM(ITXVIEWBUKMUTGKGKNTFLAT.USERPRIMARYQUANTITY) AS JML_KG,
            ITXVIEWBUKMUTGKGKNTFLAT.LOGICALWAREHOUSECODE,
            ADSTORAGE.VALUESTRING AS WARNA,
            ORDERPARTNERBRAND.LONGDESCRIPTION AS BUYER
          FROM
            INTERNALDOCUMENT INTERNALDOCUMENT
          LEFT JOIN INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON INTERNALDOCUMENT.PROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE
            AND INTERNALDOCUMENT.PROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE
          LEFT JOIN ADSTORAGE ADSTORAGE ON INTERNALDOCUMENTLINE.ABSUNIQUEID = ADSTORAGE.UNIQUEID AND ADSTORAGE.NAMENAME = 'NWarna'
          LEFT JOIN ITXVIEWBUKMUTGKGKNTFLAT ITXVIEWBUKMUTGKGKNTFLAT ON ITXVIEWBUKMUTGKGKNTFLAT.INTDOCPROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE
            AND ITXVIEWBUKMUTGKGKNTFLAT.INTDOCUMENTPROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE
            AND ITXVIEWBUKMUTGKGKNTFLAT.ORDERLINE = INTERNALDOCUMENTLINE.ORDERLINE
            AND ITXVIEWBUKMUTGKGKNTFLAT.LOGICALWAREHOUSECODE = INTERNALDOCUMENTLINE.WAREHOUSECODE
          LEFT JOIN SALESORDER SALESORDER ON 	INTERNALDOCUMENTLINE.PROJECTCODE = SALESORDER.CODE
          LEFT JOIN ORDERPARTNERBRAND ORDERPARTNERBRAND ON SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE = ORDERPARTNERBRAND.ORDPRNCUSTOMERSUPPLIERCODE
            AND SALESORDER.ORDERPARTNERBRANDCODE = ORDERPARTNERBRAND.CODE
          WHERE
            INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE = '$IntDoc'
          GROUP BY
            INTERNALDOCUMENT.PROVISIONALDOCUMENTDATE,
            INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE,
            INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
            INTERNALDOCUMENTLINE.ITEMTYPEAFICODE,
            INTERNALDOCUMENTLINE.SUBCODE01,
            INTERNALDOCUMENTLINE.SUBCODE02,
            INTERNALDOCUMENTLINE.SUBCODE03,
            INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
            INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
            INTERNALDOCUMENTLINE.PROJECTCODE,
            INTERNALDOCUMENTLINE.INTERNALREFERENCEDATE,
            ITXVIEWBUKMUTGKGKNTFLAT.RECEIVINGDATE,
            ADSTORAGE.VALUESTRING,
            ITXVIEWBUKMUTGKGKNTFLAT.LOGICALWAREHOUSECODE,
            ORDERPARTNERBRAND.LONGDESCRIPTION";
$stmt   = db2_exec($conn1, $sqlDB2, array('cursor' => DB2_SCROLLABLE));
$rowdb2 = db2_fetch_assoc($stmt);

$sqlmc = "SELECT 
PRODUCTIONDEMAND.CODE,
ADSTORAGE.VALUESTRING 
FROM PRODUCTIONDEMAND PRODUCTIONDEMAND 
LEFT JOIN ADSTORAGE ADSTORAGE ON PRODUCTIONDEMAND.ABSUNIQUEID = ADSTORAGE.UNIQUEID 
WHERE ADSTORAGE.NAMENAME ='MachineNo' AND PRODUCTIONDEMAND.CODE = '$rowdb2[LOTCODE]'";

$stmt1   = db2_exec($conn1, $sqlmc, array('cursor' => DB2_SCROLLABLE));
$rowmc = db2_fetch_assoc($stmt1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="styles_cetak.css" rel="stylesheet" type="text/css">
  <title>Cetak Identifikasi Flat Knitt</title>
  <script>

  </script>
  <style>
    .table-list td {
      color: #333;
      font-size: 12px;
      border-color: #fff;
      border-collapse: collapse;
      vertical-align: center;
      padding: 3px 5px;
      border-bottom: 1px #000000 solid;
      border-left: 1px #000000 solid;
      border-right: 1px #000000 solid;


    }

    .table-list1 {
      clear: both;
      text-align: left;
      border-collapse: collapse;
      margin: 0px 0px 5px 0px;
      background: #fff;
    }

    .table-list1 td {
      color: #333;
      font-size: 14px;
      border-color: #fff;
      border-collapse: collapse;
      vertical-align: center;
      padding: 1px 3px;
      border-bottom: 0px #000000 solid;
      border-top: 0px #000000 solid;
      border-left: 0px #000000 solid;
      border-right: 0px #000000 solid;


    }
  </style>
</head>

<body>
  <table width="100%" border="" class="table-list1" style="border-bottom:1px #000000 solid;
	border-top:1px #000000 solid;
	border-left:1px #000000 solid;
	border-right:1px #000000 solid;">
    <tr>
      <td width="100%" align="center" style="border-bottom:0px #000000 solid;
	border-top:0px #000000 solid;
	border-left:1px #000000 solid;
	border-right:1px #000000 solid;">
        <font size="+1">IDENTIFIKASI KAIN GREIGE <br /> NO FORM : FW-20-GKG-32/00</font>
      </td>
    </tr>
  </table>
  <table width="100%" border="1" class="table-list1">
    <tbody>
      <tr>
        <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Tgl Masuk</td>
        <td align="center" width="5%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
        <td align="left" width="75%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo date('d-m-Y', strtotime($rowdb2['INTERNALREFERENCEDATE'])); ?></td>
      </tr>
      <tr>
        <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">No Internal Doc.</td>
        <td align="center" width="5%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
        <td align="left" width="75%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo $rowdb2['INTDOCPROVISIONALCOUNTERCODE'] . "-" . $rowdb2['INTDOCUMENTPROVISIONALCODE']; ?></td>
      </tr>
      <tr>
        <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Buyer</td>
        <td align="center" width="5%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
        <td align="left" width="75%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo $rowdb2['BUYER']; ?></td>
      </tr>
      <tr>
        <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Project</td>
        <td align="center" width="5%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
        <td align="left" width="75%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo $rowdb2['PROJECTCODE']; ?></td>
      </tr>
      <tr>
        <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">No Artikel</td>
        <td align="center" width="5%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
        <td align="left" width="75%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><strong><?php echo trim($rowdb2['SUBCODE02']) . trim($rowdb2['SUBCODE03']); ?></strong></td>
      </tr>
      <tr>
        <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Jenis Kain</td>
        <td align="center" width="5%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
        <td align="left" width="75%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><strong><?php echo $rowdb2['ITEMDESCRIPTION']; ?></strong></td>
      </tr>
      <tr>
        <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Mesin Rajut</td>
        <td align="center" width="5%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
        <td align="left" width="75%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo $rowmc['VALUESTRING']; ?></td>
      </tr>
      <tr>
        <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">No PO</td>
        <td align="center" width="5%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
        <td align="left" width="75%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo $rowdb2['LOTCODE']; ?></td>
      </tr>
      <tr>
        <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Celup</td>
        <td align="center" width="5%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
        <td align="left" width="75%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><strong><?php if (substr($rowdb2['SUBCODE04'], 0, 1) == "D") {
                                                        echo "TUA";
                                                      } else if (substr($rowdb2['SUBCODE04'], 0, 1) == "L") {
                                                        echo "MUDA";
                                                      } else if (substr($rowdb2['SUBCODE04'], 0, 1) == "H") {
                                                        echo "MISTY";
                                                      } ?> <?php echo $rowdb2['WARNA']; ?></strong></td>
      </tr>
      <tr>
        <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Lot Benang</td>
        <td align="center" width="5%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
        <td align="left" width="75%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><strong><?php echo $rowdb2['EXTERNALREFERENCE']; ?></strong></td>
      </tr>
      <tr>
        <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Jumlah</td>
        <td align="center" width="5%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
        <td align="left" width="75%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">
            <?php 
              $sqlDB2_sum = "SELECT
                                COUNT(ITXVIEWBUKMUTGKGKNTFLAT.ITEMELEMENTCODE) AS ROLL,
                                SUM(ITXVIEWBUKMUTGKGKNTFLAT.USERPRIMARYQUANTITY) AS JML_KG
                              FROM
                                INTERNALDOCUMENT INTERNALDOCUMENT
                              LEFT JOIN INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON INTERNALDOCUMENT.PROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE
                                AND INTERNALDOCUMENT.PROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE
                              LEFT JOIN ADSTORAGE ADSTORAGE ON INTERNALDOCUMENTLINE.ABSUNIQUEID = ADSTORAGE.UNIQUEID AND ADSTORAGE.NAMENAME = 'NWarna'
                              LEFT JOIN ITXVIEWBUKMUTGKGKNTFLAT ITXVIEWBUKMUTGKGKNTFLAT ON ITXVIEWBUKMUTGKGKNTFLAT.INTDOCPROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE
                                AND ITXVIEWBUKMUTGKGKNTFLAT.INTDOCUMENTPROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE
                                AND ITXVIEWBUKMUTGKGKNTFLAT.ORDERLINE = INTERNALDOCUMENTLINE.ORDERLINE
                                AND ITXVIEWBUKMUTGKGKNTFLAT.LOGICALWAREHOUSECODE = INTERNALDOCUMENTLINE.WAREHOUSECODE
                              LEFT JOIN SALESORDER SALESORDER ON 	INTERNALDOCUMENTLINE.PROJECTCODE = SALESORDER.CODE
                              LEFT JOIN ORDERPARTNERBRAND ORDERPARTNERBRAND ON SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE = ORDERPARTNERBRAND.ORDPRNCUSTOMERSUPPLIERCODE
                                AND SALESORDER.ORDERPARTNERBRANDCODE = ORDERPARTNERBRAND.CODE
                              WHERE
                                INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE = '$IntDoc'";
              $stmt_sum   = db2_exec($conn1, $sqlDB2_sum, array('cursor' => DB2_SCROLLABLE));
              $rowdb2_sum = db2_fetch_assoc($stmt_sum);
            ?>
            <?= $rowdb2_sum['ROLL']. " Rolls  " . $rowdb2_sum['JML_KG']. " KGs"; ?>
        </td>
      </tr>
      <tr>
        <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Cek Oleh</td>
        <td align="center" width="5%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
        <td align="left" width="75%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">&nbsp;</td>
      </tr>
    </tbody>
  </table>
  <br />
  <?php
  $sqldtl = "SELECT 
  INTERNALDOCUMENT.PROVISIONALDOCUMENTDATE,
  INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE,
  INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
  INTERNALDOCUMENTLINE.ORDERLINE,
  INTERNALDOCUMENTLINE.ITEMTYPEAFICODE,
  INTERNALDOCUMENTLINE.SUBCODE01,
  INTERNALDOCUMENTLINE.SUBCODE02,
  INTERNALDOCUMENTLINE.SUBCODE03,
  INTERNALDOCUMENTLINE.SUBCODE04,
  INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
  ITXVIEWBUKMUTGKGKNTFLAT.RECEIVINGDATE,
  ITXVIEWBUKMUTGKGKNTFLAT.ITEMELEMENTCODE,
  ITXVIEWBUKMUTGKGKNTFLAT.LOTCODE,
  ITXVIEWBUKMUTGKGKNTFLAT.USERPRIMARYQUANTITY,
  ITXVIEWBUKMUTGKGKNTFLAT.LOGICALWAREHOUSECODE 
  FROM INTERNALDOCUMENT INTERNALDOCUMENT
  LEFT JOIN INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON INTERNALDOCUMENT.PROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE AND 
  INTERNALDOCUMENT.PROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE 
  LEFT JOIN ITXVIEWBUKMUTGKGKNTFLAT ITXVIEWBUKMUTGKGKNTFLAT ON ITXVIEWBUKMUTGKGKNTFLAT.INTDOCPROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE AND 
  ITXVIEWBUKMUTGKGKNTFLAT.INTDOCUMENTPROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE AND 
  ITXVIEWBUKMUTGKGKNTFLAT.ORDERLINE = INTERNALDOCUMENTLINE.ORDERLINE AND 
  ITXVIEWBUKMUTGKGKNTFLAT.LOGICALWAREHOUSECODE = INTERNALDOCUMENTLINE.WAREHOUSECODE 
  WHERE INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE='$IntDoc'";
  $stmt2   = db2_exec($conn1, $sqldtl, array('cursor' => DB2_SCROLLABLE));
  $jmldtl = db2_num_rows($stmt2);
  $batas = ceil($jmldtl / 2);
  $lawal = $batas * 1 - $batas;
  $lakhir = $batas * 2 - $batas;

  //KOLOM 1
  $sqldtl1 = "SELECT 
  INTERNALDOCUMENT.PROVISIONALDOCUMENTDATE,
  INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE,
  INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
  INTERNALDOCUMENTLINE.ORDERLINE,
  INTERNALDOCUMENTLINE.ITEMTYPEAFICODE,
  INTERNALDOCUMENTLINE.SUBCODE01,
  INTERNALDOCUMENTLINE.SUBCODE02,
  INTERNALDOCUMENTLINE.SUBCODE03,
  INTERNALDOCUMENTLINE.SUBCODE04,
  INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
  ITXVIEWBUKMUTGKGKNTFLAT.RECEIVINGDATE,
  ITXVIEWBUKMUTGKGKNTFLAT.ITEMELEMENTCODE,
  ITXVIEWBUKMUTGKGKNTFLAT.LOTCODE,
  ITXVIEWBUKMUTGKGKNTFLAT.USERPRIMARYQUANTITY,
  ITXVIEWBUKMUTGKGKNTFLAT.USERSECONDARYQUANTITY,
  ITXVIEWBUKMUTGKGKNTFLAT.LOGICALWAREHOUSECODE 
  FROM INTERNALDOCUMENT INTERNALDOCUMENT
  LEFT JOIN INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON INTERNALDOCUMENT.PROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE AND 
  INTERNALDOCUMENT.PROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE 
  LEFT JOIN ITXVIEWBUKMUTGKGKNTFLAT ITXVIEWBUKMUTGKGKNTFLAT ON ITXVIEWBUKMUTGKGKNTFLAT.INTDOCPROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE AND 
  ITXVIEWBUKMUTGKGKNTFLAT.INTDOCUMENTPROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE AND 
  ITXVIEWBUKMUTGKGKNTFLAT.ORDERLINE = INTERNALDOCUMENTLINE.ORDERLINE AND 
  ITXVIEWBUKMUTGKGKNTFLAT.LOGICALWAREHOUSECODE = INTERNALDOCUMENTLINE.WAREHOUSECODE 
  WHERE INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE='$IntDoc' 
  ORDER BY ITXVIEWBUKMUTGKGKNTFLAT.ITEMELEMENTCODE ASC LIMIT $lawal,$batas";
  $stmt3   = db2_exec($conn1, $sqldtl1, array('cursor' => DB2_SCROLLABLE));

  //KOLOM 2
  $sqldtl2 = "SELECT 
  INTERNALDOCUMENT.PROVISIONALDOCUMENTDATE,
  INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE,
  INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
  INTERNALDOCUMENTLINE.ORDERLINE,
  INTERNALDOCUMENTLINE.ITEMTYPEAFICODE,
  INTERNALDOCUMENTLINE.SUBCODE01,
  INTERNALDOCUMENTLINE.SUBCODE02,
  INTERNALDOCUMENTLINE.SUBCODE03,
  INTERNALDOCUMENTLINE.SUBCODE04,
  INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
  ITXVIEWBUKMUTGKGKNTFLAT.RECEIVINGDATE,
  ITXVIEWBUKMUTGKGKNTFLAT.ITEMELEMENTCODE,
  ITXVIEWBUKMUTGKGKNTFLAT.LOTCODE,
  ITXVIEWBUKMUTGKGKNTFLAT.USERPRIMARYQUANTITY,
  ITXVIEWBUKMUTGKGKNTFLAT.USERSECONDARYQUANTITY,
  ITXVIEWBUKMUTGKGKNTFLAT.LOGICALWAREHOUSECODE 
  FROM INTERNALDOCUMENT INTERNALDOCUMENT
  LEFT JOIN INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON INTERNALDOCUMENT.PROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE AND 
  INTERNALDOCUMENT.PROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE 
  LEFT JOIN ITXVIEWBUKMUTGKGKNTFLAT ITXVIEWBUKMUTGKGKNTFLAT ON ITXVIEWBUKMUTGKGKNTFLAT.INTDOCPROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE AND 
  ITXVIEWBUKMUTGKGKNTFLAT.INTDOCUMENTPROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE AND 
  ITXVIEWBUKMUTGKGKNTFLAT.ORDERLINE = INTERNALDOCUMENTLINE.ORDERLINE AND 
  ITXVIEWBUKMUTGKGKNTFLAT.LOGICALWAREHOUSECODE = INTERNALDOCUMENTLINE.WAREHOUSECODE 
  WHERE INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE='$IntDoc' 
  ORDER BY ITXVIEWBUKMUTGKGKNTFLAT.ITEMELEMENTCODE ASC LIMIT $lakhir,$batas";
  $stmt4   = db2_exec($conn1, $sqldtl2, array('cursor' => DB2_SCROLLABLE));
  ?>
  <table width="100%" class="table-list1">
    <tbody>
      <tr>
        <td valign="top">
          <table width="100%" border="1" class="table-list1">
            <thead>
              <tr>
                <td align="center" style="border-bottom:1px #000000 solid;
              border-top:1px #000000 solid;
              border-left:1px #000000 solid;
              border-right:1px #000000 solid;"><strong>SN</strong></td>
                <td align="center" style="border-bottom:1px #000000 solid;
              border-top:1px #000000 solid;
              border-left:1px #000000 solid;
              border-right:1px #000000 solid;"><strong>PCS</strong></td>
                <td align="center" style="border-bottom:1px #000000 solid;
              border-top:1px #000000 solid;
              border-left:1px #000000 solid;
              border-right:1px #000000 solid;"><strong>Qty KG</strong></td>
              </tr>
            </thead>
            <tbody>
              <?php
              while ($rowdtl1 = db2_fetch_assoc($stmt3)) {
              ?>
                <tr>
                  <td align="center" style="border-bottom:1px #000000 solid;
              border-top:1px #000000 solid;
              border-left:1px #000000 solid;
              border-right:1px #000000 solid;"><?php echo $rowdtl1['ITEMELEMENTCODE']; ?></td>
                  <td align="center" style="border-bottom:1px #000000 solid;
              border-top:1px #000000 solid;
              border-left:1px #000000 solid;
              border-right:1px #000000 solid;"><?php echo round($rowdtl1['USERSECONDARYQUANTITY']); ?></td>
                  <td align="center" style="border-bottom:1px #000000 solid;
              border-top:1px #000000 solid;
              border-left:1px #000000 solid;
              border-right:1px #000000 solid;"><?php echo $rowdtl1['USERPRIMARYQUANTITY']; ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </td>
        <td valign="top">
          <table width="100%" border="1" class="table-list1">
            <thead>
              <tr>
                <td align="center" style="border-bottom:1px #000000 solid;
              border-top:1px #000000 solid;
              border-left:1px #000000 solid;
              border-right:1px #000000 solid;"><strong>SN</strong></td>
                <td align="center" style="border-bottom:1px #000000 solid;
              border-top:1px #000000 solid;
              border-left:1px #000000 solid;
              border-right:1px #000000 solid;"><strong>PCS</strong></td>
                <td align="center" style="border-bottom:1px #000000 solid;
              border-top:1px #000000 solid;
              border-left:1px #000000 solid;
              border-right:1px #000000 solid;"><strong>Qty KG</strong></td>
              </tr>
            </thead>
            <tbody>
              <?php
              while ($rowdtl2 = db2_fetch_assoc($stmt4)) {
              ?>
                <tr>
                  <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo $rowdtl2['ITEMELEMENTCODE']; ?></td>
                  <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo round($rowdtl2['USERSECONDARYQUANTITY']); ?></td>
                  <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo $rowdtl2['USERPRIMARYQUANTITY']; ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
</body>

</html>