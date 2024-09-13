<?php
$DemandNO	= isset($_POST['demandno']) ? $_POST['demandno'] : '';

?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Detail Bagi Kain Greige</h3>

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
               <label for="demandno" class="col-md-1">DemandNo</label>
               <div class="col-md-2">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $DemandNO; ?>" name="demandno" required>
			        </div>	
            </div>
			<button class="btn btn-info" type="submit" >Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  
			
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Bagi  Kain Greige</h3>
				<a href="pages/cetak/cetakbagikain.php?demandno=<?php echo $DemandNO; ?>" class="btn btn-sm btn-info float-right" target="_blank"><i class="fa fa-file"></i> Cetak</a>   
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">Element</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Lokasi</th>
                    <th valign="middle" style="text-align: center">Lot</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB21 = " SELECT
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
    AND PRODUCTIONDEMAND.CODE='$DemandNO'    
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
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){	
?>
	  <tr>
	  <td style="text-align: right"><?php echo $rowdb21['ITEMELEMENTCODE']; ?></td>
	  <td style="text-align: right"><?php echo substr($rowdb21['ITEMELEMENTCODE'],8,3); ?></td>
      <td style="text-align: right"><?php echo $rowdb21['QTY_KG']; ?></td>
      <td><?php echo $rowdb21['WAREHOUSELOCATIONCODE']; ?></td>
      <td><?php echo $rowdb21['LOTCODE']; ?></td>
      </tr>
	  				  
	<?php 
	 $no++; 
	$tKRol+=$rowdb22['ROL'];
	$tKKG +=$rowdb21['QTY_KG'];	
	} ?>
				  </tbody>
                  <tfoot>
		<tr>
		  <td style="text-align: right">Total</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right"><strong><?php echo $tKKG;?></strong></td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>			
		</tfoot>
                </table>
              </div>
              <!-- /.card-body -->
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