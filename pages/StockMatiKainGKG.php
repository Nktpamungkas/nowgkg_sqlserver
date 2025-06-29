<?php
include "../koneksi.php";
$Awal  = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
?>
<!-- Main content -->
<div class="container-fluid">
  <form role="form" method="post" enctype="multipart/form-data" name="form1">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Filter Data Stock Mati Kain Greige</h3>

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
          <label for="tgl_awal" class="col-md-1">Tanggal</label>
          <div class="col-md-2">
            <div class="input-group date" id="datepicker1" data-target-input="nearest">
              <div class="input-group-prepend" data-target="#datepicker1" data-toggle="datetimepicker">
                <span class="input-group-text btn-info">
                  <i class="far fa-calendar-alt"></i>
                </span>
              </div>
              <input name="tgl_awal" value="<?php echo isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : date('YYYY-mm-dd'); ?>" type="text" class="form-control form-control-sm" id="" autocomplete="off" required>
            </div>
          </div>
        </div>
        <button class="btn btn-info" type="submit">Cari Data</button>
      </div>
      <!-- /.card-body -->     
    </div>
  </form>
  <?php if ($Awal != "") { ?>
    <div class="card card-pink">
      <div class="card-header">
        <h3 class="card-title">Stock</h3>
        <a href="pages/cetak/download_stock_kain_mati.php?tanggal1=<?php echo $Awal; ?>" class="btn bg-blue float-right" target="_blank">Cetak Excel</a>
      </div>
      <!-- /.card-header -->
      <div class="card-body table-responsive">
         <!-- Tombol Excel Export -->
         <div class="col-md-4 mb-6 d-flex align-items-end">
            <div align="right">
                 
              </a>
            </div>
          </div>
        <table id="example6" width="100%" class="table table-sm table-bordered table-hover tree" style="font-size:13px;">
          <thead>
            <tr>
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
          </thead>
          <tbody>
            <?php
            $no = 1;
            $c = 0;
            $QTPR = 0;
            
            // Ini query utama, udah disesuain supaya sama, tapi coba cek lagi 
            $sql = sqlsrv_query($con, "WITH RankedData AS (
                                                                      SELECT DISTINCT 
                                                                        proj_awal,
                                                                        no_item,
                                                                        lot,
                                                                        tipe,
                                                                        kgs,
                                                                        roll,
                                                                        tgl_tutup,
                                                                        tahun_mati,
                                                                        ROW_NUMBER() OVER (
                                                                                      PARTITION BY TRIM(proj_awal), TRIM(no_item)
                                                                                      ORDER BY tgl_tutup DESC
                                                                                    ) AS rn
                                                                        FROM Stock_mati_gkg
                                                                                  WHERE 
                                                                                    proj_awal LIKE '%/%'
                                                                        and  tgl_tutup = '$Awal'
                                                                        )
                                                                    SELECT *
                                                                    FROM RankedData
                                                                    WHERE rn = 1
                                                                    ORDER BY tahun_mati ASC");
            // End Query utama

            while ($r = sqlsrv_fetch_array($sql)) {
              $sql1 = mysqli_query($con1,"SELECT sum(berat) as KGs, group_concat(no_bon,':',berat,' ') as no_bon  
                                          FROM dbknitt.tbl_pembagian_greige_now where no_po ='$r[proj_awal]' and no_artikel='$r[no_item]' 
                                          ");		  
                                          $r1 = mysqli_fetch_array($sql1);

              // // Query penyebab lemot
              // $sqlDB210 = "SELECT SUM(a.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY, SUM(a3.VALUEDECIMAL) AS QTYSALIN  FROM ITXVIEWHEADERKNTORDER a
              //   LEFT OUTER JOIN PRODUCTIONDEMAND p ON p.CODE =a.PRODUCTIONDEMANDCODE
              //   LEFT OUTER JOIN ADSTORAGE a2 ON p.ABSUNIQUEID =a2.UNIQUEID AND a2.NAMENAME ='StatusRMP'
              //   LEFT OUTER JOIN ADSTORAGE a3 ON p.ABSUNIQUEID =a3.UNIQUEID AND a3.NAMENAME ='QtySalin'
              //   WHERE a.ITEMTYPEAFICODE ='KGF' AND (a.PROJECTCODE ='$r[proj_awal]' OR a.ORIGDLVSALORDLINESALORDERCODE ='$r[proj_awal]') AND
              //   (a.PROGRESSSTATUS='2' OR a.PROGRESSSTATUS='6') AND (NOT a2.VALUESTRING ='3' OR a2.VALUESTRING IS NULL) AND 
              //   CONCAT(TRIM(a.SUBCODE02),CONCAT(TRIM(a.SUBCODE03),CONCAT(' ',TRIM(a.SUBCODE04))))='$r[no_item]'
              //   GROUP BY a.SUBCODE02,a.SUBCODE03,a.SUBCODE04";
              // // End Query

              // $stmt10   = db2_exec($conn1, $sqlDB210, array('cursor' => DB2_SCROLLABLE));
              // $rowdb210 = db2_fetch_assoc($stmt10);
              
              // // Penyebab lemotnya ini karena di tiap rownya harus ngebaca ulang ini
              // // Mungkin solusinya bisa buat query kepisah untuk ambil value qtpr
              // $QTPR = $rowdb210['BASEPRIMARYQUANTITY'] - $rowdb210['QTYSALIN'];
              // // End Penyebab Lemot

              // $sqlDB211 = "SELECT SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_ROL  
              //                   FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION LEFT OUTER JOIN
              //                   DB2ADMIN.ITXVIEWLAPMASUKGREIGE ITXVIEWLAPMASUKGREIGE ON ITXVIEWLAPMASUKGREIGE.PROVISIONALCODE  = STOCKTRANSACTION.ORDERCODE
              //                   AND ITXVIEWLAPMASUKGREIGE.ORDERLINE  = STOCKTRANSACTION.ORDERLINE
              //                   AND ITXVIEWLAPMASUKGREIGE.PROVISIONALCOUNTERCODE  = STOCKTRANSACTION.ORDERCOUNTERCODE  
              //                   AND ITXVIEWLAPMASUKGREIGE.ITEMTYPEAFICODE = STOCKTRANSACTION.ITEMTYPECODE 
              //                   AND ITXVIEWLAPMASUKGREIGE.SUBCODE01= STOCKTRANSACTION.DECOSUBCODE01
              //                   AND ITXVIEWLAPMASUKGREIGE.SUBCODE02= STOCKTRANSACTION.DECOSUBCODE02
              //                   AND ITXVIEWLAPMASUKGREIGE.SUBCODE03= STOCKTRANSACTION.DECOSUBCODE03
              //                   AND ITXVIEWLAPMASUKGREIGE.SUBCODE04= STOCKTRANSACTION.DECOSUBCODE04
              //                   WHERE STOCKTRANSACTION.PROJECTCODE='$r[proj_awal]' AND 
              //                   CONCAT(TRIM(STOCKTRANSACTION.DECOSUBCODE02),CONCAT(TRIM(STOCKTRANSACTION.DECOSUBCODE03),CONCAT(' ',TRIM(STOCKTRANSACTION.DECOSUBCODE04))))='$r[no_item]' and 
              //                   STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M021' 
              //                   AND NOT ITXVIEWLAPMASUKGREIGE.ORDERLINE IS NULL
              //                   GROUP BY 
              //                   STOCKTRANSACTION.DECOSUBCODE02,STOCKTRANSACTION.DECOSUBCODE03,STOCKTRANSACTION.DECOSUBCODE04,STOCKTRANSACTION.LOGICALWAREHOUSECODE
              //                   ";
              // $stmt11   = db2_exec($conn1, $sqlDB211, array('cursor' => DB2_SCROLLABLE));
              // $rowdb211 = db2_fetch_assoc($stmt11);

              //query jenis benang yang di gunakan
              $sqlbenang = sqlsrv_query($con,"SELECT *  
              FROM dbnow_gkg.tbl_knitting_order where ko_no ='$r[proj_awal]' 
              ");		  
              $r2 = sqlsrv_fetch_array($sqlbenang);


              $sqlDB28 = " SELECT a.VALUEDECIMAL  FROM PRODUCT p 
LEFT OUTER JOIN ADSTORAGE a  ON a.UNIQUEID = p.ABSUNIQUEID 
WHERE CONCAT(TRIM(p.SUBCODE02), TRIM(p.SUBCODE03)) ='$r[no_item]' AND
a.NAMENAME ='Width' AND
p.ITEMTYPECODE ='KFF'  ";
              $stmt8   = db2_exec($conn1, $sqlDB28, array('cursor' => DB2_SCROLLABLE));
              $rowdb28 = db2_fetch_assoc($stmt8);
              $sqlDB29 = " SELECT a.VALUEDECIMAL  FROM PRODUCT p 
LEFT OUTER JOIN ADSTORAGE a  ON a.UNIQUEID = p.ABSUNIQUEID 
WHERE CONCAT(TRIM(p.SUBCODE02), TRIM(p.SUBCODE03))='$r[no_item]' AND
a.NAMENAME ='GSM' AND
p.ITEMTYPECODE ='KFF'  ";
              $stmt9   = db2_exec($conn1, $sqlDB29, array('cursor' => DB2_SCROLLABLE));
              $rowdb29 = db2_fetch_assoc($stmt9);
            ?>
              <tr>                
                <td style="text-align: left"><?php echo $r2['buyer']; ?></td>
                <td style="text-align: left"><?php echo $r2['benang_1']; ?></td>
                <td style="text-align: left"><?php echo $r2['benang_2']; ?></td>
                <td style="text-align: left"><?php echo $r2['benang_3']; ?></td>
                <td style="text-align: left"><?php echo $r2['benang_4']; ?></td>
                <td style="text-align: center"><?php echo $r['no_item']; ?></td>
                <td style="text-align: center"><?php echo round($rowdb28['VALUEDECIMAL']); ?></td>
                <td style="text-align: center"><?php echo round($rowdb29['VALUEDECIMAL']); ?></td>  
                <td style="text-align: center">INDOTAICHEN</td>
                <td style="text-align: center"><?php echo $r['proj_awal']; ?></td>
                <td style="text-align: center"><?php echo $r['roll']; ?></td>
                <td style="text-align: right"><?php echo $r['kgs']; ?></td>
                <td style="text-align: center"><?php echo $r['tahun_mati']; ?></td>
                
              </tr>
            <?php
              $no++;
              $totrol = $totrol + $r['roll'];
              $totkg = $totkg + $r['kgs'];             
            }
            ?>
          </tbody>
          <tfoot>
            <tr>              
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td colspan="4" style="text-align: right"><strong>TOTAL</strong></td>
              <td style="text-align: center"><span style="text-align: right"><strong><?php echo $totrol; ?></strong></span></td>
              <td style="text-align: right"><strong><?php echo number_format(round($totkg, 3), 3); ?></strong></td> 
              <td style="text-align: center">&nbsp;</td>
            </tr>
          </tfoot>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
    <?php } ?>
</div><!-- /.container-fluid -->
<!-- /.content -->
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