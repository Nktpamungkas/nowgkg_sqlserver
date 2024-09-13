<?php
$ProdOrder	= isset($_POST['prdorder']) ? $_POST['prdorder'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Data Stock Transaction</h3>

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
                <label for="prdorder" class="col-md-1">Prod. Order</label>
                <div class="col-md-2">  
                  <input type="text" class="form-control form-control-sm" value="<?php echo $ProdOrder; ?>" name="prdorder" required>
                </div>	
              </div> 		 
			  <button class="btn btn-info" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  
		<?php if($ProdOrder!=""){ ?>	
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Stock Flat Knitt Maklon</h3>
				<!-- <a href="pages/cetak/lapgmasuk_excel.php?awal=<?php echo $Awal;?>&akhir=<?php echo $Akhir;?>" class="btn bg-blue float-right" target="_blank">Cetak Excel</a>   -->
          </div>
              <!-- /.card-header -->
              <div class="card-body">				  
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">Tgl Transaksi</th>
                    <th valign="middle" style="text-align: center">No Transaksi</th>
                    <th valign="middle" style="text-align: center">Project Code</th>
                    <th valign="middle" style="text-align: center">Template Code</th>
                    <th valign="middle" style="text-align: center">Item Desc.</th>
                    <th valign="middle" style="text-align: center">Full Item</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">KG</th>
                    <th valign="middle" style="text-align: center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB2 = "SELECT 
  STOCKTRANSACTION.TRANSACTIONNUMBER,
  STOCKTRANSACTION.TRANSACTIONDATE,
  STOCKTRANSACTION.TEMPLATECODE,
  PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE,
  STOCKTRANSACTION.ITEMTYPECODE,
  STOCKTRANSACTION.DECOSUBCODE01,
  STOCKTRANSACTION.DECOSUBCODE02,
  STOCKTRANSACTION.DECOSUBCODE03,
  STOCKTRANSACTION.DECOSUBCODE04,
  COUNT(STOCKTRANSACTION.ITEMELEMENTCODE) AS JML_ROLL,
  SUM(STOCKTRANSACTION.USERPRIMARYQUANTITY) AS JML_QTY,
  STOCKTRANSACTION.USERPRIMARYUOMCODE,
  SUM(STOCKTRANSACTION.USERSECONDARYQUANTITY) AS JML_QTY_SCND,
  STOCKTRANSACTION.USERSECONDARYUOMCODE, 
  PRODUCT.LONGDESCRIPTION AS JENIS_KAIN,
  ORDERPARTNERBRAND.LONGDESCRIPTION AS BUYER
  FROM STOCKTRANSACTION STOCKTRANSACTION
  LEFT JOIN PRODUCT PRODUCT ON 
  STOCKTRANSACTION.ITEMTYPECODE = PRODUCT.ITEMTYPECODE AND 
  STOCKTRANSACTION.DECOSUBCODE01 = PRODUCT.SUBCODE01 AND 
  STOCKTRANSACTION.DECOSUBCODE02 = PRODUCT.SUBCODE02 AND 
  STOCKTRANSACTION.DECOSUBCODE03 = PRODUCT.SUBCODE03 AND 
  STOCKTRANSACTION.DECOSUBCODE04 = PRODUCT.SUBCODE04 
  LEFT JOIN PRODUCTIONDEMAND PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = STOCKTRANSACTION.ORDERCODE
  LEFT JOIN SALESORDER SALESORDER ON PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE = SALESORDER.CODE 
  LEFT JOIN ORDERPARTNERBRAND ORDERPARTNERBRAND ON SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE = ORDERPARTNERBRAND.ORDPRNCUSTOMERSUPPLIERCODE AND SALESORDER.ORDERPARTNERBRANDCODE = ORDERPARTNERBRAND.CODE
  WHERE STOCKTRANSACTION.TEMPLATECODE ='110' AND STOCKTRANSACTION.ITEMTYPECODE ='FKG' AND STOCKTRANSACTION.PRODUCTIONORDERCODE='$ProdOrder'
  GROUP BY 
  STOCKTRANSACTION.TRANSACTIONNUMBER,
  STOCKTRANSACTION.TRANSACTIONDATE,
  STOCKTRANSACTION.TEMPLATECODE,
  PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE,
  STOCKTRANSACTION.ITEMTYPECODE,
  STOCKTRANSACTION.DECOSUBCODE01,
  STOCKTRANSACTION.DECOSUBCODE02,
  STOCKTRANSACTION.DECOSUBCODE03,
  STOCKTRANSACTION.DECOSUBCODE04,
  STOCKTRANSACTION.USERPRIMARYUOMCODE,
  STOCKTRANSACTION.USERSECONDARYUOMCODE, 
  PRODUCT.LONGDESCRIPTION,
  ORDERPARTNERBRAND.LONGDESCRIPTION";
	$stmt1   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb2 = db2_fetch_assoc($stmt1)){
?>
	  <tr>
	    <td style="text-align: center"><?php echo $no;?></td>
	    <td style="text-align: center"><?php echo $rowdb2['TRANSACTIONDATE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb2['TRANSACTIONNUMBER']; ?></td>
      <td style="text-align: center"><?php echo $rowdb2['ORIGDLVSALORDLINESALORDERCODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb2['TEMPLATECODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb2['JENIS_KAIN']; ?></td>
      <td style="text-align: center"><?php echo trim($rowdb2['ITEMTYPECODE'])."-".trim($rowdb2['DECOSUBCODE01'])."-".trim($rowdb2['DECOSUBCODE02'])."-".trim($rowdb2['DECOSUBCODE03'])."-".trim($rowdb2['DECOSUBCODE04']); ?></td>
      <td style="text-align: center"><?php echo $rowdb2['JML_ROLL']." Roll"; ?></td>
      <td style="text-align: center"><?php echo $rowdb2['JML_QTY']." ".$rowdb2['USERPRIMARYUOMCODE']; ?></td>
      <td style="text-align: center"><a href="pages/cetak/iden-greige-now-flatknitt.php?prodorder=<?php echo trim($ProdOrder); ?>&transaction=<?php echo $rowdb2['TRANSACTIONNUMBER']; ?>" class="btn btn-primary btn-xs" target="_blank"><i class="fa fa-print" data-toggle="tooltip" data-placement="top" title="Cetak"></i> </a></td>
    </tr>
	  				  
	<?php 
	 $no++; 
	} ?>
				  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div> 
		<?php } ?>
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