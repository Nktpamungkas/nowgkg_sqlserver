<?php
$Project	= isset($_POST['projectcode']) ? $_POST['projectcode'] : '';
$HangerNO	= isset($_POST['hangerno']) ? $_POST['hangerno'] : '';
$subC1		= substr($HangerNO,0,3);
$subC2		= substr($HangerNO,3,5);
if(strlen(trim($HangerNO))=="13"){
$subC2		= substr($HangerNO,3,6);
$subC3		= substr($HangerNO,10,3);	
}
else if(strlen(trim($subC2))=="4"){
$subC3		= substr($HangerNO,8,3);	
}else if(strlen(trim($subC2))=="5"){
$subC3		= substr($HangerNO,9,3); 	
}

$sqlDB2 =" SELECT * FROM 
(SELECT CASE WHEN PROJECTCODE <> '' THEN PROJECTCODE ELSE ORIGDLVSALORDLINESALORDERCODE  END  AS PROJECT,
SUBCODE02,SUBCODE03,SUBCODE04, SUM(BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY, CURRENT_TIMESTAMP AS TGLS FROM ITXVIEWHEADERKNTORDER 
WHERE (ITEMTYPEAFICODE ='KFF' OR ITEMTYPEAFICODE ='KGF') AND (PROGRESSSTATUS='2' OR PROGRESSSTATUS='6')
GROUP BY SUBCODE02,SUBCODE03,SUBCODE04,CURRENT_TIMESTAMP,(CASE WHEN PROJECTCODE <> '' THEN PROJECTCODE ELSE ORIGDLVSALORDLINESALORDERCODE  END)) X
WHERE X.PROJECT='$Project' ";	
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
$sqlDB210 =" SELECT SUM(a.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY, SUM(a3.VALUEDECIMAL) AS QTYSALIN  FROM ITXVIEWHEADERKNTORDER a
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
		<div class="row">
        	<div class="col-md-3">	
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Pergerakan Kain Greige Returan</h3>

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
               <label for="projectcode" class="col-md-5">ProjectCode</label>
               <div class="col-md-7">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $Project; ?>" name="projectcode" required>
			   </div>	
            </div>
			<div class="form-group row">
                    <label for="hangerno" class="col-md-5">No. Hanger</label>
					<div class="col-md-7"> 
                    <select name="hangerno" class="form-control form-control-sm"  autocomplete="off">
						<option value="">Pilih</option>
						<?php while($rowdb2 = db2_fetch_assoc($stmt)){?>
						<option value="<?php echo trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04']);?>" <?php if($HangerNO==trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04'])){ echo "SELECTED";}?>><?php echo trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04']);?></option>
						<?php } ?>
					</select>	
                  </div>	
                  </div>
			  <div class="form-group row">
               <label for="qtyorder" class="col-md-5">Permintaan Rajut</label>
               <div class="col-md-5">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo number_format(round($rowdb210['BASEPRIMARYQUANTITY']-$rowdb210['QTYSALIN'],2),2); ?>" name="qtyorder" style="text-align: right" required>
			   </div>
			  <strong> Kgs</strong>	  
            </div>
			  
			  <button class="btn btn-info" type="submit" >Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div> 
			</div>
			<div class="col-md-6">
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Data Kain Greige Masuk Returan</h3>
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
					<table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">Tanggal</th>
                    <th valign="middle" style="text-align: center">Code</th>
                    <th valign="middle" style="text-align: center">Jenis Benang</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB21 = " SELECT 
s.TRANSACTIONNUMBER, s.TRANSACTIONDATE,s.DECOSUBCODE02,s.DECOSUBCODE03,
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
s.WAREHOUSELOCATIONCODE, s.LOTCODE, s.TRANSACTIONNUMBER";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
$itemc=trim($rowdb21['DECOSUBCODE02'])."".trim($rowdb21['DECOSUBCODE03'])." ".trim($rowdb21['DECOSUBCODE04']);		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
      <td><?php echo $itemc;?></td> 
      <td style="text-align: left"><?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21['JML']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21['KG']; ?></td>
      </tr>
	  				  
	<?php 
	 $no++; 
	$tMRol+=$rowdb21['JML'];
	$tMKG +=$rowdb21['KG'];		
	} ?>
				  </tbody>
                  <tfoot>
		<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td style="text-align: left"><span style="text-align: right"><span style="text-align: center"><strong>Total</strong></span></span></td>
	    <td style="text-align: right"><strong><?php echo $tMRol;?></strong></td>
	    <td style="text-align: right"><strong><?php echo $tMKG;?></strong></td>
	    </tr>			
		</tfoot>
                </table>					
					</div> 
              <!-- /.card-body -->
            </div>
		 </div>
		<div class="col-md-3">	
		<div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Detail Data Kain Greige Cut Element</h3>
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
	 
$no=1;   
$c=0;
					  
	$sqlDB23 = " SELECT STOCKOUT.TRANSACTIONNUMBER,
	STOCKOUT.ITEMELEMENTCODE
	FROM
(
		SELECT 
s.ITEMELEMENTCODE,s.TRANSACTIONNUMBER FROM STOCKTRANSACTION s 
LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.NAMENAME = 'StatusRetur'
WHERE s.PROJECTCODE='$Project' AND s.ITEMTYPECODE='KGF' AND 
s.DECOSUBCODE02='$subC1' AND s.DECOSUBCODE03='$subC2' AND 
s.DECOSUBCODE04='$subC3' AND s.LOGICALWAREHOUSECODE ='M021' AND
s.TEMPLATECODE = 'OPN' AND a.VALUESTRING =  '1'
	) STOCKTRANSACTION 
LEFT OUTER JOIN 	
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
ON STOCKTRANSACTION.ITEMELEMENTCODE=STOCKOUT.ITEMELEMENTCODE
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
	$tCS=0;
	$tCSR=0;
	$tCSR1=1;
}	else{
	$sts24="<small class='badge badge-danger'>Keluar</small>";
	$tCS=$rowdb24['QTY_KG'];
	$tCSR=1;
	$tCSR1=0;
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
      <td style="text-align: right"><?php echo $rowdb24['QTY_KG']; ?></td>
      <td style="text-align: center"><span style="text-align: right"><?php echo $rowdb24['ITEMELEMENTCODE']; ?></span></td>
      </tr>
	  				  
	<?php 
	$tCSRol3 += $rowdb25['QTY_DUS'];
	$tCSKG3  += $rowdb25['QTY_KG'];	
	$tCHRol3 += $rowdb24['QTY_DUS'];
	$tCHKG3  += $rowdb24['QTY_KG'];	
	$tCSRol +=$tCSR;
	$tCSRol1 +=$tCSR1;	
	$tKCutS +=$tCS;
	$tKCutH +=$tCH;	
	} ?>
				  </tbody>
                  <tfoot> 
		<tr>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right"><strong>Total</strong></td>
	    <td style="text-align: right"><strong><?php echo $tCHKG3; ?></strong></td>
	    <td style="text-align: center">&nbsp;</td>
	    </tr>			
		</tfoot>
                </table>					
					</div> 
              <!-- /.card-body -->
            </div>
		 </div>	
		</div>
			
		<div class="row">
			<div class="col-md-4">	
		<div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Detail Data Kain Greige Change Project Out</h3>
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
						
					<table id="example3" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th colspan="5" valign="middle" style="text-align: center">Change Project</th>
                    <th colspan="2" valign="middle" style="text-align: center">Sisa</th>
                    </tr>
                  <tr>
                    <th valign="middle" style="text-align: center">Userid</th>
                    <th valign="middle" style="text-align: center">Tanggal</th>
                    <th valign="middle" style="text-align: center">Project</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">KG</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB23 = " 
	SELECT STOCKOUT.TRANSACTIONDATE,
	STOCKOUT.PROJECTCODE,STOCKOUT.CREATIONUSER,
	STOCKOUT.ORDERCODE,STOCKOUT.PROVISIONALCODE,STOCKOUT.ORDERLINE,
	COUNT(STOCKOUT.ITEMELEMENTCODE) AS QTY_ROL,
	SUM(STOCKOUT.BASEPRIMARYQUANTITY) AS QTY_KG FROM
(
		SELECT 
s.ITEMELEMENTCODE,s.TRANSACTIONNUMBER FROM STOCKTRANSACTION s 
LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.NAMENAME = 'StatusRetur'
WHERE s.PROJECTCODE='$Project' AND s.ITEMTYPECODE='KGF' AND 
s.DECOSUBCODE02='$subC1' AND s.DECOSUBCODE03='$subC2' AND 
s.DECOSUBCODE04='$subC3' AND s.LOGICALWAREHOUSECODE ='M021' AND
s.TEMPLATECODE = 'OPN' AND a.VALUESTRING =  '1'
	) STOCKTRANSACTION 
LEFT OUTER JOIN 		
(SELECT STKKELUAR.ITEMELEMENTCODE,STKKELUAR.BASEPRIMARYQUANTITY,STKKELUAR.CREATIONUSER,  
       STKKELUAR.TRANSACTIONDATE,STKKELUAR.ORDERCODE, STKKELUAR.PROJECTCODE,
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
INNER JOIN (SELECT
	STOCKTRANSACTION.TRANSACTIONDATE,
	STOCKTRANSACTION.ORDERCODE,
	STOCKTRANSACTION.PROJECTCODE,
	STOCKTRANSACTION.BASEPRIMARYQUANTITY,
	STOCKTRANSACTION.ITEMELEMENTCODE,
	STOCKTRANSACTION.CREATIONUSER 
FROM STOCKTRANSACTION 
WHERE STOCKTRANSACTION.ITEMTYPECODE ='KGF'  AND STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M021' 
AND TEMPLATECODE ='312') AS STKKELUAR ON STKKELUAR.ITEMELEMENTCODE=STOCKTRANSACTION.ITEMELEMENTCODE
WHERE STOCKTRANSACTION.PROJECTCODE='$Project' AND DECOSUBCODE02='$subC1' AND DECOSUBCODE03='$subC2' AND DECOSUBCODE04='$subC3' and 
STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M021' 
AND NOT ITXVIEWLAPMASUKGREIGE.ORDERLINE IS NULL) AS STOCKOUT
ON STOCKTRANSACTION.ITEMELEMENTCODE=STOCKOUT.ITEMELEMENTCODE
GROUP BY STOCKOUT.TRANSACTIONDATE,STOCKOUT.PROJECTCODE,STOCKOUT.ORDERCODE,STOCKOUT.CREATIONUSER,
STOCKOUT.PROVISIONALCODE,STOCKOUT.ORDERLINE	
	 ";
	$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));	
	$srol1=0; $skg1=0; $TSKg1=0; $TSRol1=0;				  
    while($rowdb23 = db2_fetch_assoc($stmt3)){ 
		$bonMCG=trim($rowdb23['PROVISIONALCODE'])."-".trim($rowdb23['ORDERLINE']);
		$sqlDB23B = "
		SELECT COUNT(BALANCE.BASEPRIMARYQUANTITYUNIT) AS ROL,SUM(BALANCE.BASEPRIMARYQUANTITYUNIT) AS BERAT,BALANCE.LOTCODE  
		FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION RIGHT OUTER JOIN 
		DB2ADMIN.BALANCE  BALANCE ON BALANCE.ELEMENTSCODE =STOCKTRANSACTION.ITEMELEMENTCODE  
		WHERE STOCKTRANSACTION.LOGICALWAREHOUSECODE='M021' AND STOCKTRANSACTION.ORDERCODE='$rowdb23[PROVISIONALCODE]'
		AND STOCKTRANSACTION.ORDERLINE ='$rowdb23[ORDERLINE]' AND BALANCE.PROJECTCODE='$rowdb23[PROJECTCODE]'
		GROUP BY BALANCE.LOTCODE";					  
		$stmt2B   = db2_exec($conn1,$sqlDB23B, array('cursor'=>DB2_SCROLLABLE));	
		$rowdb23B = db2_fetch_assoc($stmt2B);
		if($rowdb23B['ROL']!=""){$srol1=$rowdb23B['ROL'];} else{ $srol1=0; }
		if($rowdb23B['BERAT']!=""){$skg1=$rowdb23B['BERAT'];} else{ $skg1=0; }
?>
	  <tr>
	    <td style="text-align: center"><?php echo $rowdb23['CREATIONUSER']; ?></td>
	  <td style="text-align: center"><?php echo $rowdb23['TRANSACTIONDATE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb23['PROJECTCODE']; ?></td>
      <td style="text-align: right"><?php echo $rowdb23['QTY_ROL']; ?></td>
      <td style="text-align: right"><?php echo $rowdb23['QTY_KG']; ?></td>
      <td style="text-align: center"><?php  echo $srol1; ?></td>
      <td style="text-align: right"><?php echo $skg1; ?></td>
      </tr>
	  				  
	<?php 
	 $no++; 
	$tCGRol3+=$rowdb23['QTY_ROL'];
	$tCGKG3 +=$rowdb23['QTY_KG'];
	$TSRol1 +=$srol1;
	$TSKg1 +=$skg1;			
	} 
	$TklCP =$tCGKG3-$TSKg1;				  
					  ?>
				  </tbody>
                  <tfoot> 
		<tr>
		  <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center"><span style="text-align: right"><strong>Total</strong></span></td>
	    <td style="text-align: right"><strong><?php echo $tCGRol3;?></strong></td>
	    <td style="text-align: right"><strong><?php echo $tCGKG3;?></strong></td>
	    <td style="text-align: center"><strong><?php echo $TSRol1; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo $TSKg1; ?></strong></td>
	    </tr>			
		</tfoot>
                </table>		
					</div> 
              <!-- /.card-body -->
            </div>
		 </div>
        	<div class="col-md-3">	
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Data Kain Greige Change Project In</h3>
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
						
					<table id="example9" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th colspan="5" valign="middle" style="text-align: center">Change Project</th>
                    </tr>
                  <tr>
                    <th valign="middle" style="text-align: center">Userid</th>
                    <th valign="middle" style="text-align: center">Tanggal</th>
                    <th valign="middle" style="text-align: center">Code</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB23In = " 
	SELECT 
STKOUT.CREATIONUSER, STKOUT.TRANSACTIONDATE, STKOUT.DECOSUBCODE02, STKOUT.DECOSUBCODE03, STKOUT.DECOSUBCODE04, 
STKOUT.LOTCODE, sum(STKOUT.BASEPRIMARYQUANTITY) AS KG, count(STKOUT.ITEMELEMENTCODE) AS JML
FROM
(
		SELECT 
s.ITEMELEMENTCODE,s.TRANSACTIONNUMBER FROM STOCKTRANSACTION s 
LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.NAMENAME = 'StatusRetur'
WHERE s.PROJECTCODE='$Project' AND s.ITEMTYPECODE='KGF' AND 
s.DECOSUBCODE02='$subC1' AND s.DECOSUBCODE03='$subC2' AND 
s.DECOSUBCODE04='$subC3' AND s.LOGICALWAREHOUSECODE ='M021' AND
s.TEMPLATECODE = 'OPN' AND a.VALUESTRING =  '1'
	) STOCKTRANSACTION 
LEFT OUTER JOIN
(SELECT 
s.CREATIONUSER, s.TRANSACTIONDATE, s.DECOSUBCODE02, s.DECOSUBCODE03, s.DECOSUBCODE04, 
s.LOTCODE, s.BASEPRIMARYQUANTITY, s.ITEMELEMENTCODE 
FROM STOCKTRANSACTION s
WHERE s.PROJECTCODE='$Project' AND s.ITEMTYPECODE='KGF' AND 
s.DECOSUBCODE02='$subC1' AND s.DECOSUBCODE03='$subC2' AND 
s.DECOSUBCODE04='$subC3' AND s.LOGICALWAREHOUSECODE ='M021' AND
s.TEMPLATECODE = '311' 
GROUP BY s.TRANSACTIONDATE, s.DECOSUBCODE02, s.DECOSUBCODE03, s.DECOSUBCODE04, 
s.LOTCODE,s.CREATIONUSER,s.BASEPRIMARYQUANTITY,s.ITEMELEMENTCODE) STKOUT
ON STOCKTRANSACTION.ITEMELEMENTCODE = STKOUT.ITEMELEMENTCODE
GROUP BY STKOUT.TRANSACTIONDATE, STKOUT.DECOSUBCODE02, STKOUT.DECOSUBCODE03, STKOUT.DECOSUBCODE04, 
STKOUT.LOTCODE,STKOUT.CREATIONUSER	
	 ";
	$stmt3In   = db2_exec($conn1,$sqlDB23In, array('cursor'=>DB2_SCROLLABLE));			  
    while($rowdb23In = db2_fetch_assoc($stmt3In)){
		$itemcIn=trim($rowdb23In['DECOSUBCODE02'])."".trim($rowdb23In['DECOSUBCODE03'])." ".trim($rowdb23In['DECOSUBCODE04']);
?>
	  <tr>
	    <td style="text-align: center"><?php echo $rowdb23In['CREATIONUSER']; ?></td>
	  <td style="text-align: center"><?php echo $rowdb23In['TRANSACTIONDATE']; ?></td>
      <td style="text-align: center"><?php echo $itemcIn; ?></td>
      <td style="text-align: right"><?php echo $rowdb23In['JML']; ?></td>
      <td style="text-align: right"><?php echo $rowdb23In['KG']; ?></td>
      </tr>
	  				  
	<?php 
	$no++; 
	$tCGRol3In+=$rowdb23In['JML'];
	$tCGKG3In +=$rowdb23In['KG'];				
	} 				  
					  ?>
				  </tbody>
                  <tfoot> 
		<tr>
		  <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center"><span style="text-align: right"><strong>Total</strong></span></td>
	    <td style="text-align: right"><strong><?php echo $tCGRol3In;?></strong></td>
	    <td style="text-align: right"><strong><?php echo $tCGKG3In;?></strong></td>
	    </tr>			
		</tfoot>
                </table>		
					</div> 
              <!-- /.card-body -->
            </div>
		 </div> 
			<div class="col-md-5">	
		<div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Detail Data Kain Greige Stock Take</h3>
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
			<table id="example10" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th rowspan="2" valign="middle" style="text-align: center">No</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Userid</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Tanggal</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Code</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Lot</th>
                    <th colspan="2" valign="middle" style="text-align: center">Stock Take</th>
                    <th colspan="2" valign="middle" style="text-align: center">Tarikan Kain</th>
                    <th colspan="2" valign="middle" style="text-align: center">Permintaan Potong</th>
                    </tr>
                  <tr>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB21TK = " SELECT 
s.CREATIONUSER, s.TRANSACTIONDATE, s.DECOSUBCODE02, s.DECOSUBCODE03, s.DECOSUBCODE04, 
s.LOTCODE, sum(s.BASEPRIMARYQUANTITY) AS KG, count(s.ITEMELEMENTCODE) AS JML, a.VALUESTRING AS PTG  
FROM STOCKTRANSACTION s
LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.NAMENAME = 'StatusPotong'
WHERE s.PROJECTCODE='$Project' AND s.ITEMTYPECODE='KGF' AND 
s.DECOSUBCODE02='$subC1' AND s.DECOSUBCODE03='$subC2' AND 
s.DECOSUBCODE04='$subC3' AND s.LOGICALWAREHOUSECODE ='M021' AND
s.TEMPLATECODE = '098' 
GROUP BY s.TRANSACTIONDATE, s.DECOSUBCODE02, s.DECOSUBCODE03, s.DECOSUBCODE04, 
s.LOTCODE,s.CREATIONUSER, a.VALUESTRING ";
	$stmt1TK   = db2_exec($conn1,$sqlDB21TK, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21TK = db2_fetch_assoc($stmt1TK)){ 
$itemcTK=trim($rowdb21TK['DECOSUBCODE02'])."".trim($rowdb21TK['DECOSUBCODE03'])." ".trim($rowdb21TK['DECOSUBCODE04']);	
		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb21TK['CREATIONUSER']; ?></td>
	  <td style="text-align: center"><?php echo $rowdb21TK['TRANSACTIONDATE']; ?></td>
      <td style="text-align: center"><?php echo $itemcTK; ?></td> 
      <td style="text-align: center"><?php echo $rowdb21TK['LOTCODE']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21TK['JML']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21TK['KG']; ?></td>
      <td style="text-align: right"><?php if($rowdb21TK['PTG']=="2") {echo $rtk1=$rowdb21TK['JML'];}else{echo $rtk1="0"; } ?></td>
      <td style="text-align: right"><?php if($rowdb21TK['PTG']=="2") {echo $tk1=$rowdb21TK['KG'];}else{echo $tk1="0"; } ?></td>
      <td style="text-align: right"><?php if($rowdb21TK['PTG']=="1") {echo $rptg=$rowdb21TK['JML'];}else{echo $rptg="0"; } ?></td>
      <td style="text-align: right"><?php if($rowdb21TK['PTG']=="1") {echo $ptg=$rowdb21TK['KG'];}else{echo $ptg="0"; } ?></td>
      </tr>
	  				  
	<?php 
	 $no++; 
	$tMRolTK+=$rowdb21TK['JML'];
	$tMKGTK +=$rowdb21TK['KG'];	
	$tPRol+=$rptg;
	$tPKG+=$ptg;
	$tTRol+=$rtk1;
	$tTKG+=$tk1;	
	} ?>
				  </tbody>
                  <tfoot>
		<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td style="text-align: center"><span style="text-align: right"><strong>Total</strong></span></td>
	    <td style="text-align: right"><strong><?php echo $tMRolTK;?></strong></td>
	    <td style="text-align: right"><strong><?php echo $tMKGTK;?></strong></td>
	    <td style="text-align: right"><strong><?php echo $tTRol;?></strong></td>
	    <td style="text-align: right"><strong><?php echo $tTKG;?></strong></td>
	    <td style="text-align: right"><strong><?php echo $tPRol;?></strong></td>
	    <td style="text-align: right"><strong><?php echo $tPKG;?></strong></td>
	    </tr>			
		</tfoot>
                </table>							
		  </div> 
              <!-- /.card-body -->
            </div>
		 </div>			 	 
		</div>			
		<div class="row">
          
		 	 
		 <div class="col-md-5">		
		<div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Detail Data Kain Greige Keluar</h3>
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
					<table id="example7" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th colspan="10" valign="middle" style="text-align: center">Keluar</th>
                    </tr>
                  <tr>
                    <th valign="middle" style="text-align: center">Tanggal</th>
                    <th valign="middle" style="text-align: center">Prod. Order</th>
                    <th valign="middle" style="text-align: center">Order</th>
                    <th valign="middle" style="text-align: center">Demand</th>
                    <th valign="middle" style="text-align: center">Bruto</th>
                    <th valign="middle" style="text-align: center">Lot</th>
                    <th valign="middle" style="text-align: center">Warna</th>
                    <th valign="middle" style="text-align: center">Project</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB23 = " 
	SELECT 
	STKKELUAR.TRANSACTIONDATE,
	STKKELUAR.ORDERCODE,
	STKKELUAR.PROJECTCODE,
	SUM(STKKELUAR.BASEPRIMARYQUANTITY) AS QTY_KG,
	COUNT(STKKELUAR.ITEMELEMENTCODE) AS QTY_ROL,
	STKKELUAR.LOTCODE FROM (
		SELECT 
s.ITEMELEMENTCODE,s.TRANSACTIONNUMBER FROM STOCKTRANSACTION s 
LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.NAMENAME = 'StatusRetur'
WHERE s.PROJECTCODE='$Project' AND s.ITEMTYPECODE='KGF' AND 
s.DECOSUBCODE02='$subC1' AND s.DECOSUBCODE03='$subC2' AND 
s.DECOSUBCODE04='$subC3' AND s.LOGICALWAREHOUSECODE ='M021' AND
s.TEMPLATECODE = 'OPN' AND a.VALUESTRING =  '1'
	) STOCKTRANSACTION
INNER JOIN (SELECT
	STOCKTRANSACTION.TRANSACTIONDATE,
	STOCKTRANSACTION.ORDERCODE,
	STOCKTRANSACTION.PROJECTCODE,
	STOCKTRANSACTION.BASEPRIMARYQUANTITY,
	STOCKTRANSACTION.ITEMELEMENTCODE,
	STOCKTRANSACTION.LOTCODE,
	STOCKTRANSACTION.CREATIONUSER 
FROM STOCKTRANSACTION 
WHERE STOCKTRANSACTION.ITEMTYPECODE ='KGF'  AND STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M021' 
AND TEMPLATECODE ='120') AS STKKELUAR ON STKKELUAR.ITEMELEMENTCODE=STOCKTRANSACTION.ITEMELEMENTCODE	 
GROUP BY STKKELUAR.ORDERCODE,
	STKKELUAR.PROJECTCODE,
	STKKELUAR.LOTCODE,
	STKKELUAR.TRANSACTIONDATE
	";
	$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));			  
    while($rowdb23 = db2_fetch_assoc($stmt3)){ 
	$sqlDB2PRJ = " 
SELECT LISTAGG(DISTINCT TRIM(PROJECTCODE),', ') AS PROJECTCODE,
LISTAGG(DISTINCT TRIM(ORIGDLVSALORDLINESALORDERCODE),', ') AS ORIGDLVSALORDLINESALORDERCODE,
LISTAGG(DISTINCT TRIM(PRODUCTIONDEMANDCODE),', ') AS PRODUCTIONDEMANDCODE,
PRODUCTIONORDERCODE
FROM ITXVIEWHEADERKNTORDER
WHERE PRODUCTIONORDERCODE='".$rowdb23['ORDERCODE']."'
GROUP BY PRODUCTIONORDERCODE
";
$stmtPRJ   = db2_exec($conn1,$sqlDB2PRJ, array('cursor'=>DB2_SCROLLABLE));
$rowdbPRJ = db2_fetch_assoc($stmtPRJ);	
if($rowdb23['ORIGDLVSALORDLINESALORDERCODE']!=""){$prjct=$rowdb23['ORIGDLVSALORDLINESALORDERCODE'];}else{$prjct=substr($rowdbPRJ['ORIGDLVSALORDLINESALORDERCODE'],0,10);}		
	$sqlDB2WRN = " 
SELECT ugp.LONGDESCRIPTION AS WARNA, p.PRODUCTIONORDERCODE ,LISTAGG(DISTINCT  TRIM(pd.CODE),', ') AS PRODUCTIONDEMANDCODE,pd.SUBCODE01,pd.SUBCODE02,
pd.SUBCODE03,pd.SUBCODE04,pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,pd.SUBCODE08,pd.ITEMTYPEAFICODE,
pd.ORIGDLVSALORDERLINEORDERLINE,pd.DESCRIPTION  
	FROM PRODUCTIONDEMANDSTEP p
	LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pd.CODE =p.PRODUCTIONDEMANDCODE
	LEFT JOIN PRODUCT pr ON
    pr.ITEMTYPECODE = pd.ITEMTYPEAFICODE
    AND pr.SUBCODE01 = pd.SUBCODE01
    AND pr.SUBCODE02 = pd.SUBCODE02
    AND pr.SUBCODE03 = pd.SUBCODE03
    AND pr.SUBCODE04 = pd.SUBCODE04
    AND pr.SUBCODE05 = pd.SUBCODE05
    AND pr.SUBCODE06 = pd.SUBCODE06
    AND pr.SUBCODE07 = pd.SUBCODE07
    AND pr.SUBCODE08 = pd.SUBCODE08
    AND pr.SUBCODE09 = pd.SUBCODE09
    AND pr.SUBCODE10 = pd.SUBCODE10
    LEFT JOIN DB2ADMIN.USERGENERICGROUP ugp ON
    pd.SUBCODE05 = ugp.CODE
    WHERE (pd.PROJECTCODE='$rowdb23[PROJECTCODE]' OR pd.ORIGDLVSALORDLINESALORDERCODE='$prjct')  AND p.PRODUCTIONORDERCODE='$rowdb23[ORDERCODE]'
	GROUP BY ugp.LONGDESCRIPTION,p.PRODUCTIONORDERCODE,
	pd.SUBCODE01,pd.SUBCODE02,pd.SUBCODE03,pd.SUBCODE04,
	pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,
	pd.SUBCODE08,pd.ITEMTYPEAFICODE,pd.ORIGDLVSALORDERLINEORDERLINE,pd.DESCRIPTION
";
$stmtWRN   = db2_exec($conn1,$sqlDB2WRN, array('cursor'=>DB2_SCROLLABLE));
$rowdbWRN = db2_fetch_assoc($stmtWRN);	
/*$sqlDB2BRTO = " SELECT SUM(x.USERPRIMARYQUANTITY) AS QTYBRUTO  FROM DB2ADMIN.ITXVIEW_KGBRUTO x
WHERE (PROJECTCODE ='$rowdb23[PROJECTCODE]' OR PROJECTCODE ='$prjct') AND ORIGDLVSALORDERLINEORDERLINE ='$rowdbWRN[ORIGDLVSALORDERLINEORDERLINE]'";		
$stmtBRTO   = db2_exec($conn1,$sqlDB2BRTO, array('cursor'=>DB2_SCROLLABLE));
$rowdbBRTO = db2_fetch_assoc($stmtBRTO);*/	
if($rowdb23['PROJECTCODE']!=""){$prj=$rowdb23['PROJECTCODE'];}else if($rowdb23['ORIGDLVSALORDLINESALORDERCODE']!=""){$prj=$rowdb23['ORIGDLVSALORDLINESALORDERCODE'];}else{$prj=$prjct;}	
if($rowdbWRN['PRODUCTIONDEMANDCODE']!=""){$dmnd=$rowdbWRN['PRODUCTIONDEMANDCODE'];}else{ $dmnd=$rowdbPRJ['PRODUCTIONDEMANDCODE'];}	
$sqlDB2ORD = " 
SELECT x.ORIGDLVSALORDLINESALORDERCODE,x.ORIGDLVSALORDERLINEORDERLINE FROM DB2ADMIN.PRODUCTIONDEMAND x
WHERE CODE = '$dmnd'
";
$stmtORD   = db2_exec($conn1,$sqlDB2ORD, array('cursor'=>DB2_SCROLLABLE));
$rowdbORD = db2_fetch_assoc($stmtORD);
$sqlDB2BRTO = " 
SELECT a.VALUEDECIMAL AS BRUTOKG, a1.VALUEDECIMAL AS NETTOKG FROM
(SELECT sol.ABSUNIQUEID  FROM DB2ADMIN.PRODUCTIONDEMAND pd
LEFT OUTER JOIN DB2ADMIN.SALESORDERLINE sol ON sol.SALESORDERCODE = pd.ORIGDLVSALORDLINESALORDERCODE AND sol.ORDERLINE =pd.ORIGDLVSALORDERLINEORDERLINE 
WHERE sol.SALESORDERCODE ='".$rowdbORD['ORIGDLVSALORDLINESALORDERCODE']."' AND sol.ORDERLINE ='".$rowdbORD['ORIGDLVSALORDERLINEORDERLINE']."'
GROUP BY sol.ABSUNIQUEID) sol 
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a ON a.UNIQUEID =sol.ABSUNIQUEID AND a.NAMENAME ='BrutoKG'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a1 ON a1.UNIQUEID =sol.ABSUNIQUEID AND a1.NAMENAME ='NettoKG'
";
$stmtBRTO   = db2_exec($conn1,$sqlDB2BRTO, array('cursor'=>DB2_SCROLLABLE));
$rowdbBRTO = db2_fetch_assoc($stmtBRTO);		
		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $rowdb23['TRANSACTIONDATE']; ?></td> 
      <td style="text-align: center"><?php echo $rowdb23['ORDERCODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdbORD['ORIGDLVSALORDLINESALORDERCODE']." - ".$rowdbORD['ORIGDLVSALORDERLINEORDERLINE']; ?></td>
      <td style="text-align: center"><?php if($rowdbWRN['PRODUCTIONDEMANDCODE']!=""){echo $rowdbWRN['PRODUCTIONDEMANDCODE'];}else{ echo $rowdbPRJ['PRODUCTIONDEMANDCODE'];} ?></td>
     <!-- <td align="right"><a href="#" id="<?php echo trim($prj)."-".trim($rowdbWRN['ORIGDLVSALORDERLINEORDERLINE']);?>" class="show_detail_bruto"><?php echo number_format(round($rowdbBRTO['QTYBRUTO'],2),2); ?></a></td>-->
	  <td align="right"><a href="#" id="<?php echo trim($rowdbORD['ORIGDLVSALORDLINESALORDERCODE'])."-".trim($rowdbORD['ORIGDLVSALORDERLINEORDERLINE']);?>" class="show_detail_bruto"><?php echo number_format(round($rowdbBRTO['BRUTOKG'],2),2); ?></a></td>
	  <td style="text-align: center"><?php echo $rowdbWRN['DESCRIPTION'];?></td>	  
      <td style="text-align: center"><?php echo $rowdbWRN['WARNA'];?></td>
      <td style="text-align: center"><?php if($rowdb23['PROJECTCODE']!=""){echo $rowdb23['PROJECTCODE'];}else if($rowdb23['ORIGDLVSALORDLINESALORDERCODE']!=""){ echo $rowdb23['ORIGDLVSALORDLINESALORDERCODE'];}else{ echo $rowdbPRJ['ORIGDLVSALORDLINESALORDERCODE'];} ?></td>
      <td style="text-align: right"><a href="#" id="<?php echo trim($rowdb23['ORDERCODE']).trim($rowdb23['LOTCODE']); ?>" class="show_detail_out"><?php echo $rowdb23['QTY_ROL']; ?></a></td>
      <td style="text-align: right"><?php echo $rowdb23['QTY_KG']; ?></td>
      </tr>
	  				  
	<?php 
	 $no++; 
	$tKRol31 +=$rowdb23['QTY_ROL'];
	$tKKG31  +=$rowdb23['QTY_KG'];	
	} ?>
				  </tbody>
                  <tfoot> 
		<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center"><span style="text-align: right"><strong>Total</strong></span></td>
	    <td style="text-align: right"><strong><?php echo $tKRol31;?></strong></td>
	    <td style="text-align: right"><strong><?php echo $tKKG31;?></strong></td>
	    </tr>			
		</tfoot>
                </table>					
					</div> 
              <!-- /.card-body -->
            </div> 
		 </div>	 
		</div> 
		<?php 
		  //$sisaGreige=(round($tMKG,2)+$tMKGR+$tCGKG3In)-(round($tKKG31,2)+$tKCutS+$tKCutH+$tMKGTK); 
			//$sisaGreigeRol=($tMRol+$tMRolR+$tMRolRP)-($tKRol31+$tCSRol); 
			$sisaGreigeRol=($tMRol+$tMRolSR+$tMRolRP+$tCSRol1)-($tKRol31 + $tPRol + $tTRol);
			$sisaGreige=($tMKG + $tMKGSR + $tMKGRP) - ($tKKG31 + $tKCutS + $tKCutH + $tPKG + $tTKG); 
		  ?>	
		<div class="row">
			<div class="col-md-4">	
		<div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Sisa Data Kain Greige</h3>
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
               <label for="sisa" class="col-md-5">Sisa Greige</label>
			   <div class="col-md-2">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $sisaGreigeRol; ?>" name="sisarol" style="text-align: right" readonly>
			   </div>			
               <div class="col-md-3">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo round($sisaGreige,3); ?>" name="sisa" style="text-align: right" readonly>
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
<div id="BrutoDetailShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>	
<div id="OutDetailShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
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
