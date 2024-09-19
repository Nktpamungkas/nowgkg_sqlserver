<!-- Main content -->
      <div class="container-fluid">
		  <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Standard Loading Mesin DYE</h3>
				<form method="post" enctype="multipart/form-data" name="form2" class="form-horizontal" id="form2" action="AddSTDdye"> 
				 <button type="submit" class="btn btn-sm btn-success float-right" name="add" value="add"><i class="fa fa-plus"></i> Add Standard</button>
			  </form>   
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">Item</th>
                    <th valign="middle" style="text-align: center">Jenis Kain</th>
                    <th valign="middle" style="text-align: center">Lebar</th>
                    <th valign="middle" style="text-align: center">Gramasi</th>
                    <th valign="middle" style="text-align: center">KNIT</th>
                    <th valign="middle" style="text-align: center">Loading</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
					  
$qry= sqlsrv_query($con,"SELECT * FROM dbnow_gkg.tbl_stdmcdye ORDER BY no_item ASC");
while($r=sqlsrv_fetch_array($qry, SQLSRV_FETCH_ASSOC)){					  
	
?>
	  <tr>
	  <td><a data-pk="<?php echo $r['id']; ?>" data-value="<?php echo $r['no_item']; ?>" class="item" href="javascipt:void(0)"><?php echo $r['no_item']; ?></a></td>
	  <td align="left"><a data-pk="<?php echo $r['id']; ?>" data-value="<?php echo $r['jenis_kain']; ?>" class="jenis_kain" href="javascipt:void(0)"><?php echo $r['jenis_kain']; ?></a></td>
      <td><a data-pk="<?php echo $r['id']; ?>" data-value="<?php echo $r['lebar']; ?>" class="lebar" href="javascipt:void(0)"><?php echo $r['lebar']; ?></a></td>
      <td><a data-pk="<?php echo $r['id']; ?>" data-value="<?php echo $r['gramasi']; ?>" class="gramasi" href="javascipt:void(0)"><?php echo $r['gramasi']; ?></a></td>
      <td><a data-pk="<?php echo $r['id']; ?>" data-value="<?php echo $r['knit_std']; ?>" class="knit" href="javascipt:void(0)"><?php echo $r['knit_std']; ?></a></td>
      <td align="left"><a data-pk="<?php echo $r['id']; ?>" data-value="<?php echo $r['loading']; ?>" class="loading" href="javascipt:void(0)"><?php echo $r['loading']; ?></a></td>
      </tr>
	  				  
	<?php 
	 $no++; 
}
	 ?>
				  </tbody>
                  <tfoot>
		<tr>
		  <td style="text-align: right">Total</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>			
		</tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>		  
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