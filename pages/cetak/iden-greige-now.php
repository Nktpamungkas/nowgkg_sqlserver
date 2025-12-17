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
INTERNALDOCUMENTLINE.ORDERLINE,
INTERNALDOCUMENTLINE.ITEMTYPEAFICODE,
INTERNALDOCUMENTLINE.SUBCODE01,
INTERNALDOCUMENTLINE.SUBCODE02,
INTERNALDOCUMENTLINE.SUBCODE03,
INTERNALDOCUMENTLINE.SUBCODE04,
INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
INTERNALDOCUMENTLINE.PROJECTCODE,
INTERNALDOCUMENTLINE.INTERNALREFERENCEDATE,
ITXVIEWBUKMUTGKGKNT.RECEIVINGDATE,
COUNT(ITXVIEWBUKMUTGKGKNT.ITEMELEMENTCODE) AS ROLL,
ITXVIEWBUKMUTGKGKNT.LOTCODE,
SUM(ITXVIEWBUKMUTGKGKNT.USERPRIMARYQUANTITY) AS JML_KG,
ITXVIEWBUKMUTGKGKNT.LOGICALWAREHOUSECODE,
ORDERPARTNERBRAND.LONGDESCRIPTION AS BUYER
FROM INTERNALDOCUMENT INTERNALDOCUMENT
LEFT JOIN INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON INTERNALDOCUMENT.PROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE AND 
INTERNALDOCUMENT.PROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE 
LEFT JOIN ITXVIEWBUKMUTGKGKNT ITXVIEWBUKMUTGKGKNT ON ITXVIEWBUKMUTGKGKNT.INTDOCPROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE AND 
ITXVIEWBUKMUTGKGKNT.INTDOCUMENTPROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE AND 
ITXVIEWBUKMUTGKGKNT.ORDERLINE = INTERNALDOCUMENTLINE.ORDERLINE AND 
ITXVIEWBUKMUTGKGKNT.LOGICALWAREHOUSECODE = INTERNALDOCUMENTLINE.WAREHOUSECODE 
LEFT JOIN SALESORDER SALESORDER ON INTERNALDOCUMENTLINE.PROJECTCODE = SALESORDER.CODE 
LEFT JOIN ORDERPARTNERBRAND ORDERPARTNERBRAND ON SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE = ORDERPARTNERBRAND.ORDPRNCUSTOMERSUPPLIERCODE AND SALESORDER.ORDERPARTNERBRANDCODE = ORDERPARTNERBRAND.CODE 
WHERE INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE='$IntDoc' AND INTERNALDOCUMENTLINE.ORDERLINE ='$Orderline'
GROUP BY 
INTERNALDOCUMENT.PROVISIONALDOCUMENTDATE,
INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE,
INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
INTERNALDOCUMENTLINE.ORDERLINE,
INTERNALDOCUMENTLINE.ITEMTYPEAFICODE,
INTERNALDOCUMENTLINE.SUBCODE01,
INTERNALDOCUMENTLINE.SUBCODE02,
INTERNALDOCUMENTLINE.SUBCODE03,
INTERNALDOCUMENTLINE.SUBCODE04,
INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
INTERNALDOCUMENTLINE.PROJECTCODE,
INTERNALDOCUMENTLINE.INTERNALREFERENCEDATE,
ITXVIEWBUKMUTGKGKNT.RECEIVINGDATE,
ITXVIEWBUKMUTGKGKNT.LOTCODE,
ITXVIEWBUKMUTGKGKNT.LOGICALWAREHOUSECODE,
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

$lkQry = " SELECT
	LISTAGG(DISTINCT TRIM(WAREHOUSELOCATIONCODE),
	' ') AS WAREHOUSELOCATIONCODE
FROM
	BALANCE b
WHERE
	(b.ITEMTYPECODE = 'KGF' OR b.ITEMTYPECODE = 'FKG')
	AND b.PROJECTCODE = '" . $rowdb2['PROJECTCODE'] . "'
	AND b.DECOSUBCODE02 = '" . trim($rowdb2['SUBCODE02']) . "'
	AND b.DECOSUBCODE03 = '" . trim($rowdb2['SUBCODE03']) . "'
	AND b.DECOSUBCODE04 = '" . trim($rowdb2['SUBCODE04']) . "' ";
$stmtLK   = db2_exec($conn1, $lkQry, array('cursor' => DB2_SCROLLABLE));
$rowLK = db2_fetch_assoc($stmtLK);

// MUNCULIN QTY PERMINTAAN RAJUT DARI KNT
$sqlDB210 = "SELECT
                SUM(a.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY,
                SUM(a3.VALUEDECIMAL) AS QTYSALIN
              FROM
                (
                  SELECT
                    PRODUCTIONDEMAND.PROJECTCODE,
                    PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE,
                    PRODUCTIONDEMAND.COMPANYCODE,
                    PRODUCTIONDEMAND.COUNTERCODE,
                    PRODUCTIONDEMAND.CODE,
                    PRODUCTIONDEMAND.ITEMTYPEAFICODE,
                    PRODUCTIONDEMAND.SUBCODE01,
                    PRODUCTIONDEMAND.SUBCODE02,
                    PRODUCTIONDEMAND.SUBCODE03,
                    PRODUCTIONDEMAND.SUBCODE04,
                    PRODUCTIONDEMAND.BASEPRIMARYUOMCODE,
                    PRODUCTIONDEMAND.BASEPRIMARYQUANTITY,
                    PRODUCTIONDEMAND.FINALPLANNEDDATE,
                    PRODUCTIONDEMAND.FINALEFFECTIVEDATE,
                    FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION,
                    BUSINESSPARTNER.LEGALNAME1,
                    PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE,
                    PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE,
                    PRODUCTIONORDER.PROGRESSSTATUS
                  FROM
                    PRODUCTIONDEMAND PRODUCTIONDEMAND
                  LEFT OUTER JOIN DB2ADMIN.COMPANY COMPANY ON	PRODUCTIONDEMAND.COMPANYCODE = COMPANY.CODE
                  LEFT OUTER JOIN DB2ADMIN.PRODUCTIONCUSTOMIZEDOPTIONS PRODUCTIONCUSTOMIZEDOPTIONS ON PRODUCTIONDEMAND.COMPANYCODE = PRODUCTIONCUSTOMIZEDOPTIONS.COMPANYCODE
                  LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON PRODUCTIONDEMAND.COMPANYCODE = FULLITEMKEYDECODER.COMPANYCODE
                    AND PRODUCTIONDEMAND.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
                  LEFT OUTER JOIN DB2ADMIN.ORDERPARTNER ORDERPARTNER ON PRODUCTIONDEMAND.CUSTOMERCODE = ORDERPARTNER.CUSTOMERSUPPLIERCODE
                  LEFT OUTER JOIN DB2ADMIN.BUSINESSPARTNER BUSINESSPARTNER ON	ORDERPARTNER.ORDERBUSINESSPARTNERNUMBERID = BUSINESSPARTNER.NUMBERID
                  LEFT OUTER JOIN DB2ADMIN.SCHEDULESOFSTEPSPLITS SCHEDULESOFSTEPSPLITS ON PRODUCTIONDEMAND.CODE = SCHEDULESOFSTEPSPLITS.CODE
                  LEFT JOIN DB2ADMIN.PRODUCTIONDEMANDSTEP PRODUCTIONDEMANDSTEP ON	PRODUCTIONDEMAND.CODE = PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE
                    AND PRODUCTIONDEMAND.COMPANYCODE = PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCOMPANYCODE
                  LEFT JOIN DB2ADMIN.PRODUCTIONORDER PRODUCTIONORDER ON PRODUCTIONORDER.CODE = PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE
                  GROUP BY
                    PRODUCTIONDEMAND.PROJECTCODE,
                    PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE,
                    PRODUCTIONDEMAND.COMPANYCODE,
                    PRODUCTIONDEMAND.COUNTERCODE,
                    PRODUCTIONDEMAND.CODE,
                    PRODUCTIONDEMAND.ITEMTYPEAFICODE,
                    PRODUCTIONDEMAND.SUBCODE01,
                    PRODUCTIONDEMAND.SUBCODE02,
                    PRODUCTIONDEMAND.SUBCODE03,
                    PRODUCTIONDEMAND.SUBCODE04,
                    PRODUCTIONDEMAND.BASEPRIMARYUOMCODE,
                    PRODUCTIONDEMAND.BASEPRIMARYQUANTITY,
                    PRODUCTIONDEMAND.FINALPLANNEDDATE,
                    PRODUCTIONDEMAND.FINALEFFECTIVEDATE,
                    FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION,
                    BUSINESSPARTNER.LEGALNAME1,
                    PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE,
                    PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE,
                    PRODUCTIONORDER.PROGRESSSTATUS
                ) a
              LEFT OUTER JOIN PRODUCTIONDEMAND p ON p.CODE = a.PRODUCTIONDEMANDCODE
              LEFT OUTER JOIN ADSTORAGE a2 ON p.ABSUNIQUEID = a2.UNIQUEID AND a2.NAMENAME = 'StatusRMP'
              LEFT OUTER JOIN ADSTORAGE a3 ON	p.ABSUNIQUEID = a3.UNIQUEID AND a3.NAMENAME = 'QtySalin'
              WHERE
                a.ITEMTYPEAFICODE = 'KGF'
                AND (a.PROJECTCODE = '$rowdb2[PROJECTCODE]'	OR a.ORIGDLVSALORDLINESALORDERCODE = '$rowdb2[PROJECTCODE]')
                AND	a.SUBCODE02 = '" . trim($rowdb2['SUBCODE02']) . "'
                AND a.SUBCODE03 = '" . trim($rowdb2['SUBCODE03']) . "'
                AND a.SUBCODE04 = '" . trim($rowdb2['SUBCODE04']) . "'
                AND (a.PROGRESSSTATUS = '2'	OR a.PROGRESSSTATUS = '6')
                AND (NOT a2.VALUESTRING = '3' OR a2.VALUESTRING IS NULL)";
$stmt10   = db2_exec($conn1, $sqlDB210, array('cursor' => DB2_SCROLLABLE));
$rowdb210 = db2_fetch_assoc($stmt10);
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
	border-right:1px #000000 solid;">
                <font size="+1">IDENTIFIKASI KAIN GREIGE <br />
                    NO FORM : FW-20-GKG-31/00</font>
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
            border-right:1px #000000 solid;"><?php echo $rowdb2['INTDOCPROVISIONALCOUNTERCODE'] . "-" . $rowdb2['INTDOCUMENTPROVISIONALCODE'] . "-" . $rowdb2['ORDERLINE']; ?></td>
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
            border-right:1px #000000 solid;"><strong><?php echo $rowdb2['PROJECTCODE']; ?></strong></td>
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
                                                        } ?> ( <?php echo $rowdb2['SUBCODE04']; ?> )</strong></td>
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
            border-right:1px #000000 solid;"><?php echo $rowdb2['ROLL'] . " Rolls  " . $rowdb2['JML_KG'] . " KGs"; ?></td>
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
            <tr>
                <td align="left" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Lokasi Penyusunan</td>
                <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
                <td align="left" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?php echo $rowLK['WAREHOUSELOCATIONCODE']; ?></td>
            </tr>
            <tr>
                <td align="left" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">Permintaan Rajut</td>
                <td align="center" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;">:</td>
                <td align="left" style="border-bottom:1px #000000 solid;
            border-top:1px #000000 solid;
            border-left:1px #000000 solid;
            border-right:1px #000000 solid;"><?= number_format(round($rowdb210['BASEPRIMARYQUANTITY'] - $rowdb210['QTYSALIN'], 2), 2); ?></td>
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
  ITXVIEWBUKMUTGKGKNT.RECEIVINGDATE,
  ITXVIEWBUKMUTGKGKNT.ITEMELEMENTCODE,
  ITXVIEWBUKMUTGKGKNT.LOTCODE,
  ITXVIEWBUKMUTGKGKNT.USERPRIMARYQUANTITY,
  ITXVIEWBUKMUTGKGKNT.LOGICALWAREHOUSECODE 
  FROM INTERNALDOCUMENT INTERNALDOCUMENT
  LEFT JOIN INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON INTERNALDOCUMENT.PROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE AND 
  INTERNALDOCUMENT.PROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE 
  LEFT JOIN ITXVIEWBUKMUTGKGKNT ITXVIEWBUKMUTGKGKNT ON ITXVIEWBUKMUTGKGKNT.INTDOCPROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE AND 
  ITXVIEWBUKMUTGKGKNT.INTDOCUMENTPROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE AND 
  ITXVIEWBUKMUTGKGKNT.ORDERLINE = INTERNALDOCUMENTLINE.ORDERLINE AND 
  ITXVIEWBUKMUTGKGKNT.LOGICALWAREHOUSECODE = INTERNALDOCUMENTLINE.WAREHOUSECODE 
  WHERE INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE='$IntDoc' AND INTERNALDOCUMENTLINE.ORDERLINE ='$Orderline'";
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
  ITXVIEWBUKMUTGKGKNT.RECEIVINGDATE,
  ITXVIEWBUKMUTGKGKNT.ITEMELEMENTCODE,
  ITXVIEWBUKMUTGKGKNT.LOTCODE,
  ITXVIEWBUKMUTGKGKNT.USERPRIMARYQUANTITY,
  ITXVIEWBUKMUTGKGKNT.LOGICALWAREHOUSECODE 
  FROM INTERNALDOCUMENT INTERNALDOCUMENT
  LEFT JOIN INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON INTERNALDOCUMENT.PROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE AND 
  INTERNALDOCUMENT.PROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE 
  LEFT JOIN ITXVIEWBUKMUTGKGKNT ITXVIEWBUKMUTGKGKNT ON ITXVIEWBUKMUTGKGKNT.INTDOCPROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE AND 
  ITXVIEWBUKMUTGKGKNT.INTDOCUMENTPROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE AND 
  ITXVIEWBUKMUTGKGKNT.ORDERLINE = INTERNALDOCUMENTLINE.ORDERLINE AND 
  ITXVIEWBUKMUTGKGKNT.LOGICALWAREHOUSECODE = INTERNALDOCUMENTLINE.WAREHOUSECODE 
  WHERE INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE='$IntDoc' AND INTERNALDOCUMENTLINE.ORDERLINE ='$Orderline' 
  ORDER BY ITXVIEWBUKMUTGKGKNT.ITEMELEMENTCODE ASC LIMIT $lawal,$batas";
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
  ITXVIEWBUKMUTGKGKNT.RECEIVINGDATE,
  ITXVIEWBUKMUTGKGKNT.ITEMELEMENTCODE,
  ITXVIEWBUKMUTGKGKNT.LOTCODE,
  ITXVIEWBUKMUTGKGKNT.USERPRIMARYQUANTITY,
  ITXVIEWBUKMUTGKGKNT.LOGICALWAREHOUSECODE 
  FROM INTERNALDOCUMENT INTERNALDOCUMENT
  LEFT JOIN INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON INTERNALDOCUMENT.PROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE AND 
  INTERNALDOCUMENT.PROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE 
  LEFT JOIN ITXVIEWBUKMUTGKGKNT ITXVIEWBUKMUTGKGKNT ON ITXVIEWBUKMUTGKGKNT.INTDOCPROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE AND 
  ITXVIEWBUKMUTGKGKNT.INTDOCUMENTPROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE AND 
  ITXVIEWBUKMUTGKGKNT.ORDERLINE = INTERNALDOCUMENTLINE.ORDERLINE AND 
  ITXVIEWBUKMUTGKGKNT.LOGICALWAREHOUSECODE = INTERNALDOCUMENTLINE.WAREHOUSECODE 
  WHERE INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE='$IntDoc' AND INTERNALDOCUMENTLINE.ORDERLINE ='$Orderline' 
  ORDER BY ITXVIEWBUKMUTGKGKNT.ITEMELEMENTCODE ASC LIMIT $lakhir,$batas";
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
              border-right:1px #000000 solid;"><strong>Roll</strong></td>
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
              border-right:1px #000000 solid;"><?php echo substr($rowdtl1['ITEMELEMENTCODE'], 8, 3); ?></td>
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
              border-right:1px #000000 solid;"><strong>Roll</strong></td>
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
            border-right:1px #000000 solid;"><?php echo substr($rowdtl2['ITEMELEMENTCODE'], 8, 3); ?></td>
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