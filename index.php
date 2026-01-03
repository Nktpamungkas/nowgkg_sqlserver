<?php
session_start();
//include config
include "koneksi.php";
ini_set("error_reporting", 1);

//request page
$page = isset($_GET['p']) ? $_GET['p'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$page = strtolower($page);
?>

<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>NOWgkg | <?php if ($_GET['p'] != "") {
    echo ucwords($_GET['p']);
} else {
    echo "Home";
}?></title>

  <!-- Google Font: Source Sans Pro -->
  <!--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">-->
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <!-- ChartJS -->
	<script src="plugins/chart.js/Chart.min.js"></script>	
	<!-- <script src="plugins/chart.js/chart371.js"></script>	 -->
	<!-- <script src="plugins/chart.js/chartjs-plugin-datalabels.js"></script>  	 -->

  <!-- Theme style -->
  <?php if ($page == "stdlodingdye") {?>
  <!-- X Editable -->
	<link rel="stylesheet" href="plugins/x-editable/dist/bootstrap4-editable/css/bootstrap-editable.css">
  <?php }?>
  <style>
	  .blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
	</style>
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="icon" type="image/png" href="dist/img/ITTI_Logo index.ico">
</head>
<body class="hold-transition sidebar-collapse layout-top-nav">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md navbar-dark navbar-blue">
    <div class="container">
      <a href="Home" class="navbar-brand">
        <img src="dist/img/ITTI_Logo 2021.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">NOW<strong>gkg</strong></span>
      </a>

      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="Home" class="nav-link">Home</a>
          </li>
		  <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Laporan</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
			  <li><a href="MasukKainGreige" class="dropdown-item">Masuk Kain Greige</a></li>
			  <li><a href="KeluarKainGreige" class="dropdown-item">Keluar Kain Greige</a></li>
			  <li><a href="PermintaanPotong" class="dropdown-item">Permintaan Potong</a></li>
			  <li><a href="CutElements" class="dropdown-item">Cut Elements</a></li>
			  <li><a href="DetailBagikain" class="dropdown-item">Detail BagiKain</a></li>
			  <li><a href="StdLodingDYE" class="dropdown-item">Standard Loading Mesin Dyeing</a></li>
			  <li><a href="ChangeLocation" class="dropdown-item">Change Location</a></li>
        <li><a href="StockMatiKainGKG" class="dropdown-item">Kain Greige (Stock Mati)</a></li>
			</ul>
          </li>
		  <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Pergerakan Stock</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="IdentifikasiKainGreige" class="dropdown-item">Identifikasi Kain Greige</a></li>
			  <li><a href="IdentifikasiKainGreigeFKG" class="dropdown-item">Identifikasi Kain Greige FKG</a></li>
			  <li><a href="IdentifikasiKainGreigeCMD" class="dropdown-item">Identifikasi Kain Greige CMD</a></li>
			  <li><a href="PersediaanKainGreige" class="dropdown-item">Persediaan Kain Greige</a></li>
			  <li><a href="PersediaanKainGreigeTahun" class="dropdown-item">Persediaan Kain Greige Per Tahun</a></li>
			  <li><a href="PergerakanKainGreige" class="dropdown-item">Pergerakan Kain Greige</a></li>
			  <li><a href="PergerakanKainGreigeFKG" class="dropdown-item">Pergerakan Kain Greige FKG</a></li>
			  <li><a href="PergerakanKainGreigeCMD" class="dropdown-item">Pergerakan Kain Greige CMD</a></li>
			  <li><a href="PergerakanKainGreigeRTR" class="dropdown-item">Pergerakan Kain Greige RTR</a></li>
			  <li><a href="PergerakanKainChangeProject" class="dropdown-item">Pergerakan Kain Change Project</a></li>
			  <li><a href="StockKainGKG" class="dropdown-item">Stock Kain GKG</a></li>
			  <li><a href="CekGreigeMasuk" class="dropdown-item">Cek Kain Greige Masuk</a></li>
			  <li><a href="CekSuratJalanMasuk" class="dropdown-item">Cek Surat Jalan Masuk</a></li>
			  <li><a href="CekChangeLocation" class="dropdown-item">Cek Change Location</a></li>

			</ul>
          </li>
		  <!--<li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Stock Opname</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="TutupHarian" class="dropdown-item">Tutup Transaksi Harian</a></li>
			  <li><a href="TutupBulanan" class="dropdown-item">Tutup Transaksi Bulanan</a></li>
			</ul>
          </li>-->
      <li class="nav-item dropdown">
          <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Cetak From NOW</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="CetakIdenKainGreige" class="dropdown-item">Cetak Identifikasi Kain Greige</a></li>
              <li><a href="CetakIdenKainGreigeRetur" class="dropdown-item">Cetak Identifikasi Kain Greige Retur Produksi</a></li>
			  <li><a href="CetakIdenFlatKnittMaklon" class="dropdown-item">Cetak Identifikasi Flat Knitt Maklon</a></li>
			  <li><a href="CetakIdenFlatKnitt" class="dropdown-item">Cetak Identifikasi Flat Knitt</a></li>
			  <li><a href="CetakIdenBagiKain" class="dropdown-item">Cetak Identifikasi Bagi Kain</a></li>
			      </ul>
      </li>
	  <li class="nav-item">
            <a href="ProductionOrderTracing" class="nav-link">Production Order Tracing</a>
          </li>

          <li class="nav-item">
            <a href="MasterRak" class="nav-link">Master Rak</a>
          </li>
        </ul>

      </div>

    </div>
  </nav>
  <!-- /.navbar -->


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">

    </div>
    <!-- /.content-header -->

    <!-- Main content -->
	<section class="content">
    <div class="content">
     <?php
if (!empty($page) and !empty($act)) {
    $files = 'pages/' . $page . '.' . $act . '.php';
} elseif (!empty($page)) {
    $files = 'pages/' . $page . '.php';
} else {
    $files = 'pages/home.php';
}

if (file_exists($files)) {
    include $files;
} else {
    include_once "blank.php";
}
?>

    </div>
	</section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->



  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Indo Taichen Textile Industy
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="">DIT</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- DataTables  & Plugins -->
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="plugins/jszip/jszip.min.js"></script>
  <script src="plugins/pdfmake/pdfmake.min.js"></script>
  <script src="plugins/pdfmake/vfs_fonts.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Bootstrap Switch -->
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="plugins/dropzone/min/dropzone.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<?php if ($page == "stdlodingdye") {?>
<!-- xeditablejs -->
<script src="plugins/x-editable/dist/bootstrap4-editable/js/bootstrap-editable.min.js"></script>
<?php }?>	
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["excel", "pdf"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
	$("#example3").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false, "searching": true,
      "buttons": ["excel", "pdf"]
    }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
	$("#example4").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false, "searching": false,
      "buttons": ["excel", "pdf"]
    }).buttons().container().appendTo('#example4_wrapper .col-md-6:eq(0)');
	$("#example5").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false, "searching": false,
      "buttons": ["excel", "pdf"]
    }).buttons().container().appendTo('#example5_wrapper .col-md-6:eq(0)');
	$("#example6").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false, "searching": true,
      "buttons": ["excel", "pdf"]
    }).buttons().container().appendTo('#example6_wrapper .col-md-6:eq(0)');
	$("#example7").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false, "searching": true,
      "buttons": ["excel", "pdf"]
    }).buttons().container().appendTo('#example7_wrapper .col-md-6:eq(0)');
	$("#example8").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false, "searching": false,
      "buttons": ["excel", "pdf"]
    }).buttons().container().appendTo('#example8_wrapper .col-md-6:eq(0)');
	$("#example9").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false, "searching": false,
      "buttons": ["excel", "pdf"]
    }).buttons().container().appendTo('#example9_wrapper .col-md-6:eq(0)');
	$("#example10").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false, "searching": false,
      "buttons": ["excel", "pdf"]
    }).buttons().container().appendTo('#example10_wrapper .col-md-6:eq(0)');
	$("#example11").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false, "searching": false,
      "buttons": ["excel", "pdf"]
    }).buttons().container().appendTo('#example11_wrapper .col-md-6:eq(0)');
	$("#example13").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false, "pageLength": 20,
      "buttons": ["copy", "csv", "excel", "pdf"]
    }).buttons().container().appendTo('#example12_wrapper .col-md-6:eq(0)');
	$('#example14').DataTable({
	  "paging": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "responsive": true,
	  "scrollX": true,
      "scrollY": '150px',
	  "buttons": ["copy", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example14_wrapper .col-md-6:eq(0)');
	$('#example15').DataTable({
	  "paging": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "responsive": true,
	  "scrollX": true,
      "scrollY": '150px',
	  "buttons": ["copy", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example15_wrapper .col-md-6:eq(0)');
	$('#example16').DataTable({
	  "paging": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "responsive": true,
	  "scrollX": true,
      "scrollY": '150px',
	  "buttons": ["copy", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example16_wrapper .col-md-6:eq(0)');
	$('#example17').DataTable({
	  "paging": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "responsive": true,
	  "scrollX": true,
      "scrollY": '150px',
	  "buttons": ["copy", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example17_wrapper .col-md-6:eq(0)');
	$('#example18').DataTable({
	  "paging": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "responsive": true,
	  "scrollX": true,
      "scrollY": '150px',
	  "buttons": ["copy", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example18_wrapper .col-md-6:eq(0)');
  });
  $("#example19").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": [
        {
            extend: 'excel',
            text: 'EXCEL',
            title: 'CATATAN CHANGE LOCATION',  // Menambahkan judul di header file
            messageTop: 'NO FORM : FW-19-GKG-19/04',  // Menambahkan judul di atas data tabel
            exportOptions: {
                columns: ':visible'  // Mengatur kolom mana yang akan diekspor (opsional)
            }
        },
        'pdf'
    ]
    }).buttons().container().appendTo('#example19_wrapper .col-md-6:eq(0)');
</script>
<script>
	$(function () {

	//Initialize Select2 Elements
    $('.select2').select2()
	//Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
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
	//Date picker
    $('#reservationdate').datetimepicker({
        format: 'L'
    });
});
</script>
<script>
$(document).on('click', '.show_detail_bruto', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_detail_bruto.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#BrutoDetailShow").html(ajaxData);
        $("#BrutoDetailShow").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });
$(document).on('click', '.show_detail_out', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_detail_out.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#OutDetailShow").html(ajaxData);
        $("#OutDetailShow").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });
$(document).on('click', '.show_detail_dyc', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_detail_dyc.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#DYCDetailShow").html(ajaxData);
        $("#DYCDetailShow").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });
$(document).on('click', '.show_detail_lot', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_detail_lot.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#LOTDetailShow").html(ajaxData);
        $("#LOTDetailShow").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });
$(document).on('click', '.show_detail', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_detail.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#DetailShow").html(ajaxData);
        $("#DetailShow").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });
  $(document).on('click', '.show_pergerakan_detail', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_pergerakan_detail.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#DetailPergerakanShow").html(ajaxData);
        $("#DetailPergerakanShow").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });
</script>
<?php if ($page == "stdlodingdye") { ?>
<script language="javascript">
	$.fn.editable.defaults.mode = 'popup';
//	$.fn.editable.defaults.mode = 'inline';
    $(document).ready(function() {
	  $('.item').editable({
        type: 'text',
        disabled : false,
        url: 'pages/editable/editable_item.php',
      });
	  $('.jenis_kain').editable({
        type: 'textarea',
        disabled : false,
        url: 'pages/editable/editable_jenis_kain.php',
      });
	  $('.lebar').editable({
        type: 'number',
        disabled : false,
        url: 'pages/editable/editable_lebar.php',
      });
	  $('.gramasi').editable({
        type: 'number',
        disabled : false,
        url: 'pages/editable/editable_gramasi.php',
      });
	  $('.loading').editable({
        type: 'textarea',
        disabled : false,
        url: 'pages/editable/editable_loading.php',
      });
	  $('.knit').editable({
        type: 'number',
		step: '0.01',
        disabled : false,
        url: 'pages/editable/editable_knit.php',
      });
    })

</script>
<?php } ?>
</body>
</html>
