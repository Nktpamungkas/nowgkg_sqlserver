<?php
$Zone		= isset($_POST['zone']) ? $_POST['zone'] : '';
$Lokasi		= isset($_POST['lokasi']) ? $_POST['lokasi'] : '';
$Barcode	= substr($_POST['barcode'],-13);
?>

<?php 
$sqlCek1=mysqli_query($con,"SELECT COUNT(*) as jml FROM	tbl_stokfull WHERE status='ok' and zone='$Zone' AND lokasi='$Lokasi'");
$ck1=mysqli_fetch_array($sqlCek1);
$sqlCek2=mysqli_query($con,"SELECT COUNT(*) as jml FROM	tbl_stokfull WHERE status='belum cek' and zone='$Zone' AND lokasi='$Lokasi'");
$ck2=mysqli_fetch_array($sqlCek2);

if($_POST['cek']=="Cek" or $_POST['cari']=="Cari"){
	//if (strlen($_POST['barcode'])==13){
	$sqlCek=mysqli_query($con,"SELECT COUNT(*) as jml FROM	tbl_stokfull WHERE zone='$Zone' AND lokasi='$Lokasi' AND SN='$Barcode'");
	$ck=mysqli_fetch_array($sqlCek);
	if($Zone=="" and $Lokasi==""){
		echo"<script>alert('Zone atau Lokasi belum dipilih');</script>";
	}else if($Barcode!="" and strlen($Barcode)==13 ){
	if($ck['jml']>0){		
	$sqlData=mysqli_query($con,"UPDATE tbl_stokfull SET 
		  status='ok',
		  tgl_cek=now()
		  WHERE zone='$Zone' AND lokasi='$Lokasi' AND SN='$Barcode'");
		}else{
	$sqlDB21 = " SELECT WHSLOCATIONWAREHOUSEZONECODE, WAREHOUSELOCATIONCODE, CREATIONDATETIME,BASEPRIMARYQUANTITYUNIT FROM 
	BALANCE b WHERE b.ITEMTYPECODE='KFF' AND b.LOGICALWAREHOUSECODE='M031' AND b.ELEMENTSCODE='$Barcode' ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	$rowdb21 = db2_fetch_assoc($stmt1);
	$lokasiAsli=trim($rowdb21['WHSLOCATIONWAREHOUSEZONECODE'])."-".trim($rowdb21['WAREHOUSELOCATIONCODE']);
	$tglMasuk=substr($rowdb21['CREATIONDATETIME'],0,10);
	$KGnow=round($rowdb21['BASEPRIMARYQUANTITYUNIT'],2);	
	if($lokasiAsli!="-"){
		echo"<script>alert('Data Roll ini dilokasi $lokasiAsli');</script>";
	}else{
		echo"<script>alert('SN tidak OK');</script>";
	}
	$sqlDataE=mysqli_query($con,"INSERT INTO tbl_stokloss SET 
		  lokasi='$Lokasi',
		  lokasi_asli='$lokasiAsli',
		  KG='$KGnow',
		  zone='$Zone',
		  SN='$Barcode',
		  tgl_masuk='$tglMasuk',
		  tgl_cek=now()");	
		}
	}
	//}else{
		//echo"<script>alert('SN harus 13 karakter');</script>";
//	}

}
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Filter Data</h3>

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
               <label for="zone" class="col-md-1">Zone</label>               
                 <select class="form-control select2bs4" style="width: 100%;" name="zone">
				   <option value="">Pilih</option>	 
					<?php $sqlZ=mysqli_query($con," SELECT * FROM tbl_zone order by nama ASC"); 
					  while($rZ=mysqli_fetch_array($sqlZ)){
					 ?>
                    <option value="<?php echo $rZ['nama'];?>" <?php if($rZ['nama']==$Zone){ echo "SELECTED"; }?>><?php echo $rZ['nama'];?></option>
                    <?php  } ?>
                  </select>			   
            </div>
				 <div class="form-group row">
                    <label for="lokasi" class="col-md-1">Location</label>
					<select class="form-control select2bs4" style="width: 100%;" name="lokasi">
                    <option value="">Pilih</option>	 
					<?php $sqlL=mysqli_query($con," SELECT * FROM tbl_lokasi WHERE zone='$Zone' order by nama ASC"); 
					  while($rL=mysqli_fetch_array($sqlL)){
					 ?>
                    <option value="<?php echo $rL['nama'];?>" <?php if($rL['nama']==$Lokasi){ echo "SELECTED"; }?>><?php echo $rL['nama'];?></option>
                    <?php  } ?>
                  </select>	
                  </div> 
			  <button class="btn btn-info" type="submit" value="Cari" name="cari">Cari Data</button>
          </div>
		  
		  <!-- /.card-body -->
          
        </div> 
		<!--	</form>
		<form role="form" method="post" enctype="multipart/form-data" name="form2">-->  
		<div class="card card-default">
         
          <!-- /.card-header -->
          <div class="card-body">
             <div class="form-group row">
               <label for="barcode" class="col-md-1">Barcode</label>               
               <input type="text" class="form-control"  name="barcode" placeholder="SN / Elements" id="barcode" on autofocus>			    
            </div>	
			  <button class="btn btn-primary" type="submit" name="cek" value="Cek">Check</button>
			  
          </div>
		  
		  <!-- /.card-body -->
          
        </div> 
			</form>
		  <div class="card">
              <div class="card-header">
                <h3 class="card-title">Stock</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
				 <strong>Stok OK Sesuai Tempat</strong> <small class='badge badge-success'> <?php echo $ck1['jml'];?> roll </small><br>
				 <strong>Stok belum Cek</strong> <small class='badge badge-danger'> <?php echo $ck2['jml'];?> roll </small> 
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size:13px;">
                  <thead>
                  <tr>
                    <th style="text-align: center">SN</th>
                    <th style="text-align: center">Kg</th>
                    <th style="text-align: center">Status</th>
                    <th style="text-align: center">Lokasi</th>
                    <th style="text-align: center">NOW</th>
                    <th style="text-align: center">Lot</th>
                    <th style="text-align: center">Warna</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	if( $Zone!="" and $Lokasi!=""){				  
	$Where= " Where `zone`='$Zone' AND `lokasi`='$Lokasi' " ;
	}else{
		$Where= " Where `zone`='$Zone' AND `lokasi`='$Lokasi' " ;
	}
	if($Shift!=""){
		$Shft=" AND a.shft='$Shift' ";
	}else{
		$Shft=" ";
	}				  
		$sql=mysqli_query($con," SELECT * FROM tbl_stokfull $Where ");
   $no=1;   
   $c=0;
    while($rowd=mysqli_fetch_array($sql)){
	$sqlDB22 = " SELECT WHSLOCATIONWAREHOUSEZONECODE, WAREHOUSELOCATIONCODE FROM 
	BALANCE b WHERE b.ITEMTYPECODE='KFF' AND b.ELEMENTSCODE='$rowd[SN]' ";
	$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
	$rowdb22 = db2_fetch_assoc($stmt2);
	$lokasiBalance=trim($rowdb22['WHSLOCATIONWAREHOUSEZONECODE'])."-".trim($rowdb22['WAREHOUSELOCATIONCODE']);
	   ?>
	  <tr>
      <td style="text-align: center"><?php echo $rowd['SN']; ?></td>
      <td style="text-align: right"><?php echo $rowd['KG']; ?></td>
      <td style="text-align: center"><small class='badge <?php if($rowd['status']=="ok"){ echo"badge-success";}else if($rowd['status']=="belum cek"){ echo"badge-danger";}?>'> <?php echo $rowd['status']; ?></small></td>
      <td style="text-align: center"><?php echo $rowd['zone']."-".$rowd['lokasi']; ?></td>
      <td style="text-align: center"><?php echo $lokasiBalance; ?></td>
      <td style="text-align: center"><?php echo $rowd['lot']; ?></td>
      <td style="text-align: center"><?php echo $rowd['warna']; ?></td>
      </tr>				  
					  <?php 
	 
	 $no++;} ?>
				  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
        </div>  
<div class="card">
              <div class="card-header">
                <h3 class="card-title">ReCheck Stock </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example3" class="table table-sm table-bordered table-striped" style="font-size:13px;">
                  <thead>
                  <tr>
                    <th style="text-align: center">SN</th>
                    <th style="text-align: center">KG</th>
                    <th style="text-align: center">Lokasi Scan</th>
                    <th style="text-align: center">Lokasi Asli</th>
                    <th style="text-align: center">Tgl Masuk</th>
                    <th style="text-align: center">Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	if( $Zone!="" and $Lokasi!=""){				  
	$Where= " Where `zone`='$Zone' AND `lokasi`='$Lokasi' " ;
	}else{
		$Where= " Where `zone`='$Zone' AND `lokasi`='$Lokasi' " ;
	}
					  
		$sql1=mysqli_query($con," SELECT *, count(SN) as jmlscn FROM tbl_stokloss $Where  group by SN");
   $no=1;   
   $c=0;
    while($rowd1=mysqli_fetch_array($sql1)){
	if(strlen($rowd1['SN'])!="13"){	
	$ketSN= "jumlah Karakter di SN tidak Sesuai";}else{$ketSN= "";}
	if($rowd1['jmlscn']>1){
	$ketSCN= "Jumlah Scan ".$rowd1['jmlscn']." kali";	
	}else{ $ketSCN= "";}	
	if($rowd1['tgl_masuk']=="0000-00-00" or $rowd1['tgl_masuk']==""){
			$tglmsk="";
	}else{
			$tglmsk=$rowd1['tgl_masuk']; }
	   ?>
	  <tr>
      <td style="text-align: center"><?php echo $rowd1['SN']; ?></td>
      <td style="text-align: center"><?php echo $rowd1['KG']; ?></td>
      <td style="text-align: center"><?php echo $rowd1['zone']."-".$rowd1['lokasi']; ?></td>
      <td style="text-align: center"><?php echo $rowd1['lokasi_asli']; ?></td>
      <td style="text-align: center"><?php echo $tglmsk; ?></td>
      <td style="text-align: center"><small class='badge <?php if($rowd1['status']=="tidak ok"){ echo"badge-warning";}?>' ><i class='fas fa-exclamation-triangle text-default blink_me'></i> <?php echo $rowd1['status']; ?></small> <?php echo $ketSN.", ".$ketSCN; ?> </td>
      </tr>				  
					  <?php 
	 
	 $no++;} ?>
				  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
            </div>      
</div><!-- /.container-fluid -->
    <!-- /.content -->
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