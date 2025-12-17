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
        input{
    text-align:center;
    border:hidden;
    }
    @media print {
    ::-webkit-input-placeholder { /* WebKit browsers */
        color: transparent;
    }
    :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
        color: transparent;
    }
    ::-moz-placeholder { /* Mozilla Firefox 19+ */
        color: transparent;
    }
    :-ms-input-placeholder { /* Internet Explorer 10+ */
        color: transparent;
    }
    .pagebreak { page-break-before:always; }
    .header {display:block}
    table thead 
        {
            display: table-header-group;
        }
    }
</style>
<link href="styles_cetak.css" rel="stylesheet" type="text/css">
<table width="100%" border="0" style="width:9.50in;" >
    <thead>
        <tr>
            <td>
                <table width="100%" border="0" class="table-list1" >
                    <tr>
                        <td width="6%" >
                            <img src="Indo.jpg" alt="" width="50" height="50">
                        </td>
                        <td width="94%">
                            <div align="center">
                                <font size="+1"><strong>LAPORAN STOCK BULANAN KAIN GREIGE</strong><br>
                                </font>NO. FORM : FW - 19 - GKG - 05/05<br>
                                </font>HALAMAN
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </thead>
    <tr>
        <td>
            <table width="100%" border="0" class="table-list1">
                <thead>
                    <tr>
                        <td colspan="12" class="tombol" style="border-bottom:0px #000 solid;
                            border-top:0px #000 solid;
                            border-left:0px #000 solid;
                            border-right:0px #000 solid;">TGL: <?php echo $Awal; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="tombol" rowspan="2"><center><strong>No</strong></center></td>
                        <td class="tombol" rowspan="2"><center><strong>Langganan</strong></center></td>
                        <td class="tombol" rowspan="2"><center><strong>Buyer</strong></center></td>
                        <td class="tombol" rowspan="2"><center><strong>Project Akhir</strong></center></td>
                        <td class="tombol" rowspan="2"><center><strong>Project Awal</strong></center></td>
                        <td class="tombol" rowspan="2"><center><strong>Lot</strong></center></td>
                        <td class="tombol" rowspan="2"><center><strong>Tipe</strong></center></td>
                        <td class="tombol" rowspan="2"><center><strong>No Item</strong></center></td>
                        <td class="tombol" rowspan="2"><center><strong>Lebar</strong></center></td>
                        <td class="tombol" rowspan="2"><center><strong>Gramasi</strong></center></td>
                        <td class="tombol" colspan="4"><center><strong>Jenis Benang</strong></center></td>
                        <td class="tombol" rowspan="2"><center><strong>Roll</strong></center></td>
                        <td class="tombol" rowspan="2"><center><strong>Stock GKG</strong></center></td>
                    </tr>
                    <tr>
                        <td class="tombol"><center><strong>Benang 1</strong></center></td>
                        <td class="tombol"><center><strong>Benang 2</strong></center></td>
                        <td class="tombol"><center><strong>Benang 3</strong></center></td>
                        <td class="tombol"><center><strong>Benang 4</strong></center></td>
                    </tr>
                </thead>
                <tbody>
                    <?php  
                        $no = 1;
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
                        while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                        
                        echo"<tr valign='top'>
                            <td>$no</td>
                            <td>{$row['langganan']}</td>
                            <td>{$row['buyer']}</td>
                            <td>{$row['proj_akhir']}</td>
                            <td>{$row['proj_awal']}</td>
                            <td>{$row['lot']}</td>
                            <td>{$row['tipe']}</td>
                            <td>{$row['no_item']}</td>
                            <td>" . rtrim(rtrim(number_format($row['lebar'], 5, ',', ''), '0'), ',') . "</td>
                            <td>" . rtrim(rtrim(number_format($row['gramasi'], 5, ',', ''), '0'), ',') . "</td>
                            <td>{$row['benang_1']}</td>
                            <td>{$row['benang_2']}</td>
                            <td>{$row['benang_3']}</td>
                            <td>{$row['benang_4']}</td>
                            <td>{$row['qty_roll']}</td>
                            <td>".number_format($row['qty_kg'], 2, ',', '.')."</td>
                            </tr>";
                            $totr += $row['qty_roll'];
                            $totkg += $row['qty_kg'];
                            $no++;
                        }
                    ?>
                    <tr>
                        <?php for ($i=$no; $i <= 40; $i++){ ?>
                        <td class="tombol"><?php echo $i;?></td>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td align="right" >&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                    </tr><?php }?>
                        
                    <tr>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td class="tombol">&nbsp;</td>
                        <td align="right" ><strong>TOTAL :</strong></td>
                        <td class="tombol" align="right"><?php echo $totr; ?></td>
                        <td class="tombol"><?php echo $totkg; ?></td>
                    </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0" class="table-list1">
                <tr>
                    <td width="15%">&nbsp;</td>
                    <td width="31%"><div align="center">DIBUAT OLEH:</div></td>
                    <td width="27%"><div align="center">DIPERIKSA OLEH:</div></td>
                    <td width="27%"><div align="center">DIKETAHUI OLEH:</div></td>
                </tr>
                <tr>
                    <td>NAMA</td>
                    <td>
                        <div align="center">
                            <input name=nama type=text placeholder="Ketik disini" size="33" maxlength="30">
                        </div>
                    </td>
                    <td>
                        <div align="center">
                            <input name=nama3 type=text placeholder="Ketik disini" size="33" maxlength="30">
                        </div>
                    </td>
                    <td>
                        <div align="center">
                            <input name=nama5 type=text placeholder="Ketik disini" size="33" maxlength="30">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>JABATAN</td>
                    <td>
                        <div align="center">
                            <input name=nama2 type=text placeholder="Ketik disini" size="33" maxlength="30">
                        </div>
                    </td>
                    <td>
                        <div align="center">
                            <input name=nama4 type=text placeholder="Ketik disini" size="33" maxlength="30">
                        </div>
                    </td>
                    <td>
                        <div align="center">
                            <input name=nama6 type=text placeholder="Ketik disini" size="33" maxlength="30">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>TANGGAL</td>
                    <td>
                        <div align="center">
                            <input type="text" name="date" placeholder="dd-mm-yyyy" onKeyUp="
                                var date = this.value;
                                if (date.match(/^\d{2}$/) !== null) {
                                    this.value = date + '-';
                                } else if (date.match(/^\d{2}\-\d{2}$/) !== null) {
                                    this.value = date + '-';
                                }" maxlength="10">
                                        </div></td>
                                        <td><div align="center">
                                            <input type="text" name="date" placeholder="dd-mm-yyyy" onKeyUp="
                                var date = this.value;
                                if (date.match(/^\d{2}$/) !== null) {
                                    this.value = date + '-';
                                } else if (date.match(/^\d{2}\-\d{2}$/) !== null) {
                                    this.value = date + '-';
                                }" maxlength="10">
                                        </div></td>
                                        <td><div align="center">
                                            <input type="text" name="date" placeholder="dd-mm-yyyy" onKeyUp="
                                var date = this.value;
                                if (date.match(/^\d{2}$/) !== null) {
                                    this.value = date + '-';
                                } else if (date.match(/^\d{2}\-\d{2}$/) !== null) {
                                    this.value = date + '-';
                                }" maxlength="10">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td height="60" valign="top">TANDA TANGAN</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
	</tbody>
</table>

