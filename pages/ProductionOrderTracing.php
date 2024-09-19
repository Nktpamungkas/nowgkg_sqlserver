<?php
$ProdOrder 	= isset($_POST['prod_order']) ? $_POST['prod_order'] : '';
$ProdDemand = isset($_POST['prod_demand']) ? $_POST['prod_demand'] : '';	    	 
	
?>

<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">
		<div class="row">
          <div class="col-md-3">	
		<div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Filter By Prod. Order or Prod. Demand</h3>

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
               <label for="prod_order" class="col-md-4">Prod. Order</label>               
<div class="col-md-7"> 
                    <input name="prod_order" value="<?php echo $ProdOrder;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" >
			   </div>	
              </div>
			  <div class="form-group row">
               <label for="prod_demand" class="col-md-4">Prod. Demand</label>
               <div class="col-md-7"> 
                    <input name="prod_demand" value="<?php echo $ProdDemand;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" >
			   </div>	
              </div>
          </div>
		  <div class="card-footer">
			  <button class="btn btn-info" type="submit" >Cari Data</button>
		  </div>	  
		  <!-- /.card-body -->          
        </div>  
		</div>
			
		</div>
		<div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Detail</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example3" class="table table-sm table-bordered table-striped" style="font-size:13px;">
                  <thead>
                  <tr>
                    <th>KFF Demand</th>
                    <th>KFF Order</th>
                    <th>Original PD</th>
                    <th>ProjectCode KGF</th>
                    <th>KGF Demand</th>
                    <th>KGF Order</th>
                    <th>No OF KGF</th>
                    <th>GYR Lot</th>
                    <th>Machine</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
if($ProdOrder!="" and $ProdDemand!=""){
	$where=" AND x.PRODUCTIONORDERCODE ='$ProdOrder' AND PRODUCTIONDEMANDCODE='$ProdDemand' ";
}else if($ProdOrder!="" and $ProdDemand=="") {
	$where=" AND x.PRODUCTIONORDERCODE ='$ProdOrder' ";
}elseif($ProdOrder=="" and $ProdDemand!="") {
	$where=" AND PRODUCTIONDEMANDCODE='$ProdDemand' ";
}else{
	$where=" AND x.PRODUCTIONORDERCODE ='$ProdOrder' ";
}	
$sqlDB2 =" SELECT x.PRODUCTIONDEMANDCODE, x.PRODUCTIONORDERCODE, s.LOTCODE, COUNT(s.ITEMELEMENTCODE) AS JML   FROM DB2ADMIN.ITXVIEWKK x
LEFT OUTER JOIN DB2ADMIN.STOCKTRANSACTION s ON x.PRODUCTIONORDERCODE = s.ORDERCODE 
WHERE s.TEMPLATECODE ='120' AND s.ITEMTYPECODE ='KGF' $where
GROUP BY x.PRODUCTIONDEMANDCODE, x.PRODUCTIONORDERCODE, s.LOTCODE ";	
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
$no=1;   
$c=0;
$prsn=0;
$prsn1=0;
$prsn2=0;					  
while ($rowdb2 = db2_fetch_assoc($stmt)) { 	
	
$sqlDB21 =" SELECT x.* FROM DB2ADMIN.ITXVIEWKK x
WHERE PRODUCTIONDEMANDCODE = '".$rowdb2['LOTCODE']."' ";	
$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
$rowdb21 = db2_fetch_assoc($stmt1);	
$sqlDBMC = " 
SELECT ad.VALUESTRING AS MESIN FROM PRODUCTIONDEMAND pd 
LEFT OUTER JOIN ADSTORAGE ad ON ad.UNIQUEID = pd.ABSUNIQUEID AND ad.NAMENAME ='MachineNo'
WHERE CODE ='".$rowdb2['LOTCODE']."'
";					  
$stmt2MC   = db2_exec($conn1,$sqlDBMC, array('cursor'=>DB2_SCROLLABLE));	
$rowdbMC = db2_fetch_assoc($stmt2MC);
$sqlDBLOT = " 
SELECT  LISTAGG(DISTINCT  a.LOT, ', ') AS LOT FROM 
(SELECT 
CASE
        WHEN LOCATE('+', s.LOTCODE) = 0 THEN
    s.LOTCODE
        ELSE
    SUBSTR(s.LOTCODE, 1, LOCATE('+', s.LOTCODE)-1)
    END
    AS LOT  FROM STOCKTRANSACTION s 
WHERE s.ORDERCODE ='".$rowdb21['PRODUCTIONORDERCODE']."') a
";					  
$stmt2LOT   = db2_exec($conn1,$sqlDBLOT, array('cursor'=>DB2_SCROLLABLE));	
$rowdbLOT = db2_fetch_assoc($stmt2LOT);	
$sqlDBPROKGF =" SELECT PROJECTCODE FROM DB2ADMIN.STOCKTRANSACTION 
WHERE LOTCODE ='".$rowdb2['LOTCODE']."' AND TEMPLATECODE ='120' AND 
ITEMTYPECODE ='KGF' AND ORDERCODE ='".$rowdb2['PRODUCTIONORDERCODE']."'
GROUP BY PROJECTCODE ";	
$stmtPKGF   = db2_exec($conn1,$sqlDBPROKGF, array('cursor'=>DB2_SCROLLABLE));
$rPKGF = db2_fetch_assoc($stmtPKGF);	

					  ?> 
	  <tr>
      <td style="text-align: center"><a href="#" id="<?php echo trim($rowdb2['PRODUCTIONDEMANDCODE']); ?>" class="show_detail_dyc"><?php echo $rowdb2['PRODUCTIONDEMANDCODE']; ?></a></td>
      <td style="text-align: center"><?php echo $rowdb2['PRODUCTIONORDERCODE']; ?></td>
      <td>&nbsp;</td>
      <td><span style="text-align: center"><?php echo $rPKGF['PROJECTCODE']; ?></span></td>
      <td style="text-align: center"><?php echo $rowdb2['LOTCODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['PRODUCTIONORDERCODE']; ?></td>
      <td style="text-align: center"><a href="#" id="<?php echo trim($rowdb2['PRODUCTIONORDERCODE']).trim($rowdb2['LOTCODE']); ?>" class="show_detail_out"><?php echo $rowdb2['JML']; ?></a></td>
      <td style="text-align: left"><a href="#" id="<?php echo trim($rowdb21['PRODUCTIONORDERCODE']); ?>" class="show_detail_lot"><?php echo $rowdbLOT['LOT'];?></a></td>
      <td style="text-align: center"><?php echo $rowdbMC['MESIN'];?></td>
      </tr>				  
	<?php 
	 $no++;} ?>
				  </tbody>
                  <!--<tfoot>
                  <tr>
                    <th>No</th>
                    <th>No Mc</th>
                    <th>Sft</th>
                    <th>User</th>
                    <th>Operator</th>
					<th>Leader</th>
                    <th>NoArt</th>
                    <th>TgtCnt (100%)</th>
                    <th>Rpm</th>
                    <th>Cnt/Roll</th>
					<th>Jam Kerja</th>
				    <th>Count</th>
				    <th>Count</th>
				    <th>RL</th>
				    <th>Kgs</th>
				    <th>Grp</th>
      				<th>Tgt Grp (%)</th>
      				<th>Eff (%)</th>
      				<th>Hasil (%)</th>  
				    <th>Kd</th>
				    <th>Min</th>
				    <th>Kd</th>
				    <th>Min</th>
				    <th>Kd</th>
				    <th>Min</th> 
					<th>Tanggal</th>
      				<th>Keterangan</th>
                  </tr>
                  </tfoot>-->
                </table>
              </div>
              <!-- /.card-body -->
            </div>
	</form>		
      </div><!-- /.container-fluid -->
    <!-- /.content -->
<div id="OutDetailShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
</div>
<div id="DYCDetailShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
</div>
<div id="LOTDetailShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
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
