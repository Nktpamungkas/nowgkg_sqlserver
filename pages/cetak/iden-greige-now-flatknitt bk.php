<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
//--
$ProdOrder = $_GET['prodorder'];
$Transaction = $_GET['transaction'];
//-
$sqlDB2 ="SELECT 
STOCKTRANSACTION.TRANSACTIONNUMBER,
STOCKTRANSACTION.TRANSACTIONDATE,
STOCKTRANSACTION.TEMPLATECODE,
STOCKTRANSACTION.PROJECTCODE,
STOCKTRANSACTION.ITEMTYPECODE,
STOCKTRANSACTION.DECOSUBCODE01,
STOCKTRANSACTION.DECOSUBCODE02,
STOCKTRANSACTION.DECOSUBCODE03,
STOCKTRANSACTION.DECOSUBCODE04,
PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE,
COUNT(STOCKTRANSACTION.ITEMELEMENTCODE) AS JML_ROLL,
SUM(STOCKTRANSACTION.USERPRIMARYQUANTITY) AS JML_QTY,
STOCKTRANSACTION.USERPRIMARYUOMCODE,
SUM(STOCKTRANSACTION.USERSECONDARYQUANTITY) AS JML_QTY_SCND,
STOCKTRANSACTION.USERSECONDARYUOMCODE, 
PRODUCT.LONGDESCRIPTION AS JENIS_KAIN,
ORDERPARTNERBRAND.LONGDESCRIPTION AS BUYER
FROM STOCKTRANSACTION STOCKTRANSACTION 
LEFT JOIN PRODUCT PRODUCT ON 
STOCKTRANSACTION.ITEMTYPECODE = PRODUCT.ITEMTYPECODE AND 
STOCKTRANSACTION.DECOSUBCODE01 = PRODUCT.SUBCODE01 AND 
STOCKTRANSACTION.DECOSUBCODE02 = PRODUCT.SUBCODE02 AND 
STOCKTRANSACTION.DECOSUBCODE03 = PRODUCT.SUBCODE03 AND 
STOCKTRANSACTION.DECOSUBCODE04 = PRODUCT.SUBCODE04 
LEFT JOIN PRODUCTIONDEMAND PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = STOCKTRANSACTION.ORDERCODE
LEFT JOIN SALESORDER SALESORDER ON PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE = SALESORDER.CODE 
LEFT JOIN ORDERPARTNERBRAND ORDERPARTNERBRAND ON SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE = ORDERPARTNERBRAND.ORDPRNCUSTOMERSUPPLIERCODE AND SALESORDER.ORDERPARTNERBRANDCODE = ORDERPARTNERBRAND.CODE
WHERE STOCKTRANSACTION.TEMPLATECODE ='110' AND STOCKTRANSACTION.ITEMTYPECODE ='FKG' AND STOCKTRANSACTION.PRODUCTIONORDERCODE='$ProdOrder' AND STOCKTRANSACTION.TRANSACTIONNUMBER='$Transaction'
GROUP BY 
STOCKTRANSACTION.TRANSACTIONNUMBER,
STOCKTRANSACTION.TRANSACTIONDATE,
STOCKTRANSACTION.TEMPLATECODE,
STOCKTRANSACTION.PROJECTCODE,
STOCKTRANSACTION.ITEMTYPECODE,
STOCKTRANSACTION.DECOSUBCODE01,
STOCKTRANSACTION.DECOSUBCODE02,
STOCKTRANSACTION.DECOSUBCODE03,
STOCKTRANSACTION.DECOSUBCODE04,
STOCKTRANSACTION.USERPRIMARYUOMCODE,
STOCKTRANSACTION.USERSECONDARYUOMCODE, 
PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE,
PRODUCT.LONGDESCRIPTION,
ORDERPARTNERBRAND.LONGDESCRIPTION";	
	$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
  $rowdb2 = db2_fetch_assoc($stmt);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="styles_cetak.css" rel="stylesheet" type="text/css">
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
	border-right:1px #000000 solid;"><font size="+1">IDENTIFIKASI KAIN GREIGE <br/> NO FORM : FW-20-GKG-20/01</font></td>
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
            border-right:1px #000000 solid;"><?php echo date('d-m-Y', strtotime($rowdb2['TRANSACTIONDATE']));?></td>
      </tr>
      <tr>
          <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Project Code</td>
          <td align="center" width="5%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
          <td align="left" width="75%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo $rowdb2['ORIGDLVSALORDLINESALORDERCODE'];?></td>
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
            border-right:1px #000000 solid;"><?php echo $rowdb2['BUYER'];?></td>
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
            border-right:1px #000000 solid;"><?php echo $rowdb2['ORIGDLVSALORDLINESALORDERCODE'];?></td>
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
            border-right:1px #000000 solid;"><strong><?php echo trim($rowdb2['DECOSUBCODE02']).trim($rowdb2['DECOSUBCODE03']);?></strong></td>
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
            border-right:1px #000000 solid;"><strong><?php echo $rowdb2['JENIS_KAIN'];?></strong></td>
      </tr> 
      <tr>
          <td align="left" width="20%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">No Transaction</td>
          <td align="center" width="5%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
          <td align="left" width="75%" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo $rowdb2['TRANSACTIONNUMBER'];?></td>
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
            border-right:1px #000000 solid;"><strong><?php if(substr($rowdb2['DECOSUBCODE04'],0,1)=="D"){echo "TUA";}else if(substr($rowdb2['DECOSUBCODE04'],0,1)=="L"){echo "MUDA";}else if(substr($rowdb2['DECOSUBCODE04'],0,1)=="H"){echo "MISTY";}?></strong></td>
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
            border-right:1px #000000 solid;"><?php echo $rowdb2['JML_ROLL']." Rolls  ".round($rowdb2['JML_QTY_SCND'])." PCS  ".$rowdb2['JML_QTY']." KGs";?></td>
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
  $sqldtl="SELECT 
  STOCKTRANSACTION.TRANSACTIONNUMBER,
  STOCKTRANSACTION.TRANSACTIONDATE,
  STOCKTRANSACTION.TEMPLATECODE,
  STOCKTRANSACTION.PROJECTCODE,
  STOCKTRANSACTION.ITEMTYPECODE,
  STOCKTRANSACTION.DECOSUBCODE01,
  STOCKTRANSACTION.DECOSUBCODE02,
  STOCKTRANSACTION.DECOSUBCODE03,
  STOCKTRANSACTION.DECOSUBCODE04,
  STOCKTRANSACTION.ITEMELEMENTCODE,
  STOCKTRANSACTION.USERPRIMARYQUANTITY,
  STOCKTRANSACTION.USERPRIMARYUOMCODE,
  STOCKTRANSACTION.USERSECONDARYQUANTITY,
  STOCKTRANSACTION.USERSECONDARYUOMCODE, 
  PRODUCT.LONGDESCRIPTION AS JENIS_KAIN,
  ORDERPARTNERBRAND.LONGDESCRIPTION AS BUYER
  FROM STOCKTRANSACTION STOCKTRANSACTION
  LEFT JOIN PRODUCT PRODUCT ON 
  STOCKTRANSACTION.ITEMTYPECODE = PRODUCT.ITEMTYPECODE AND 
  STOCKTRANSACTION.DECOSUBCODE01 = PRODUCT.SUBCODE01 AND 
  STOCKTRANSACTION.DECOSUBCODE02 = PRODUCT.SUBCODE02 AND 
  STOCKTRANSACTION.DECOSUBCODE03 = PRODUCT.SUBCODE03 AND 
  STOCKTRANSACTION.DECOSUBCODE04 = PRODUCT.SUBCODE04 
  LEFT JOIN SALESORDER SALESORDER ON STOCKTRANSACTION.PROJECTCODE = SALESORDER.CODE 
  LEFT JOIN ORDERPARTNERBRAND ORDERPARTNERBRAND ON SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE = ORDERPARTNERBRAND.ORDPRNCUSTOMERSUPPLIERCODE AND SALESORDER.ORDERPARTNERBRANDCODE = ORDERPARTNERBRAND.CODE
  WHERE STOCKTRANSACTION.TEMPLATECODE ='110' AND STOCKTRANSACTION.ITEMTYPECODE ='FKG' AND STOCKTRANSACTION.PRODUCTIONORDERCODE='$ProdOrder' AND STOCKTRANSACTION.TRANSACTIONNUMBER='$Transaction'";
  $stmt2   = db2_exec($conn1,$sqldtl, array('cursor'=>DB2_SCROLLABLE));
  $jmldtl = db2_num_rows($stmt2);
  $batas=ceil($jmldtl/2);
  $lawal=$batas*1-$batas;
  $lakhir=$batas*2-$batas;

  //KOLOM 1
  $sqldtl1="SELECT 
  STOCKTRANSACTION.TRANSACTIONNUMBER,
  STOCKTRANSACTION.TRANSACTIONDATE,
  STOCKTRANSACTION.TEMPLATECODE,
  STOCKTRANSACTION.PROJECTCODE,
  STOCKTRANSACTION.ITEMTYPECODE,
  STOCKTRANSACTION.DECOSUBCODE01,
  STOCKTRANSACTION.DECOSUBCODE02,
  STOCKTRANSACTION.DECOSUBCODE03,
  STOCKTRANSACTION.DECOSUBCODE04,
  STOCKTRANSACTION.ITEMELEMENTCODE,
  STOCKTRANSACTION.USERPRIMARYQUANTITY,
  STOCKTRANSACTION.USERPRIMARYUOMCODE,
  STOCKTRANSACTION.USERSECONDARYQUANTITY,
  STOCKTRANSACTION.USERSECONDARYUOMCODE, 
  PRODUCT.LONGDESCRIPTION AS JENIS_KAIN,
  ORDERPARTNERBRAND.LONGDESCRIPTION AS BUYER
  FROM STOCKTRANSACTION STOCKTRANSACTION 
  LEFT JOIN PRODUCT PRODUCT ON 
  STOCKTRANSACTION.ITEMTYPECODE = PRODUCT.ITEMTYPECODE AND 
  STOCKTRANSACTION.DECOSUBCODE01 = PRODUCT.SUBCODE01 AND 
  STOCKTRANSACTION.DECOSUBCODE02 = PRODUCT.SUBCODE02 AND 
  STOCKTRANSACTION.DECOSUBCODE03 = PRODUCT.SUBCODE03 AND 
  STOCKTRANSACTION.DECOSUBCODE04 = PRODUCT.SUBCODE04 
  LEFT JOIN SALESORDER SALESORDER ON STOCKTRANSACTION.PROJECTCODE = SALESORDER.CODE 
  LEFT JOIN ORDERPARTNERBRAND ORDERPARTNERBRAND ON SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE = ORDERPARTNERBRAND.ORDPRNCUSTOMERSUPPLIERCODE AND SALESORDER.ORDERPARTNERBRANDCODE = ORDERPARTNERBRAND.CODE
  WHERE STOCKTRANSACTION.TEMPLATECODE ='110' AND STOCKTRANSACTION.ITEMTYPECODE ='FKG' AND STOCKTRANSACTION.PRODUCTIONORDERCODE='$ProdOrder' AND STOCKTRANSACTION.TRANSACTIONNUMBER='$Transaction' 
  ORDER BY STOCKTRANSACTION.ITEMELEMENTCODE ASC LIMIT $lawal,$batas";
  $stmt3   = db2_exec($conn1,$sqldtl1, array('cursor'=>DB2_SCROLLABLE));

  //KOLOM 2
  $sqldtl2="SELECT 
  STOCKTRANSACTION.TRANSACTIONNUMBER,
  STOCKTRANSACTION.TRANSACTIONDATE,
  STOCKTRANSACTION.TEMPLATECODE,
  STOCKTRANSACTION.PROJECTCODE,
  STOCKTRANSACTION.ITEMTYPECODE,
  STOCKTRANSACTION.DECOSUBCODE01,
  STOCKTRANSACTION.DECOSUBCODE02,
  STOCKTRANSACTION.DECOSUBCODE03,
  STOCKTRANSACTION.DECOSUBCODE04,
  STOCKTRANSACTION.ITEMELEMENTCODE,
  STOCKTRANSACTION.USERPRIMARYQUANTITY,
  STOCKTRANSACTION.USERPRIMARYUOMCODE,
  STOCKTRANSACTION.USERSECONDARYQUANTITY,
  STOCKTRANSACTION.USERSECONDARYUOMCODE, 
  PRODUCT.LONGDESCRIPTION AS JENIS_KAIN,
  ORDERPARTNERBRAND.LONGDESCRIPTION AS BUYER
  FROM STOCKTRANSACTION STOCKTRANSACTION 
  LEFT JOIN PRODUCT PRODUCT ON 
  STOCKTRANSACTION.ITEMTYPECODE = PRODUCT.ITEMTYPECODE AND 
  STOCKTRANSACTION.DECOSUBCODE01 = PRODUCT.SUBCODE01 AND 
  STOCKTRANSACTION.DECOSUBCODE02 = PRODUCT.SUBCODE02 AND 
  STOCKTRANSACTION.DECOSUBCODE03 = PRODUCT.SUBCODE03 AND 
  STOCKTRANSACTION.DECOSUBCODE04 = PRODUCT.SUBCODE04 
  LEFT JOIN SALESORDER SALESORDER ON STOCKTRANSACTION.PROJECTCODE = SALESORDER.CODE 
  LEFT JOIN ORDERPARTNERBRAND ORDERPARTNERBRAND ON SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE = ORDERPARTNERBRAND.ORDPRNCUSTOMERSUPPLIERCODE AND SALESORDER.ORDERPARTNERBRANDCODE = ORDERPARTNERBRAND.CODE
  WHERE STOCKTRANSACTION.TEMPLATECODE ='110' AND STOCKTRANSACTION.ITEMTYPECODE ='FKG' AND STOCKTRANSACTION.PRODUCTIONORDERCODE='$ProdOrder' AND STOCKTRANSACTION.TRANSACTIONNUMBER='$Transaction' 
  ORDER BY STOCKTRANSACTION.ITEMELEMENTCODE ASC LIMIT $lakhir,$batas";
  $stmt4   = db2_exec($conn1,$sqldtl2, array('cursor'=>DB2_SCROLLABLE));
  ?>
<table width="100%" class="table-list1">
    <tbody>
      <tr>
         <td valign="top"><table width="100%" border="1" class="table-list1">
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
              while($rowdtl1=db2_fetch_assoc($stmt3)){
              ?>
              <tr>
                <td align="center" style="border-bottom:1px #000000 solid;
              border-top:1px #000000 solid;
              border-left:1px #000000 solid;
              border-right:1px #000000 solid;"><?php echo $rowdtl1['ITEMELEMENTCODE'];?></td>
                <td align="center" style="border-bottom:1px #000000 solid;
              border-top:1px #000000 solid;
              border-left:1px #000000 solid;
              border-right:1px #000000 solid;"><?php echo round($rowdtl1['USERSECONDARYQUANTITY']);?></td>
                <td align="center" style="border-bottom:1px #000000 solid;
              border-top:1px #000000 solid;
              border-left:1px #000000 solid;
              border-right:1px #000000 solid;"><?php echo $rowdtl1['USERPRIMARYQUANTITY'];?></td>
              </tr>
              <?php }?>
            </tbody>
         </table></td>
         <td valign="top"><table width="100%" border="1" class="table-list1">
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
            while($rowdtl2=db2_fetch_assoc($stmt4)){
            ?>
            <tr>
              <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo $rowdtl2['ITEMELEMENTCODE'];?></td>
              <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo round($rowdtl2['USERSECONDARYQUANTITY']);?></td>
              <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo $rowdtl2['USERPRIMARYQUANTITY'];?></td>
            </tr>
            <?php }?>
          </tbody>
         </table></td>
      </tr>
    </tbody>
</table>
</body>

</html>