<?php
$tanggalAwal = isset($_POST['tanggal_awal']) ? $_POST['tanggal_awal'] : '';
$tanggalAkhir = isset($_POST['tanggal_akhir']) ? $_POST['tanggal_akhir'] : '';
?>
<!-- Main content -->
<div class="container-fluid">
  <form role="form" method="post" enctype="multipart/form-data" name="form1">
    <div class="card card-success">
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
                <label for="tanggal_awal" class="col-md-1">Tanggal Awal</label>
                <div class="col-md-2">
                    <input name="tanggal_awal" value="<?php echo $tanggalAwal; ?>" type="date" class="form-control form-control-sm" id="tanggal_awal" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <label for="tanggal_akhir" class="col-md-1">Tanggal Akhir</label>
                <div class="col-md-2">
                    <input name="tanggal_akhir" value="<?php echo $tanggalAkhir; ?>" type="date" class="form-control form-control-sm" id="tanggal_akhir" autocomplete="off">
                </div>
            </div>
            <button class="btn btn-info" type="submit" id="cari-data">Cari Data</button>
        </div>
      <!-- /.card-body -->
    </div>

    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Summary Data Surat Jalan Masuk Dari KNT</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
          <thead>
            <tr>
              <th rowspan="2" valign="middle" style="text-align: center">No</th>
              <th rowspan="2" valign="middle" style="text-align: center">TGLBuat KNT</th>
              <th rowspan="2" valign="middle" style="text-align: center">No BON</th>
              <th rowspan="2" valign="middle" style="text-align: center">Project Code</th>
              <th rowspan="2" valign="middle" style="text-align: center">Jenis Benang</th>
              <th rowspan="2" valign="middle" style="text-align: center">No. Hanger</th>
              <th rowspan="2" valign="middle" style="text-align: center">Lot</th>
              <th height="16" valign="middle" style="text-align: center">KNT</th>
              <th valign="middle" style="text-align: center">GD Transit</th>
              <th colspan="2" valign="middle" style="text-align: center">Masuk GKG</th>
              <th colspan="1" valign="middle" style="text-align: center">Balance</th>
              <th rowspan="2" valign="middle" style="text-align: center">Status</th>
            </tr>
            <tr>
              <th valign="middle" style="text-align: center">Berat/Kg</th>
              <th valign="middle" style="text-align: center">Berat/Kg</th>
              <th valign="middle" style="text-align: center">Berat/Kg</th>
              <th valign="middle" style="text-align: center">TGL</th>
              <th valign="middle" style="text-align: center">Berat/Kg</th>
            </tr>
          </thead>
          <tbody>
<?php

$no = 1;
$c = 0;

$sqlDB21 = " SELECT
    DATA_SURAT_JALAN_MASUK.ORDERCODE,
    DATA_SURAT_JALAN_MASUK.ORDERLINE,
    MAX(DATA_SURAT_JALAN_MASUK.PROJECTCODE) AS PROJECTCODE,
    MAX(DATA_SURAT_JALAN_MASUK.DECOSUBCODE01) AS DECOSUBCODE01,
    MAX(DATA_SURAT_JALAN_MASUK.DECOSUBCODE02) AS DECOSUBCODE02,
    MAX(DATA_SURAT_JALAN_MASUK.DECOSUBCODE03) AS DECOSUBCODE03,
    MAX(DATA_SURAT_JALAN_MASUK.DECOSUBCODE04) AS DECOSUBCODE04,
    MAX(DATA_SURAT_JALAN_MASUK.LOTCODE) AS LOTCODE,
    MAX(DATA_SURAT_JALAN_MASUK.TRANSACTIONDATE) AS TRANSACTIONDATE,
    MAX(DATA_SURAT_JALAN_MASUK.ITEMELEMENTCODE) AS ITEMELEMENTCODE,
    MAX(DATA_SURAT_JALAN_MASUK.CREATIONUSER) AS CREATIONUSER,
    MAX(DATA_SURAT_JALAN_MASUK.LOGICALWAREHOUSECODE) AS LOGICALWAREHOUSECODE,
    MAX(DATA_SURAT_JALAN_MASUK.WHSLOCATIONWAREHOUSEZONECODE) AS WHSLOCATIONWAREHOUSEZONECODE,
    MAX(DATA_SURAT_JALAN_MASUK.WAREHOUSELOCATIONCODE) AS WAREHOUSELOCATIONCODE,
    MAX(DATA_SURAT_JALAN_MASUK.SUMMARIZEDDESCRIPTION) AS SUMMARIZEDDESCRIPTION,
    SUM(DATA_SURAT_JALAN_MASUK.QTY_KG) AS QTY_KG,
    SUM(DATA_SURAT_JALAN_MASUK.QTY_YD) AS QTY_YD,
    SUM(DATA_SURAT_JALAN_MASUK.ROL) AS ROL
FROM (
    SELECT
        STOCKTRANSACTION.ORDERCODE,
        STOCKTRANSACTION.ORDERLINE,
        STOCKTRANSACTION.PROJECTCODE,
        STOCKTRANSACTION.DECOSUBCODE01,
        STOCKTRANSACTION.DECOSUBCODE02,
        STOCKTRANSACTION.DECOSUBCODE03,
        STOCKTRANSACTION.DECOSUBCODE04,
        STOCKTRANSACTION.LOTCODE,
        STOCKTRANSACTION.TRANSACTIONDATE,
        SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,
        SUM(STOCKTRANSACTION.BASESECONDARYQUANTITY) AS QTY_YD,
        STOCKTRANSACTION.ITEMELEMENTCODE,
        COUNT(STOCKTRANSACTION.ITEMELEMENTCODE) AS ROL,
        STOCKTRANSACTION.CREATIONUSER,
        STOCKTRANSACTION.LOGICALWAREHOUSECODE,
        STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
        STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
        FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
    FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION
    LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
        STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
    WHERE STOCKTRANSACTION.LOGICALWAREHOUSECODE = 'M502' AND DERIVATIONCODE <> ''
    GROUP BY
        STOCKTRANSACTION.ORDERCODE,
        STOCKTRANSACTION.ORDERLINE,
        STOCKTRANSACTION.PROJECTCODE,
        STOCKTRANSACTION.DECOSUBCODE01,
        STOCKTRANSACTION.DECOSUBCODE02,
        STOCKTRANSACTION.DECOSUBCODE03,
        STOCKTRANSACTION.DECOSUBCODE04,
        STOCKTRANSACTION.LOTCODE,
        STOCKTRANSACTION.TRANSACTIONDATE,
        STOCKTRANSACTION.ITEMELEMENTCODE,
        STOCKTRANSACTION.CREATIONUSER,
        STOCKTRANSACTION.LOGICALWAREHOUSECODE,
        STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
        STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
        FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
) AS DATA_SURAT_JALAN_MASUK
WHERE DATA_SURAT_JALAN_MASUK.TRANSACTIONDATE BETWEEN '$tanggalAwal' AND '$tanggalAkhir'
GROUP BY
    DATA_SURAT_JALAN_MASUK.ORDERCODE,
    DATA_SURAT_JALAN_MASUK.ORDERLINE
";

$stmt1 = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));
//}
$knitt = "";

while ($rowdb21 = db2_fetch_assoc($stmt1)) {
    $bon = $rowdb21['ORDERCODE'] . "-" . $rowdb21['ORDERLINE'];
    $NoBon = $rowdb21['ORDERCODE'];
    $NoLine = $rowdb21['ORDERLINE'];

    $itemElementCodes = [];
    $jumlahTglBeda = 0;

    $sqlDetail = " SELECT
      STOCKTRANSACTION.ORDERCODE,
      STOCKTRANSACTION.ORDERLINE,
      STOCKTRANSACTION.DECOSUBCODE01,
      STOCKTRANSACTION.DECOSUBCODE02,
      STOCKTRANSACTION.DECOSUBCODE03,
      STOCKTRANSACTION.DECOSUBCODE04,
      STOCKTRANSACTION.LOTCODE,
      STOCKTRANSACTION.TRANSACTIONDATE,
      SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,
      SUM(STOCKTRANSACTION.BASESECONDARYQUANTITY) AS QTY_YD,
      STOCKTRANSACTION.ITEMELEMENTCODE,
      COUNT(STOCKTRANSACTION.ITEMELEMENTCODE) AS ROL,
      STOCKTRANSACTION.CREATIONUSER,
      STOCKTRANSACTION.LOGICALWAREHOUSECODE,
      STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
      STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
      FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
      FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION
      LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
      STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
      WHERE STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M502' AND
      STOCKTRANSACTION.ORDERCODE='$NoBon' AND STOCKTRANSACTION.ORDERLINE='$NoLine'
      GROUP BY
      STOCKTRANSACTION.ORDERCODE,
      STOCKTRANSACTION.ORDERLINE,
      STOCKTRANSACTION.DECOSUBCODE01,
      STOCKTRANSACTION.DECOSUBCODE02,
      STOCKTRANSACTION.DECOSUBCODE03,
      STOCKTRANSACTION.DECOSUBCODE04,
      STOCKTRANSACTION.LOTCODE,
      STOCKTRANSACTION.TRANSACTIONDATE,
      STOCKTRANSACTION.ITEMELEMENTCODE,
      STOCKTRANSACTION.CREATIONUSER,
      STOCKTRANSACTION.LOGICALWAREHOUSECODE,
      STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
      STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
      FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
      ";

    $stmtDetail = db2_exec($conn1, $sqlDetail, ['cursor' => DB2_SCROLLABLE]);

    if ($stmtDetail) {
        while ($row = db2_fetch_assoc($stmtDetail)) {
            $itemElementCodes[] = $row['ITEMELEMENTCODE'];
            $tanggalKNT = $row['TRANSACTIONDATE'];

            $sqlCekCount = "SELECT STOCKTRANSACTION.TRANSACTIONDATE,STOCKTRANSACTION.LOGICALWAREHOUSECODE,
            STOCKTRANSACTION.ITEMELEMENTCODE,STOCKTRANSACTION.BASESECONDARYQUANTITY,
            STOCKTRANSACTION.BASEPRIMARYQUANTITY,STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
            STOCKTRANSACTION.WAREHOUSELOCATIONCODE,STOCKTRANSACTION.LOTCODE
            FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION
            WHERE STOCKTRANSACTION.LOGICALWAREHOUSECODE='M021' AND STOCKTRANSACTION.TOKENCODE ='RECEIPT' AND
            STOCKTRANSACTION.TEMPLATECODE ='204' AND
            STOCKTRANSACTION.ITEMELEMENTCODE='$row[ITEMELEMENTCODE]'";

            $stmtCekCount = db2_exec($conn1, $sqlCekCount, array('cursor' => DB2_SCROLLABLE));
            $dataCekCount = db2_fetch_assoc($stmtCekCount);

            $tanggalGKG = $dataCekCount['TRANSACTIONDATE'];

            if ($tanggalKNT != $tanggalGKG) {
                $jumlahTglBeda++;
            }
        }

        $itemElementCodesList = "'" . implode("','", $itemElementCodes) . "'";
    }

    $sqlDB22 = "SELECT
    MAX(STOCKTRANSACTION.TRANSACTIONDATE) AS TRANSACTIONDATE,
    SUM(STOCKTRANSACTION.BASESECONDARYQUANTITY) AS BASESECONDARYQUANTITY,
    SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY
    FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION
    WHERE
    STOCKTRANSACTION.LOGICALWAREHOUSECODE = 'M021'
    AND STOCKTRANSACTION.TOKENCODE = 'RECEIPT'
    AND STOCKTRANSACTION.TEMPLATECODE = '204'
    AND STOCKTRANSACTION.ITEMELEMENTCODE IN ($itemElementCodesList)";

    $stmt2 = db2_exec($conn1, $sqlDB22, array('cursor' => DB2_SCROLLABLE));
    $rD = db2_fetch_assoc($stmt2);

    $sqlDB23 = "SELECT
    SUM(BASEPRIMARYQUANTITYUNIT) AS BASEPRIMARYQUANTITYUNIT,
    SUM(BASESECONDARYQUANTITYUNIT) AS BASESECONDARYQUANTITYUNIT
		FROM DB2ADMIN.BALANCE BALANCE
		WHERE BALANCE.LOGICALWAREHOUSECODE='M021'
    AND BALANCE.ELEMENTSCODE IN ($itemElementCodesList)";

    $stmt3 = db2_exec($conn1, $sqlDB23, array('cursor' => DB2_SCROLLABLE));
    $rD1 = db2_fetch_assoc($stmt3);

    $sqlDB24 = "SELECT
    SUM(BASEPRIMARYQUANTITYUNIT) AS BASEPRIMARYQUANTITYUNIT,
    SUM(BASESECONDARYQUANTITYUNIT) AS BASESECONDARYQUANTITYUNIT,
    COUNT(ELEMENTSCODE) AS ROL
    FROM
    DB2ADMIN.BALANCE BALANCE
    WHERE
    BALANCE.LOGICALWAREHOUSECODE = 'TR11'
    AND
    BALANCE.ELEMENTSCODE IN ($itemElementCodesList)";

    $stmt4 = db2_exec($conn1, $sqlDB24, array('cursor' => DB2_SCROLLABLE));
    $rD2 = db2_fetch_assoc($stmt4);

    $stts = "";
    $statusTanggal = "";

    if ($rowdb21['QTY_KG'] == $rD['BASEPRIMARYQUANTITY']) {
        $stts = "<small class='badge badge-success'> OK</small>";
    } elseif (($rowdb21['QTY_KG'] > $rD['BASEPRIMARYQUANTITY'] and $rD1['BASEPRIMARYQUANTITYUNIT'] > 0) or ($rD2['BASEPRIMARYQUANTITYUNIT'] > 0 and $rowdb21['QTY_KG'] > $rD2['BASEPRIMARYQUANTITYUNIT'])) {
        $stts = "<small class='badge badge-warning'><i class='fas fa-exclamation-triangle text-white blink_me'></i> Tidak OK</small>";
    } else if ((number_format(round($rowdb21['QTY_KG'], 2), 2) == number_format(round($rD2['BASEPRIMARYQUANTITYUNIT'], 2), 2)) and (round($rowdb21['QTY_YD'], 2) == round($rD2['BASESECONDARYQUANTITYUNIT'], 2))) {
        $stts = "<small class='badge badge-danger'><i class='far fa-clock blink_me'></i> Belum Masuk</small>";
    } else if ($rowdb21['QTY_KG'] > 0 and $rD['BASEPRIMARYQUANTITY'] > 0 and $rD1['BASEPRIMARYQUANTITYUNIT'] == "0") {
        $stts = "<small class='badge badge-info'><i class='far fa-clock blink_me'></i> Sudah Pakai</small>";
    }

    if ($jumlahTglBeda > 0) {
        $statusTanggal = "<small class='badge badge-danger'><i class='far fa-clock blink_me'></i>Tanggal Beda</small>";
    }

    // Set session variables
    $_SESSION['nobon'] = $NoBon;
    $_SESSION['lineno'] = $NoLine;
    $_SESSION['tanggal_awal'] = $tanggalAwal;
    $_SESSION['tanggal_akhir'] = $tanggalAkhir;

    ?>
              <tr>
                <td style="text-align: center"><?php echo $no; ?></td>
                <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
                <td style="text-align: center">
                    <a href="javascript:void(0);" class="btn btn-link" onclick="openInNewTab('<?php echo $NoBon; ?>', '<?php echo $NoLine; ?>', '<?php echo $tanggalAwal; ?>', '<?php echo $tanggalAkhir; ?>')">
                        <?php echo $bon; ?>
                    </a>
                </td>
                <td style="text-align: center"><?php echo $rowdb21['PROJECTCODE']; ?></td>
                <td style="text-align: left"><?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?></td>
                <td style="text-align: center"><?php echo trim($rowdb21['DECOSUBCODE02']) . trim($rowdb21['DECOSUBCODE03']) . " " . $rowdb21['DECOSUBCODE04']; ?></td>
                <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
                <td style="text-align: right"><?php echo number_format(round($rowdb21['QTY_KG'], 2), 2); ?></td>
                <td style="text-align: right"><?php echo number_format(round($rD2['BASEPRIMARYQUANTITYUNIT'], 2), 2); ?></td>
                <td style="text-align: right"><?php echo number_format(round($rD['BASEPRIMARYQUANTITY'], 2), 2); ?></td>
                <td>
  <?php
echo $rD['TRANSACTIONDATE'];
    ?>
                </td>
                <td style="text-align: right"><?php echo number_format(round($rD1['BASEPRIMARYQUANTITYUNIT'], 2), 2); ?></td>
                <td><?php echo $stts . '<br>' . nl2br($statusTanggal); ?></td>
              </tr>
            <?php
$no++;

    $TkntYD += round($rowdb21['ROL']);
    $TkntKGS += round($rowdb21['QTY_KG'], 2);
    $TtrnYD += round($rD2['ROL']);
    $TtrnKGS += round($rD2['BASEPRIMARYQUANTITYUNIT'], 2);
    $TgkgYD += round($rD['BASESECONDARYQUANTITY']);
    $TgkgKGS += round($rD['BASEPRIMARYQUANTITY'], 2);
    $TblcYD += round($rD1['BASESECONDARYQUANTITYUNIT']);
    $TblcKGS += round($rD1['BASEPRIMARYQUANTITYUNIT'], 2);
}?>
          </tbody>
          <tfoot>
            <tr>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: left">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: right"><strong><span style="text-align: center">Total</span></strong></td>
              <td style="text-align: right"><strong><?php echo $TkntKGS; ?></strong></td>
              <td style="text-align: right"><strong><?php echo $TtrnKGS; ?></strong></td>
              <td style="text-align: right"><strong><?php echo $TgkgKGS; ?></strong></td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: right"><strong><?php echo $TblcKGS; ?></strong></td>
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
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
    function openInNewTab(NoBon, NoLine, tanggalAwal, tanggalAkhir) {
        // Buat form dinamis
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'CekSuratJalanMasukDetail'; // Ubah ke halaman tujuan Anda
        form.target = '_blank'; // Form dikirim ke tab baru

        // Buat input untuk setiap parameter yang ingin dikirim
        var input1 = document.createElement('input');
        input1.type = 'hidden';
        input1.name = 'NoBon';
        input1.value = NoBon;

        var input2 = document.createElement('input');
        input2.type = 'hidden';
        input2.name = 'NoLine';
        input2.value = NoLine;

        var input3 = document.createElement('input');
        input3.type = 'hidden';
        input3.name = 'tanggalAwal';
        input3.value = tanggalAwal;

        var input4 = document.createElement('input');
        input4.type = 'hidden';
        input4.name = 'tanggalAkhir';
        input4.value = tanggalAkhir;

        // Menambahkan input ke dalam form
        form.appendChild(input1);
        form.appendChild(input2);
        form.appendChild(input3);
        form.appendChild(input4);

        // Tambahkan form ke body dan submit
        document.body.appendChild(form);
        form.submit();
    }
</script>

<script>
    function saveSessionAndOpenTab(NoBon, NoLine, tanggalAwal, tanggalAkhir) {
        sessionStorage.setItem('NoBon', NoBon);
        sessionStorage.setItem('NoLine', NoLine);
        sessionStorage.setItem('tanggalAwal', tanggalAwal);
        sessionStorage.setItem('tanggalAkhir', tanggalAkhir);

        window.open('CekSuratJalanMasukDetail', '_blank');
    }
</script>
<script>
    document.getElementById("cari-data").addEventListener("click", function(event) {
        const tanggalAwal = document.getElementById("tanggal_awal").value;
        const tanggalAkhir = document.getElementById("tanggal_akhir").value;

        if (!tanggalAwal || !tanggalAkhir) {
            event.preventDefault(); // Mencegah pengiriman form
            Swal.fire({
                icon: 'warning',
                title: 'Validasi Tanggal',
                text: 'Tanggal Awal dan Tanggal Akhir tidak boleh kosong!',
                confirmButtonText: 'OK'
            });
        }
    });
</script>

<script>
  $(function() {
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
  function checkAll(form1) {
    for (var i = 0; i < document.forms['form1'].elements.length; i++) {
      var e = document.forms['form1'].elements[i];
      if ((e.name != 'allbox') && (e.type == 'checkbox')) {
        e.checked = document.forms['form1'].allbox.checked;

      }
    }
  }
</script>