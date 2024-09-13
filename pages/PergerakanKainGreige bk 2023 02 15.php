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
SUBCODE02,SUBCODE03,SUBCODE04, SUM(BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY, CURRENT_TIMESTAMP AS TGLS FROM ITXVIEWKNTORDER 
WHERE ITEMTYPEAFICODE ='KGF' AND (PROGRESSSTATUS='2' OR PROGRESSSTATUS='6')
GROUP BY SUBCODE02,SUBCODE03,SUBCODE04,CURRENT_TIMESTAMP,(CASE WHEN PROJECTCODE <> '' THEN PROJECTCODE ELSE ORIGDLVSALORDLINESALORDERCODE  END)) X
WHERE X.PROJECT='$Project' ";	
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
$sqlDB210 =" SELECT SUM(a.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY, SUM(a3.VALUEDECIMAL) AS QTYSALIN  FROM ITXVIEWKNTORDER a
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
            <h3 class="card-title">Filter Pergerakan Kain Greige</h3>

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
                <h3 class="card-title">Detail Data Kain Greige Masuk</h3>
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
                    <th valign="middle" style="text-align: center">No BON</th>
                    <th valign="middle" style="text-align: center">Prod. Order</th>
                    <th valign="middle" style="text-align: center">Code</th>
                    <th valign="middle" style="text-align: center">Jenis Benang</th>
                    <th valign="middle" style="text-align: center">Demand</th>
                    <th valign="middle" style="text-align: center">Mesin</th>
                    <th valign="middle" style="text-align: center">Hasil Inspek</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB21 = " SELECT STOCKTRANSACTION.TRANSACTIONDATE,STOCKTRANSACTION.PROJECTCODE,
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
ITXVIEWLAPMASUKGREIGE.USERPRIMARYUOMCODE,STOCKTRANSACTION.PROJECTCODE,STOCKTRANSACTION.TRANSACTIONDATE,
       ITXVIEWLAPMASUKGREIGE.WHSLOCATIONWAREHOUSEZONECODE,ITXVIEWLAPMASUKGREIGE.PROVISIONALCOUNTERCODE,
       ITXVIEWLAPMASUKGREIGE.WAREHOUSELOCATIONCODE,ITXVIEWLAPMASUKGREIGE.ORDERLINE,ITXVIEWLAPMASUKGREIGE.EXTERNALREFERENCE,ITXVIEWLAPMASUKGREIGE.INTERNALREFERENCE,
       ITXVIEWLAPMASUKGREIGE.ITEMDESCRIPTION,ITXVIEWLAPMASUKGREIGE.LOTCODE,ITXVIEWLAPMASUKGREIGE.SUBCODE02,
       ITXVIEWLAPMASUKGREIGE.SUBCODE03,ITXVIEWLAPMASUKGREIGE.SUBCODE04";
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
	  <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
      <td style="text-align: center"><?php echo $bon; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['EXTERNALREFERENCE']; ?></td>
      <td><?php echo $itemc;?></td> 
      <td style="text-align: left"><?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
      <td style="text-align: right"><?php echo $rowdbMC['MESIN'];?></td>
      <td style="text-align: right"><?php echo $rowdb21['INTERNALREFERENCE']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21['QTY_ROL']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21['QTY_KG']; ?></td>
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
	    <td>&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right"><span style="text-align: center"><strong>Total</strong></span></td>
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
                <h3 class="card-title">Detail Data Retur Produksi</h3>
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
                <table id="example11" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">UserID</th>
                    <th valign="middle" style="text-align: center">Project</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB21RP = " SELECT STOCKOUT.TRANSACTIONDATE,
	STOCKOUT.PROJECTCODE,STOCKOUT.CREATIONUSER,
	STOCKOUT.ORDERCODE,STOCKOUT.PROVISIONALCODE,STOCKOUT.ORDERLINE,
	COUNT(STOCKOUT.ITEMELEMENTCODE) AS QTY_ROL,
	SUM(STOCKOUT.BASEPRIMARYQUANTITY) AS QTY_KG
	FROM
(
SELECT 
STKKELUAR.ITEMELEMENTCODE,STKKELUAR.BASEPRIMARYQUANTITY,STKKELUAR.CREATIONUSER,  
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
AND TEMPLATECODE ='125') AS STKKELUAR ON STKKELUAR.ITEMELEMENTCODE=STOCKTRANSACTION.ITEMELEMENTCODE
WHERE STOCKTRANSACTION.PROJECTCODE='$Project' AND DECOSUBCODE02='$subC1' AND DECOSUBCODE03='$subC2' AND DECOSUBCODE04='$subC3' and 
STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M021' 
AND NOT ITXVIEWLAPMASUKGREIGE.ORDERLINE IS NULL
GROUP BY STKKELUAR.ITEMELEMENTCODE,STKKELUAR.BASEPRIMARYQUANTITY,STKKELUAR.CREATIONUSER,  
       STKKELUAR.TRANSACTIONDATE,STKKELUAR.ORDERCODE, STKKELUAR.PROJECTCODE,
	   ITXVIEWLAPMASUKGREIGE.PROVISIONALCODE,ITXVIEWLAPMASUKGREIGE.ORDERLINE) AS STOCKOUT
GROUP BY STOCKOUT.TRANSACTIONDATE,STOCKOUT.PROJECTCODE,STOCKOUT.ORDERCODE,STOCKOUT.CREATIONUSER,
STOCKOUT.PROVISIONALCODE,STOCKOUT.ORDERLINE ";
	$stmt1RP   = db2_exec($conn1,$sqlDB21RP, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21RP = db2_fetch_assoc($stmt1RP)){ 
$itemcRP=trim($rowdb21RP['DECOSUBCODE02'])."".trim($rowdb21RP['DECOSUBCODE03'])." ".trim($rowdb21RP['DECOSUBCODE04']);	
		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $rowdb21RP['CREATIONUSER']; ?></td>
	  <td style="text-align: center"><?php echo $rowdb21RP['PROJECTCODE']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21RP['QTY_ROL']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21RP['QTY_KG']; ?></td>
      </tr>
	  				  
	<?php 
	 $no++; 
	$tMRolRP+=$rowdb21RP['QTY_ROL'];
	$tMKGRP +=$rowdb21RP['QTY_KG'];	
	} ?>
				  </tbody>
                  <tfoot>
		<tr>
	    <td>&nbsp;</td>
	    <td style="text-align: center"><span style="text-align: right"><strong>Total</strong></span></td>
	    <td style="text-align: right"><strong><?php echo $tMRolRP;?></strong></td>
	    <td style="text-align: right"><strong><?php echo $tMKGRP;?></strong></td>
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
		<div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Detail Data Retur Kain Greige</h3>
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
                <table id="example8" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">Tanggal</th>
                    <th valign="middle" style="text-align: center">Code</th>
                    <th valign="middle" style="text-align: center">Lot</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
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
		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb21R['TRANSACTIONDATE']; ?></td>
      <td style="text-align: center"><?php echo $itemcR; ?></td> 
      <td style="text-align: center"><?php echo $rowdb21R['LOTCODE']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21R['JML']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21R['KG']; ?></td>
      </tr>
	  				  
	<?php 
	 $no++; 
	$tMRolR+=$rowdb21R['JML'];
	$tMKGR +=$rowdb21R['KG'];	
	} ?>
				  </tbody>
                  <tfoot>
		<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td style="text-align: center"><span style="text-align: right"><strong>Total</strong></span></td>
	    <td style="text-align: right"><strong><?php echo $tMRolR;?></strong></td>
	    <td style="text-align: right"><strong><?php echo $tMKGR;?></strong></td>
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
s.CREATIONUSER, s.TRANSACTIONDATE, s.DECOSUBCODE02, s.DECOSUBCODE03, s.DECOSUBCODE04, 
s.LOTCODE, sum(s.BASEPRIMARYQUANTITY) AS KG, count(s.ITEMELEMENTCODE) AS JML 
FROM STOCKTRANSACTION s
WHERE s.PROJECTCODE='$Project' AND s.ITEMTYPECODE='KGF' AND 
s.DECOSUBCODE02='$subC1' AND s.DECOSUBCODE03='$subC2' AND 
s.DECOSUBCODE04='$subC3' AND s.LOGICALWAREHOUSECODE ='M021' AND
s.TEMPLATECODE = '311' 
GROUP BY s.TRANSACTIONDATE, s.DECOSUBCODE02, s.DECOSUBCODE03, s.DECOSUBCODE04, 
s.LOTCODE,s.CREATIONUSER	
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
                    <th colspan="2" valign="middle" style="text-align: center">Permintaan Potong</th>
                    </tr>
                  <tr>
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
      <td style="text-align: right"><?php if($rowdb21TK['PTG']=="1") {echo $rptg=$rowdb21TK['JML'];}else{echo $rptg="0"; } ?></td>
      <td style="text-align: right"><?php if($rowdb21TK['PTG']=="1") {echo $ptg=$rowdb21TK['KG'];}else{echo $ptg="0"; } ?></td>
      </tr>
	  				  
	<?php 
	 $no++; 
	$tMRolTK+=$rowdb21TK['JML'];
	$tMKGTK +=$rowdb21TK['KG'];	
	$tPRol+=$rptg;
	$tPKG+=$ptg;	
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
                    <th colspan="8" valign="middle" style="text-align: center">Keluar</th>
                    </tr>
                  <tr>
                    <th valign="middle" style="text-align: center">Tanggal</th>
                    <th valign="middle" style="text-align: center">Prod. Order</th>
                    <th valign="middle" style="text-align: center">Demand</th>
                    <th valign="middle" style="text-align: center">Bruto</th>
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
	 SELECT STOCKOUT.TRANSACTIONDATE,
	STOCKOUT.PROJECTCODE,STOCKOUT.CREATIONUSER,
	STOCKOUT.ORDERCODE,STOCKOUT.PROVISIONALCODE,STOCKOUT.ORDERLINE,
	COUNT(STOCKOUT.ITEMELEMENTCODE) AS QTY_ROL,
	SUM(STOCKOUT.BASEPRIMARYQUANTITY) AS QTY_KG,
	STOCKOUT.ORIGDLVSALORDLINESALORDERCODE
	FROM
(
SELECT 
STKKELUAR.ITEMELEMENTCODE,STKKELUAR.BASEPRIMARYQUANTITY,STKKELUAR.CREATIONUSER,  
       STKKELUAR.TRANSACTIONDATE,STKKELUAR.ORDERCODE, STKKELUAR.PROJECTCODE,
	   ITXVIEWLAPMASUKGREIGE.PROVISIONALCODE,ITXVIEWLAPMASUKGREIGE.ORDERLINE,pd.ORIGDLVSALORDLINESALORDERCODE
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
AND TEMPLATECODE ='120') AS STKKELUAR ON STKKELUAR.ITEMELEMENTCODE=STOCKTRANSACTION.ITEMELEMENTCODE
LEFT JOIN (
SELECT PROJECTCODE,ORIGDLVSALORDLINESALORDERCODE,PRODUCTIONDEMANDCODE,PRODUCTIONORDERCODE,
ITEMTYPEAFICODE,SUBCODE01,SUBCODE02,SUBCODE03,SUBCODE04 FROM ITXVIEWKNTORDER i
GROUP BY PROJECTCODE,ORIGDLVSALORDLINESALORDERCODE,PRODUCTIONDEMANDCODE,PRODUCTIONORDERCODE,
ITEMTYPEAFICODE,SUBCODE01,SUBCODE02,SUBCODE03,SUBCODE04
) pd ON pd.PRODUCTIONORDERCODE=STKKELUAR.ORDERCODE
WHERE STOCKTRANSACTION.PROJECTCODE='$Project' AND DECOSUBCODE02='$subC1' AND DECOSUBCODE03='$subC2' AND DECOSUBCODE04='$subC3' and 
STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M021' 
AND NOT ITXVIEWLAPMASUKGREIGE.ORDERLINE IS NULL
GROUP BY STKKELUAR.ITEMELEMENTCODE,STKKELUAR.BASEPRIMARYQUANTITY,STKKELUAR.CREATIONUSER,  
       STKKELUAR.TRANSACTIONDATE,STKKELUAR.ORDERCODE, STKKELUAR.PROJECTCODE,
	   ITXVIEWLAPMASUKGREIGE.PROVISIONALCODE,ITXVIEWLAPMASUKGREIGE.ORDERLINE,pd.ORIGDLVSALORDLINESALORDERCODE) AS STOCKOUT
GROUP BY STOCKOUT.TRANSACTIONDATE,STOCKOUT.PROJECTCODE,STOCKOUT.ORDERCODE,STOCKOUT.CREATIONUSER,
STOCKOUT.PROVISIONALCODE,STOCKOUT.ORDERLINE,STOCKOUT.ORIGDLVSALORDLINESALORDERCODE
	";
	$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));			  
    while($rowdb23 = db2_fetch_assoc($stmt3)){ 
	$sqlDB2WRN = " 
SELECT ugp.LONGDESCRIPTION AS WARNA, p.PRODUCTIONORDERCODE ,LISTAGG(DISTINCT  TRIM(pd.CODE),', ') AS PRODUCTIONDEMANDCODE,pd.SUBCODE01,pd.SUBCODE02,
pd.SUBCODE03,pd.SUBCODE04,pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,pd.SUBCODE08,pd.ITEMTYPEAFICODE  
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
    WHERE (pd.PROJECTCODE='$rowdb23[PROJECTCODE]' OR pd.ORIGDLVSALORDLINESALORDERCODE='$rowdb23[ORIGDLVSALORDLINESALORDERCODE]')  AND p.PRODUCTIONORDERCODE='$rowdb23[ORDERCODE]'
	GROUP BY ugp.LONGDESCRIPTION,p.PRODUCTIONORDERCODE,
	pd.SUBCODE01,pd.SUBCODE02,pd.SUBCODE03,pd.SUBCODE04,
	pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,
	pd.SUBCODE08,pd.ITEMTYPEAFICODE
";
$stmtWRN   = db2_exec($conn1,$sqlDB2WRN, array('cursor'=>DB2_SCROLLABLE));
$rowdbWRN = db2_fetch_assoc($stmtWRN);	
$sqlDB2BRTO = " SELECT SUM(x.USERPRIMARYQUANTITY) AS QTYBRUTO  FROM DB2ADMIN.ITXVIEW_KGBRUTO x
WHERE (PROJECTCODE ='$rowdb23[PROJECTCODE]' OR PROJECTCODE ='$rowdb23[ORIGDLVSALORDLINESALORDERCODE]') AND ITEMTYPE_DEMAND ='$rowdbWRN[ITEMTYPEAFICODE]' AND 
SUBCODE01 ='$rowdbWRN[SUBCODE01]' AND SUBCODE02 ='$rowdbWRN[SUBCODE02]' AND 
SUBCODE03 ='$rowdbWRN[SUBCODE03]' AND SUBCODE04 ='$rowdbWRN[SUBCODE04]' AND 
SUBCODE05 ='$rowdbWRN[SUBCODE05]' AND SUBCODE06 ='$rowdbWRN[SUBCODE06]' AND 
SUBCODE07 ='$rowdbWRN[SUBCODE07]' AND SUBCODE08 ='$rowdbWRN[SUBCODE08]'";		
$stmtBRTO   = db2_exec($conn1,$sqlDB2BRTO, array('cursor'=>DB2_SCROLLABLE));
$rowdbBRTO = db2_fetch_assoc($stmtBRTO);	
if($rowdb23['PROJECTCODE']!=""){$prj=$rowdb23['PROJECTCODE'];}else{$prj=$rowdb23['ORIGDLVSALORDLINESALORDERCODE'];}		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $rowdb23['TRANSACTIONDATE']; ?></td> 
      <td style="text-align: center"><?php echo $rowdb23['ORDERCODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdbWRN['PRODUCTIONDEMANDCODE']; ?></td>
      <td align="right"><a href="#" id="<?php echo trim($prj)."-".trim($rowdbWRN['ITEMTYPEAFICODE'])."".trim($rowdbWRN['SUBCODE01'])."".trim($rowdbWRN['SUBCODE02'])."".trim($rowdbWRN['SUBCODE03'])."".trim($rowdbWRN['SUBCODE04'])."".trim($rowdbWRN['SUBCODE05'])."".trim($rowdbWRN['SUBCODE06'])."".trim($rowdbWRN['SUBCODE07'])."".trim($rowdbWRN['SUBCODE08']);?>" class="show_detail_bruto"><?php echo number_format(round($rowdbBRTO['QTYBRUTO'],2),2); ?></a></td>
      <td style="text-align: center"><?php echo $rowdbWRN['WARNA']; ?></td>
      <td style="text-align: center"><?php if($rowdb23['PROJECTCODE']!=""){echo $rowdb23['PROJECTCODE'];}else{ echo $rowdb23['ORIGDLVSALORDLINESALORDERCODE'];} ?></td>
      <td style="text-align: right"><a href="#" id="<?php echo $rowdb23['ORDERCODE']; ?>" class="show_detail_out"><?php echo $rowdb23['QTY_ROL']; ?></a></td>
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
			$sisaGreigeRol=($tMRol+$tMRolR+$tMRolRP+$tCSRol1)-($tKRol31 + $tPRol);
			$sisaGreige=($tMKG + $tMKGR + $tMKGRP) - ($tKKG31 + $tKCutS + $tKCutH + $tPKG); 
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