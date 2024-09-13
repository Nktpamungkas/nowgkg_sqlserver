<?php
//$id	= isset($_GET['id']) ? $_GET['id'] : '';
// $DemandP	= isset($_POST['demand']) ? $_POST['demand'] : '';
$demand	= isset($_GET['demand']) ? $_GET['demand'] : '';
  $sqlDB2 = "SELECT PRODUCTIONDEMAND.CODE, PRODUCTIONDEMAND.INTERNALREFERENCE, PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE,
  PRODUCTIONDEMAND.FINALPLANNEDDATE, PRODUCTIONDEMAND.ITEMTYPEAFICODE,
  TRIM(PRODUCTIONDEMAND.SUBCODE01) AS SUBCODE01, TRIM(PRODUCTIONDEMAND.SUBCODE02) AS SUBCODE02, TRIM(PRODUCTIONDEMAND.SUBCODE03) AS SUBCODE03,
  TRIM(PRODUCTIONDEMAND.SUBCODE04) AS SUBCODE04, TRIM(PRODUCTIONDEMAND.SUBCODE05) AS SUBCODE05, TRIM(PRODUCTIONDEMAND.SUBCODE06) AS SUBCODE06,
  TRIM(PRODUCTIONDEMAND.SUBCODE07) AS SUBCODE07, TRIM(PRODUCTIONDEMAND.SUBCODE08) AS SUBCODE08, TRIM(PRODUCTIONDEMAND.SUBCODE09) AS SUBCODE09,
  TRIM(PRODUCTIONDEMAND.SUBCODE10) AS SUBCODE10, PRODUCT.LONGDESCRIPTION, A.WARNA, PRODUCTIONDEMAND.PROJECTCODE,
  PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE,
  PRODUCTIONDEMAND.ORIGDLVSALORDERLINEORDERLINE, 
  PRODUCTIONDEMAND.USERPRIMARYQUANTITY, PRODUCTIONDEMAND.USERPRIMARYUOMCODE,
  PRODUCTIONDEMAND.USERSECONDARYQUANTITY, PRODUCTIONDEMAND.USERSECONDARYUOMCODE, 
  SALESORDERDELIVERY.DELIVERYDATE,
  BUSINESSPARTNER.LEGALNAME1 AS LANGGANAN,
  ORDERPARTNERBRAND.LONGDESCRIPTION AS BUYER
  FROM PRODUCTIONDEMAND PRODUCTIONDEMAND LEFT JOIN PRODUCTIONDEMANDSTEP PRODUCTIONDEMANDSTEP
  ON PRODUCTIONDEMAND.CODE = PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE 
  LEFT JOIN PRODUCT PRODUCT ON PRODUCTIONDEMAND.ITEMTYPEAFICODE = PRODUCT.ITEMTYPECODE AND 
  PRODUCTIONDEMAND.SUBCODE01 = PRODUCT.SUBCODE01 AND 
  PRODUCTIONDEMAND.SUBCODE02 = PRODUCT.SUBCODE02 AND 
  PRODUCTIONDEMAND.SUBCODE03 = PRODUCT.SUBCODE03 AND 
  PRODUCTIONDEMAND.SUBCODE04 = PRODUCT.SUBCODE04 AND 
  PRODUCTIONDEMAND.SUBCODE05 = PRODUCT.SUBCODE05 AND 
  PRODUCTIONDEMAND.SUBCODE06 = PRODUCT.SUBCODE06 AND 
  PRODUCTIONDEMAND.SUBCODE07 = PRODUCT.SUBCODE07 AND 
  PRODUCTIONDEMAND.SUBCODE08 = PRODUCT.SUBCODE08 AND 
  PRODUCTIONDEMAND.SUBCODE09 = PRODUCT.SUBCODE09 AND 
  PRODUCTIONDEMAND.SUBCODE10 = PRODUCT.SUBCODE10
  LEFT JOIN ITXVIEWCOLOR A ON PRODUCTIONDEMAND.ITEMTYPEAFICODE = A.ITEMTYPECODE AND 
  PRODUCTIONDEMAND.SUBCODE01 = A.SUBCODE01 AND 
  PRODUCTIONDEMAND.SUBCODE02 = A.SUBCODE02 AND 
  PRODUCTIONDEMAND.SUBCODE03 = A.SUBCODE03 AND 
  PRODUCTIONDEMAND.SUBCODE04 = A.SUBCODE04 AND 
  PRODUCTIONDEMAND.SUBCODE05 = A.SUBCODE05 AND 
  PRODUCTIONDEMAND.SUBCODE06 = A.SUBCODE06 AND 
  PRODUCTIONDEMAND.SUBCODE07 = A.SUBCODE07 AND 
  PRODUCTIONDEMAND.SUBCODE08 = A.SUBCODE08 AND 
  PRODUCTIONDEMAND.SUBCODE09 = A.SUBCODE09 AND 
  PRODUCTIONDEMAND.SUBCODE10 = A.SUBCODE10 
  LEFT JOIN SALESORDERDELIVERY SALESORDERDELIVERY ON PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE = SALESORDERDELIVERY.SALESORDERLINESALESORDERCODE AND 
  PRODUCTIONDEMAND.ORIGDLVSALORDERLINEORDERLINE = SALESORDERDELIVERY.SALESORDERLINEORDERLINE 
  LEFT JOIN ORDERPARTNER ORDERPARTNER 
  ON PRODUCTIONDEMAND.CUSTOMERCODE = ORDERPARTNER.CUSTOMERSUPPLIERCODE 
  LEFT JOIN BUSINESSPARTNER BUSINESSPARTNER 
  ON ORDERPARTNER.ORDERBUSINESSPARTNERNUMBERID = BUSINESSPARTNER.NUMBERID 
  LEFT JOIN SALESORDER SALESORDER ON 
  PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE = SALESORDER.CODE 
  LEFT JOIN ORDERPARTNERBRAND ORDERPARTNERBRAND ON 
  SALESORDER.ORDERPARTNERBRANDCODE = ORDERPARTNERBRAND.CODE AND SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE = ORDERPARTNERBRAND.ORDPRNCUSTOMERSUPPLIERCODE
  WHERE PRODUCTIONDEMANDSTEP.OPERATIONCODE ='BAT1' AND PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE ='$demand'";
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
$rowdb2 = db2_fetch_assoc($stmt);

$sqlBK="SELECT 
PRODUCTIONORDER.CODE,
PRODUCTIONRESERVATION.PRODUCTIONORDERCODE,
PRODUCTIONRESERVATION.ITEMTYPEAFICODE,
SUM(PRODUCTIONRESERVATION.USEDUSERPRIMARYQUANTITY) AS USERPRIMARYQUANTITY,
PRODUCTIONRESERVATION.USERPRIMARYUOMCODE,
SUM(PRODUCTIONRESERVATION.USEDUSERSECONDARYQUANTITY) AS USERSECONDARYQUANTITY,
PRODUCTIONRESERVATION.USERSECONDARYUOMCODE
FROM PRODUCTIONORDER PRODUCTIONORDER
LEFT JOIN PRODUCTIONRESERVATION PRODUCTIONRESERVATION 
ON PRODUCTIONORDER.CODE = PRODUCTIONRESERVATION.PRODUCTIONORDERCODE 
WHERE (PRODUCTIONRESERVATION.ITEMTYPEAFICODE ='KGF' OR PRODUCTIONRESERVATION.ITEMTYPEAFICODE ='KFF')
AND PRODUCTIONORDER.CODE='$rowdb2[PRODUCTIONORDERCODE]'
GROUP BY 
PRODUCTIONORDER.CODE,
PRODUCTIONRESERVATION.PRODUCTIONORDERCODE,
PRODUCTIONRESERVATION.ITEMTYPEAFICODE,
PRODUCTIONRESERVATION.USERPRIMARYUOMCODE,
PRODUCTIONRESERVATION.USERSECONDARYUOMCODE";	
      $stmt1   = db2_exec($conn1,$sqlBK, array('cursor'=>DB2_SCROLLABLE));	
      $rowBK = db2_fetch_assoc($stmt1);
$sqlPOGreige="
SELECT PRODUCTIONDEMAND.CODE,ADSTORAGE.NAMENAME,ADSTORAGE.VALUESTRING 
FROM DB2ADMIN.PRODUCTIONDEMAND PRODUCTIONDEMAND LEFT OUTER JOIN 
       DB2ADMIN.ADSTORAGE ADSTORAGE ON 
       PRODUCTIONDEMAND.ABSUNIQUEID=ADSTORAGE.UNIQUEID AND ADSTORAGE.NAMENAME ='ProAllow' 
WHERE  PRODUCTIONDEMAND.CODE='00141500'";
	  $stmt2   = db2_exec($conn1,$sqlPOGreige, array('cursor'=>DB2_SCROLLABLE));	
      $rowPG   = db2_fetch_assoc($stmt2);


?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Identitas Bagi Kain</h3>

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
                <label for="demand" class="col-md-1 col-form-label">Prod. Demand</label>  
                    <div class="col-sm-2">
                      <input class="form-control form-control-sm" onchange="window.location='CetakIdenBagiKain-'+this.value" value="<?php echo $_GET['demand'];?>" type="text" name="demand" id="demand" placeholder="">	
                    </div>	 
              </div>
              <div class="form-group row">
                <label for="deliv" class="col-md-1 col-form-label">Delivery Date</label>  
                    <div class="col-sm-2">
                      <input class="form-control form-control-sm" readonly value="<?php if($rowdb2['DELIVERYDATE']!=""){echo $rowdb2['DELIVERYDATE'];}?>" type="text" name="deliv" id="deliv" placeholder="">	
                    </div>	 
              </div>
              <div class="form-group row">
               <label for="customer" class="col-md-1 col-form-label">Customer</label>  
				          <div class="col-sm-4">
                 	  <input class="form-control form-control-sm" readonly value="<?php echo $rowdb2['LANGGANAN']."/".$rowdb2['BUYER'];?>" type="text" name="customer" id="customer" placeholder="">	
				          </div>	 
              </div>
			        <div class="form-group row">
               <label for="itemcode" class="col-md-1 col-form-label">Full Item</label>  
				          <div class="col-sm-4">
                 	  <input class="form-control form-control-sm" readonly value="<?php echo $rowdb2['SUBCODE01']."-".$rowdb2['SUBCODE02']."-".$rowdb2['SUBCODE03']."-".$rowdb2['SUBCODE04']."-".$rowdb2['SUBCODE05']."-".$rowdb2['SUBCODE06']."-".$rowdb2['SUBCODE07']."-".$rowdb2['SUBCODE08']."-".$rowdb2['SUBCODE09']."-".$rowdb2['SUBCODE10'];?>" type="text" name="itemcode" id="itemcode" placeholder="">	
				          </div>	 
              </div>
              <div class="form-group row">
                <label for="jenis_kain" class="col-md-1 col-form-label">Description</label>  
                  <div class="col-sm-4">
                    <textarea class="form-control form-control-sm" readonly type="text" name="jenis_kain" id="jenis_kain" placeholder=""><?php echo $rowdb2['LONGDESCRIPTION'];?></textarea>	
                  </div>	 
              </div>
              <div class="form-group row">
                <label for="color" class="col-md-1 col-form-label">Color Name</label>  
                  <div class="col-sm-2">
                    <input class="form-control form-control-sm" readonly value="<?php if($rowdb2['WARNA']!=""){echo $rowdb2['WARNA'];}?>" type="text" name="color" id="color" placeholder="">	
                  </div>	 
              </div>
              <div class="form-group row">
                <label for="project" class="col-md-1 col-form-label">Project</label>  
                  <div class="col-sm-2">
                    <input class="form-control form-control-sm" readonly value="<?php if($rowdb2['ORIGDLVSALORDLINESALORDERCODE']!=""){echo $rowdb2['ORIGDLVSALORDLINESALORDERCODE'];}?>" type="text" name="project" id="project" placeholder="">	
                  </div>	 
              </div>
			  <div class="form-group row">
                <label for="pogreige" class="col-md-1 col-form-label">PO Greige</label>  
                  <div class="col-sm-2">
                    <input class="form-control form-control-sm" readonly value="<?php if($rowdb2['INTERNALREFERENCE']!=""){echo $rowdb2['INTERNALREFERENCE']; }else{echo $rowPG['VALUESTRING'];}?>" type="text" name="pogreige" id="pogreige" placeholder="">	
                  </div>	 
              </div>
              <div class="form-group row">
                <label for="qty" class="col-sm-1 col-form-label">Quantity</label>
                  <div class="col-sm-2">
                    <input name="qty_prm" type="text" readonly class="form-control form-control-sm" id="qty_prm" value="<?php echo number_format($rowBK['USERPRIMARYQUANTITY'],2); ?>" placeholder="0.00" style="text-align: right;">
                    <input name="qty_scnd" type="text" readonly class="form-control form-control-sm" id="qty_scnd" value="<?php echo number_format($rowBK['USERSECONDARYQUANTITY'],2); ?>" placeholder="0.00" style="text-align: right;">
                  </div>				   
                  <div class="col-sm-1">
                    <input name="satuan_prm" type="text" readonly class="form-control form-control-sm" id="satuan_prm" value="<?php echo $rowBK['USERPRIMARYUOMCODE']; ?>" placeholder="">	
                    <input name="satuan_scnd" type="text" readonly class="form-control form-control-sm" id="satuan_scnd" value="<?php echo $rowBK['USERSECONDARYUOMCODE']; ?>" placeholder="">		
                  </div>				   
              </div>
              <a href="pages/cetak/iden-bagikain-now.php?demand=<?php echo $demand; ?>&" target="_blank" class="btn btn-sm btn-danger <?php if($demand==""){echo "disabled";}?>"><i class="fa fa-print"></i> Cetak</a>	
        </div>
		  
		  <!-- /.card-body -->
          
        </div>		    
		</form>	
</div><!-- /.container-fluid -->
    <!-- /.content -->
<div id="DetailGerobak" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>	
<div id="DetailQaData" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>	
<div id="DetailTimeStart" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>	
<div id="DetailTimeEnd" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>	
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