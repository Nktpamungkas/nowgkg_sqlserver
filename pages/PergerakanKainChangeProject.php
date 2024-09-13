<?php
$Project	= isset($_POST['projectcode']) ? $_POST['projectcode'] : '';
$HangerNO	= isset($_POST['hangerno']) ? $_POST['hangerno'] : '';
$subC1		= substr($HangerNO,0,3);
$subC2		= substr($HangerNO,3,5);
$subC3		= substr($HangerNO,9,3);

$sqlDB2 =" SELECT SUBCODE02,SUBCODE03,SUBCODE04, SUM(BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY, CURRENT_TIMESTAMP AS TGLS FROM ITXVIEWKNTORDER 
WHERE ITEMTYPEAFICODE ='KFF' AND PROJECTCODE ='$Project' AND (PROGRESSSTATUS='2' OR PROGRESSSTATUS='6')
GROUP BY SUBCODE02,SUBCODE03,SUBCODE04,CURRENT_TIMESTAMP ";	
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
$sqlDB210 =" SELECT SUM(BASEPRIMARYQUANTITY) AS QTY FROM ITXVIEWKNTORDER WHERE ITEMTYPEAFICODE ='KFF' AND PROJECTCODE ='$Project' AND
SUBCODE02='$subC1' AND SUBCODE03='$subC2' AND SUBCODE04='$subC3' AND (PROGRESSSTATUS='2' OR PROGRESSSTATUS='6')  ";	
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
            <h3 class="card-title">Filter Pergerakan Kain Change Project</h3>

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
			  <!--<div class="form-group row">
               <label for="qtynetto" class="col-md-5">Netto</label>
               <div class="col-md-5">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo number_format(round($rowdb210['QTY'],2),2); ?>" name="qtynetto" style="text-align: right" required>
			   </div>
			  <strong> Kgs</strong>	  
            </div>-->
			  <div class="form-group row">
               <label for="qtybruto" class="col-md-5">Bruto</label>
               <div class="col-md-5">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo number_format(round($rowdb210['QTY'],2),2); ?>" name="qtybruto" style="text-align: right" required>
			   </div>
			  <strong> Kgs</strong>	  
            </div>
			  
			  <button class="btn btn-info" type="submit" >Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  
			</div>
		<div class="col-md-9">	
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Data Kain Change Project</h3>				 
          </div>
              <!-- /.card-header -->              		
					<div class="card-body">
					<table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">UserID</th>
                    <th valign="middle" style="text-align: center">Tgl Change</th>
                    <th valign="middle" style="text-align: center">Project Awal</th>
                    <th valign="middle" style="text-align: center">Demand KGF</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB21 = " SELECT STKMASUK.PROJECTCODE AS PROJECTCODEAWAL,STOCKTRANSACTION.TRANSACTIONDATE,STOCKTRANSACTION.CREATIONUSER,
	COUNT(STOCKTRANSACTION.ITEMELEMENTCODE) AS QTY_ROL, SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,STOCKTRANSACTION.LOTCODE FROM STOCKTRANSACTION 
LEFT OUTER JOIN (
SELECT * FROM STOCKTRANSACTION 
WHERE STOCKTRANSACTION.TEMPLATECODE ='311' AND STOCKTRANSACTION.ITEMTYPECODE ='KGF' AND 
STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M021'
) STKMASUK ON STKMASUK.ITEMELEMENTCODE = STOCKTRANSACTION.ITEMELEMENTCODE
WHERE STOCKTRANSACTION.TEMPLATECODE ='312' AND STOCKTRANSACTION.ITEMTYPECODE ='KGF' AND 
STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M021' AND STOCKTRANSACTION.PROJECTCODE='$Project' AND NOT STKMASUK.PROJECTCODE='$Project' AND
STOCKTRANSACTION.DECOSUBCODE02='$subC1' AND STOCKTRANSACTION.DECOSUBCODE03='$subC2' AND STOCKTRANSACTION.DECOSUBCODE04='$subC3'
GROUP BY STKMASUK.PROJECTCODE,STOCKTRANSACTION.TRANSACTIONDATE,STOCKTRANSACTION.CREATIONUSER,STOCKTRANSACTION.LOTCODE
";
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
		$sqlDBMC = " 
		SELECT ad.VALUESTRING AS MESIN FROM PRODUCTIONDEMAND pd 
		LEFT OUTER JOIN ADSTORAGE ad ON ad.UNIQUEID = pd.ABSUNIQUEID AND ad.NAMENAME ='MachineNo'
		WHERE CODE ='$rowdb21[LOTCODE]'
		";					  
		$stmt2MC   = db2_exec($conn1,$sqlDBMC, array('cursor'=>DB2_SCROLLABLE));	
		$rowdbMC = db2_fetch_assoc($stmt2MC);
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb21['CREATIONUSER']; ?></td>
	  <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['PROJECTCODEAWAL']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21['QTY_ROL']; ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['QTY_KG'],2),2); ?></td>
      </tr>
	  				  
	<?php 
	 $no++; 
	$tMRol+=$rowdb21['QTY_ROL'];
	$tMKG +=$rowdb21['QTY_KG'];		
	} ?>
				  </tbody>
                  <tfoot>
		<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center"><span style="text-align: right"><strong>Total</strong></span></td>
	    <td style="text-align: right"><strong><?php echo $tMRol;?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format(round($tMKG,2),2);?></strong></td>
	    </tr>			
		</tfoot>
                </table>					
					</div> 
              <!-- /.card-body -->
            </div>
		 </div>	
			
			</div>	
			  <div class="row">
		<div class="col-md-3">	
		<div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Detail Data Kain Change Project Return Production</h3>				 
          </div>
              <!-- /.card-header -->              		
					<div class="card-body">
					<table id="example5" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">Tgl</th>
                    <th valign="middle" style="text-align: center">OrderCode</th>
                    <th valign="middle" style="text-align: center">Rol</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">UserID</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB23 = " SELECT 
	STOCKTRANSACTION.LOGICALWAREHOUSECODE,
	STOCKTRANSACTION.DECOSUBCODE01,
	STOCKTRANSACTION.DECOSUBCODE02,
	STOCKTRANSACTION.DECOSUBCODE03,
	STOCKTRANSACTION.DECOSUBCODE04,
	STOCKTRANSACTION.DECOSUBCODE05,
	STOCKTRANSACTION.DECOSUBCODE06,
	STOCKTRANSACTION.DECOSUBCODE07,
	STOCKTRANSACTION.DECOSUBCODE08,
	STOCKTRANSACTION.TRANSACTIONDATE,
	STOCKTRANSACTION.LOTCODE,
	STOCKTRANSACTION.ORDERCODE,
	STOCKTRANSACTION.CREATIONUSER,
	STOCKTRANSACTION.PROJECTCODE,
	COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_DUS,
	SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,
	FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
	FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION 	
	LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
    STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER    
WHERE (STOCKTRANSACTION.ITEMTYPECODE ='KGF' OR STOCKTRANSACTION.ITEMTYPECODE ='FKG') and 
STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M021' AND STOCKTRANSACTION.PROJECTCODE='$Project' AND DECOSUBCODE02='$subC1' AND DECOSUBCODE03='$subC2' AND DECOSUBCODE04='$subC3' AND 
STOCKTRANSACTION.TEMPLATECODE ='125'
GROUP BY
	STOCKTRANSACTION.LOGICALWAREHOUSECODE,
	STOCKTRANSACTION.DECOSUBCODE01,
	STOCKTRANSACTION.DECOSUBCODE02,
	STOCKTRANSACTION.DECOSUBCODE03,
	STOCKTRANSACTION.DECOSUBCODE04,
	STOCKTRANSACTION.DECOSUBCODE05,
	STOCKTRANSACTION.DECOSUBCODE06,
	STOCKTRANSACTION.DECOSUBCODE07,
	STOCKTRANSACTION.DECOSUBCODE08,
	STOCKTRANSACTION.TRANSACTIONDATE,
	STOCKTRANSACTION.LOTCODE,
	STOCKTRANSACTION.ORDERCODE,
	STOCKTRANSACTION.CREATIONUSER,
	STOCKTRANSACTION.PROJECTCODE,
	FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION";
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
}	else{
	$sts24="<small class='badge badge-danger'>Keluar</small>";
	$tCS=$rowdb24['QTY_KG'];
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
	    <td style="text-align: right"><span style="text-align: center"><?php echo $rowdb23['TRANSACTIONDATE']; ?></span></td>
	  <td style="text-align: right"><span style="text-align: center"><?php echo $rowdb23['ORDERCODE']; ?></span></td>
      <td style="text-align: center"><span style="text-align: center"><?php echo $rowdb23['QTY_DUS']; ?></span></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb23['QTY_KG'],2),2); ?></td>
      <td style="text-align: center"><?php echo $rowdb23['CREATIONUSER']; ?></td>
      </tr>
	  				  
	<?php 
	$tRPKG3  += $rowdb23['QTY_KG'];
	} ?>
				  </tbody>
                  <tfoot> 
		<tr>
		  <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right"><strong>Total</strong></td>
	    <td style="text-align: right"><strong><?php echo number_format($tRPKG3,2); ?></strong></td>
	    <td style="text-align: center">&nbsp;</td>
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
					  
	$sqlDB23 = " SELECT 
	STOCKTRANSACTION.TRANSACTIONNUMBER,
	STOCKTRANSACTION.LOGICALWAREHOUSECODE,
	STOCKTRANSACTION.DECOSUBCODE01,
	STOCKTRANSACTION.DECOSUBCODE02,
	STOCKTRANSACTION.DECOSUBCODE03,
	STOCKTRANSACTION.DECOSUBCODE04,
	STOCKTRANSACTION.DECOSUBCODE05,
	STOCKTRANSACTION.DECOSUBCODE06,
	STOCKTRANSACTION.DECOSUBCODE07,
	STOCKTRANSACTION.DECOSUBCODE08,
	STOCKTRANSACTION.TRANSACTIONDATE,
	STOCKTRANSACTION.LOTCODE,
	STOCKTRANSACTION.ITEMELEMENTCODE,
	STOCKTRANSACTION.CREATIONUSER,
	STOCKTRANSACTION.PROJECTCODE,
	COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_DUS,
	SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,
	FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
	FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION 	
	LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
    STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER    
WHERE (STOCKTRANSACTION.ITEMTYPECODE ='KGF' OR STOCKTRANSACTION.ITEMTYPECODE ='FKG') and 
STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M021' AND STOCKTRANSACTION.PROJECTCODE='$Project' AND DECOSUBCODE02='$subC1' AND DECOSUBCODE03='$subC2' AND DECOSUBCODE04='$subC3' AND 
STOCKTRANSACTION.TEMPLATECODE ='341'
GROUP BY
	STOCKTRANSACTION.TRANSACTIONNUMBER,
	STOCKTRANSACTION.LOGICALWAREHOUSECODE,
	STOCKTRANSACTION.DECOSUBCODE01,
	STOCKTRANSACTION.DECOSUBCODE02,
	STOCKTRANSACTION.DECOSUBCODE03,
	STOCKTRANSACTION.DECOSUBCODE04,
	STOCKTRANSACTION.DECOSUBCODE05,
	STOCKTRANSACTION.DECOSUBCODE06,
	STOCKTRANSACTION.DECOSUBCODE07,
	STOCKTRANSACTION.DECOSUBCODE08,
	STOCKTRANSACTION.TRANSACTIONDATE,
	STOCKTRANSACTION.LOTCODE,
	STOCKTRANSACTION.ITEMELEMENTCODE,
	STOCKTRANSACTION.CREATIONUSER,
	STOCKTRANSACTION.PROJECTCODE,
	FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION";
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
}	else{
	$sts24="<small class='badge badge-danger'>Keluar</small>";
	$tCS=$rowdb24['QTY_KG'];
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
	$tCSRol3 += $rowdb25['QTY_DUS'];
	$tCSKG3  += $rowdb25['QTY_KG'];	
	$tCHRol3 += $rowdb24['QTY_DUS'];
	$tCHKG3  += $rowdb24['QTY_KG'];	
	$tKCutS +=$tCS;
	$tKCutH +=$tCH;	
	} ?>
				  </tbody>
                  <tfoot> 
		<tr>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right"><strong>Total</strong></td>
	    <td style="text-align: right"><strong><?php echo number_format($tCHKG3,2); ?></strong></td>
	    <td style="text-align: center">&nbsp;</td>
	    </tr>			
		</tfoot>
                </table>					
					</div> 
              <!-- /.card-body -->
            </div>
		 </div>	 
		 <div class="col-md-6">	
		<div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Detail Data Kain Greige Keluar</h3>				 
          </div>
              <!-- /.card-header -->              		
					<div class="card-body">
					<table id="example6" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th colspan="8" valign="middle" style="text-align: center">Keluar</th>
                    </tr>
                  <tr>
                    <th valign="middle" style="text-align: center">Tanggal</th>
                    <th valign="middle" style="text-align: center">Prod. Order</th>
                    <th valign="middle" style="text-align: center">Demand</th>
                    <th valign="middle" style="text-align: center">Warna</th>
                    <th valign="middle" style="text-align: center">Project</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">UserID</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
			  
	$sqlDB23 = " 
	SELECT KD.PRODUCTIONDEMANDCODE,KD.SUBCODE01,KD.SUBCODE02,KD.SUBCODE03,KD.SUBCODE04,KD.SUBCODE05,KD.SUBCODE06,
KD.SUBCODE07,KD.SUBCODE08,STKKELUAR.PROJECTCODE,STKKELUAR.ORDERCODE,STOCKTRANSACTION.TRANSACTIONDATE,STOCKTRANSACTION.CREATIONUSER,
COUNT(STKKELUAR.ITEMELEMENTCODE) AS QTY_ROL, SUM(STKKELUAR.BASEPRIMARYQUANTITY) AS QTY_KG,STOCKTRANSACTION.LOTCODE FROM STOCKTRANSACTION 
INNER JOIN (
SELECT * FROM STOCKTRANSACTION 
WHERE STOCKTRANSACTION.TEMPLATECODE ='120' AND STOCKTRANSACTION.ITEMTYPECODE ='KGF' AND 
STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M021' 
) STKKELUAR ON STKKELUAR.ITEMELEMENTCODE = STOCKTRANSACTION.ITEMELEMENTCODE
LEFT OUTER JOIN (
SELECT p.PRODUCTIONORDERCODE,p.PRODUCTIONDEMANDCODE,pd.SUBCODE01,pd.SUBCODE02,pd.SUBCODE03,
pd.SUBCODE04,pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,pd.SUBCODE08  FROM PRODUCTIONDEMANDSTEP p
LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pd.CODE =p.PRODUCTIONDEMANDCODE
GROUP BY p.PRODUCTIONORDERCODE,p.PRODUCTIONDEMANDCODE,pd.SUBCODE01,pd.SUBCODE02,pd.SUBCODE03,
pd.SUBCODE04,pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,pd.SUBCODE08
) KD ON KD.PRODUCTIONORDERCODE=STKKELUAR.ORDERCODE
WHERE STOCKTRANSACTION.TEMPLATECODE ='312' AND STOCKTRANSACTION.ITEMTYPECODE ='KGF' AND 
STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M021' AND STOCKTRANSACTION.PROJECTCODE='$Project' AND
STOCKTRANSACTION.DECOSUBCODE02='$subC1' AND STOCKTRANSACTION.DECOSUBCODE03='$subC2' AND STOCKTRANSACTION.DECOSUBCODE04='$subC3'
GROUP BY STKKELUAR.PROJECTCODE,STKKELUAR.ORDERCODE,STOCKTRANSACTION.TRANSACTIONDATE,STOCKTRANSACTION.CREATIONUSER,STOCKTRANSACTION.LOTCODE,
KD.PRODUCTIONDEMANDCODE,KD.SUBCODE01,KD.SUBCODE02,KD.SUBCODE03,KD.SUBCODE04,KD.SUBCODE05,KD.SUBCODE06,KD.SUBCODE07,KD.SUBCODE08
	";
	$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));			  
    while($rowdb23 = db2_fetch_assoc($stmt3)){ 
		
		$sqlDB2WRN = " 
SELECT i.WARNA  FROM PRODUCTIONDEMAND p 
LEFT OUTER JOIN ITXVIEWCOLOR i ON
p.SUBCODE01=i.SUBCODE01 AND
p.SUBCODE02=i.SUBCODE02 AND
p.SUBCODE03=i.SUBCODE03 AND
p.SUBCODE04=i.SUBCODE04 AND
p.SUBCODE05=i.SUBCODE05 AND
p.SUBCODE06=i.SUBCODE06 AND
p.SUBCODE07=i.SUBCODE07 AND
p.SUBCODE08=i.SUBCODE08
WHERE CODE ='$rowdb23[PRODUCTIONDEMANDCODE]'
";
$stmtWRN   = db2_exec($conn1,$sqlDB2WRN, array('cursor'=>DB2_SCROLLABLE));
$rowdbWRN = db2_fetch_assoc($stmtWRN);
		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $rowdb23['TRANSACTIONDATE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb23['ORDERCODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb23['PRODUCTIONDEMANDCODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdbWRN['WARNA']; ?></td>
      <td style="text-align: center"><?php echo $rowdb23['PROJECTCODE']; ?></td>
      <td style="text-align: right"><?php echo $rowdb23['QTY_ROL']; ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb23['QTY_KG'],2),2); ?></td>
      <td style="text-align: center"><?php echo $rowdb23['CREATIONUSER']; ?></td>
      </tr>
	  				  
	<?php 
	 $no++; 
	$tKRol3+=$rowdb23['QTY_ROL'];
	$tKKG3 +=$rowdb23['QTY_KG'];	
	} ?>
				  </tbody>
                  <tfoot> 
		<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center"><span style="text-align: right"><strong>Total</strong></span></td>
	    <td style="text-align: right"><strong><?php echo $tKRol3;?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format(round($tKKG3,2),2);?></strong></td>
	    <td style="text-align: right">&nbsp;</td>
	    </tr>			
		</tfoot>
                </table>					
					</div> 
              <!-- /.card-body -->
            </div>
		 </div>	 
		</div> 
		<?php 
		  $sisaGreige=(round($tMKG,2)+$tRPKG3)-(round($tKKG3,2)+$tKCutS+$tKCutH+$TklCP); 
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
               <div class="col-md-5">  
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