<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Filter Data Kain Greige Cut Elements</h3>

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
               <label for="tgl_awal" class="col-md-1">Tgl Awal</label>
               <div class="col-md-2">  
                 <div class="input-group date" id="datepicker1" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker1" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_awal" value="<?php echo $Awal;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" required>
                 </div>
			   </div>	
            </div>
			 <div class="form-group row">
               <label for="tgl_akhir" class="col-md-1">Tgl Akhir</label>
               <div class="col-md-2">  
                 <div class="input-group date" id="datepicker2" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker2" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_akhir" value="<?php echo $Akhir;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" required>
                 </div>
			   </div>	
            </div> 
				 
			  <button class="btn btn-info" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  
			
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Data Kain Greige Cut Elements</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th colspan="15" valign="middle" style="text-align: center">Masuk</th>
                    <th colspan="4" valign="middle" style="text-align: center">Sisa</th>
                    <th colspan="4" valign="middle" style="text-align: center">Hasil Potong</th>
                    </tr>
                  <tr>
                    <th rowspan="2" valign="middle" style="text-align: center">No</th>
                    <th rowspan="2" valign="middle" style="text-align: center">TglCut</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Buyer</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Customer</th>
                    <th rowspan="2" valign="middle" style="text-align: center">ProjectCode</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Code</th>
                    <th rowspan="2" valign="middle" style="text-align: center">LOT</th>
                    <th colspan="4" valign="middle" style="text-align: center">Jenis Benang</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Jenis Kain</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Qty</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Berat/Kg</th>
                    <th rowspan="2" valign="middle" style="text-align: center">User</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Qty</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Berat/Kg</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Elements</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Status</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Qty</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Berat/Kg</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Elements</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Status</th>
                    </tr>
                  <tr>
                    <th valign="middle" style="text-align: center">1</th>
                    <th valign="middle" style="text-align: center">2</th>
                    <th valign="middle" style="text-align: center">3</th>
                    <th valign="middle" style="text-align: center">4</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB21 = " SELECT 
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
STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M021' AND TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir' AND 
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
	FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
if ($rowdb21['LOGICALWAREHOUSECODE'] =='M501') { $knitt = 'LT2'; }
else if($rowdb21['LOGICALWAREHOUSECODE'] ='P501'){ $knitt = 'LT1'; }
$kdbenang=trim($rowdb21['DECOSUBCODE01'])." ".trim($rowdb21['DECOSUBCODE02'])." ".trim($rowdb21['DECOSUBCODE03'])." ".trim($rowdb21['DECOSUBCODE04'])." ".trim($rowdb21['DECOSUBCODE05'])." ".trim($rowdb21['DECOSUBCODE06'])." ".trim($rowdb21['DECOSUBCODE07'])." ".trim($rowdb21['DECOSUBCODE08']);
$sqlDB22 = " SELECT SALESORDER.CODE, SALESORDER.EXTERNALREFERENCE, SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE,
		ITXVIEWAKJ.LEGALNAME1, ITXVIEWAKJ.ORDERPARTNERBRANDCODE, ITXVIEWAKJ.LONGDESCRIPTION
		FROM DB2ADMIN.SALESORDER SALESORDER LEFT OUTER JOIN DB2ADMIN.ITXVIEWAKJ 
       	ITXVIEWAKJ ON SALESORDER.CODE=ITXVIEWAKJ.CODE
		WHERE SALESORDER.CODE='$rowdb21[PROJECTCODE]' ";
$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
$rowdb22 = db2_fetch_assoc($stmt2);
		
$sqlDB23 = " SELECT p.SUBCODE01,p.SUBCODE02,p.SUBCODE03,p.SUBCODE04,p.SUBCODE05,p.SUBCODE06,p.SUBCODE07, p.LONGDESCRIPTION FROM (
SELECT p2.ITEMTYPEAFICODE,p2.SUBCODE01,p2.SUBCODE02,p2.SUBCODE03,p2.SUBCODE04,
p2.SUBCODE05,p2.SUBCODE06,p2.SUBCODE07  FROM PRODUCTIONDEMAND p 
LEFT OUTER JOIN PRODUCTIONRESERVATION p2 ON p.CODE =p2.ORDERCODE 
WHERE p.ITEMTYPEAFICODE ='KGF' AND p.SUBCODE01='".trim($rowdb21['DECOSUBCODE01'])."' 
AND p.SUBCODE02 ='".trim($rowdb21['DECOSUBCODE02'])."' AND p.SUBCODE03 ='".trim($rowdb21['DECOSUBCODE03'])."' AND
p.SUBCODE04='".trim($rowdb21['DECOSUBCODE04'])."' AND p.PROJECTCODE ='".trim($rowdb21['PROJECTCODE'])."'
) a LEFT OUTER JOIN PRODUCT p ON
p.ITEMTYPECODE ='GYR' AND
p.SUBCODE01= a.SUBCODE01 AND p.SUBCODE02= a.SUBCODE02 AND 
p.SUBCODE03= a.SUBCODE03 AND p.SUBCODE04= a.SUBCODE04 AND 
p.SUBCODE05= a.SUBCODE05 AND p.SUBCODE06= a.SUBCODE06 AND
p.SUBCODE07= a.SUBCODE07 
GROUP BY 
p.SUBCODE01,p.SUBCODE02, 
p.SUBCODE03,p.SUBCODE04,
p.SUBCODE05,p.SUBCODE06,
p.SUBCODE07,p.LONGDESCRIPTION ";
$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
$ai=0;
while($rowdb23 = db2_fetch_assoc($stmt3)){
	$a[$ai]=$rowdb23['LONGDESCRIPTION'];
	$ai++;
}
$sqlDB24 = " SELECT 
  -- ITXVIEWKK.LONGDESCRIPTION,
	ITXVIEWKK.DSUBCODE01, 
	ITXVIEWKK.DSUBCODE02,
	ITXVIEWKK.DSUBCODE03,
	ITXVIEWKK.DSUBCODE04,
	ITXVIEWKK.DSUBCODE05,
	ITXVIEWKK.DSUBCODE06,
	ITXVIEWKK.DSUBCODE07,
	ITXVIEWKK.DSUBCODE08,
	ITXVIEWKK.PRODUCTIONORDERCODE,
	ITXVIEWKK.PROJECTCODE,
	LISTAGG(DISTINCT  TRIM(ITXVIEWKK.DEAMAND),', ') AS PRODUCTIONDEMANDCODE
FROM ITXVIEWKK 
WHERE ITXVIEWKK.PRODUCTIONORDERCODE='$rowdb21[ORDERCODE]' AND ITXVIEWKK.PROJECTCODE='$rowdb21[PROJECTCODE]'
GROUP BY 
-- ITXVIEWKK.LONGDESCRIPTION,
	ITXVIEWKK.DSUBCODE01, 
	ITXVIEWKK.DSUBCODE02,
	ITXVIEWKK.DSUBCODE03,
	ITXVIEWKK.DSUBCODE04,
	ITXVIEWKK.DSUBCODE05,
	ITXVIEWKK.DSUBCODE06,
	ITXVIEWKK.DSUBCODE07,
	ITXVIEWKK.DSUBCODE08,
	ITXVIEWKK.PRODUCTIONORDERCODE,
	ITXVIEWKK.PROJECTCODE ";
$stmt4   = db2_exec($conn1,$sqlDB24, array('cursor'=>DB2_SCROLLABLE));
$rowdb24 = db2_fetch_assoc($stmt4);

$sqlDB25 = " 
SELECT x.ITEMELEMENTCODE, COUNT(x.BASEPRIMARYQUANTITY) AS QTY_DUS,
	SUM(x.BASEPRIMARYQUANTITY) AS QTY_KG FROM DB2ADMIN.STOCKTRANSACTION x
WHERE x.CUTORGTRTRANSACTIONNUMBER='".$rowdb21['TRANSACTIONNUMBER']."'
GROUP BY x.ITEMELEMENTCODE
";
$stmt5   = db2_exec($conn1,$sqlDB25, array('cursor'=>DB2_SCROLLABLE));
$rowdb25 = db2_fetch_assoc($stmt5);
$sqlDB26 = " 
SELECT x.ITEMELEMENTCODE, COUNT(x.BASEPRIMARYQUANTITY) AS QTY_DUS,
	SUM(x.BASEPRIMARYQUANTITY) AS QTY_KG FROM DB2ADMIN.STOCKTRANSACTION x
WHERE x.ITEMELEMENTCODE='".$rowdb21['ITEMELEMENTCODE']."' AND X.TEMPLATECODE ='342'
GROUP BY x.ITEMELEMENTCODE
";
$stmt6   = db2_exec($conn1,$sqlDB26, array('cursor'=>DB2_SCROLLABLE));
$rowdb26 = db2_fetch_assoc($stmt6);		
$sqlDB27 = " 
SELECT x.ITEMELEMENTCODE, y.ELEMENTSCODE,
	x.BASEPRIMARYQUANTITY AS QTY_KG FROM DB2ADMIN.STOCKTRANSACTION x
LEFT OUTER JOIN (
SELECT ELEMENTSCODE FROM BALANCE
) y ON x.ITEMELEMENTCODE = y.ELEMENTSCODE	
WHERE x.CUTORGTRTRANSACTIONNUMBER='".$rowdb23['TRANSACTIONNUMBER']."'
";
$stmt7   = db2_exec($conn1,$sqlDB27, array('cursor'=>DB2_SCROLLABLE));
$rowdb27 = db2_fetch_assoc($stmt7);
if ($rowdb27['ELEMENTSCODE']!=""){
	$sts27="<small class='badge badge-success'>Ada</small>";
}	else{
	$sts27="<small class='badge badge-danger'>Keluar</small>";
}		
		
	if($rowdb22['LEGALNAME1']==""){$langganan="";}else{$langganan=$rowdb22['LEGALNAME1'];}
	if($rowdb22['ORDERPARTNERBRANDCODE']==""){$buyer="";}else{$buyer=$rowdb22['ORDERPARTNERBRANDCODE'];}
		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
	  <td style="text-align: left"><?php echo $buyer; ?></td>
	  <td style="text-align: left"><?php echo $langganan; ?></td>
	  <td style="text-align: center"><?php echo $rowdb21['PROJECTCODE']; ?></td>
	  <td><?php echo $kdbenang; ?></td> 
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
      <td style="text-align: left"><?php echo $a[0]; ?></td>
      <td style="text-align: left"><?php echo $a[1]; ?></td>
      <td style="text-align: left"><?php echo $a[2]; ?></td>
      <td style="text-align: left"><?php echo $a[3]; ?></td>
      <td style="text-align: left"><?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['QTY_DUS']; ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['QTY_KG'],2),2); ?></td>
      <td style="text-align: center"><?php  echo $rowdb21['CREATIONUSER']; ?></td>
      <td style="text-align: center"><?php echo $rowdb26['QTY_DUS']; ?></td>
      <td style="text-align: center"><?php echo number_format(round($rowdb26['QTY_KG'],2),2); ?></td>
      <td style="text-align: center"><?php echo $rowdb26['ITEMELEMENTCODE']; ?></td>
      <td style="text-align: center"><?php echo $sts27; ?></td>
      <td style="text-align: center"><?php echo $rowdb25['QTY_DUS']; ?></td>
      <td style="text-align: center"><?php echo number_format(round($rowdb25['QTY_KG'],2),2); ?></td>
      <td style="text-align: center"><?php echo $rowdb25['ITEMELEMENTCODE']; ?></td>
      <td style="text-align: center">&nbsp;</td>
      </tr>
	  				  
	<?php 
	 $no++; 
	$totRol=$totRol+$rowdb21['QTY_DUS'];
	$totKG=$totKG+$rowdb21['QTY_KG'];
	$totRolS+=$rowdb26['QTY_DUS'];
	$totKGS+=$rowdb26['QTY_KG'];
	$totRolP+=$rowdb25['QTY_DUS'];
	$totKGP+=$rowdb25['QTY_KG'];	
	
	} ?>
				  </tbody>
     <tfoot>
	<tr>
	    <th style="text-align: center">&nbsp;</th>
	    <th style="text-align: center">&nbsp;</th>
	    <th style="text-align: left">&nbsp;</th>
	    <th style="text-align: left">&nbsp;</th>
	    <th style="text-align: center">&nbsp;</th>
	    <th>&nbsp;</th>
	    <th style="text-align: center">&nbsp;</th>
	    <th style="text-align: left">&nbsp;</th>
	    <th style="text-align: left">&nbsp;</th>
	    <th style="text-align: left">&nbsp;</th>
	    <th style="text-align: left">&nbsp;</th>
	    <th style="text-align: left">Total</th>
	    <th style="text-align: center"><?php echo $totRol;?></th>
	    <th style="text-align: right"><?php echo number_format(round($totKG,2),2);?></th>
	    <th style="text-align: center">&nbsp;</th>
	    <th style="text-align: center"><?php echo $totRolS;?></th>
	    <th style="text-align: right"><?php echo number_format(round($totKGS,2),2);?></th>
	    <th style="text-align: center">&nbsp;</th>
	    <th style="text-align: center">&nbsp;</th>
	    <th style="text-align: center"><?php echo $totRolP;?></th>
	    <th style="text-align: right"><?php echo number_format(round($totKGP,2),2);?></th>
	    <th style="text-align: center">&nbsp;</th>
	    <th style="text-align: center">&nbsp;</th>
	    </tr>				
	 </tfoot>             
                </table>
              </div>
              <!-- /.card-body -->
            </div> 
	</form>		
      </div><!-- /.container-fluid -->
    <!-- /.content -->
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