<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-danger">
          <div class="card-header">
            <h3 class="card-title">Filter Data Permintaan Potong</h3>

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
		<?php if($Awal!="" and $Akhir!=""){ ?>	
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Laporan Harian Bagi Kain Greige</h3>
				
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">TglKeluar</th>
                    <th valign="middle" style="text-align: center">Buyer</th>
                    <th valign="middle" style="text-align: center">Customer</th>
                    <th valign="middle" style="text-align: center">ProjectCode</th>
                    <th valign="middle" style="text-align: center">Code</th>
                    <th valign="middle" style="text-align: center">LOT</th>
                    <th valign="middle" style="text-align: center">Qty</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Note</th>
                    <th valign="middle" style="text-align: center">Project Awal</th>
                    <th valign="middle" style="text-align: center">User</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB21 = " SELECT 
s.CREATIONUSER, s.TRANSACTIONDATE, s.DECOSUBCODE02, s.DECOSUBCODE03, s.DECOSUBCODE04, 
s.LOTCODE, sum(s.BASEPRIMARYQUANTITY) AS KG, count(s.ITEMELEMENTCODE) AS JML, a.VALUESTRING AS PTG, a1.VALUESTRING as NOTE, s.PROJECTCODE  
FROM STOCKTRANSACTION s
LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.NAMENAME = 'StatusPotong'
LEFT OUTER JOIN ADSTORAGE a1 ON a1.UNIQUEID = s.ABSUNIQUEID AND a1.NAMENAME = 'NoteMintaPotong'
WHERE s.ITEMTYPECODE='KGF' AND s.LOGICALWAREHOUSECODE ='M021' AND a.VALUESTRING ='1' AND
s.TEMPLATECODE = '098' AND s.TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir' 
GROUP BY s.TRANSACTIONDATE, s.DECOSUBCODE02, s.DECOSUBCODE03, s.DECOSUBCODE04, 
s.LOTCODE,s.CREATIONUSER,a.VALUESTRING, a1.VALUESTRING, s.PROJECTCODE ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
if ($rowdb21['LOGICALWAREHOUSECODE'] =='M501') { $knitt = 'LT2'; }
else if($rowdb21['LOGICALWAREHOUSECODE'] ='P501'){ $knitt = 'LT1'; }
if($rowdb21['PROJECTCODE']!=""){$project=$rowdb21['PROJECTCODE'];}else{$project=$rowdb21['ORIGDLVSALORDLINESALORDERCODE'];}		
$kdbenang=trim($rowdb21['DECOSUBCODE01'])." ".trim($rowdb21['DECOSUBCODE02'])." ".trim($rowdb21['DECOSUBCODE03'])." ".trim($rowdb21['DECOSUBCODE04'])." ".trim($rowdb21['DECOSUBCODE05'])." ".trim($rowdb21['DECOSUBCODE06'])." ".trim($rowdb21['DECOSUBCODE07'])." ".trim($rowdb21['DECOSUBCODE08']);
$sqlDB22 = " SELECT SALESORDER.CODE, SALESORDER.EXTERNALREFERENCE, SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE,
		ITXVIEWAKJ.LEGALNAME1, ITXVIEWAKJ.ORDERPARTNERBRANDCODE, ITXVIEWAKJ.LONGDESCRIPTION
		FROM DB2ADMIN.SALESORDER SALESORDER LEFT OUTER JOIN DB2ADMIN.ITXVIEWAKJ 
       	ITXVIEWAKJ ON SALESORDER.CODE=ITXVIEWAKJ.CODE
		WHERE SALESORDER.CODE='$project' ";
$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
$rowdb22 = db2_fetch_assoc($stmt2);
if(strlen(trim($rowdb21['LOTCODE']))=="8"){$Wlot=" AND ( p.PROJECTCODE ='".trim($rowdb21['PROJAWAL'])."' OR p.ORIGDLVSALORDLINESALORDERCODE ='".trim($rowdb21['PROJAWAL'])."' OR p.CODE='".trim($rowdb21['LOTCODE'])."' ) ";}
else{$Wlot=" AND ( p.PROJECTCODE ='".trim($project)."' OR p.ORIGDLVSALORDLINESALORDERCODE ='".trim($project)."' ) ";}		

$sqlDB23 = " SELECT p.SUBCODE01,p.SUBCODE02,p.SUBCODE03,p.SUBCODE04,p.SUBCODE05,p.SUBCODE06,p.SUBCODE07, p.LONGDESCRIPTION FROM (
SELECT p2.ITEMTYPEAFICODE,p2.SUBCODE01,p2.SUBCODE02,p2.SUBCODE03,p2.SUBCODE04,
p2.SUBCODE05,p2.SUBCODE06,p2.SUBCODE07  FROM PRODUCTIONDEMAND p 
LEFT OUTER JOIN PRODUCTIONRESERVATION p2 ON p.CODE =p2.ORDERCODE 
WHERE p.ITEMTYPEAFICODE ='KGF' AND p.SUBCODE01='".trim($rowdb21['DECOSUBCODE01'])."' 
AND p.SUBCODE02 ='".trim($rowdb21['DECOSUBCODE02'])."' AND p.SUBCODE03 ='".trim($rowdb21['DECOSUBCODE03'])."' AND
p.SUBCODE04='".trim($rowdb21['DECOSUBCODE04'])."' $Wlot 
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
$a[0]="";$a[1]="";$a[2]="";$a[3]="";		
while($rowdb23 = db2_fetch_assoc($stmt3)){
	$a[$ai]=$rowdb23['LONGDESCRIPTION'];
	$ai++;
}
$sqlDB24 = " 
SELECT pr.LONGDESCRIPTION,p.PRODUCTIONORDERCODE,pd.SUBCODE01,pd.SUBCODE02,pd.SUBCODE03,
	pd.SUBCODE04,pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,pd.SUBCODE08,pd.INTERNALREFERENCE,
	LISTAGG(DISTINCT  TRIM(pd.CODE),', ') AS PRODUCTIONDEMANDCODE
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
WHERE (pd.PROJECTCODE ='$project' OR pd.ORIGDLVSALORDLINESALORDERCODE ='$project') AND p.PRODUCTIONORDERCODE='$rowdb21[ORDERCODE]'	
	GROUP BY pr.LONGDESCRIPTION,p.PRODUCTIONORDERCODE,pd.SUBCODE01,pd.SUBCODE02,pd.SUBCODE03,
	pd.SUBCODE04,pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,pd.SUBCODE08,pd.INTERNALREFERENCE ";
$stmt4   = db2_exec($conn1,$sqlDB24, array('cursor'=>DB2_SCROLLABLE));
$rowdb24 = db2_fetch_assoc($stmt4);
		
$sqlDB25 = " 
SELECT CASE WHEN PROJECTCODE <> '' THEN PROJECTCODE ELSE ORIGDLVSALORDLINESALORDERCODE  END  AS PROJECT FROM PRODUCTIONDEMAND WHERE CODE='".$rowdb21['LOTCODE']."'
";
$stmt5   = db2_exec($conn1,$sqlDB25, array('cursor'=>DB2_SCROLLABLE));
$rowdb25 = db2_fetch_assoc($stmt5);		
		
	if($rowdb22['LEGALNAME1']==""){$langganan="";}else{$langganan=$rowdb22['LEGALNAME1'];}
	if($rowdb22['ORDERPARTNERBRANDCODE']==""){$buyer="";}else{$buyer=$rowdb22['ORDERPARTNERBRANDCODE'];}
		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
	  <td style="text-align: left"><?php echo $buyer; ?></td>
	  <td style="text-align: left"><?php echo $langganan; ?></td>
	  <td style="text-align: center"><?php echo $project; ?></td>
	  <td><?php echo $kdbenang; ?></td> 
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['JML']; ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['KG'],2),2); ?></td>
      <td style="text-align: left"><?php echo $rowdb21['NOTE']; ?></span></td>
      <td style="text-align: center"><?php if($rowdb21['PROJAWAL']!=""){echo $rowdb21['PROJAWAL'];}else if($rowdb25['PROJECT']!=""){echo $rowdb25['PROJECT'];}else{echo $rowdb24['INTERNALREFERENCE'];} ?></td>
      <td style="text-align: center"><?php  echo $rowdb21['CREATIONUSER']; ?></td>
      </tr>
	  				  
	<?php 
	 $no++; 
	$totRol=$totRol+$rowdb21['JML'];
	$totKG=$totKG+$rowdb21['KG'];	
	
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
	    <th style="text-align: center"><span style="text-align: left">Total</span></th>
	    <th style="text-align: center"><?php echo $totRol;?></th>
	    <th style="text-align: right"><?php echo number_format(round($totKG,2),2);?></th>
	    <th style="text-align: center">&nbsp;</th>
	    <th style="text-align: center">&nbsp;</th>
	    <th style="text-align: center">&nbsp;</th>
	    </tr>				
	 </tfoot>             
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