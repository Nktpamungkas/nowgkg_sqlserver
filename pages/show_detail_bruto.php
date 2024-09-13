<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
    $modal_id=$_GET['id'];
	$Proj=substr($modal_id,0,10);
	$Code=substr($modal_id,11,200);
	$Line=substr($modal_id,11,4);
	
	
?>
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">  
            <div class="modal-header">
              <h5 class="modal-title">Detail Data</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body"><i>
			PROJECTCODE : <b><?php echo $Proj;?></b>		
			</i>	
			<table id="lookup1" class="table table-sm table-bordered table-hover table-striped" width="100%" style="font-size: 14px;">
						<thead>
							<tr>
								<th>#</th>
								<th><div align="center">KGs</div></th>
								<th><div align="center">KG Bagi Kain</div></th>
								<th><div align="center">Demand</div></th>
								<th><div align="center">Prod. Order</div></th>															
							</tr>
						</thead>
						<tbody>
							<?php
							$no=1;
							$sqlDB22 = "SELECT x.USERPRIMARYQUANTITY,x.CODE,x.PRODUCTIONORDERCODE,x.PROJECTCODE  
FROM DB2ADMIN.ITXVIEW_KGBRUTO x
WHERE (PROJECTCODE ='$Proj' OR PROJECTCODE ='$Proj') AND x.ORIGDLVSALORDERLINEORDERLINE='$Line' 
		";					  
		$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
while($rD=db2_fetch_assoc($stmt2)){
$sqlUsed = "SELECT x.USEDUSERPRIMARYQUANTITY FROM DB2ADMIN.PRODUCTIONRESERVATION x
WHERE x.ORDERCODE ='".trim($rD['CODE'])."' and x.ITEMTYPEAFICODE ='KGF'";
$stUsed  = db2_exec($conn1,$sqlUsed, array('cursor'=>DB2_SCROLLABLE));
						$rU=db2_fetch_assoc($stUsed);								
								
	echo"<tr'>
  	<td align=center>$no</td>
	<td align=right>".round($rD['USERPRIMARYQUANTITY'],2)."</td>
	<td align=right>".round($rU['USEDUSERPRIMARYQUANTITY'],2)."</td>
	<td align=center>$rD[CODE]</td>
	<td align=center>$rD[PRODUCTIONORDERCODE]</td>
	</tr>";
				$no++;	
								$tKGs+=round($rD['USERPRIMARYQUANTITY'],2);
								$tUKGs+=round($rU['USEDUSERPRIMARYQUANTITY'],2);
							}
  ?>
						</tbody>
				<tfoot>
				<tr>
								<th><div align="right">Total</div></th>
								<th><div align="right"><?php echo $tKGs; ?></div></th>
								<th><div align="right"><?php echo $tUKGs; ?></div></th>
								<th><div align="center">&nbsp;</div></th>
								<th><div align="center">&nbsp;</div></th>															
							</tr>
				</tfoot>
					</table>   	
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
              			  	
            </div>
			</form>	
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
               
<script>
  $(function () {	 
	$('.select2sts').select2({
    placeholder: "Select a status",
    allowClear: true
});   
  });
</script>
