<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
//--
$IntDoc = $_GET['intdoc'];
$Orderline = $_GET['orderline'];
//-
$demand	= isset($_GET['demand']) ? $_GET['demand'] : '';
  $demand	= isset($_GET['demand']) ? $_GET['demand'] : '';
  $sqlDB2 = "SELECT PRODUCTIONDEMAND.CODE, PRODUCTIONDEMAND.INTERNALREFERENCE, PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE,
  PRODUCTIONDEMAND.FINALPLANNEDDATE, PRODUCTIONDEMAND.ITEMTYPEAFICODE,
  TRIM(PRODUCTIONDEMAND.SUBCODE01) AS SUBCODE01, TRIM(PRODUCTIONDEMAND.SUBCODE02) AS SUBCODE02, TRIM(PRODUCTIONDEMAND.SUBCODE03) AS SUBCODE03,
  TRIM(PRODUCTIONDEMAND.SUBCODE04) AS SUBCODE04, TRIM(PRODUCTIONDEMAND.SUBCODE05) AS SUBCODE05, TRIM(PRODUCTIONDEMAND.SUBCODE06) AS SUBCODE06,
  TRIM(PRODUCTIONDEMAND.SUBCODE07) AS SUBCODE07, TRIM(PRODUCTIONDEMAND.SUBCODE08) AS SUBCODE08, TRIM(PRODUCTIONDEMAND.SUBCODE09) AS SUBCODE09,
  TRIM(PRODUCTIONDEMAND.SUBCODE10) AS SUBCODE10, PRODUCT.LONGDESCRIPTION, A.WARNA, PRODUCTIONDEMAND.PROJECTCODE,
  PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE,
  PRODUCTIONDEMAND.ORIGDLVSALORDERLINEORDERLINE, 
  PRODUCTIONDEMAND.USERPRIMARYQUANTITY, PRODUCTIONDEMAND.USERPRIMARYUOMCODE,
  PRODUCTIONDEMAND.USERSECONDARYQUANTITY, PRODUCTIONDEMAND.USERSECONDARYUOMCODE, 
  SALESORDERDELIVERY.DELIVERYDATE,
  BUSINESSPARTNER.LEGALNAME1 AS LANGGANAN,
  ORDERPARTNERBRAND.LONGDESCRIPTION AS BUYER
  FROM PRODUCTIONDEMAND PRODUCTIONDEMAND LEFT JOIN PRODUCTIONDEMANDSTEP PRODUCTIONDEMANDSTEP
  ON PRODUCTIONDEMAND.CODE = PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE 
  LEFT JOIN PRODUCT PRODUCT ON PRODUCTIONDEMAND.ITEMTYPEAFICODE = PRODUCT.ITEMTYPECODE AND 
  PRODUCTIONDEMAND.SUBCODE01 = PRODUCT.SUBCODE01 AND 
  PRODUCTIONDEMAND.SUBCODE02 = PRODUCT.SUBCODE02 AND 
  PRODUCTIONDEMAND.SUBCODE03 = PRODUCT.SUBCODE03 AND 
  PRODUCTIONDEMAND.SUBCODE04 = PRODUCT.SUBCODE04 AND 
  PRODUCTIONDEMAND.SUBCODE05 = PRODUCT.SUBCODE05 AND 
  PRODUCTIONDEMAND.SUBCODE06 = PRODUCT.SUBCODE06 AND 
  PRODUCTIONDEMAND.SUBCODE07 = PRODUCT.SUBCODE07 AND 
  PRODUCTIONDEMAND.SUBCODE08 = PRODUCT.SUBCODE08 AND 
  PRODUCTIONDEMAND.SUBCODE09 = PRODUCT.SUBCODE09 AND 
  PRODUCTIONDEMAND.SUBCODE10 = PRODUCT.SUBCODE10
  LEFT JOIN ITXVIEWCOLOR A ON PRODUCTIONDEMAND.ITEMTYPEAFICODE = A.ITEMTYPECODE AND 
  PRODUCTIONDEMAND.SUBCODE01 = A.SUBCODE01 AND 
  PRODUCTIONDEMAND.SUBCODE02 = A.SUBCODE02 AND 
  PRODUCTIONDEMAND.SUBCODE03 = A.SUBCODE03 AND 
  PRODUCTIONDEMAND.SUBCODE04 = A.SUBCODE04 AND 
  PRODUCTIONDEMAND.SUBCODE05 = A.SUBCODE05 AND 
  PRODUCTIONDEMAND.SUBCODE06 = A.SUBCODE06 AND 
  PRODUCTIONDEMAND.SUBCODE07 = A.SUBCODE07 AND 
  PRODUCTIONDEMAND.SUBCODE08 = A.SUBCODE08 AND 
  PRODUCTIONDEMAND.SUBCODE09 = A.SUBCODE09 AND 
  PRODUCTIONDEMAND.SUBCODE10 = A.SUBCODE10 
  LEFT JOIN SALESORDERDELIVERY SALESORDERDELIVERY ON PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE = SALESORDERDELIVERY.SALESORDERLINESALESORDERCODE AND 
  PRODUCTIONDEMAND.ORIGDLVSALORDERLINEORDERLINE = SALESORDERDELIVERY.SALESORDERLINEORDERLINE 
  LEFT JOIN ORDERPARTNER ORDERPARTNER 
  ON PRODUCTIONDEMAND.CUSTOMERCODE = ORDERPARTNER.CUSTOMERSUPPLIERCODE 
  LEFT JOIN BUSINESSPARTNER BUSINESSPARTNER 
  ON ORDERPARTNER.ORDERBUSINESSPARTNERNUMBERID = BUSINESSPARTNER.NUMBERID 
  LEFT JOIN SALESORDER SALESORDER ON 
  PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE = SALESORDER.CODE 
  LEFT JOIN ORDERPARTNERBRAND ORDERPARTNERBRAND ON 
  SALESORDER.ORDERPARTNERBRANDCODE = ORDERPARTNERBRAND.CODE AND SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE = ORDERPARTNERBRAND.ORDPRNCUSTOMERSUPPLIERCODE
  WHERE PRODUCTIONDEMANDSTEP.OPERATIONCODE ='BAT1' AND PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE ='$demand'";
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
$rowdb2 = db2_fetch_assoc($stmt);

$sqlBK="SELECT 
PRODUCTIONORDER.CODE,
PRODUCTIONRESERVATION.PRODUCTIONORDERCODE,
PRODUCTIONRESERVATION.ITEMTYPEAFICODE,
SUM(PRODUCTIONRESERVATION.USEDUSERPRIMARYQUANTITY) AS USERPRIMARYQUANTITY,
PRODUCTIONRESERVATION.USERPRIMARYUOMCODE,
SUM(PRODUCTIONRESERVATION.USEDUSERSECONDARYQUANTITY) AS USERSECONDARYQUANTITY,
PRODUCTIONRESERVATION.USERSECONDARYUOMCODE
FROM PRODUCTIONORDER PRODUCTIONORDER
LEFT JOIN PRODUCTIONRESERVATION PRODUCTIONRESERVATION 
ON PRODUCTIONORDER.CODE = PRODUCTIONRESERVATION.PRODUCTIONORDERCODE 
WHERE (PRODUCTIONRESERVATION.ITEMTYPEAFICODE ='KGF' OR PRODUCTIONRESERVATION.ITEMTYPEAFICODE ='KFF')
AND PRODUCTIONORDER.CODE='$rowdb2[PRODUCTIONORDERCODE]'
GROUP BY 
PRODUCTIONORDER.CODE,
PRODUCTIONRESERVATION.PRODUCTIONORDERCODE,
PRODUCTIONRESERVATION.ITEMTYPEAFICODE,
PRODUCTIONRESERVATION.USERPRIMARYUOMCODE,
PRODUCTIONRESERVATION.USERSECONDARYUOMCODE";	
      $stmt1   = db2_exec($conn1,$sqlBK, array('cursor'=>DB2_SCROLLABLE));	
      $rowBK = db2_fetch_assoc($stmt1);

$sqlPOGreige="
SELECT PRODUCTIONDEMAND.CODE,ADSTORAGE.NAMENAME,ADSTORAGE.VALUESTRING,DESCRIPTION 
FROM DB2ADMIN.PRODUCTIONDEMAND PRODUCTIONDEMAND LEFT OUTER JOIN 
       DB2ADMIN.ADSTORAGE ADSTORAGE ON 
       PRODUCTIONDEMAND.ABSUNIQUEID=ADSTORAGE.UNIQUEID AND ADSTORAGE.NAMENAME ='ProAllow' 
WHERE  PRODUCTIONDEMAND.CODE='$demand'";
	  $stmt2   = db2_exec($conn1,$sqlPOGreige, array('cursor'=>DB2_SCROLLABLE));	
      $rowPG   = db2_fetch_assoc($stmt2);

$sqlroll="SELECT 
STOCKTRANSACTION.ORDERCODE,
VARCHAR_FORMAT(STOCKTRANSACTION.CREATIONDATETIME,'DD-MM-YYYY') AS TGBAGI,
COUNT(STOCKTRANSACTION.ITEMELEMENTCODE) AS JML_ROLL,
SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS KG_ROLL,
STOCKTRANSACTION.CREATIONUSER 
FROM STOCKTRANSACTION STOCKTRANSACTION
WHERE STOCKTRANSACTION.ORDERCODE ='$rowdb2[PRODUCTIONORDERCODE]' AND STOCKTRANSACTION.TEMPLATECODE ='120'
AND STOCKTRANSACTION.ITEMTYPECODE ='KGF'
GROUP BY 
VARCHAR_FORMAT(STOCKTRANSACTION.CREATIONDATETIME,'DD-MM-YYYY'),
STOCKTRANSACTION.ORDERCODE,
STOCKTRANSACTION.CREATIONUSER ";
$stmt1=db2_exec($conn1,$sqlroll, array('cursor'=>DB2_SCROLLABLE));
$rowr = db2_fetch_assoc($stmt1);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="styles_cetak_gkg.css" rel="stylesheet" type="text/css">
  <title>Cetak Identifikasi Kain Greige</title>
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
	border-right:1px #000000 solid;"><font size="+1"><strong>KAIN SELESAI PROSES BAGI KAIN <br/>
      NO FORM : FW-14-GKG-10/02</strong></font></td>
    </tr>
  </table>
  <table width="100%" border="1" class="table-list1">
    <tbody>
      <tr>
          <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Tgl Bagi</td>
          <td align="center" width="2%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
          <td align="left" width="78%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><strong><?php echo $rowr['TGBAGI']; ?></strong></td>
      </tr> 
      <tr>
          <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Pelanggan (Buyer)</td>
          <td align="center" width="2%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
          <td align="left" width="78%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><strong><?php echo $rowdb2['LANGGANAN']."/".$rowdb2['BUYER'];?></strong></td>
      </tr> 
      <tr>
          <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Nomor Order</td>
          <td align="center" width="2%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
          <td align="left" width="78%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><strong><?php if($rowdb2['ORIGDLVSALORDLINESALORDERCODE']!=""){echo $rowdb2['ORIGDLVSALORDLINESALORDERCODE'];}?></strong></td>
      </tr> 
      <tr>
          <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Nomor Demand</td>
          <td align="center" width="2%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
          <td align="left" width="78%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><strong><?php echo $rowdb2['CODE'];?></strong></td>
      </tr> 
      <tr>
          <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">No Production Order</td>
          <td align="center" width="2%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
          <td align="left" width="78%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><strong><?php echo $rowdb2['PRODUCTIONORDERCODE'];?></strong></td>
      </tr> 
      <tr>
          <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">PO Greige</td>
          <td align="center" width="2%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
          <td align="left" width="78%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><strong>
          <?php if($rowdb2['INTERNALREFERENCE']!=""){echo $rowdb2['INTERNALREFERENCE']; }else{echo $rowPG['VALUESTRING'];}?>
          </strong></td>
      </tr> 
      <tr>
          <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Acuan Quality dan Jenis Kain</td>
          <td align="center" width="2%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
          <td align="left" width="78%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><strong><?php echo $rowdb2['SUBCODE01']."-".$rowdb2['SUBCODE02']."-".$rowdb2['SUBCODE03']."-".$rowdb2['SUBCODE04']."-".$rowdb2['SUBCODE05']."-".$rowdb2['SUBCODE06']."-".$rowdb2['SUBCODE07']."-".$rowdb2['SUBCODE08']."-".$rowdb2['SUBCODE09']."-".$rowdb2['SUBCODE10'];?> / <?php echo $rowdb2['LONGDESCRIPTION'];?></strong></td>
      </tr> 
      <tr>
          <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Warna</td>
          <td align="center" width="2%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
          <td align="left" width="78%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><strong><?php if($rowdb2['WARNA']!=""){echo $rowdb2['WARNA'];}?></strong></td>
      </tr> 
      <tr>
          <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Jumlah Roll</td>
          <td align="center" width="2%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
          <td align="left" width="78%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><strong><?php echo $rowr['JML_ROLL']; ?></strong></td>
      </tr> 
      <tr>
        <td align="left" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">LOT</td>
        <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
        <td align="left" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><strong><?php echo $rowPG['DESCRIPTION'];?></strong></td>
      </tr>
      <tr>
          <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Lokasi</td>
          <td align="center" width="2%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
          <td align="left" width="78%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">&nbsp;</td>
      </tr> 
      <tr>
          <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Dicek Oleh</td>
          <td align="center" width="2%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
          <td align="left" width="78%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><strong><?php echo $rowr['CREATIONUSER']; ?></strong></td>
      </tr> 
    </tbody>
  </table>  

</body>

</html>