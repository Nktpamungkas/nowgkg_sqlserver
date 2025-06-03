<?php
    $namaFile = 'Stock_kain_mati_gkg.xls';
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$namaFile");
    header("Pragma: no-cache");
    header("Expires: 0");
    //disini script laporan anda
    include "./../../koneksi.php";
?>

<?php
    ini_set("error_reporting", 1);
    session_start();
    include "koneksi.php";
    $ip_num = $_SERVER['REMOTE_ADDR'];
    $os     = $_SERVER['HTTP_USER_AGENT'];
?>

<?php
    $Awal  = isset($_GET['tanggal1']) ? $_GET['tanggal1'] : '';

    function replace_nonbreaking_space($data)
    {
        return str_replace("\xC2\xA0", " ", $data);
    }

    function cek_tanggal($ins_tgl)
    {
        if (! empty($ins_tgl)) {
            $benchmark_date = new DateTime($ins_tgl);
            return $benchmark_date->format('d-M-y');
        } else {
            return "";
        }
    }
?>
<body>
    <?php
        if (!empty($Awal)) {
            $timestamp = strtotime($Awal);
            $bulanTahun = date('F Y', $timestamp);
            $bulanIndonesia = [
                'January' => 'Januari',
                'February' => 'Februari',
                'March' => 'Maret',
                'April' => 'April',
                'May' => 'Mei',
                'June' => 'Juni',
                'July' => 'Juli',
                'August' => 'Agustus',
                'September' => 'September',
                'October' => 'Oktober',
                'November' => 'November',
                'December' => 'Desember',
            ];

            $bulanTahun = strtr($bulanTahun, $bulanIndonesia);
        }
    ?>

    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
        <!-- Logo (optional) -->
        <!--
        <div style="margin-right: 20px;">
            <img src="dist/img/indo.png" alt="Logo" style="height: 80px;">
        </div>
        -->

        <!-- Judul -->
        <div style="text-align: center;">
            <h1 style="margin: 0;"><strong>LAPORAN SISA KAIN GREIGE (Stock Mati)</strong></h1>
            <h3 style="margin: 10px 0;"><strong>ORDER/PO YANG TELAH SELESAI</strong></h3>
            <h3 style="margin: 10px 0;"><strong>BULAN <?php echo $bulanTahun; ?></strong></h3>
            <h3 style="margin: 10px 0;"><strong>No Form : FW-19-GKG-07/04</strong></h3>
            <h3 style="margin: 10px 0;"><strong>Halaman :</strong></h3>
        </div>
    </div>
<body>

<div align="LEFT">TGL : <?php echo date($_GET['tanggal1']); ?></div>

<table style="width: auto;" border="1">
    <tr>
        <th rowspan="2" style="text-align: center">No</th>              
        <th rowspan="2" style="text-align: center">Langganan</th>              
        <th colspan="4" style="text-align: center">Jenis Benang</th>
        <th rowspan="2" style="text-align: center">Jenis Kain</th>
        <th colspan="2" style="text-align: center">Ukuran Jadi</th>
        <th rowspan="2" style="text-align: center">Knitt</th>
        <th rowspan="2" style="text-align: center">Knit Order</th>
        <th colspan="2" style="text-align: center">Stock</th>
        <th rowspan="2" style="text-align: center">Keterangan</th>
    </tr>
    <tr>
        <th style="text-align: center">1</th>
        <th style="text-align: center">2</th>
        <th style="text-align: center">3</th>
        <th style="text-align: center">4</th>
        <th style="text-align: center">Lebar</th>
        <th style="text-align: center">Gramasi</th>
        <th style="text-align: center">Roll</th>
        <th style="text-align: center">Stock GKG</th>              
    </tr>

      <!-- Query disini -->
    <?php
    $no = 1;
    $c = 0;
    $QTPR = 0;

    // Ini query utama, udah disesuain supaya sama, tapi coba cek lagi 
    $sql = sqlsrv_query($con, "WITH RankedData AS (
            SELECT
            id,
            TRIM(proj_awal) AS proj_awal,
            TRIM(no_item) AS no_item,
            langganan,
            buyer,
            lot,
            tipe,
            benang_1,
            benang_2,
            benang_3,
            benang_4,
            weight AS kgs,
            rol AS roll,
            tgl_tutup,
            CASE 
                WHEN CHARINDEX('/', REVERSE(TRIM(proj_awal))) > 0 
                THEN '20' + LEFT(
                    REVERSE(
                        LEFT(
                            REVERSE(TRIM(proj_awal)),
                            CHARINDEX('/', REVERSE(TRIM(proj_awal))) - 1
                        )
                    ),
                    2
                )
                ELSE NULL
            END AS tahun_mati,
            ROW_NUMBER() OVER (
                PARTITION BY TRIM(proj_awal), TRIM(no_item)
                ORDER BY tgl_tutup DESC
            ) AS rn
            FROM dbnow_gkg.tblopname
            WHERE 
            proj_awal LIKE '%/%'
            and  tgl_tutup = '$Awal'
        --    AND proj_awal = '2023562/XII/21'
        )
        SELECT *
        FROM RankedData
        WHERE rn = 1
        and tahun_mati IN ('2019', '2020', '2021')
    ORDER BY tahun_mati ASC");
     // End Query utama
     
    while ($r = sqlsrv_fetch_array($sql)) {
        $sql1 = mysqli_query($con1, "SELECT sum(berat) as KGs, group_concat(no_bon,':',berat,' ') as no_bon  
            FROM dbknitt.tbl_pembagian_greige_now where no_po ='$r[proj_awal]' and no_artikel='$r[no_item]'");
        $r1 = mysqli_fetch_array($sql1);        
     
        $sqlDB28 = " SELECT a.VALUEDECIMAL  FROM PRODUCT p 
            LEFT OUTER JOIN ADSTORAGE a  ON a.UNIQUEID = p.ABSUNIQUEID 
            WHERE CONCAT(TRIM(p.SUBCODE02),CONCAT(TRIM(p.SUBCODE03),CONCAT(' ',TRIM(p.SUBCODE04))))='$r[no_item]' AND
            a.NAMENAME ='Width' AND
            p.ITEMTYPECODE ='KFF'  ";
                    $stmt8 = db2_exec($conn1, $sqlDB28, array('cursor' => DB2_SCROLLABLE));
                    $rowdb28 = db2_fetch_assoc($stmt8);
                    $sqlDB29 = " SELECT a.VALUEDECIMAL  FROM PRODUCT p 
            LEFT OUTER JOIN ADSTORAGE a  ON a.UNIQUEID = p.ABSUNIQUEID 
            WHERE CONCAT(TRIM(p.SUBCODE02),CONCAT(TRIM(p.SUBCODE03),CONCAT(' ',TRIM(p.SUBCODE04))))='$r[no_item]' AND
            a.NAMENAME ='GSM' AND
            p.ITEMTYPECODE ='KFF'  "
        ;
        $stmt9 = db2_exec($conn1, $sqlDB29, array('cursor' => DB2_SCROLLABLE));
        $rowdb29 = db2_fetch_assoc($stmt9);
        ?>
        <tr>                
            <td style="text-align: left"><?php echo $no; ?></td>
            <td style="text-align: left"><?php echo $r['langganan']; ?></td>
            <td style="text-align: left"><?php echo $r['benang_1']; ?></td>
            <td style="text-align: left"><?php echo $r['benang_2']; ?></td>
            <td style="text-align: left"><?php echo $r['benang_3']; ?></td>
            <td style="text-align: left"><?php echo $r['benang_4']; ?></td>
            <td style="text-align: center"><?php echo $r['no_item']; ?></td>
            <td style="text-align: center"><?php echo round($rowdb28['VALUEDECIMAL']); ?></td>
            <td style="text-align: center"><?php echo round($rowdb29['VALUEDECIMAL']); ?></td>  
            <td style="text-align: center"></td>
            <td style="text-align: center"><?php echo $r['proj_awal']; ?></td>
            <td style="text-align: center"><?php echo $r['roll']; ?></td>
            <td style="text-align: right"><?php echo $r['kgs']; ?></td>
            <td style="text-align: center"><?php echo $r['tahun_mati']; ?></td>
            
        </tr>

        <?php
            $totrol = $totrol + $r['roll'];
            $totkg = $totkg + $r['kgs'];    
            $no++;         
    }
        ?>
    <tfoot>
        <tr>
            <td style="text-align: center">&nbsp;</td>
            <td style="text-align: center">&nbsp;</td>
            <td style="text-align: center">&nbsp;</td>
            <td style="text-align: center">&nbsp;</td>
            <td style="text-align: center">&nbsp;</td>
            <td style="text-align: center">&nbsp;</td>
            <td style="text-align: center">&nbsp;</td>
            <td colspan="4" style="text-align: right"><strong>TOTAL</strong></td>
            <td style="text-align: center"><span style="text-align: right"><strong><?php echo $totrol; ?></strong></span>
            </td>
            <td style="text-align: right"><strong><?php echo number_format(round($totkg, 3), 3); ?></strong></td>
            <td style="text-align: center">&nbsp;</td>
        </tr>
    </tfoot>
</table>

<table ></table>
<table ></table>

<table style="width: auto;" border="1">
    <tr>
        <td colspan="4"></td>
        <td colspan="3" style="text-align: center; vertical-align: middle;">Dibuat Oleh :</td>
        <td colspan="3" style="text-align: center; vertical-align: middle;">Diperiksa Oleh :</td>
        <td colspan="4" style="text-align: center; vertical-align: middle;">Mengertahui :</td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: center; vertical-align: middle;">Nama</td>
        <td colspan="3" style="text-align: center; vertical-align: middle;"></td>
        <td colspan="3" style="text-align: center; vertical-align: middle;"></td>
        <td colspan="4" style="text-align: center; vertical-align: middle;"></td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: center; vertical-align: middle;">Jabatan</td>
        <td colspan="3" style="text-align: center; vertical-align: middle;"></td>
        <td colspan="3" style="text-align: center; vertical-align: middle;"></td>
        <td colspan="4" style="text-align: center; vertical-align: middle;"></td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: center; vertical-align: middle;">Tanggal</td>
        <td colspan="3" style="text-align: center; vertical-align: middle;"></td>
        <td colspan="3" style="text-align: center; vertical-align: middle;"></td>
        <td colspan="4" style="text-align: center; vertical-align: middle;"></td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: center; vertical-align: middle;">Tanda Tangan</td>
        <td colspan="3" style="text-align: center; vertical-align: middle;"><br><br><br><br></td>
        <td colspan="3" style="text-align: center; vertical-align: middle;"></td>
        <td colspan="4" style="text-align: center; vertical-align: middle;"></td>
    </tr>
</table>