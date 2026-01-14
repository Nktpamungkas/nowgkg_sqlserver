<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

$demand	= isset($_GET['demandno']) ? $_GET['demandno'] : '';  
$sqlDB2 = " SELECT
	PRODUCTIONDEMAND.CODE,
	PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE,
	A.PRODUCTIONORDERCODE,
	SUBSTR(STOCKTRANSACTION.ITEMELEMENTCODE, 1, 8) AS DEMAND_KGF,
	pr.LONGDESCRIPTION,
	PRODUCTIONDEMAND.ITEMTYPEAFICODE,
	PRODUCTIONDEMAND.SUBCODE01,
	PRODUCTIONDEMAND.SUBCODE02,
	PRODUCTIONDEMAND.SUBCODE03,
	PRODUCTIONDEMAND.SUBCODE04,
	PRODUCTIONDEMAND.SUBCODE05,
	PRODUCTIONDEMAND.SUBCODE06,
	PRODUCTIONDEMAND.SUBCODE07,
	PRODUCTIONDEMAND.SUBCODE08,
	STOCKTRANSACTION.ITEMELEMENTCODE,
  a.VALUESTRING AS PANJANG_BENANG
FROM
	PRODUCTIONDEMAND PRODUCTIONDEMAND
LEFT OUTER JOIN (
	SELECT
		PRODUCTIONRESERVATION.ORDERCODE,
		PRODUCTIONRESERVATION.PRODUCTIONORDERCODE
	FROM
		PRODUCTIONRESERVATION PRODUCTIONRESERVATION
	WHERE
		(PRODUCTIONRESERVATION.ITEMTYPEAFICODE = 'KGF'
			OR PRODUCTIONRESERVATION.ITEMTYPEAFICODE = 'FKG')
) A ON
	PRODUCTIONDEMAND.CODE = A.ORDERCODE
LEFT OUTER JOIN STOCKTRANSACTION STOCKTRANSACTION
ON
	A.PRODUCTIONORDERCODE = STOCKTRANSACTION.ORDERCODE AND NOT STOCKTRANSACTION.ORDERLINE = '0'
LEFT OUTER JOIN PRODUCT pr ON
	PRODUCTIONDEMAND.ITEMTYPEAFICODE = pr.ITEMTYPECODE
	AND 
PRODUCTIONDEMAND.SUBCODE01 = pr.SUBCODE01
	AND 
PRODUCTIONDEMAND.SUBCODE02 = pr.SUBCODE02
	AND 
PRODUCTIONDEMAND.SUBCODE03 = pr.SUBCODE03
	AND
PRODUCTIONDEMAND.SUBCODE04 = pr.SUBCODE04
	AND
PRODUCTIONDEMAND.SUBCODE05 = pr.SUBCODE05
	AND
PRODUCTIONDEMAND.SUBCODE06 = pr.SUBCODE06
	AND
PRODUCTIONDEMAND.SUBCODE07 = pr.SUBCODE07
	AND
PRODUCTIONDEMAND.SUBCODE08 = pr.SUBCODE08
  LEFT JOIN PRODUCTIONDEMAND p ON p.CODE = SUBSTR(STOCKTRANSACTION.ITEMELEMENTCODE, 1, 8)
  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.FIELDNAME = 'FALoopLenght'
WHERE
	PRODUCTIONDEMAND.CODE = '$demand'
GROUP BY
	PRODUCTIONDEMAND.CODE,
	PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE,
	A.PRODUCTIONORDERCODE,
	SUBSTR(STOCKTRANSACTION.ITEMELEMENTCODE, 1, 8),
	pr.LONGDESCRIPTION,
	PRODUCTIONDEMAND.ITEMTYPEAFICODE,
	PRODUCTIONDEMAND.SUBCODE01,
	PRODUCTIONDEMAND.SUBCODE02,
	PRODUCTIONDEMAND.SUBCODE03,
	PRODUCTIONDEMAND.SUBCODE04,
	PRODUCTIONDEMAND.SUBCODE05,
	PRODUCTIONDEMAND.SUBCODE06,
	PRODUCTIONDEMAND.SUBCODE07,
	PRODUCTIONDEMAND.SUBCODE08,
	STOCKTRANSACTION.ITEMELEMENTCODE,
  a.VALUESTRING
LIMIT 1 ";
$stmt   = db2_prepare($conn1,$sqlDB2);
db2_execute($stmt);
$rowdb2 = db2_fetch_assoc($stmt);
$itemKFF= trim($rowdb2['SUBCODE01'])."-".trim($rowdb2['SUBCODE02'])."-".trim($rowdb2['SUBCODE03'])."-".trim($rowdb2['SUBCODE04'])."-".trim($rowdb2['SUBCODE05'])."-".trim($rowdb2['SUBCODE06'])."-".trim($rowdb2['SUBCODE07'])."-".trim($rowdb2['SUBCODE08']);
$sqlBK="SELECT
    LISTAGG(TRIM(PRODUCTIONRESERVATION.ORDERCODE),
    ',') AS DEMAND,
    PRODUCTIONRESERVATION.PRODUCTIONORDERCODE
FROM
    PRODUCTIONRESERVATION PRODUCTIONRESERVATION
LEFT JOIN PRODUCTIONDEMAND PRODUCTIONDEMAND ON
    PRODUCTIONRESERVATION.ORDERCODE = PRODUCTIONDEMAND.CODE
WHERE
    (PRODUCTIONRESERVATION.ITEMTYPEAFICODE = 'KGF'
        OR PRODUCTIONRESERVATION.ITEMTYPEAFICODE = 'FKG')
	AND PRODUCTIONRESERVATION.PRODUCTIONORDERCODE='".$rowdb2['PRODUCTIONORDERCODE']."'	
GROUP BY
    PRODUCTIONRESERVATION.PRODUCTIONORDERCODE";	
      $stmt1   = db2_prepare($conn1,$sqlBK);	
	  			db2_execute($stmt1);
      $rowBK = db2_fetch_assoc($stmt1);
$sqlPrj="
SELECT PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE 
FROM DB2ADMIN.PRODUCTIONDEMAND PRODUCTIONDEMAND 
WHERE  PRODUCTIONDEMAND.CODE='".$rowdb2['DEMAND_KGF']."'";
	  $stmtPrj   = db2_prepare($conn1,$sqlPrj);
				db2_execute($stmtPrj);
      $rowPrj   = db2_fetch_assoc($stmtPrj);

$sqlPrj1="SELECT
	PROJECTCODE,ORDERLINE,ORDERCODE 
FROM
	DB2ADMIN.STOCKTRANSACTION
WHERE
	TEMPLATECODE = '204'
	AND ITEMELEMENTCODE = '".$rowdb2['ITEMELEMENTCODE']."'
	AND LOGICALWAREHOUSECODE = 'M021'";
	  $stmtPrj1   = db2_prepare($conn1,$sqlPrj1);	
				db2_execute($stmtPrj1);
      $rowPrj1   = db2_fetch_assoc($stmtPrj1);

$sqlPrj2="SELECT
	LISTAGG(TRIM(a.PROJECTCODE),
	', ') AS PROJECTCODE
FROM
	(
	SELECT
		PROJECTCODE
	FROM
		DB2ADMIN.STOCKTRANSACTION
	WHERE
		TEMPLATECODE = '120'
		AND ORDERCODE = '".$rowdb2['PRODUCTIONORDERCODE']."'
	GROUP BY
		PROJECTCODE) a
";
	  $stmtPrj2   = db2_prepare($conn1,$sqlPrj2);
				db2_execute($stmtPrj2);
      $rowPrj2   = db2_fetch_assoc($stmtPrj2);

$sqlTGLBG="
SELECT
    STOCKTRANSACTION.TRANSACTIONDATE,
    STOCKTRANSACTION.ORDERCODE
FROM
    STOCKTRANSACTION STOCKTRANSACTION
WHERE
    STOCKTRANSACTION.TEMPLATECODE = '120' AND
	STOCKTRANSACTION.ORDERCODE = '".$rowdb2['PRODUCTIONORDERCODE']."'
GROUP BY
    STOCKTRANSACTION.TRANSACTIONDATE,
    STOCKTRANSACTION.ORDERCODE";
	  $stmtBG   = db2_prepare($conn1,$sqlTGLBG);	
				db2_execute($stmtBG);
      $rowBG   = db2_fetch_assoc($stmtBG);

//$sqlLG="
//SELECT
//    e.ELEMENTCODE,
//    e.WIDTHGROSS,
//    a.VALUEDECIMAL
//FROM
//    ELEMENTSINSPECTION e
//LEFT OUTER JOIN ADSTORAGE a ON
//    a.UNIQUEID = e.ABSUNIQUEID
//    AND a.NAMENAME = 'GSM'
//WHERE e.ELEMENTCODE LIKE '$rowdb2[DEMAND_KGF]%' LIMIT 1	";
//	  $stmtLG   = db2_prepare($conn1,$sqlLG);
// db2_execute($stmtLG);
//      $rowLG   = db2_fetch_assoc($stmtLG);
$sqlLG="SELECT LISTAGG(TRIM(GSM),
    ', ') AS LG1,LISTAGG(TRIM(MESIN_KNT),
    ', ') AS MESIN1 FROM (
SELECT
	DISTINCT
	CONCAT(floor(e.WIDTHNET),CONCAT('x',floor(a.VALUEDECIMAL))) AS GSM, 
	a2.VALUESTRING AS MESIN_KNT
FROM
	STOCKTRANSACTION s
LEFT JOIN STOCKTRANSACTION s2 ON
	s2.ITEMELEMENTCODE = s.ITEMELEMENTCODE
	AND s2.TEMPLATECODE = '204'
LEFT JOIN ELEMENTSINSPECTION e ON
	e.DEMANDCODE = s2.LOTCODE
	AND e.ELEMENTCODE = s2.ITEMELEMENTCODE
LEFT JOIN ADSTORAGE a ON
	a.UNIQUEID = e.ABSUNIQUEID
	AND a.FIELDNAME = 'GSM'
LEFT JOIN PRODUCTIONDEMAND p ON
	p.CODE = s2.LOTCODE
LEFT JOIN ADSTORAGE a2 ON
	a2.UNIQUEID = p.ABSUNIQUEID
	AND a2.FIELDNAME = 'MachineNoCode'
WHERE
	s.TEMPLATECODE = '120'
	AND 
    s.ORDERCODE = '".$rowdb2['PRODUCTIONORDERCODE']."'
	AND SUBSTR(s.ITEMELEMENTCODE, 1, 1) = '0')
";
	  $stmtLG   = db2_prepare($conn1,$sqlLG);
				db2_execute($stmtLG);
      $rowLG   = db2_fetch_assoc($stmtLG);
$sqlDBLBNG = " SELECT
    STOCKTRANSACTION.PROJECTCODE,
    STOCKTRANSACTION.ORDERCODE,
    STOCKTRANSACTION.ORDERLINE,
    STOCKTRANSACTION.LOTCODE,
    STOCKTRANSACTION.ITEMTYPECODE,
    STOCKTRANSACTION.DECOSUBCODE01,
    STOCKTRANSACTION.DECOSUBCODE02,
    STOCKTRANSACTION.DECOSUBCODE03,
    STOCKTRANSACTION.DECOSUBCODE04,
    LISTAGG(DISTINCT(TRIM(INTERNALDOCUMENTLINE.EXTERNALREFERENCE)),
    ',') AS EXTERNALREFERENCE
FROM
    STOCKTRANSACTION STOCKTRANSACTION
LEFT JOIN INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE 
ON
    STOCKTRANSACTION.ORDERCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE
    AND 
STOCKTRANSACTION.ORDERLINE = INTERNALDOCUMENTLINE.ORDERLINE
WHERE
    STOCKTRANSACTION.ORDERCOUNTERCODE = 'I02M50' AND 
    STOCKTRANSACTION.DECOSUBCODE02 = '".$rowdb2['SUBCODE02']."' AND
    STOCKTRANSACTION.DECOSUBCODE03 = '".$rowdb2['SUBCODE03']."' AND
    STOCKTRANSACTION.PROJECTCODE = '".$rowPrj['ORIGDLVSALORDLINESALORDERCODE']."' AND
	STOCKTRANSACTION.ORDERLINE = '".$rowPrj1['ORDERLINE']."' AND
	STOCKTRANSACTION.ORDERCODE = '".$rowPrj1['ORDERCODE']."' AND
    STOCKTRANSACTION.LOTCODE = '".$rowdb2['DEMAND_KGF']."'
    
GROUP BY
    STOCKTRANSACTION.PROJECTCODE,
    STOCKTRANSACTION.ORDERCODE,
    STOCKTRANSACTION.ORDERLINE,
    STOCKTRANSACTION.LOTCODE,
    STOCKTRANSACTION.ITEMTYPECODE,
    STOCKTRANSACTION.DECOSUBCODE01,
    STOCKTRANSACTION.DECOSUBCODE02,
    STOCKTRANSACTION.DECOSUBCODE03,
    STOCKTRANSACTION.DECOSUBCODE04
";
$stmtLBNG   = db2_prepare($conn1,$sqlDBLBNG);
db2_execute($stmtLBNG);
$rowdbLBNG  = db2_fetch_assoc($stmtLBNG);
$sqlDB25 = " 
SELECT ad.VALUESTRING AS NO_MESIN
FROM PRODUCTIONDEMAND pd 	
LEFT OUTER JOIN ADSTORAGE ad ON ad.UNIQUEID = pd.ABSUNIQUEID AND ad.NAMENAME ='MachineNo'
WHERE  pd.CODE ='$rowdb2[DEMAND_KGF]'
GROUP BY ad.VALUESTRING
";
$stmt5   = db2_prepare($conn1,$sqlDB25);
db2_execute($stmt5);
$rowdb25 = db2_fetch_assoc($stmt5);

$sqlDB26 = " SELECT
    STOCKTRANSACTION.PROJECTCODE,
    STOCKTRANSACTION.ORDERCODE,
    STOCKTRANSACTION.ORDERLINE,
    STOCKTRANSACTION.LOTCODE,
    STOCKTRANSACTION.ITEMTYPECODE,
    STOCKTRANSACTION.DECOSUBCODE01,
    STOCKTRANSACTION.DECOSUBCODE02,
    STOCKTRANSACTION.DECOSUBCODE03,
    STOCKTRANSACTION.DECOSUBCODE04,
    LISTAGG(DISTINCT(TRIM(INTERNALDOCUMENTLINE.INTERNALREFERENCE)),
    ',') AS INTERNALREFERENCE
FROM
    STOCKTRANSACTION STOCKTRANSACTION
LEFT JOIN INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE 
ON
    STOCKTRANSACTION.ORDERCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE
    AND 
STOCKTRANSACTION.ORDERLINE = INTERNALDOCUMENTLINE.ORDERLINE
WHERE
    STOCKTRANSACTION.ORDERCOUNTERCODE = 'I02M50' AND 
    STOCKTRANSACTION.DECOSUBCODE02 = '".$rowdb2['SUBCODE02']."' AND
    STOCKTRANSACTION.DECOSUBCODE03 = '".$rowdb2['SUBCODE03']."' AND
    STOCKTRANSACTION.PROJECTCODE = '".$rowPrj['ORIGDLVSALORDLINESALORDERCODE']."' AND
    STOCKTRANSACTION.LOTCODE = '".$rowdb2['DEMAND_KGF']."'
    
GROUP BY
    STOCKTRANSACTION.PROJECTCODE,
    STOCKTRANSACTION.ORDERCODE,
    STOCKTRANSACTION.ORDERLINE,
    STOCKTRANSACTION.LOTCODE,
    STOCKTRANSACTION.ITEMTYPECODE,
    STOCKTRANSACTION.DECOSUBCODE01,
    STOCKTRANSACTION.DECOSUBCODE02,
    STOCKTRANSACTION.DECOSUBCODE03,
    STOCKTRANSACTION.DECOSUBCODE04
";
$stmt6   = db2_prepare($conn1,$sqlDB26);	
db2_execute($stmt6);
$rowdb26 = db2_fetch_assoc($stmt6);
$itemKGF= trim($rowdb26['DECOSUBCODE01'])."-".trim($rowdb26['DECOSUBCODE02'])."-".trim($rowdb26['DECOSUBCODE03'])."-".trim($rowdb26['DECOSUBCODE04']);
$sqlDB27 = " SELECT i.WARNA FROM (SELECT
    ITEMTYPECODE,
    SUBCODE01,
    SUBCODE02,
    SUBCODE03,
    SUBCODE04,
    SUBCODE05,
    SUBCODE06,
    SUBCODE07,
    SUBCODE08,
    SUBCODE09,
    SUBCODE10,
    CASE
        WHEN WARNA = 'NULL' THEN WARNA_FKF
        ELSE WARNA
    END AS WARNA,
    WARNA_DASAR
FROM
    (
    SELECT
        PRODUCT.ITEMTYPECODE AS ITEMTYPECODE,
        PRODUCT.SUBCODE01 AS SUBCODE01,
        PRODUCT.SUBCODE02 AS SUBCODE02,
        PRODUCT.SUBCODE03 AS SUBCODE03,
        PRODUCT.SUBCODE04 AS SUBCODE04,
        PRODUCT.SUBCODE05 AS SUBCODE05,
        PRODUCT.SUBCODE06 AS SUBCODE06,
        PRODUCT.SUBCODE07 AS SUBCODE07,
        PRODUCT.SUBCODE08 AS SUBCODE08,
        PRODUCT.SUBCODE09 AS SUBCODE09,
        PRODUCT.SUBCODE10 AS SUBCODE10,
        CASE
            WHEN PRODUCT.ITEMTYPECODE = 'KFF'
            AND PRODUCT.SUBCODE07 = '-' THEN A.LONGDESCRIPTION
            WHEN PRODUCT.ITEMTYPECODE = 'KFF'
            AND PRODUCT.SUBCODE07 <> '-'
            OR PRODUCT.SUBCODE07 <> '' THEN B.COLOR_PRT
            ELSE 'NULL'
        END AS WARNA,
        CASE
            WHEN PRODUCT.ITEMTYPECODE = 'FKF'
            AND LOCATE('-', PRODUCT.LONGDESCRIPTION) = 0 THEN PRODUCT.LONGDESCRIPTION
            WHEN PRODUCT.ITEMTYPECODE = 'FKF'
            AND LOCATE('-', PRODUCT.LONGDESCRIPTION) > 0 THEN SUBSTR(PRODUCT.LONGDESCRIPTION , 1, LOCATE('-', PRODUCT.LONGDESCRIPTION)-1)
            ELSE 'NULL'
        END AS WARNA_FKF,
        WARNA_DASAR
    FROM
        PRODUCT PRODUCT
    LEFT JOIN(
        SELECT
            CAST(SUBSTR(RECIPE.SUBCODE01, 1, LOCATE('/', RECIPE.SUBCODE01)-1) AS CHARACTER(10)) AS ARTIKEL,
            CAST(SUBSTR(RECIPE.SUBCODE01, LOCATE('/', RECIPE.SUBCODE01)+ 1, 7) AS CHARACTER(10)) AS NO_WARNA,
            SUBSTR(SUBSTR(RECIPE.SUBCODE01, LOCATE('/', RECIPE.SUBCODE01)+ 1), LOCATE('/', SUBSTR(RECIPE.SUBCODE01, LOCATE('/', RECIPE.SUBCODE01)+ 1))+ 1) AS CELUP,
            RECIPE.LONGDESCRIPTION,
            RECIPE.SHORTDESCRIPTION,
            RECIPE.SEARCHDESCRIPTION,
            RECIPE.NUMBERID,
            PRODUCT.SUBCODE03,
            PRODUCT.SUBCODE05,
            PRODUCT.LONGDESCRIPTION AS PRODUCT_LONG
        FROM
            RECIPE RECIPE
        LEFT JOIN PRODUCT PRODUCT ON
            SUBSTR(RECIPE.SUBCODE01, 1, LOCATE('/', RECIPE.SUBCODE01)-1) = PRODUCT.SUBCODE03
            AND SUBSTR(RECIPE.SUBCODE01, LOCATE('/', RECIPE.SUBCODE01)+ 1, 7) = PRODUCT.SUBCODE05
        WHERE
            RECIPE.ITEMTYPECODE = 'RFD'
            AND LOCATE('/', RECIPE.SUBCODE01) > 0
            AND RECIPE.SUFFIXCODE = '001'
            --            AND NOT RECIPE.SEARCHDESCRIPTION LIKE '%DELETE%' AND NOT RECIPE.SEARCHDESCRIPTION LIKE '%delete%'
            ) A ON
        PRODUCT.SUBCODE03 = A.SUBCODE03
        AND PRODUCT.SUBCODE05 = A.SUBCODE05
    LEFT JOIN(
        SELECT
            DESIGN.SUBCODE01,
            DESIGNCOMPONENT.VARIANTCODE,
            DESIGNCOMPONENT.LONGDESCRIPTION AS COLOR_PRT,
            DESIGNCOMPONENT.SHORTDESCRIPTION AS WARNA_DASAR
        FROM
            DESIGN DESIGN
        LEFT JOIN DESIGNCOMPONENT DESIGNCOMPONENT ON
            DESIGN.NUMBERID = DESIGNCOMPONENT.DESIGNNUMBERID
            AND DESIGN.SUBCODE01 = DESIGNCOMPONENT.DESIGNSUBCODE01) B ON
        PRODUCT.SUBCODE07 = B.SUBCODE01
        AND PRODUCT.SUBCODE08 = B.VARIANTCODE
    WHERE
        (PRODUCT.ITEMTYPECODE = 'KFF'
            OR PRODUCT.ITEMTYPECODE = 'FKF')
    GROUP BY
        PRODUCT.ITEMTYPECODE,
        PRODUCT.SUBCODE01,
        PRODUCT.SUBCODE02,
        PRODUCT.SUBCODE03,
        PRODUCT.SUBCODE04,
        PRODUCT.SUBCODE05,
        PRODUCT.SUBCODE06,
        PRODUCT.SUBCODE07,
        PRODUCT.SUBCODE08,
        PRODUCT.SUBCODE09,
        PRODUCT.SUBCODE10,
         PRODUCT.LONGDESCRIPTION,
        A.LONGDESCRIPTION,
        B.COLOR_PRT,
        WARNA_DASAR,
        PRODUCT.SHORTDESCRIPTION)) i      
   WHERE i.ITEMTYPECODE='".$rowdb2['ITEMTYPEAFICODE']."' AND
i.SUBCODE01 = '".$rowdb2['SUBCODE01']."' AND
i.SUBCODE02 = '".$rowdb2['SUBCODE02']."' AND
i.SUBCODE03 = '".$rowdb2['SUBCODE03']."' AND
i.SUBCODE04 = '".$rowdb2['SUBCODE04']."' AND
i.SUBCODE05 = '".$rowdb2['SUBCODE05']."' AND
i.SUBCODE06 = '".$rowdb2['SUBCODE06']."' AND
i.SUBCODE07 = '".$rowdb2['SUBCODE07']."' AND
i.SUBCODE08 = '".$rowdb2['SUBCODE08']."'     
";
$stmt7   = db2_prepare($conn1,$sqlDB27);
db2_execute($stmt7);
$rowdb27 = db2_fetch_assoc($stmt7);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="styles_cetak.css" rel="stylesheet" type="text/css">
  <title>Cetak Bagi Kain Greige</title>
  <script>

  </script>
  <style>
    .table-list td {
      color: #333;
      font-size: 10px;
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
      font-size: 10px;
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
  <table width="100%" border="0" style="font-size: 10px;">
    <tbody>
      <tr>
          <td width="14%" align="left" valign="top">Prod. Demand KFF</td>
          <td width="1%" align="center" valign="top">:</td>
          <td width="22%" align="left" valign="top"><?php echo $rowBK['DEMAND']?></td>
          <td width="14%" align="left" valign="top">&nbsp;</td>
          <td width="1%" align="left" valign="top">&nbsp;</td>
          <td width="16%" align="left" valign="top">&nbsp;</td>
          <td width="11%" align="left" valign="top">&nbsp;</td>
          <td align="left" width="1%">&nbsp;</td>
          <td align="left" width="20%">&nbsp;</td>
      </tr> 
      <tr>
          <td width="14%" align="left" valign="top">Order</td>
          <td width="1%" align="center" valign="top">:</td>
          <td width="22%" align="left" valign="top"><?php echo $rowdb2['PRODUCTIONORDERCODE'];?></td>
          <td width="14%" align="right" valign="top">Lebar x Gramasi</td>
          <td width="1%" align="left" valign="top">:</td>
          <td width="16%" align="left" valign="top"><?php echo $rowLG['LG1']; // echo number_format(round($rowLG['WIDTHGROSS'],2),2)." x ".number_format(round($rowLG['VALUEDECIMAL'],2),2); ?></td>
          <td width="11%" align="right" valign="top">Project Akhir</td>
          <td width="1%" align="left" valign="top">:</td>
          <td width="20%" align="left" valign="top"><?php echo $rowdb2['ORIGDLVSALORDLINESALORDERCODE'];?></td>
      </tr> 
      <tr>
          <td width="14%" align="left" valign="top">Project</td>
          <td width="1%" align="center" valign="top">:</td>
          <td width="22%" align="left" valign="top"><?php echo $rowPrj2['PROJECTCODE'];?></td>
          <td width="14%" align="right" valign="top">Mesin KNT</td>
          <td width="1%" align="left" valign="top">:</td>
          <td width="16%" align="left" valign="top"><?php echo $rowLG['MESIN1'];//echo $rowdb25['NO_MESIN']; ?></td>
          <td width="11%" align="right" valign="top">Panjang Benang</td>
          <td width="1%" align="left" valign="top">:</td>
          <td width="20%" align="left" valign="top"><?= $rowdb2['PANJANG_BENANG']; ?></td>
      </tr> 
      <tr>
          <td width="14%" align="left" valign="top">Jenis Kain</td>
          <td width="1%" align="center" valign="top">:</td>
          <td width="22%" align="left" valign="top"><?php echo $rowdb2['LONGDESCRIPTION'];?></td>
          <td width="14%" align="right" valign="top">Hasil Inspek</td>
          <td width="1%" align="left" valign="top">:</td>
          <td colspan="2" align="left" valign="top"><?php echo $rowdb26['INTERNALREFERENCE']; ?></td>
          <td width="1%" align="left" valign="top">&nbsp;</td>
          <td width="20%" align="left" valign="top">&nbsp;</td>
      </tr> 
      <tr>
          <td width="14%" align="left" valign="top">Full Item KFF</td>
          <td width="1%" align="center" valign="top">:</td>
          <td width="22%" align="left" valign="top"><?php echo $itemKFF; ?></td>
          <td width="14%" align="right" valign="top">Warna</td>
          <td width="1%" align="left" valign="top">:</td>
          <td colspan="2" align="left" valign="top"><?php echo $rowdb27['WARNA']; ?></td>
          <td width="1%" align="left" valign="top">&nbsp;</td>
          <td width="20%" align="left" valign="top">&nbsp;</td>
      </tr>
		
      <tr>
          <td width="14%" align="left" valign="top">Lot Benang</td>
          <td width="1%" align="center" valign="top">:</td>
          <td width="22%" align="left" valign="top"><?php echo $rowdbLBNG['EXTERNALREFERENCE']; ?></td>
          <td width="14%" align="right" valign="top">Tgl Bagi</td>
          <td width="1%" align="left" valign="top">:</td>
          <td width="16%" align="left" valign="top"><?php $date=date_create($rowBG['TRANSACTIONDATE']);
			  							echo date_format($date,"d F Y"); ?></td>
          <td width="11%" align="right" valign="top">Full Item KGF</td>
          <td width="1%" align="left" valign="top">:</td>
          <td width="20%" align="left" valign="top"><?php echo $itemKGF; ?></td>
      </tr> 
    </tbody>
  </table>
<?php
$sqlC = " SELECT
    PRODUCTIONDEMAND.CODE,
    A.PRODUCTIONORDERCODE,
    SUBSTR(STOCKTRANSACTION.ITEMELEMENTCODE, 1, 8) AS DEMAND_KGF,
    STOCKTRANSACTION.ITEMTYPECODE,
    COUNT(STOCKTRANSACTION.ITEMELEMENTCODE) AS ROL,
    STOCKTRANSACTION.ITEMELEMENTCODE,
    STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
    STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
    SUM(STOCKTRANSACTION.USERPRIMARYQUANTITY) AS QTY_KG,
    STOCKTRANSACTION.USERPRIMARYUOMCODE,
    SUM(STOCKTRANSACTION.USERSECONDARYQUANTITY) AS QTY_YD,
    STOCKTRANSACTION.USERSECONDARYUOMCODE,
    STOCKTRANSACTION.ORDERLINE,
    STOCKTRANSACTION.DECOSUBCODE01,
    STOCKTRANSACTION.DECOSUBCODE02,
    STOCKTRANSACTION.DECOSUBCODE03,
    STOCKTRANSACTION.DECOSUBCODE04,
    STOCKTRANSACTION.LOTCODE  
FROM
    PRODUCTIONDEMAND PRODUCTIONDEMAND
LEFT JOIN (
    SELECT
        PRODUCTIONRESERVATION.ORDERCODE,
        PRODUCTIONRESERVATION.PRODUCTIONORDERCODE
    FROM
        PRODUCTIONRESERVATION PRODUCTIONRESERVATION
    WHERE
        (PRODUCTIONRESERVATION.ITEMTYPEAFICODE = 'KGF'
            OR PRODUCTIONRESERVATION.ITEMTYPEAFICODE = 'FKG')
) A ON
    PRODUCTIONDEMAND.CODE = A.ORDERCODE
LEFT JOIN STOCKTRANSACTION STOCKTRANSACTION
ON
    A.PRODUCTIONORDERCODE = STOCKTRANSACTION.ORDERCODE
WHERE
    STOCKTRANSACTION.ONHANDUPDATE > 1
    AND (STOCKTRANSACTION.ITEMTYPECODE = 'KGF'
        OR STOCKTRANSACTION.ITEMTYPECODE = 'FKG')
    AND PRODUCTIONDEMAND.CODE='$demand'    
GROUP BY 
 	PRODUCTIONDEMAND.CODE,
    A.PRODUCTIONORDERCODE,
    STOCKTRANSACTION.LOTCODE,
    STOCKTRANSACTION.ITEMTYPECODE,
    STOCKTRANSACTION.ITEMELEMENTCODE,
    STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
    STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
    STOCKTRANSACTION.USERPRIMARYQUANTITY,
    STOCKTRANSACTION.USERPRIMARYUOMCODE,
    STOCKTRANSACTION.USERSECONDARYQUANTITY,
    STOCKTRANSACTION.USERSECONDARYUOMCODE,
    STOCKTRANSACTION.ORDERLINE,
    STOCKTRANSACTION.DECOSUBCODE01,
    STOCKTRANSACTION.DECOSUBCODE02,
    STOCKTRANSACTION.DECOSUBCODE03,
    STOCKTRANSACTION.DECOSUBCODE04";
	$stmtC   = db2_prepare($conn1,$sqlC);	
	db2_execute($stmtC);
	$jml = 0;
	while ($rowBTS = db2_fetch_assoc($stmtC)) {
		$jml++;
	}
	//$jml = db2_num_rows($stmtC);
$batas=ceil($jml/3);
$lawal=$batas*1-$batas;
$ltgh=$batas*2-$batas;
$lakhr=$batas*3-$batas;
?>	
  <table width="100%" border="0">
    <tbody>
      <tr>
        <td width="17%" align="left" valign="top" ><table width="100%" border="1" class="table-list1">
          <tbody>
            <tr>
              <td width="60%" align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">ELEMENT</td>
              <td width="10%" align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">PROJECT KGF</td>
              <td width="14%" align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">QTY(KG)</td>
              <td width="16%" align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">YD</td>
              <td width="16%" align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">LOKASI</td>
            </tr>
<?php					  
	$sqlC1 = " SELECT
    PRODUCTIONDEMAND.CODE,
    A.PRODUCTIONORDERCODE,
    SUBSTR(STOCKTRANSACTION.ITEMELEMENTCODE, 1, 8) AS DEMAND_KGF,
    STOCKTRANSACTION.ITEMTYPECODE,
    COUNT(STOCKTRANSACTION.ITEMELEMENTCODE) AS ROL,
    STOCKTRANSACTION.ITEMELEMENTCODE,
    STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
    STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
    SUM(STOCKTRANSACTION.USERPRIMARYQUANTITY) AS QTY_KG,
    STOCKTRANSACTION.USERPRIMARYUOMCODE,
    SUM(STOCKTRANSACTION.USERSECONDARYQUANTITY) AS QTY_YD,
    STOCKTRANSACTION.USERSECONDARYUOMCODE,
    STOCKTRANSACTION.ORDERLINE,
    STOCKTRANSACTION.DECOSUBCODE01,
    STOCKTRANSACTION.DECOSUBCODE02,
    STOCKTRANSACTION.DECOSUBCODE03,
    STOCKTRANSACTION.DECOSUBCODE04,
    STOCKTRANSACTION.LOTCODE  
FROM
    PRODUCTIONDEMAND PRODUCTIONDEMAND
LEFT JOIN (
    SELECT
        PRODUCTIONRESERVATION.ORDERCODE,
        PRODUCTIONRESERVATION.PRODUCTIONORDERCODE
    FROM
        PRODUCTIONRESERVATION PRODUCTIONRESERVATION
    WHERE
        (PRODUCTIONRESERVATION.ITEMTYPEAFICODE = 'KGF'
            OR PRODUCTIONRESERVATION.ITEMTYPEAFICODE = 'FKG')
) A ON
    PRODUCTIONDEMAND.CODE = A.ORDERCODE
LEFT JOIN STOCKTRANSACTION STOCKTRANSACTION
ON
    A.PRODUCTIONORDERCODE = STOCKTRANSACTION.ORDERCODE
WHERE
    STOCKTRANSACTION.ONHANDUPDATE > 1
    AND (STOCKTRANSACTION.ITEMTYPECODE = 'KGF'
        OR STOCKTRANSACTION.ITEMTYPECODE = 'FKG')
    AND PRODUCTIONDEMAND.CODE='$demand'    
GROUP BY 
 	PRODUCTIONDEMAND.CODE,
    A.PRODUCTIONORDERCODE,
    STOCKTRANSACTION.LOTCODE,
    STOCKTRANSACTION.ITEMTYPECODE,
    STOCKTRANSACTION.ITEMELEMENTCODE,
    STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
    STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
    STOCKTRANSACTION.USERPRIMARYQUANTITY,
    STOCKTRANSACTION.USERPRIMARYUOMCODE,
    STOCKTRANSACTION.USERSECONDARYQUANTITY,
    STOCKTRANSACTION.USERSECONDARYUOMCODE,
    STOCKTRANSACTION.ORDERLINE,
    STOCKTRANSACTION.DECOSUBCODE01,
    STOCKTRANSACTION.DECOSUBCODE02,
    STOCKTRANSACTION.DECOSUBCODE03,
    STOCKTRANSACTION.DECOSUBCODE04
	LIMIT $lawal,$batas";
	$stmtC1   = db2_prepare($conn1,$sqlC1);
			  db2_execute($stmtC1);
	//}				  
    while($rowC1 = db2_fetch_assoc($stmtC1)){	
      $project1 = "SELECT
	                    PROJECTCODE,ORDERLINE,ORDERCODE 
                  FROM
                    DB2ADMIN.STOCKTRANSACTION
                  WHERE
                    ITEMELEMENTCODE = '$rowC1[ITEMELEMENTCODE]'
                    AND LOGICALWAREHOUSECODE = 'M021'
                  ORDER BY (TRANSACTIONDATE || ' ' || TRANSACTIONTIME) DESC
                  LIMIT 1";
      $stmtPrj1 = db2_prepare($conn1,$project1);
				db2_execute($stmtPrj1);
      $rowPrj1 = db2_fetch_assoc($stmtPrj1);
                  ?>  
            <tr>
              <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo $rowC1['ITEMELEMENTCODE']; ?></td>
              <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php 
                                                      // echo substr($rowC1['ITEMELEMENTCODE'],0,8); 
                                                      echo $rowPrj1['PROJECTCODE'];?></td>
              <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo number_format(round($rowC1['QTY_KG'],2),2); ?></td>
              <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo number_format(round($rowC1['QTY_YD'],2),2); ?></td>
              <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo $rowC1['WAREHOUSELOCATIONCODE']; ?></td>
            </tr>
		<?php $tKgC1+=round($rowC1['QTY_KG'],2);
				$tYDC1+=round($rowC1['QTY_YD'],2);
			  $tRolC1+=$rowC1['ROL'];								 
											 } ?>	  
          </tbody>
        </table></td>
        <td width="16%" align="left" valign="top" ><table width="100%" border="1" class="table-list1">
          <tbody>
            <tr>
              <td width="60%" align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">ELEMENT</td>
              <td width="10%" align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">PROJECT KGF</td>
              <td width="14%" align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">QTY(KG)</td>
              <td width="16%" align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">YD</td>
              <td width="16%" align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">LOKASI</td>
            </tr>
            <?php  
	$sqlC2 = " SELECT
    PRODUCTIONDEMAND.CODE,
    A.PRODUCTIONORDERCODE,
    SUBSTR(STOCKTRANSACTION.ITEMELEMENTCODE, 1, 8) AS DEMAND_KGF,
    STOCKTRANSACTION.ITEMTYPECODE,
    COUNT(STOCKTRANSACTION.ITEMELEMENTCODE) AS ROL,
    STOCKTRANSACTION.ITEMELEMENTCODE,
    STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
    STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
    SUM(STOCKTRANSACTION.USERPRIMARYQUANTITY) AS QTY_KG,
    STOCKTRANSACTION.USERPRIMARYUOMCODE,
    SUM(STOCKTRANSACTION.USERSECONDARYQUANTITY) AS QTY_YD,
    STOCKTRANSACTION.USERSECONDARYUOMCODE,
    STOCKTRANSACTION.ORDERLINE,
    STOCKTRANSACTION.DECOSUBCODE01,
    STOCKTRANSACTION.DECOSUBCODE02,
    STOCKTRANSACTION.DECOSUBCODE03,
    STOCKTRANSACTION.DECOSUBCODE04,
    STOCKTRANSACTION.LOTCODE  
FROM
    PRODUCTIONDEMAND PRODUCTIONDEMAND
LEFT JOIN (
    SELECT
        PRODUCTIONRESERVATION.ORDERCODE,
        PRODUCTIONRESERVATION.PRODUCTIONORDERCODE
    FROM
        PRODUCTIONRESERVATION PRODUCTIONRESERVATION
    WHERE
        (PRODUCTIONRESERVATION.ITEMTYPEAFICODE = 'KGF'
            OR PRODUCTIONRESERVATION.ITEMTYPEAFICODE = 'FKG')
) A ON
    PRODUCTIONDEMAND.CODE = A.ORDERCODE
LEFT JOIN STOCKTRANSACTION STOCKTRANSACTION
ON
    A.PRODUCTIONORDERCODE = STOCKTRANSACTION.ORDERCODE
WHERE
    STOCKTRANSACTION.ONHANDUPDATE > 1
    AND (STOCKTRANSACTION.ITEMTYPECODE = 'KGF'
        OR STOCKTRANSACTION.ITEMTYPECODE = 'FKG')
    AND PRODUCTIONDEMAND.CODE='$demand'    
GROUP BY 
 	PRODUCTIONDEMAND.CODE,
    A.PRODUCTIONORDERCODE,
    STOCKTRANSACTION.LOTCODE,
    STOCKTRANSACTION.ITEMTYPECODE,
    STOCKTRANSACTION.ITEMELEMENTCODE,
    STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
    STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
    STOCKTRANSACTION.USERPRIMARYQUANTITY,
    STOCKTRANSACTION.USERPRIMARYUOMCODE,
    STOCKTRANSACTION.USERSECONDARYQUANTITY,
    STOCKTRANSACTION.USERSECONDARYUOMCODE,
    STOCKTRANSACTION.ORDERLINE,
    STOCKTRANSACTION.DECOSUBCODE01,
    STOCKTRANSACTION.DECOSUBCODE02,
    STOCKTRANSACTION.DECOSUBCODE03,
    STOCKTRANSACTION.DECOSUBCODE04
	LIMIT $ltgh,$batas";
	$stmtC2   = db2_prepare($conn1,$sqlC2);
			  db2_execute($stmtC2);
	//}				  
    while($rowC2 = db2_fetch_assoc($stmtC2)){	
      $project2 = "SELECT
	                    PROJECTCODE,ORDERLINE,ORDERCODE 
                  FROM
                    DB2ADMIN.STOCKTRANSACTION
                  WHERE
                    ITEMELEMENTCODE = '$rowC2[ITEMELEMENTCODE]'
                    AND LOGICALWAREHOUSECODE = 'M021'
                  ORDER BY (TRANSACTIONDATE || ' ' || TRANSACTIONTIME) DESC
                  LIMIT 1";
      $stmtPrj2 = db2_prepare($conn1,$project2);
		db2_execute($stmtPrj2);
      $rowPrj2 = db2_fetch_assoc($stmtPrj2);
      ?>
            <tr>
              <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo $rowC2['ITEMELEMENTCODE']; ?></td>
              <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php 
                                                //  echo substr($rowC2['ITEMELEMENTCODE'],0,8); 
                                                echo $rowPrj2['PROJECTCODE'];
                                                ?></td>
              <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo number_format(round($rowC2['QTY_KG'],2),2); ?></td>
              <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo number_format(round($rowC2['QTY_YD'],2),2); ?></td>
              <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo $rowC2['WAREHOUSELOCATIONCODE']; ?></td>
            </tr>
            <?php $tKgC2+=round($rowC2['QTY_KG'],2);
				$tYDC2+=round($rowC2['QTY_YD'],2);							 
			  $tRolC2+=$rowC2['ROL'];								 
											 } ?>
          </tbody>
        </table></td>
        <td width="16%" align="left" valign="top" ><table width="100%" border="1" class="table-list1">
          <tbody>
            <tr>
              <td width="60%" align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">ELEMENT</td>
              <td width="10%" align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">PROJECT KGF</td>
              <td width="14%" align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">QTY(KG)</td>
              <td width="16%" align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">YD</td>
              <td width="16%" align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">LOKASI</td>
            </tr>
            <?php					  
	$sqlC3 = " SELECT
    PRODUCTIONDEMAND.CODE,
    A.PRODUCTIONORDERCODE,
    SUBSTR(STOCKTRANSACTION.ITEMELEMENTCODE, 1, 8) AS DEMAND_KGF,
    STOCKTRANSACTION.ITEMTYPECODE,
    COUNT(STOCKTRANSACTION.ITEMELEMENTCODE) AS ROL,
    STOCKTRANSACTION.ITEMELEMENTCODE,
    STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
    STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
    SUM(STOCKTRANSACTION.USERPRIMARYQUANTITY) AS QTY_KG,
    STOCKTRANSACTION.USERPRIMARYUOMCODE,
    SUM(STOCKTRANSACTION.USERSECONDARYQUANTITY) AS QTY_YD,
    STOCKTRANSACTION.USERSECONDARYUOMCODE,
    STOCKTRANSACTION.ORDERLINE,
    STOCKTRANSACTION.DECOSUBCODE01,
    STOCKTRANSACTION.DECOSUBCODE02,
    STOCKTRANSACTION.DECOSUBCODE03,
    STOCKTRANSACTION.DECOSUBCODE04,
    STOCKTRANSACTION.LOTCODE  
FROM
    PRODUCTIONDEMAND PRODUCTIONDEMAND
LEFT JOIN (
    SELECT
        PRODUCTIONRESERVATION.ORDERCODE,
        PRODUCTIONRESERVATION.PRODUCTIONORDERCODE
    FROM
        PRODUCTIONRESERVATION PRODUCTIONRESERVATION
    WHERE
        (PRODUCTIONRESERVATION.ITEMTYPEAFICODE = 'KGF'
            OR PRODUCTIONRESERVATION.ITEMTYPEAFICODE = 'FKG')
) A ON
    PRODUCTIONDEMAND.CODE = A.ORDERCODE
LEFT JOIN STOCKTRANSACTION STOCKTRANSACTION
ON
    A.PRODUCTIONORDERCODE = STOCKTRANSACTION.ORDERCODE
WHERE
    STOCKTRANSACTION.ONHANDUPDATE > 1
    AND (STOCKTRANSACTION.ITEMTYPECODE = 'KGF'
        OR STOCKTRANSACTION.ITEMTYPECODE = 'FKG')
    AND PRODUCTIONDEMAND.CODE='$demand'    
GROUP BY 
 	PRODUCTIONDEMAND.CODE,
    A.PRODUCTIONORDERCODE,
    STOCKTRANSACTION.LOTCODE,
    STOCKTRANSACTION.ITEMTYPECODE,
    STOCKTRANSACTION.ITEMELEMENTCODE,
    STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
    STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
    STOCKTRANSACTION.USERPRIMARYQUANTITY,
    STOCKTRANSACTION.USERPRIMARYUOMCODE,
    STOCKTRANSACTION.USERSECONDARYQUANTITY,
    STOCKTRANSACTION.USERSECONDARYUOMCODE,
    STOCKTRANSACTION.ORDERLINE,
    STOCKTRANSACTION.DECOSUBCODE01,
    STOCKTRANSACTION.DECOSUBCODE02,
    STOCKTRANSACTION.DECOSUBCODE03,
    STOCKTRANSACTION.DECOSUBCODE04
	LIMIT $lakhr,$batas";
	$stmtC3   = db2_prepare($conn1,$sqlC3);
			  db2_execute($stmtC3);
	//}				  
    while($rowC3 = db2_fetch_assoc($stmtC3)){	
      $project3 = "SELECT
	                    PROJECTCODE,ORDERLINE,ORDERCODE 
                  FROM
                    DB2ADMIN.STOCKTRANSACTION
                  WHERE
                    ITEMELEMENTCODE = '$rowC3[ITEMELEMENTCODE]'
                    AND LOGICALWAREHOUSECODE = 'M021'
                  ORDER BY (TRANSACTIONDATE || ' ' || TRANSACTIONTIME) DESC
                  LIMIT 1";
      $stmtPrj3 = db2_prepare($conn1,$project3);
		db2_execute($stmtPrj3);
      $rowPrj3 = db2_fetch_assoc($stmtPrj3);
			  ?>
            <tr>
              <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo $rowC3['ITEMELEMENTCODE']; ?></td>
              <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php 
                                                      // echo substr($rowC3['ITEMELEMENTCODE'],0,8); 
                                                      echo $rowPrj3['PROJECTCODE'];
                                                      ?></td>
              <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo number_format(round($rowC3['QTY_KG'],2),2); ?></td>
              <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo number_format(round($rowC3['QTY_YD'],2),2); ?></td>
              <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo $rowC3['WAREHOUSELOCATIONCODE']; ?></td>
            </tr>
            <?php $tKgC3+=round($rowC3['QTY_KG'],2);
				$tYDC3+=round($rowC3['QTY_YD'],2);							 
			  $tRolC3+=$rowC3['ROL'];								 
											 } ?>
          </tbody>
        </table></td>
      </tr>
      <tr>
        <td align="left" valign="top" >&nbsp;</td>
        <td align="left" valign="top" >&nbsp;</td>
        <td align="left" valign="top" ><table width="100%" border="1" class="table-list1">
          <tbody>
            <tr>
              <td width="60%" align="right" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><strong>Total</strong></td>
              <td width="10%" align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><strong><?php echo $tRolC1+$tRolC2+$tRolC3;?></strong></td>
              <td width="14%" align="right" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><strong><?php echo number_format($tKgC1+$tKgC2+$tKgC3,2);?></strong></td>
              <td width="16%" align="right" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><strong><?php echo number_format($tYDC1+$tYDC2+$tYDC3,2);?></strong></td>
              <td width="16%" align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><font color="#FFFFFF">LOKASI</font></td>
            </tr>
          </tbody>
        </table></td>
      </tr>
    </tbody>
  </table>
  <p>&nbsp;</p>
  <blockquote>&nbsp;</blockquote>
  <p>&nbsp;</p>  

</body>

</html>