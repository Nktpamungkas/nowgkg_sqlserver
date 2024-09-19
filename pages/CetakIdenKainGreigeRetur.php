<?php
$Project	= isset($_POST['project']) ? $_POST['project'] : '';
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
                <label for="project" class="col-md-1">Project Code</label>
                <div class="col-md-2">  
                  <input type="text" class="form-control form-control-sm" value="<?php echo $Project; ?>" name="project" required>
                </div>	
              </div> 		 
			  <button class="btn btn-info" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  
		<?php if($Project!=""){ ?>	
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Stock Retur Produksi</h3>
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
  STOCKTRANSACTION.PROJECTCODE,
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
  LEFT JOIN ADSTORAGE ADSTORAGE ON STOCKTRANSACTION.ABSUNIQUEID = ADSTORAGE.UNIQUEID 
  LEFT JOIN PRODUCT PRODUCT ON 
  STOCKTRANSACTION.ITEMTYPECODE = PRODUCT.ITEMTYPECODE AND 
  STOCKTRANSACTION.DECOSUBCODE01 = PRODUCT.SUBCODE01 AND 
  STOCKTRANSACTION.DECOSUBCODE02 = PRODUCT.SUBCODE02 AND 
  STOCKTRANSACTION.DECOSUBCODE03 = PRODUCT.SUBCODE03 AND 
  STOCKTRANSACTION.DECOSUBCODE04 = PRODUCT.SUBCODE04 
  LEFT JOIN SALESORDER SALESORDER ON STOCKTRANSACTION.PROJECTCODE = SALESORDER.CODE 
  LEFT JOIN ORDERPARTNERBRAND ORDERPARTNERBRAND ON SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE = ORDERPARTNERBRAND.ORDPRNCUSTOMERSUPPLIERCODE AND SALESORDER.ORDERPARTNERBRANDCODE = ORDERPARTNERBRAND.CODE
  WHERE STOCKTRANSACTION.TEMPLATECODE ='OPN' AND STOCKTRANSACTION.ITEMTYPECODE ='KGF' AND ADSTORAGE.NAMENAME ='StatusRetur' AND 
  ADSTORAGE.VALUESTRING ='1' AND STOCKTRANSACTION.PROJECTCODE='$Project'
  GROUP BY 
  STOCKTRANSACTION.TRANSACTIONNUMBER,
  STOCKTRANSACTION.TRANSACTIONDATE,
  STOCKTRANSACTION.TEMPLATECODE,
  STOCKTRANSACTION.PROJECTCODE,
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
      <td style="text-align: center"><?php echo $rowdb2['PROJECTCODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb2['TEMPLATECODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb2['JENIS_KAIN']; ?></td>
      <td style="text-align: center"><?php echo trim($rowdb2['ITEMTYPEAFICODE'])."-".trim($rowdb2['DECOSUBCODE01'])."-".trim($rowdb2['DECOSUBCODE02'])."-".trim($rowdb2['DECOSUBCODE03'])."-".trim($rowdb2['DECOSUBCODE04']); ?></td>
      <td style="text-align: center"><?php echo $rowdb2['JML_ROLL']." Roll"; ?></td>
      <td style="text-align: center"><?php echo $rowdb2['JML_QTY']." ".$rowdb2['USERPRIMARYUOMCODE']; ?></td>
      <td style="text-align: center"><a href="pages/cetak/iden-greige-now-retur.php?project=<?php echo trim($rowdb2['PROJECTCODE']); ?>&transaction=<?php echo $rowdb2['TRANSACTIONNUMBER']; ?>" class="btn btn-primary btn-xs" target="_blank"><i class="fa fa-print" data-toggle="tooltip" data-placement="top" title="Cetak"></i> </a></td>
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