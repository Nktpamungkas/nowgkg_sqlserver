<?php
ini_set("error_reporting", 1);
session_start();
include '../../koneksi.php';

$Awal = isset($_GET['awal']) ? $_GET['awal'] : '';
$ipaddress = $_SERVER['REMOTE_ADDR'];
?>
<style type="text/css">

    
.tombolkanan {
	text-align: right;
}
input {
	text-align: center;
	border: hidden;
}
@media print {
  .pagebreak {
    page-break-before: always;
  }
  .header {display: block}
  table thead {
    display: table-header-group;
  }
  .table-list1 {
    border-collapse: collapse;
    width: 100%;
    table-layout: fixed;
  }
  .table-list1 td.tombol {
    border: 1px solid #000;
    font-size: 5px;
  }
  .tombol {
    font-size: 5px !important;
  }
  .table-list1 td, .table-list1 th, .tombol {
    font-size: 5px;
    padding: 0px;
    border: 1px solid #000;
    word-wrap: break-word;
  }

  @media print {
  input {
    border: none;
    width: 100%;
    font-size: 10px;
  }

  table {
    table-layout: fixed;
  }

  td {
    word-wrap: break-word;
    overflow: hidden;
  }
}

}
</style>
<link href="styles_cetak.css" rel="stylesheet" type="text/css">

<?php
$no = 1;
$rowPerPage = 60;
$pageRow = 0;
$totr = 0;
$totkg = 0;

$sqlDB21 = "SELECT * 
    FROM dbnow_gkg.tbl_stock_excel 
    WHERE tgl_tutup = '$Awal' AND ip_address = '$ipaddress' 
    ORDER BY 
        CASE 
            WHEN langganan = '' OR langganan IS NULL THEN 1 
            ELSE 0 
        END, 
    langganan ASC";

$stmt1 = sqlsrv_query($con, $sqlDB21);

if (!$stmt1) {
    echo "Query Gagal: " . db2_stmt_errormsg();
    exit;
}

function print_table_header($Awal) {
    echo '
    <table width="100%" border="0" class="table-list1">
        <tr>
            <td width="5%"><img src="Indo.jpg" alt="" width="50" height="50"></td>
            <td>
                <div align="center">
                    <font size="+1">LAPORAN STOCK BULANAN KAIN GREIGE</font><br>
                    NO. FORM : FW - 19 - GKG - 05/05<br>
                    HALAMAN :
                </div>
            </td>
        </tr>
    </table>
    <table width="100%" border="0" class="table-list1" style="margin-top: 4px;">
        <thead>
            <tr>
                <td colspan="16" class="tombol" style="border:0px solid #000;">TGL: ' . $Awal . '</td>
            </tr>
            <tr>
                <td class="tombol" width="2%" rowspan="2"><center><strong>No</strong></center></td>
                <td class="tombol" width="5%" rowspan="2"><center><strong>Langganan</strong></center></td>
                <td class="tombol" width="2%" rowspan="2"><center><strong>Buyer</strong></center></td>
                <td class="tombol" width="5%" rowspan="2"><center><strong>Project Akhir</strong></center></td>
                <td class="tombol" width="5%" rowspan="2"><center><strong>Project Awal</strong></center></td>
                <td class="tombol" width="2%" rowspan="2"><center><strong>Lot</strong></center></td>
                <td class="tombol" width="2%" rowspan="2"><center><strong>Tipe</strong></center></td>
                <td class="tombol" width="5%" rowspan="2"><center><strong>No Item</strong></center></td>
                <td class="tombol" width="2%" rowspan="2"><center><strong>Lebar</strong></center></td>
                <td class="tombol" width="2%" rowspan="2"><center><strong>Gramasi</strong></center></td>
                <td class="tombol" colspan="4"><center><strong>Jenis Benang</strong></center></td>
                <td class="tombol" width="2%" rowspan="2"><center><strong>Roll</strong></center></td>
                <td class="tombol" width="2%" rowspan="2"><center><strong>Stock GKG</strong></center></td>
            </tr>
            <tr>
                <td class="tombol" width="5%"><center><strong>Benang 1</strong></center></td>
                <td class="tombol" width="5%"><center><strong>Benang 2</strong></center></td>
                <td class="tombol" width="5%"><center><strong>Benang 3</strong></center></td>
                <td class="tombol" width="5%"><center><strong>Benang 4</strong></center></td>
            </tr>
        </thead>
        <tbody>';
}

$first = true;
while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
    if ($pageRow == 0) {
        if (!$first) echo '</tbody></table><div class="pagebreak"></div>';
        print_table_header($Awal);
        $first = false;
    }

    echo "<tr>
        <td>$no</td>
        <td>{$row['langganan']}</td>
        <td>{$row['buyer']}</td>
        <td>{$row['proj_akhir']}</td>
        <td>{$row['proj_awal']}</td>
        <td>{$row['lot']}</td>
        <td>{$row['tipe']}</td>
        <td>{$row['no_item']}</td>
        <td>".number_format($row['lebar'], 2, ',', '.')."</td>
        <td>".number_format($row['gramasi'], 2, ',', '.')."</td>
        <td>{$row['benang_1']}</td>
        <td>{$row['benang_2']}</td>
        <td>{$row['benang_3']}</td>
        <td>{$row['benang_4']}</td>
        <td>".number_format($row['qty_roll'], 2, ',', '.')."</td>
        <td>".number_format($row['qty_kg'], 2, ',', '.')."</td>
    </tr>";

    $totr += $row['qty_roll'];
    $totkg += $row['qty_kg'];
    $no++;
    $pageRow++;

    if ($pageRow == $rowPerPage) {
        $pageRow = 0;
    }
}

// ✅ CETAK BARIS TOTAL DI DALAM TABEL UTAMA
echo "
    <tr>
        <td colspan='14' style='text-align:right; font-weight:bold;'>Total</td>
        <td style='text-align:right; font-weight:bold;'>".number_format($totr, 2, ',', '.')."</td>
        <td style='text-align:right; font-weight:bold;'>".number_format($totkg, 2, ',', '.')."</td>
    </tr>
</tbody></table>";




// Cetak tanda tangan
?>
<table width="100%" border="1" class="table-list1" style="margin-top:10px; table-layout: fixed; border-collapse: collapse;">
    <colgroup>
        <col style="width: 33%;">
        <col style="width: 33%;">
        <col style="width: 33%;">
    </colgroup>
    <tr>
        <td style="text-align:center;"><strong>DIBUAT OLEH:</strong></td>
        <td style="text-align:center;"><strong>DIPERIKSA OLEH:</strong></td>
        <td style="text-align:center;"><strong>DIKETAHUI OLEH:</strong></td>
    </tr>
    <tr>
        <td><input type="text" placeholder="Ketik disini" style="width:100%; text-align:center;"></td>
        <td><input type="text" placeholder="Ketik disini" style="width:100%; text-align:center;"></td>
        <td><input type="text" placeholder="Ketik disini" style="width:100%; text-align:center;"></td>
    </tr>
    <tr>
        <td><input type="text" placeholder="Jabatan" style="width:100%; text-align:center;"></td>
        <td><input type="text" placeholder="Jabatan" style="width:100%; text-align:center;"></td>
        <td><input type="text" placeholder="Jabatan" style="width:100%; text-align:center;"></td>
    </tr>
    <tr>
        <td><input type="text" placeholder="dd-mm-yyyy" maxlength="10" onkeyup="formatDate(this)" style="width:100%; text-align:center;"></td>
        <td><input type="text" placeholder="dd-mm-yyyy" maxlength="10" onkeyup="formatDate(this)" style="width:100%; text-align:center;"></td>
        <td><input type="text" placeholder="dd-mm-yyyy" maxlength="10" onkeyup="formatDate(this)" style="width:100%; text-align:center;"></td>
    </tr>
    <tr>
        <td style="height: 60px;">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>



<script>
function formatDate(input) {
    var date = input.value;
    if (date.match(/^\d{2}$/) !== null) {
        input.value = date + '-';
    } else if (date.match(/^\d{2}-\d{2}$/) !== null) {
        input.value = date + '-';
    }
}
</script>
