<?php
$InternalDoc	= isset($_POST['internaldoc']) ? $_POST['internaldoc'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Data Internal Document</h3>

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
                <label for="internaldoc" class="col-md-1">Internal Document</label>
                <div class="col-md-2">  
                  <input type="text" class="form-control form-control-sm" value="<?php echo $InternalDoc; ?>" name="internaldoc" required>
                </div>	
              </div> 		 
			  <button class="btn btn-info" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  
		<?php if($InternalDoc!=""){ ?>	
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Internal Document</h3>
				<!-- <a href="pages/cetak/lapgmasuk_excel.php?awal=<?php echo $Awal;?>&akhir=<?php echo $Akhir;?>" class="btn bg-blue float-right" target="_blank">Cetak Excel</a>   -->
          </div>
              <!-- /.card-header -->
              <div class="card-body">				  
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th style="text-align: center">No</th>
                    <th style="text-align: center">Tgl Internal Doc.</th>
                    <th style="text-align: center">No Internal Doc.</th>
                    <th style="text-align: center">Orderline</th>
                    <th style="text-align: center">Item Desc.</th>
                    <th style="text-align: center">Full Item</th>
                    <th style="text-align: center">Primary Qty</th>
                    <th style="text-align: center">Secondary Qty</th>
                    <th style="text-align: center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB2 = "SELECT 
  INTERNALDOCUMENT.PROVISIONALDOCUMENTDATE,
  INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE,
  INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
  INTERNALDOCUMENTLINE.ORDERLINE,
  INTERNALDOCUMENTLINE.ITEMTYPEAFICODE,
  INTERNALDOCUMENTLINE.SUBCODE01,
  INTERNALDOCUMENTLINE.SUBCODE02,
  INTERNALDOCUMENTLINE.SUBCODE03,
  INTERNALDOCUMENTLINE.SUBCODE04,
  INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
  INTERNALDOCUMENTLINE.USERPRIMARYQUANTITY,
  INTERNALDOCUMENTLINE.USERSECONDARYQUANTITY,
  INTERNALDOCUMENTLINE.INTERNALREFERENCEDATE
  FROM INTERNALDOCUMENT INTERNALDOCUMENT
  LEFT JOIN INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON INTERNALDOCUMENT.PROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE AND 
  INTERNALDOCUMENT.PROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE 
  WHERE INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE='$InternalDoc'
  GROUP BY 
  INTERNALDOCUMENT.PROVISIONALDOCUMENTDATE,
  INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE,
  INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
  INTERNALDOCUMENTLINE.ORDERLINE,
  INTERNALDOCUMENTLINE.ITEMTYPEAFICODE,
  INTERNALDOCUMENTLINE.SUBCODE01,
  INTERNALDOCUMENTLINE.SUBCODE02,
  INTERNALDOCUMENTLINE.SUBCODE03,
  INTERNALDOCUMENTLINE.SUBCODE04,
  INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
  INTERNALDOCUMENTLINE.USERPRIMARYQUANTITY,
  INTERNALDOCUMENTLINE.USERSECONDARYQUANTITY,
  INTERNALDOCUMENTLINE.INTERNALREFERENCEDATE ";
	$stmt1   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb2 = db2_fetch_assoc($stmt1)){
?>
	  <tr>
	    <td style="text-align: center"><?php echo $no;?></td>
	    <td style="text-align: center"><?php echo $rowdb2['INTERNALREFERENCEDATE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb2['INTDOCUMENTPROVISIONALCODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb2['ORDERLINE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb2['ITEMDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php echo trim($rowdb2['ITEMTYPEAFICODE'])."-".trim($rowdb2['SUBCODE01'])."-".trim($rowdb2['SUBCODE02'])."-".trim($rowdb2['SUBCODE03'])."-".trim($rowdb2['SUBCODE04']); ?></td>
      <td style="text-align: center"><?php echo $rowdb2['USERPRIMARYQUANTITY']; ?></td>
      <td style="text-align: center"><?php echo $rowdb2['USERSECONDARYQUANTITY']; ?></td>
      <td style="text-align: center"><a href="pages/cetak/iden-flatknitt-now.php?intdoc=<?php echo trim($rowdb2['INTDOCUMENTPROVISIONALCODE']); ?>&orderline=<?php echo trim($rowdb2['ORDERLINE']); ?>" class="btn btn-primary btn-xs" target="_blank"><i class="fa fa-print" data-toggle="tooltip" data-placement="top" title="Cetak"></i> </a></td>
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