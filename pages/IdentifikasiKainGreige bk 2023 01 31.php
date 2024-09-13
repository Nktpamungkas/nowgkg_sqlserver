<?php
$Project	= isset($_POST['projectcode']) ? $_POST['projectcode'] : '';
$HangerNO	= isset($_POST['hangerno']) ? $_POST['hangerno'] : '';
$subC1		= substr($HangerNO,0,3);
$subC2		= substr($HangerNO,3,5);
if(strlen(trim($subC2))=="4"){
$subC3		= substr($HangerNO,8,3);	
}else if(strlen(trim($subC2))=="5"){
$subC3		= substr($HangerNO,9,3); 	
}

$sqlDB2 =" SELECT * FROM 
(SELECT CASE WHEN PROJECTCODE <> '' THEN PROJECTCODE ELSE ORIGDLVSALORDLINESALORDERCODE  END  AS PROJECT,
SUBCODE02,SUBCODE03,SUBCODE04, SUM(BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY, CURRENT_TIMESTAMP AS TGLS FROM ITXVIEWKNTORDER 
WHERE ITEMTYPEAFICODE ='KGF' AND (PROGRESSSTATUS='2' OR PROGRESSSTATUS='6')
GROUP BY SUBCODE02,SUBCODE03,SUBCODE04,CURRENT_TIMESTAMP,(CASE WHEN PROJECTCODE <> '' THEN PROJECTCODE ELSE ORIGDLVSALORDLINESALORDERCODE  END)) X
WHERE X.PROJECT='$Project' ";	
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
$sqlDB210 =" 
SELECT SUM(a.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY, SUM(a3.VALUEDECIMAL) AS QTYSALIN  FROM ITXVIEWKNTORDER a
LEFT OUTER JOIN PRODUCTIONDEMAND p ON p.CODE =a.PRODUCTIONDEMANDCODE
LEFT OUTER JOIN ADSTORAGE a2 ON p.ABSUNIQUEID =a2.UNIQUEID AND a2.NAMENAME ='StatusRMP'
LEFT OUTER JOIN ADSTORAGE a3 ON p.ABSUNIQUEID =a3.UNIQUEID AND a3.NAMENAME ='QtySalin'
WHERE a.ITEMTYPEAFICODE ='KGF' AND (a.PROJECTCODE ='$Project' OR a.ORIGDLVSALORDLINESALORDERCODE ='$Project') AND
a.SUBCODE02='$subC1' AND a.SUBCODE03='$subC2' AND a.SUBCODE04='$subC3' AND (a.PROGRESSSTATUS='2' OR a.PROGRESSSTATUS='6') AND (NOT a2.VALUESTRING ='3' OR a2.VALUESTRING IS NULL) ";	
$stmt10   = db2_exec($conn1,$sqlDB210, array('cursor'=>DB2_SCROLLABLE));
$rowdb210 = db2_fetch_assoc($stmt10);
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Identifikasi Kain Greige</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->		  
          <div class="card-body">
             <div class="form-group row">
               <label for="projectcode" class="col-md-1">ProjectCode</label>
               <div class="col-md-2">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $Project; ?>" name="projectcode" required>
			        </div>	
            </div>
			<div class="form-group row">
                    <label for="hangerno" class="col-md-1">No. Hanger</label>
					<div class="col-md-2"> 
                    <select name="hangerno" class="form-control form-control-sm"  autocomplete="off">
						<option value="">Pilih</option>
						<?php while($rowdb2 = db2_fetch_assoc($stmt)){?>
						<option value="<?php echo trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04']);?>" <?php if($HangerNO==trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04'])){ echo "SELECTED";}?>><?php echo trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04']);?></option>
						<?php } ?>
					</select>	
                  </div>	
                  </div>
			  <div class="form-group row">
               <label for="qtyorder" class="col-md-1">Permintaan Rajut</label>
               <div class="col-md-1">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo number_format(round($rowdb210['BASEPRIMARYQUANTITY']-$rowdb210['QTYSALIN'],2),2); ?>" name="qtyorder" style="text-align: right" required>
			   </div>
			  <strong> Kgs</strong>	  
            </div>
			  <button class="btn btn-info" type="submit" >Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  
			
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Data  Kain Greige</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th colspan="15" valign="middle" style="text-align: center">Masuk</th>
                    <th colspan="4" valign="middle" style="text-align: center">Sisa</th>
                    </tr>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">Tanggal</th>
                    <th valign="middle" style="text-align: center">No BON</th>
                    <th valign="middle" style="text-align: center">KNITT</th>
                    <th valign="middle" style="text-align: center">Prod. Order</th>
                    <th valign="middle" style="text-align: center">Code</th>
                    <th valign="middle" style="text-align: center">Jenis Benang</th>
                    <th valign="middle" style="text-align: center">Demand</th>
                    <th valign="middle" style="text-align: center">Mesin Rajut</th>
                    <th valign="middle" style="text-align: center">Lebar</th>
                    <th valign="middle" style="text-align: center">Gramasi</th>
                    <th valign="middle" style="text-align: center">Hasil Inspek</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Lokasi</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Lot</th>
                    <th valign="middle" style="text-align: center">Pergerakan Kain</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB21 = " SELECT STOCKTRANSACTION.PROJECTCODE,STOCKTRANSACTION.TRANSACTIONDATE,
	ITXVIEWLAPMASUKGREIGE.SUBCODE02,ITXVIEWLAPMASUKGREIGE.SUBCODE03,ITXVIEWLAPMASUKGREIGE.SUBCODE04,
	   ITXVIEWLAPMASUKGREIGE.ORDERLINE,ITXVIEWLAPMASUKGREIGE.EXTERNALREFERENCE,ITXVIEWLAPMASUKGREIGE.INTERNALREFERENCE,
       ITXVIEWLAPMASUKGREIGE.ITEMDESCRIPTION,ITXVIEWLAPMASUKGREIGE.LOTCODE,
       ITXVIEWLAPMASUKGREIGE.USERPRIMARYUOMCODE,
	   ITXVIEWLAPMASUKGREIGE.PROVISIONALCOUNTERCODE,
       ITXVIEWLAPMASUKGREIGE.WHSLOCATIONWAREHOUSEZONECODE,
       ITXVIEWLAPMASUKGREIGE.WAREHOUSELOCATIONCODE,ITXVIEWLAPMASUKGREIGE.DESTINATIONWAREHOUSECODE,
       ITXVIEWLAPMASUKGREIGE.SUMMARIZEDDESCRIPTION,
       ITXVIEWLAPMASUKGREIGE.PROVISIONALCODE,SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_ROL  
       FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION LEFT OUTER JOIN
DB2ADMIN.ITXVIEWLAPMASUKGREIGE ITXVIEWLAPMASUKGREIGE ON ITXVIEWLAPMASUKGREIGE.PROVISIONALCODE  = STOCKTRANSACTION.ORDERCODE
AND ITXVIEWLAPMASUKGREIGE.ORDERLINE  = STOCKTRANSACTION.ORDERLINE
AND ITXVIEWLAPMASUKGREIGE.PROVISIONALCOUNTERCODE  = STOCKTRANSACTION.ORDERCOUNTERCODE  
AND ITXVIEWLAPMASUKGREIGE.ITEMTYPEAFICODE = STOCKTRANSACTION.ITEMTYPECODE 
AND ITXVIEWLAPMASUKGREIGE.SUBCODE01= STOCKTRANSACTION.DECOSUBCODE01
AND ITXVIEWLAPMASUKGREIGE.SUBCODE02= STOCKTRANSACTION.DECOSUBCODE02
AND ITXVIEWLAPMASUKGREIGE.SUBCODE03= STOCKTRANSACTION.DECOSUBCODE03
AND ITXVIEWLAPMASUKGREIGE.SUBCODE04= STOCKTRANSACTION.DECOSUBCODE04
WHERE STOCKTRANSACTION.PROJECTCODE='$Project' AND DECOSUBCODE02='$subC1' AND DECOSUBCODE03='$subC2' AND DECOSUBCODE04='$subC3' and 
STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M021' 
AND NOT ITXVIEWLAPMASUKGREIGE.ORDERLINE IS NULL
GROUP BY ITXVIEWLAPMASUKGREIGE.PROVISIONALCODE,
ITXVIEWLAPMASUKGREIGE.SUMMARIZEDDESCRIPTION,ITXVIEWLAPMASUKGREIGE.DESTINATIONWAREHOUSECODE,
ITXVIEWLAPMASUKGREIGE.USERPRIMARYUOMCODE,STOCKTRANSACTION.PROJECTCODE,
       ITXVIEWLAPMASUKGREIGE.WHSLOCATIONWAREHOUSEZONECODE,ITXVIEWLAPMASUKGREIGE.PROVISIONALCOUNTERCODE,
       ITXVIEWLAPMASUKGREIGE.WAREHOUSELOCATIONCODE,ITXVIEWLAPMASUKGREIGE.ORDERLINE,ITXVIEWLAPMASUKGREIGE.EXTERNALREFERENCE,ITXVIEWLAPMASUKGREIGE.INTERNALREFERENCE,
       ITXVIEWLAPMASUKGREIGE.ITEMDESCRIPTION,ITXVIEWLAPMASUKGREIGE.LOTCODE,ITXVIEWLAPMASUKGREIGE.SUBCODE02,
	   ITXVIEWLAPMASUKGREIGE.SUBCODE03,ITXVIEWLAPMASUKGREIGE.SUBCODE04,STOCKTRANSACTION.TRANSACTIONDATE
ORDER BY STOCKTRANSACTION.TRANSACTIONDATE ASC ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
$bon=trim($rowdb21['PROVISIONALCODE'])."-".trim($rowdb21['ORDERLINE']);
$itemc=trim($rowdb21['SUBCODE02'])."".trim($rowdb21['SUBCODE03'])." ".trim($rowdb21['SUBCODE04']);		
if (trim($rowdb21['PROVISIONALCOUNTERCODE']) =='I02M50') { $knitt = 'KNITTING ITTI- GREIGE'; } 
		$sqlDB22 = "SELECT COUNT(BALANCE.BASEPRIMARYQUANTITYUNIT) AS ROL,SUM(BALANCE.BASEPRIMARYQUANTITYUNIT) AS BERAT,BALANCE.LOTCODE  
		FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION RIGHT OUTER JOIN 
		DB2ADMIN.BALANCE  BALANCE ON BALANCE.ELEMENTSCODE =STOCKTRANSACTION.ITEMELEMENTCODE  
		WHERE STOCKTRANSACTION.LOGICALWAREHOUSECODE='M021' AND STOCKTRANSACTION.ORDERCODE='$rowdb21[PROVISIONALCODE]'
		AND STOCKTRANSACTION.ORDERLINE ='$rowdb21[ORDERLINE]' 
		GROUP BY BALANCE.LOTCODE ";					  
		$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));	
		$rowdb22 = db2_fetch_assoc($stmt2);
		
		$sqlDB23 = " SELECT x.* FROM DB2ADMIN.ITXVIEWKNTORDER x
		WHERE PROJECTCODE ='$Project' AND ITEMTYPEAFICODE ='KGF' AND PRODUCTIONORDERCODE ='$rowdb21[EXTERNALREFERENCE]' ";					  
		$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));	
		$rowdb23 = db2_fetch_assoc($stmt3); 
		$sqlDB24 = " SELECT LISTAGG(DISTINCT  TRIM(BLKOKASI.WAREHOUSELOCATIONCODE),', ') AS WAREHOUSELOCATIONCODE
	 FROM
(SELECT DISTINCT STKBLANCE.ELEMENTSCODE,  
       STKBLANCE.WAREHOUSELOCATIONCODE,
	   ITXVIEWLAPMASUKGREIGE.PROVISIONALCODE,ITXVIEWLAPMASUKGREIGE.ORDERLINE
       FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION LEFT OUTER JOIN
DB2ADMIN.ITXVIEWLAPMASUKGREIGE ITXVIEWLAPMASUKGREIGE ON ITXVIEWLAPMASUKGREIGE.PROVISIONALCODE  = STOCKTRANSACTION.ORDERCODE
AND ITXVIEWLAPMASUKGREIGE.ORDERLINE  = STOCKTRANSACTION.ORDERLINE
AND ITXVIEWLAPMASUKGREIGE.PROVISIONALCOUNTERCODE  = STOCKTRANSACTION.ORDERCOUNTERCODE  
AND ITXVIEWLAPMASUKGREIGE.ITEMTYPEAFICODE = STOCKTRANSACTION.ITEMTYPECODE 
AND ITXVIEWLAPMASUKGREIGE.SUBCODE01= STOCKTRANSACTION.DECOSUBCODE01
AND ITXVIEWLAPMASUKGREIGE.SUBCODE02= STOCKTRANSACTION.DECOSUBCODE02
AND ITXVIEWLAPMASUKGREIGE.SUBCODE03= STOCKTRANSACTION.DECOSUBCODE03
AND ITXVIEWLAPMASUKGREIGE.SUBCODE04= STOCKTRANSACTION.DECOSUBCODE04
INNER JOIN ( SELECT
	b.WAREHOUSELOCATIONCODE,b.ELEMENTSCODE  
FROM BALANCE b  
WHERE b.ITEMTYPECODE ='KGF'  AND b.LOGICALWAREHOUSECODE ='M021' ) AS STKBLANCE ON STKBLANCE.ELEMENTSCODE=STOCKTRANSACTION.ITEMELEMENTCODE
WHERE STOCKTRANSACTION.ORDERCODE ='$rowdb21[PROVISIONALCODE]'  and STOCKTRANSACTION.ORDERLINE ='$rowdb21[ORDERLINE]' AND
STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M021' 
AND NOT ITXVIEWLAPMASUKGREIGE.ORDERLINE IS NULL) AS BLKOKASI ";
$stmt4   = db2_exec($conn1,$sqlDB24, array('cursor'=>DB2_SCROLLABLE));					  
$rowdb24 = db2_fetch_assoc($stmt4);
$sqlDB25 = " 
SELECT ad.VALUESTRING AS NO_MESIN
FROM PRODUCTIONDEMAND pd 	
LEFT OUTER JOIN ADSTORAGE ad ON ad.UNIQUEID = pd.ABSUNIQUEID AND ad.NAMENAME ='MachineNo'
WHERE  pd.CODE ='$rowdb21[LOTCODE]'
GROUP BY ad.VALUESTRING
";
$stmt5   = db2_exec($conn1,$sqlDB25, array('cursor'=>DB2_SCROLLABLE));					  
$rowdb25 = db2_fetch_assoc($stmt5);	
		
$sqlDB26 = " SELECT
   QUALITYDOCLINE.VALUEQUANTITY AS LEBAR1,GSM.VALUEQUANTITY AS GSM1
FROM
    QUALITYDOCLINE 
LEFT OUTER JOIN 
(SELECT
  QUALITYDOCPRODUCTIONORDERCODE,VALUEQUANTITY
FROM
    QUALITYDOCLINE
WHERE
	QUALITYDOCUMENTHEADERNUMBERID ='35' AND
    QUALITYDOCLINE.CHARACTERISTICCODE = 'GSM' AND 
	QUALITYDOCUMENTITEMTYPEAFICODE ='KGF'
	) GSM ON GSM.QUALITYDOCPRODUCTIONORDERCODE=QUALITYDOCLINE.QUALITYDOCPRODUCTIONORDERCODE
WHERE
	QUALITYDOCUMENTHEADERNUMBERID ='35'  AND
	QUALITYDOCLINE.CHARACTERISTICCODE = 'LEBAR1' AND
	QUALITYDOCUMENTITEMTYPEAFICODE ='KGF' AND
	QUALITYDOCLINE.QUALITYDOCPRODUCTIONORDERCODE='$rowdb21[EXTERNALREFERENCE]' ";
$stmt6   = db2_exec($conn1,$sqlDB26, array('cursor'=>DB2_SCROLLABLE));
$rowdb26 = db2_fetch_assoc($stmt6);
		
$sqlDB27 = " SELECT
   QUALITYDOCLINE.VALUEQUANTITY AS LEBAR1,GSM.VALUEQUANTITY AS GSM1
FROM
    QUALITYDOCLINE 
LEFT OUTER JOIN 
(SELECT
  QUALITYDOCPRODUCTIONORDERCODE,VALUEQUANTITY
FROM
    QUALITYDOCLINE
WHERE
	QUALITYDOCUMENTHEADERNUMBERID ='1379' AND
    QUALITYDOCLINE.CHARACTERISTICCODE = 'GSM' AND 
	QUALITYDOCUMENTITEMTYPEAFICODE ='KGF'
	) GSM ON GSM.QUALITYDOCPRODUCTIONORDERCODE=QUALITYDOCLINE.QUALITYDOCPRODUCTIONORDERCODE
WHERE
	QUALITYDOCUMENTHEADERNUMBERID ='1379' AND
	QUALITYDOCLINE.CHARACTERISTICCODE = 'LEBAR1' AND
	QUALITYDOCUMENTITEMTYPEAFICODE ='KGF' AND
	QUALITYDOCLINE.QUALITYDOCPRODUCTIONORDERCODE='$rowdb21[EXTERNALREFERENCE]' ";
$stmt7   = db2_exec($conn1,$sqlDB27, array('cursor'=>DB2_SCROLLABLE));
$rowdb27 = db2_fetch_assoc($stmt7);			
$sqlDB30 = " 
SELECT e.WIDTHGROSS AS LEBAR1,a.VALUEDECIMAL AS GSM1,s.ORDERCODE,s.ORDERLINE  FROM ELEMENTSINSPECTION e 
LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID =e.ABSUNIQUEID AND a.NAMENAME ='GSM'
LEFT OUTER JOIN STOCKTRANSACTION s ON s.ITEMELEMENTCODE =e.ELEMENTCODE
WHERE s.ORDERCODE='".$rowdb21['PROVISIONALCODE']."' AND s.ORDERLINE='".$rowdb21['ORDERLINE']."'
GROUP BY s.ORDERCODE,s.ORDERLINE,e.WIDTHGROSS,a.VALUEDECIMAL
";
$stmt10   = db2_exec($conn1,$sqlDB30, array('cursor'=>DB2_SCROLLABLE));					  
$rowdb30 = db2_fetch_assoc($stmt10);
		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
      <td style="text-align: center"><?php echo $bon; ?></td>
      <td style="text-align: center"><?php echo $knitt; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['EXTERNALREFERENCE']; ?></td>
      <td><?php echo $itemc;?></td> 
      <td style="text-align: left"><?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
      <td style="text-align: center"><?php if($rowdb23['SCHEDULEDRESOURCECODE']!=""){echo $rowdb23['SCHEDULEDRESOURCECODE'];}else{echo $rowdb25['NO_MESIN'];} ?></td>
      <td style="text-align: left"><span style="text-align: center">
        <?php if($rowdb26['LEBAR1']!=""){echo round($rowdb26['LEBAR1']);}else if($rowdb27['LEBAR1']!=""){echo round($rowdb27['LEBAR1']);}else{ echo round($rowdb30['LEBAR1']); } ?>
      </span></td>
      <td style="text-align: left"><span style="text-align: center">
        <?php if($rowdb26['GSM1']!=""){echo round($rowdb26['GSM1']);}else if($rowdb27['GSM1']!=""){echo round($rowdb27['GSM1']);}else{ echo round($rowdb30['GSM1']); } ?>
      </span></td>
      <td style="text-align: right"><?php echo $rowdb21['INTERNALREFERENCE']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21['QTY_ROL']; ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['QTY_KG'],2),2); ?></td>
      <td style="text-align: right"><?php echo $rowdb24['WAREHOUSELOCATIONCODE']; ?></td>
      <td style="text-align: right"><?php if($rowdb22['ROL']!=""){echo $rowdb22['ROL'];}else{ echo"0";} ?></td>
      <td style="text-align: right"><?php if($rowdb22['BERAT']!=""){echo number_format(round($rowdb22['BERAT'],2),2);}else{ echo"0.00";} ?></td>
      <td><a href="#" id="<?php echo trim($rowdb21['PROVISIONALCODE'])."-".trim($rowdb21['ORDERLINE'])." -".trim($rowdb22['LOTCODE']); ?>" class="show_detail"><?php echo $rowdb22['LOTCODE']; ?></a></td>
      <td><a href="#" class="btn btn-success btn-xs show_pergerakan_detail" id="<?php echo trim($rowdb21['PROVISIONALCODE'])."-".trim($rowdb21['ORDERLINE'])." -".trim($rowdb22['LOTCODE']); ?>">Lihat Detail</a></td>
      </tr>
	  				  
	<?php 
	 $no++; 
	$tMRol+=$rowdb21['QTY_ROL'];
	$tMKG +=$rowdb21['QTY_KG'];
	$tKRol+=$rowdb22['ROL'];
	$tKKG +=$rowdb22['BERAT'];	
	} ?>
				  </tbody>
                  <tfoot>
		<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right"><span style="text-align: center"><strong>Total</strong></span></td>
	    <td style="text-align: right"><strong><?php echo $tMRol;?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format(round($tMKG,2),2);?></strong></td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right"><strong><?php echo $tKRol;?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format(round($tKKG,2),2);?></strong></td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>			
		</tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
		<div class="row">
		<div class="col-md-8">	
		<div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Detail Data Retur Kain Greige</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example3" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th colspan="9" valign="middle" style="text-align: center">Masuk</th>
                    <th colspan="4" valign="middle" style="text-align: center">Sisa</th>
                    </tr>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">Tanggal</th>
                    <th valign="middle" style="text-align: center">Code</th>
                    <th valign="middle" style="text-align: center">Lot</th>
                    <th valign="middle" style="text-align: center">Lebar</th>
                    <th valign="middle" style="text-align: center">Gramasi</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Lokasi</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Lot</th>
                    <th valign="middle" style="text-align: center">Pergerakan Kain</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB21R = " SELECT 
s.TRANSACTIONDATE,s.DECOSUBCODE02,s.DECOSUBCODE03,
s.DECOSUBCODE04,s.WHSLOCATIONWAREHOUSEZONECODE,s.WAREHOUSELOCATIONCODE,
s.LOTCODE,SUM(s.BASEPRIMARYQUANTITY) AS KG,COUNT(s.ITEMELEMENTCODE) AS JML  FROM STOCKTRANSACTION s 
LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.NAMENAME = 'StatusRetur'
WHERE s.PROJECTCODE='$Project' AND s.ITEMTYPECODE='KGF' AND 
s.DECOSUBCODE02='$subC1' AND s.DECOSUBCODE03='$subC2' AND 
s.DECOSUBCODE04='$subC3' AND s.LOGICALWAREHOUSECODE ='M021' AND
s.TEMPLATECODE = 'OPN' AND a.VALUESTRING =  '1'
GROUP BY 
s.TRANSACTIONDATE, s.DECOSUBCODE02,
s.DECOSUBCODE03, s.DECOSUBCODE04,
s.WHSLOCATIONWAREHOUSEZONECODE,
s.WAREHOUSELOCATIONCODE, s.LOTCODE ";
	$stmt1R   = db2_exec($conn1,$sqlDB21R, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21R = db2_fetch_assoc($stmt1R)){ 
$itemcR=trim($rowdb21R['DECOSUBCODE02'])."".trim($rowdb21R['DECOSUBCODE03'])." ".trim($rowdb21R['DECOSUBCODE04']);	
		
$sqlDB22R = " SELECT COUNT(BALANCE.BASEPRIMARYQUANTITYUNIT) AS ROL,SUM(BALANCE.BASEPRIMARYQUANTITYUNIT) AS BERAT,BALANCE.LOTCODE  
		FROM (
		SELECT 
s.ITEMELEMENTCODE  FROM STOCKTRANSACTION s 
LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.NAMENAME = 'StatusRetur'
WHERE s.PROJECTCODE='$Project' AND s.ITEMTYPECODE='KGF' AND 
s.DECOSUBCODE02='$subC1' AND s.DECOSUBCODE03='$subC2' AND 
s.DECOSUBCODE04='$subC3' AND s.LOGICALWAREHOUSECODE ='M021' AND
s.TEMPLATECODE = 'OPN' AND a.VALUESTRING =  '1'
GROUP BY 
s.ITEMELEMENTCODE
		) STOCKTRANSACTION LEFT OUTER JOIN 
		DB2ADMIN.BALANCE  BALANCE ON BALANCE.ELEMENTSCODE =STOCKTRANSACTION.ITEMELEMENTCODE  
		GROUP BY BALANCE.LOTCODE ";					  
		$stmt2R   = db2_exec($conn1,$sqlDB22R, array('cursor'=>DB2_SCROLLABLE));	
		$rowdb22R = db2_fetch_assoc($stmt2R);		
$sqlDB26R = " SELECT
   QUALITYDOCLINE.VALUEQUANTITY AS LEBAR1,GSM.VALUEQUANTITY AS GSM1
FROM
    QUALITYDOCLINE 
LEFT OUTER JOIN 
(SELECT
  QUALITYDOCPRODUCTIONORDERCODE,VALUEQUANTITY
FROM
    QUALITYDOCLINE
WHERE
	QUALITYDOCUMENTHEADERNUMBERID ='35' AND
    QUALITYDOCLINE.CHARACTERISTICCODE = 'GSM' AND 
	QUALITYDOCUMENTITEMTYPEAFICODE ='KGF'
	) GSM ON GSM.QUALITYDOCPRODUCTIONORDERCODE=QUALITYDOCLINE.QUALITYDOCPRODUCTIONORDERCODE
WHERE
	QUALITYDOCUMENTHEADERNUMBERID ='35'  AND
	QUALITYDOCLINE.CHARACTERISTICCODE = 'LEBAR1' AND
	QUALITYDOCUMENTITEMTYPEAFICODE ='KGF' AND
	QUALITYDOCLINE.QUALITYDOCPRODUCTIONORDERCODE='$rowdb21R[LOTCODE]' ";
$stmt6R   = db2_exec($conn1,$sqlDB26R, array('cursor'=>DB2_SCROLLABLE));
$rowdb26R = db2_fetch_assoc($stmt6R);
		
$sqlDB27R = " SELECT
   QUALITYDOCLINE.VALUEQUANTITY AS LEBAR1,GSM.VALUEQUANTITY AS GSM1
FROM
    QUALITYDOCLINE 
LEFT OUTER JOIN 
(SELECT
  QUALITYDOCPRODUCTIONORDERCODE,VALUEQUANTITY
FROM
    QUALITYDOCLINE
WHERE
	QUALITYDOCUMENTHEADERNUMBERID ='1379' AND
    QUALITYDOCLINE.CHARACTERISTICCODE = 'GSM' AND 
	QUALITYDOCUMENTITEMTYPEAFICODE ='KGF'
	) GSM ON GSM.QUALITYDOCPRODUCTIONORDERCODE=QUALITYDOCLINE.QUALITYDOCPRODUCTIONORDERCODE
WHERE
	QUALITYDOCUMENTHEADERNUMBERID ='1379' AND
	QUALITYDOCLINE.CHARACTERISTICCODE = 'LEBAR1' AND
	QUALITYDOCUMENTITEMTYPEAFICODE ='KGF' AND
	QUALITYDOCLINE.QUALITYDOCPRODUCTIONORDERCODE='$rowdb21R[LOTCODE]' ";
$stmt7R   = db2_exec($conn1,$sqlDB27R, array('cursor'=>DB2_SCROLLABLE));
$rowdb27R = db2_fetch_assoc($stmt7R);
		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb21R['TRANSACTIONDATE']; ?></td>
      <td style="text-align: center"><?php echo $itemcR; ?></td> 
      <td style="text-align: center"><?php echo $rowdb21R['LOTCODE']; ?></td>
      <td style="text-align: left"><span style="text-align: center">
        <?php if($rowdb26R['LEBAR1']!=""){echo round($rowdb26R['LEBAR1']);}else{echo round($rowdb27R['LEBAR1']);} ?>
      </span></td>
      <td style="text-align: left"><span style="text-align: center">
        <?php if($rowdb26R['GSM1']!=""){echo round($rowdb26R['GSM1']);}else{echo round($rowdb27R['GSM1']);} ?>
      </span></td>
      <td style="text-align: right"><?php echo $rowdb21R['JML']; ?></td>
      <td style="text-align: right"><?php echo round($rowdb21R['KG'],2); ?></td>
      <td style="text-align: right"><?php echo $rowdb21R['WAREHOUSELOCATIONCODE']; ?></td>
      <td style="text-align: right"><?php if($rowdb22R['ROL']!=""){echo $rowdb22R['ROL'];}else{ echo"0";} ?></td>
      <td style="text-align: right"><?php if($rowdb22R['BERAT']!=""){echo number_format(round($rowdb22R['BERAT'],2),2);}else{ echo"0.00";} ?></td>
      <td><a href="#" id="<?php echo trim($rowdb22['LOTCODE']); ?>" class="show_detailR"><?php echo $rowdb22R['LOTCODE']; ?></a></td>
      <td><a href="#" class="btn btn-success btn-xs show_pergerakan_detailR" id="<?php echo trim($rowdb22['LOTCODE']); ?>">Lihat Detail</a></td>
      </tr>
	  				  
	<?php 
	 $no++; 
	$tMRolR+=$rowdb21R['JML'];
	$tMKGR +=$rowdb21R['KG'];
	$tKRolR+=$rowdb22R['ROL'];
	$tKKGR +=$rowdb22R['BERAT'];	
	} ?>
				  </tbody>
                  <tfoot>
		<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right"><span style="text-align: center"><strong>Total</strong></span></td>
	    <td style="text-align: right"><strong><?php echo $tMRolR;?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format(round($tMKGR,2),2);?></strong></td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right"><strong><?php echo $tKRolR;?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format(round($tKKGR,2),2);?></strong></td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>			
		</tfoot>
                </table>
              </div>
              <!-- /.card-body -->
          </div>
			</div>	
		<div class="col-md-4">	
		<div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Detail Data Kain Greige Cut Element</h3>			  	  
          </div>
              <!-- /.card-header -->              		
					<div class="card-body">
					<table id="example4" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">Element</th>
                    <th valign="middle" style="text-align: center">Status</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Element Cut</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$noCT=1;   
$c=0;
					  
	$sqlDB23 = " SELECT STOCKOUT.TRANSACTIONNUMBER,
	STOCKOUT.ITEMELEMENTCODE
	FROM
(
SELECT 
STKKELUAR.ITEMELEMENTCODE, STKKELUAR.TRANSACTIONNUMBER
       FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION LEFT OUTER JOIN
DB2ADMIN.ITXVIEWLAPMASUKGREIGE ITXVIEWLAPMASUKGREIGE ON ITXVIEWLAPMASUKGREIGE.PROVISIONALCODE  = STOCKTRANSACTION.ORDERCODE
AND ITXVIEWLAPMASUKGREIGE.ORDERLINE  = STOCKTRANSACTION.ORDERLINE
AND ITXVIEWLAPMASUKGREIGE.PROVISIONALCOUNTERCODE  = STOCKTRANSACTION.ORDERCOUNTERCODE  
AND ITXVIEWLAPMASUKGREIGE.ITEMTYPEAFICODE = STOCKTRANSACTION.ITEMTYPECODE 
AND ITXVIEWLAPMASUKGREIGE.SUBCODE01= STOCKTRANSACTION.DECOSUBCODE01
AND ITXVIEWLAPMASUKGREIGE.SUBCODE02= STOCKTRANSACTION.DECOSUBCODE02
AND ITXVIEWLAPMASUKGREIGE.SUBCODE03= STOCKTRANSACTION.DECOSUBCODE03
AND ITXVIEWLAPMASUKGREIGE.SUBCODE04= STOCKTRANSACTION.DECOSUBCODE04 
INNER JOIN (SELECT
	STOCKTRANSACTION.TRANSACTIONNUMBER,	
	STOCKTRANSACTION.TRANSACTIONDATE,
	STOCKTRANSACTION.ORDERCODE,
	STOCKTRANSACTION.PROJECTCODE,
	STOCKTRANSACTION.BASEPRIMARYQUANTITY,
	STOCKTRANSACTION.ITEMELEMENTCODE,
	STOCKTRANSACTION.CREATIONUSER 
FROM STOCKTRANSACTION 
WHERE STOCKTRANSACTION.ITEMTYPECODE ='KGF'  AND STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M021' 
AND TEMPLATECODE ='341') AS STKKELUAR ON STKKELUAR.ITEMELEMENTCODE=STOCKTRANSACTION.ITEMELEMENTCODE
WHERE STOCKTRANSACTION.PROJECTCODE='$Project' AND DECOSUBCODE02='$subC1' AND DECOSUBCODE03='$subC2' AND DECOSUBCODE04='$subC3' and 
STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M021' 
AND NOT ITXVIEWLAPMASUKGREIGE.ORDERLINE IS NULL
GROUP BY STKKELUAR.ITEMELEMENTCODE,STKKELUAR.TRANSACTIONNUMBER) AS STOCKOUT
GROUP BY STOCKOUT.TRANSACTIONNUMBER,
	STOCKOUT.ITEMELEMENTCODE";
	$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));			  
    while($rowdb23 = db2_fetch_assoc($stmt3)){ 
		$sqlDB24 = " 
SELECT x.ITEMELEMENTCODE, y.ELEMENTSCODE,
	x.BASEPRIMARYQUANTITY AS QTY_KG FROM DB2ADMIN.STOCKTRANSACTION x
LEFT OUTER JOIN (
SELECT ELEMENTSCODE FROM BALANCE
) y ON x.ITEMELEMENTCODE = y.ELEMENTSCODE	
WHERE x.CUTORGTRTRANSACTIONNUMBER='".$rowdb23['TRANSACTIONNUMBER']."'
";
$stmt4   = db2_exec($conn1,$sqlDB24, array('cursor'=>DB2_SCROLLABLE));
$rowdb24 = db2_fetch_assoc($stmt4);
if ($rowdb24['ELEMENTSCODE']!=""){
	$sts24="<small class='badge badge-success'>Ada</small>";
	$tCS=$rowdb24['QTY_KG'];
	$tCSR=1;
	$tCSRs=1;
}	else{
	$sts24="<small class='badge badge-danger'>Keluar</small>";
	$tCS=0;
	$tCSR=0;
	$tCSRs=1;
}		
$sqlDB25 = " 
SELECT x.ITEMELEMENTCODE, x.BASEPRIMARYQUANTITY AS QTY_KG, y.ELEMENTSCODE FROM DB2ADMIN.STOCKTRANSACTION x
LEFT OUTER JOIN (
SELECT ELEMENTSCODE FROM BALANCE
) y ON x.ITEMELEMENTCODE = y.ELEMENTSCODE	
WHERE x.ITEMELEMENTCODE='".$rowdb23['ITEMELEMENTCODE']."' AND X.TEMPLATECODE ='342'
";
$stmt5   = db2_exec($conn1,$sqlDB25, array('cursor'=>DB2_SCROLLABLE));
$rowdb25 = db2_fetch_assoc($stmt5);	
if ($rowdb25['ELEMENTSCODE']!=""){
	$sts25="<small class='badge badge-success'>Ada</small>";	
}	else{
	$sts25="<small class='badge badge-danger'>Keluar</small>";	
}
?>
	  <tr>
	  <td style="text-align: right"><span style="text-align: center"><?php echo $rowdb25['ITEMELEMENTCODE']; ?></span></td>
      <td style="text-align: center"><span style="text-align: center"><?php echo $sts24; ?></span></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb24['QTY_KG'],2),2); ?></td>
      <td style="text-align: center"><span style="text-align: right"><?php echo $rowdb24['ITEMELEMENTCODE']; ?></span></td>
      </tr>
	  				  
	<?php 
	$tCHKG3  += $rowdb24['QTY_KG'];	
	$tCRS1 += $tCSR;
    $tCRS1s += $tCSRs;		
	$tKCutS +=$tCS;
	$tKCutH +=$tCH;	
		
		$noCT++;
	} ?>
				  </tbody>
                  <tfoot> 
		<tr>
	    <td style="text-align: right"><strong>Total</strong></td>
	    <td style="text-align: right"><strong><?php echo $tCRS1s; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format($tCHKG3,2); ?></strong></td>
	    <td style="text-align: center">&nbsp;</td>
	    </tr>			
		</tfoot>
                </table>					
					</div> 
              <!-- /.card-body -->
            </div>
		 </div>	
			</div>	
	<?php
		$sisaGreigeRol=$tKRol+$tKRolR+$tCRS1;	
		$sisaGreige=$tKKG+$tKKGR+$tKCutS;	
			?>
	<div class="row">
			<div class="col-md-4">	
		<div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Sisa Data Kain Greige</h3>				 
          </div>
              <!-- /.card-header -->              		
					<div class="card-body">
					<div class="form-group row">
               <label for="sisa" class="col-md-5">Sisa Greige</label>
			   <div class="col-md-2">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $sisaGreigeRol; ?>" name="sisarol" style="text-align: right" readonly>
			   </div>			
               <div class="col-md-3">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo number_format($sisaGreige,2); ?>" name="sisa" style="text-align: right" readonly>
			   </div>
			  <strong> Kgs</strong>	  
            </div>					
					</div> 
              <!-- /.card-body -->
            </div>
		 </div>
			</div>	  
	</form>		
      </div><!-- /.container-fluid -->
    <!-- /.content -->
<div id="DetailShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>	
<div id="DetailPergerakanShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
</div>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>	
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
	$(function () {
		//Datepicker
    $('#datepicker').datetimepicker({
      format: 'YYYY-MM-DD'
    });
    $('#datepicker1').datetimepicker({
      format: 'YYYY-MM-DD'
    });
    $('#datepicker2').datetimepicker({
      format: 'YYYY-MM-DD'
    });
	
});		
</script>
<script type="text/javascript">
function checkAll(form1){
    for (var i=0;i<document.forms['form1'].elements.length;i++)
    {
        var e=document.forms['form1'].elements[i];
        if ((e.name !='allbox') && (e.type=='checkbox'))
        {
            e.checked=document.forms['form1'].allbox.checked;
			
        }
    }
}
</script>
<?php 
if($_POST['mutasikain']=="MutasiKain"){
	
function mutasiurut(){
include "koneksi.php";		
$format = "20".date("ymd");
$sql=mysqli_query($con,"SELECT no_mutasi FROM tbl_mutasi_kain WHERE substr(no_mutasi,1,8) like '%".$format."%' ORDER BY no_mutasi DESC LIMIT 1 ") or die (mysql_error());
$d=mysqli_num_rows($sql);
if($d>0){
$r=mysqli_fetch_array($sql);
$d=$r['no_mutasi'];
$str=substr($d,8,2);
$Urut = (int)$str;
}else{
$Urut = 0;
}
$Urut = $Urut + 1;
$Nol="";
$nilai=2-strlen($Urut);
for ($i=1;$i<=$nilai;$i++){
$Nol= $Nol."0";
}
$tidbr =$format.$Nol.$Urut;
return $tidbr;
}
$nomid=mutasiurut();	

$sql1=mysqli_query($con,"SELECT *,count(b.transid) as jmlrol,a.transid as kdtrans FROM tbl_mutasi_kain a 
LEFT JOIN tbl_prodemand b ON a.transid=b.transid 
WHERE isnull(a.no_mutasi) AND date_format(a.tgl_buat ,'%Y-%m-%d')='$Awal' AND a.gshift='$Gshift' 
GROUP BY a.transid");
$n1=1;
$noceklist1=1;	
while($r1=mysqli_fetch_array($sql1)){	
	if($_POST['cek'][$n1]!='') 
		{
		$transid1 = $_POST['cek'][$n1];
		mysqli_query($con,"UPDATE tbl_mutasi_kain SET
		no_mutasi='$nomid',
		tgl_mutasi=now()
		WHERE transid='$transid1'
		");
		}else{
			$noceklist1++;
	}
	$n1++;
	}
if($noceklist1==$n1){
	echo "<script>
  	$(function() {
    const Toast = Swal.mixin({
      toast: false,
      position: 'middle',
      showConfirmButton: false,
      timer: 2000
    });
	Toast.fire({
        icon: 'info',
        title: 'Data tidak ada yang di Ceklist',
		
      })
  });
  
</script>";	
}else{	
echo "<script>
	$(function() {
    const Toast = Swal.mixin({
      toast: false,
      position: 'middle',
      showConfirmButton: true,
      timer: 6000
    });
	Toast.fire({
  title: 'Data telah di Mutasi',
  text: 'klik OK untuk Cetak Bukti Mutasi',
  icon: 'success',  
}).then((result) => {
  if (result.isConfirmed) {
    	window.open('pages/cetak/cetak_mutasi_ulang.php?mutasi=$nomid', '_blank');
  }
})
  });
	</script>";
	
/*echo "<script>
	Swal.fire({
  title: 'Data telah di Mutasi',
  text: 'klik OK untuk Cetak Bukti Mutasi',
  icon: 'success',  
}).then((result) => {
  if (result.isConfirmed) {
    	window.location='Mutasi';
  }
});
	</script>";	*/
}
}
?>