<?php
include "../koneksi.php";
$Awal  = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
?>
<!-- Main content -->
<div class="container-fluid">
  <form role="form" method="post" enctype="multipart/form-data" name="form1">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Filter Data Stock Kain Greige</h3>

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
              <input name="tgl_awal" value="<?php echo $Awal; ?>" type="text" class="form-control form-control-sm" id="" autocomplete="off" required>
            </div>
          </div>
        </div>
        <!-- <button class="btn btn-info" type="submit">Cari Data</button> -->

        <!-- <button class="btn btn-info" type="submit">Cari Data</button> -->
      <form method="POST">
        <button type="submit" name="submit" class="btn btn-success">
          <i class="icofont icofont-search-alt-1"></i> Cari data
        </button>
      </form>

      <?php
      include 'koneksi.php'; // koneksi ke SQL Server

      $ipaddress = $_SERVER['REMOTE_ADDR'];

      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
          if (filter_var($ipaddress, FILTER_VALIDATE_IP)) {

              // Siapkan parameter
              $params = array($ipaddress);

              // Hapus dari tabel pertama
              $sql1 = "DELETE FROM dbnow_gkg.tbl_stock_excel WHERE ip_address = ?";
              $stmt1 = sqlsrv_query($con, $sql1, $params);

              if ($stmt1 === false) {
                  echo "<div class='alert alert-danger'>Gagal menghapus dari tbl_stock_excel:</div>";
                  echo "<pre>"; print_r(sqlsrv_errors()); echo "</pre>";
              } else {
                  sqlsrv_free_stmt($stmt1);
              }

          } else {
              echo "<div class='alert alert-warning'>IP address tidak valid.</div>";
          }
      }
      ?>
      </div>
      <!-- /.card-body -->
    </div>
  </form>
  <?php if ($Awal != "") { ?>
    <div class="card card-pink">
      <div class="card-header">
        <h3 class="card-title">Stock</h3>
            <!-- <a href="pages/cetak/stock_pdf.php?awal=<?php echo $Awal; ?>" class="btn bg-green float-right me-2" target="_blank">Print</a> -->
            <a href="pages/cetak/stock_excel.php?awal=<?php echo $Awal; ?>" class="btn bg-blue float-right" style="margin-right: 10px;" target="_blank">Print</a>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="example1" class="table table-sm table-bordered table-striped" style="font-size:13px;">
          <thead>
            <tr>
              <th rowspan="2" style="text-align: center">Langganan</th>
              <th rowspan="2" style="text-align: center">Buyer</th>
              <th rowspan="2" style="text-align: center">Project Akhir</th>
              <th rowspan="2" style="text-align: center">Project Awal</th>
              <th rowspan="2" style="text-align: center">Lot</th>
              <th rowspan="2" style="text-align: center">Tipe</th>
              <th rowspan="2" style="text-align: center">No Item</th>
              <th colspan="2" style="text-align: center">Kain Jadi</th>
              <th colspan="4" style="text-align: center">Jenis Benang</th>
              <th rowspan="2" style="text-align: center">Permintaan Rajut</th>
              <th rowspan="2" style="text-align: center">Qty Selesai Rajut</th>
              <th rowspan="2" style="text-align: center">Stock GKG</th>
              <th rowspan="2" style="text-align: center">Roll</th>
              <th rowspan="2" style="text-align: center">Alokasi</th>
              <th rowspan="2" style="text-align: center">No BON</th>
            </tr>
            <tr>
              <th style="text-align: center">Lebar</th>
              <th style="text-align: center">Gramasi</th>
              <th style="text-align: center">1</th>
              <th style="text-align: center">2</th>
              <th style="text-align: center">3</th>
              <th style="text-align: center">4</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            $c = 0;
            $QTPR = 0;
             
                $sql = sqlsrv_query($con, "SELECT
                      MIN(id) as id,
                      TRIM(proj_awal) as proj_awal,
                      TRIM(no_item) as no_item,
                      MAX(langganan) AS langganan,
                      MAX(buyer) AS buyer,
                      MAX(lot) AS lot,
                      MAX(tipe) AS tipe,
                      MAX(benang_1) AS benang_1,
                      MAX(benang_2) AS benang_2,
                      MAX(benang_3) AS benang_3,
                      MAX(benang_4) AS benang_4,
                      SUM(weight) AS kgs,
                      SUM(rol) AS roll,  
                      tgl_tutup
                  FROM
                      dbnow_gkg.tblopname
                  WHERE
                      tgl_tutup = ?
                  GROUP BY
                      proj_awal, no_item, tgl_tutup
                  ORDER BY id ASC", [$Awal]);

                  // if ($sql === false) {
                  //     die(print_r(sqlsrv_errors(), true));
                  // }

            while ($r = sqlsrv_fetch_array($sql)) {
              $sql1 = mysqli_query($con1,"SELECT sum(berat) as KGs, group_concat(no_bon,':',berat,' ') as no_bon  
                                          FROM dbknitt.tbl_pembagian_greige_now where no_po ='$r[proj_awal]' and no_artikel='$r[no_item]' 
                                          ");		  
                                          $r1 = mysqli_fetch_array($sql1);

              // Query penyebab lemot
              $sqlDB210 = "SELECT SUM(a.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY, SUM(a3.VALUEDECIMAL) AS QTYSALIN  FROM ITXVIEWHEADERKNTORDER a
                LEFT OUTER JOIN PRODUCTIONDEMAND p ON p.CODE =a.PRODUCTIONDEMANDCODE
                LEFT OUTER JOIN ADSTORAGE a2 ON p.ABSUNIQUEID =a2.UNIQUEID AND a2.NAMENAME ='StatusRMP'
                LEFT OUTER JOIN ADSTORAGE a3 ON p.ABSUNIQUEID =a3.UNIQUEID AND a3.NAMENAME ='QtySalin'
                WHERE a.ITEMTYPEAFICODE ='KGF' AND (a.PROJECTCODE ='$r[proj_awal]' OR a.ORIGDLVSALORDLINESALORDERCODE ='$r[proj_awal]') AND
                (a.PROGRESSSTATUS='2' OR a.PROGRESSSTATUS='6') AND (NOT a2.VALUESTRING ='3' OR a2.VALUESTRING IS NULL) AND 
                CONCAT(TRIM(a.SUBCODE02),CONCAT(TRIM(a.SUBCODE03),CONCAT(' ',TRIM(a.SUBCODE04))))='$r[no_item]'
                GROUP BY a.SUBCODE02,a.SUBCODE03,a.SUBCODE04";
              // End Query

              $stmt10   = db2_exec($conn1, $sqlDB210, array('cursor' => DB2_SCROLLABLE));
              $rowdb210 = db2_fetch_assoc($stmt10);
              
              // Penyebab lemotnya ini karena di tiap rownya harus ngebaca ulang ini
              // Mungkin solusinya bisa buat query kepisah untuk ambil value qtpr
              $QTPR = $rowdb210['BASEPRIMARYQUANTITY'] - $rowdb210['QTYSALIN'];
              // End Penyebab Lemot

              $sqlDB211 = "SELECT SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_ROL  
                                FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION LEFT OUTER JOIN
                                DB2ADMIN.ITXVIEWLAPMASUKGREIGE ITXVIEWLAPMASUKGREIGE ON ITXVIEWLAPMASUKGREIGE.PROVISIONALCODE  = STOCKTRANSACTION.ORDERCODE
                                AND ITXVIEWLAPMASUKGREIGE.ORDERLINE  = STOCKTRANSACTION.ORDERLINE
                                AND ITXVIEWLAPMASUKGREIGE.PROVISIONALCOUNTERCODE  = STOCKTRANSACTION.ORDERCOUNTERCODE  
                                AND ITXVIEWLAPMASUKGREIGE.ITEMTYPEAFICODE = STOCKTRANSACTION.ITEMTYPECODE 
                                AND ITXVIEWLAPMASUKGREIGE.SUBCODE01= STOCKTRANSACTION.DECOSUBCODE01
                                AND ITXVIEWLAPMASUKGREIGE.SUBCODE02= STOCKTRANSACTION.DECOSUBCODE02
                                AND ITXVIEWLAPMASUKGREIGE.SUBCODE03= STOCKTRANSACTION.DECOSUBCODE03
                                AND ITXVIEWLAPMASUKGREIGE.SUBCODE04= STOCKTRANSACTION.DECOSUBCODE04
                                WHERE STOCKTRANSACTION.PROJECTCODE='$r[proj_awal]' AND 
                                CONCAT(TRIM(STOCKTRANSACTION.DECOSUBCODE02),CONCAT(TRIM(STOCKTRANSACTION.DECOSUBCODE03),CONCAT(' ',TRIM(STOCKTRANSACTION.DECOSUBCODE04))))='$r[no_item]' and 
                                STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M021' 
                                AND NOT ITXVIEWLAPMASUKGREIGE.ORDERLINE IS NULL
                                GROUP BY 
                                STOCKTRANSACTION.DECOSUBCODE02,STOCKTRANSACTION.DECOSUBCODE03,STOCKTRANSACTION.DECOSUBCODE04,STOCKTRANSACTION.LOGICALWAREHOUSECODE
                                ";
              $stmt11   = db2_exec($conn1, $sqlDB211, array('cursor' => DB2_SCROLLABLE));
              $rowdb211 = db2_fetch_assoc($stmt11);

              $sqlDB28 = " SELECT a.VALUEDECIMAL  FROM PRODUCT p 
LEFT OUTER JOIN ADSTORAGE a  ON a.UNIQUEID = p.ABSUNIQUEID 
WHERE CONCAT(TRIM(p.SUBCODE02),CONCAT(TRIM(p.SUBCODE03),CONCAT(' ',TRIM(p.SUBCODE04))))='$r[no_item]' AND
a.NAMENAME ='Width' AND
p.ITEMTYPECODE ='KFF'  ";
              $stmt8   = db2_exec($conn1, $sqlDB28, array('cursor' => DB2_SCROLLABLE));
              $rowdb28 = db2_fetch_assoc($stmt8);
              $sqlDB29 = " SELECT a.VALUEDECIMAL  FROM PRODUCT p 
LEFT OUTER JOIN ADSTORAGE a  ON a.UNIQUEID = p.ABSUNIQUEID 
WHERE CONCAT(TRIM(p.SUBCODE02),CONCAT(TRIM(p.SUBCODE03),CONCAT(' ',TRIM(p.SUBCODE04))))='$r[no_item]' AND
a.NAMENAME ='GSM' AND
p.ITEMTYPECODE ='KFF'  ";
              $stmt9   = db2_exec($conn1, $sqlDB29, array('cursor' => DB2_SCROLLABLE));
              $rowdb29 = db2_fetch_assoc($stmt9);
            ?>
              <tr>
                <td style="text-align: left"><?php echo $r['langganan']; ?></td>
                <td style="text-align: left"><?php echo $r['buyer']; ?></td>
                <td style="text-align: center"><?php echo $r['proj_akhir ']; ?></td>
                <td style="text-align: center"><?php echo $r['proj_awal']; ?></td>
                <td style="text-align: center"><?php echo $r['lot']; ?></td>
                <td style="text-align: center"><?php echo $r['tipe']; ?></td>
                <td style="text-align: center"><?php echo $r['no_item']; ?></td>
                <td style="text-align: center"><?php echo round($rowdb28['VALUEDECIMAL']); ?></td>
                <td style="text-align: center"><?php echo round($rowdb29['VALUEDECIMAL']); ?></td>
                <td style="text-align: left"><?php echo $r['benang_1']; ?></td>
                <td style="text-align: left"><?php echo $r['benang_2']; ?></td>
                <td style="text-align: left"><?php echo $r['benang_3']; ?></td>
                <td style="text-align: left"><?php echo $r['benang_4']; ?></td>
                <td style="text-align: right"><?php echo round($QTPR, 3); ?></td>
                <td style="text-align: right"><?php echo round($rowdb211['QTY_KG'], 3); ?></td>
                <td style="text-align: right"><?php echo $r['kgs']; ?></td>
                <td style="text-align: center"><?php echo $r['roll']; ?></td>
                <td style="text-align: right"><?php if ($r1['KGs'] > 0) {
                                                echo $r1['KGs'];
                                              } else {
                                                echo "0";
                                              } ?></td>
                <td style="text-align: left"><?php if ($r1['KGs'] > 0) {
                                                echo $r1['no_bon'];
                                              } else {
                                                echo "";
                                              } ?></td>
              </tr>
            <?php
              $no++;
              $totrol = $totrol + $r['roll'];
              $totkg = $totkg + $r['kgs'];
              $totpr = $totpr + $QTPR;
              $totsr = $totsr + $rowdb211['QTY_KG'];
              $totlk = $totlk + $r1['KGs'];

              $ipaddress = $_SERVER['REMOTE_ADDR'];
              include_once("koneksi.php");

              $sql2 = "INSERT INTO dbnow_gkg.tbl_stock_excel (
                tgl_tutup,
                langganan,
                buyer,
                proj_akhir,
                proj_awal,
                lot,
                tipe,
                no_item,
                lebar,
                gramasi,
                benang_1,
                benang_2,
                benang_3,
                benang_4,
                qty_kg,
                qty_roll,
                ip_address
              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

              $params = [
                $r['tgl_tutup'],
                $r['langganan'],
                $r['buyer'],
                $r['proj_akhir'],
                $r['proj_awal'],
                $r['lot'],
                $r['tipe'],
                $r['no_item'],
                $rowdb28['VALUEDECIMAL'],
                $rowdb29['VALUEDECIMAL'],
                $r['benang_1'],
                $r['benang_2'],
                $r['benang_3'],
                $r['benang_4'],
                $r['kgs'],
                $r['roll'],
                $ipaddress
              ];

              $result = sqlsrv_query($con, $sql2, $params);

              if (!$result) {
                  die(print_r(sqlsrv_errors(), true));
              }
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
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td colspan="4" style="text-align: right"><strong>TOTAL</strong></td>
              <td style="text-align: right"><strong><?php echo number_format(round($totpr, 3), 3); ?></strong></td>
              <td style="text-align: right"><strong><?php echo number_format(round($totsr, 3), 3); ?></strong></td>
              <td style="text-align: right"><strong><?php echo number_format(round($totkg, 3), 3); ?></strong></td>
              <td style="text-align: center"><span style="text-align: right"><strong><?php echo $totrol; ?></strong></span></td>
              <td style="text-align: right"><strong><?php echo number_format(round($totlk, 3), 3); ?></strong></td>
              <td style="text-align: right">&nbsp;</td>
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