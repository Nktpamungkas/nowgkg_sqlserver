<?php
$NoBon  = isset($_POST['nobon']) ? $_POST['nobon'] : '';
$NoLine  = isset($_POST['lineno']) ? $_POST['lineno'] : '';
?>
<!-- Main content -->
<div class="container-fluid">
  <form role="form" method="post" enctype="multipart/form-data" name="form1">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Filter Data Greige Masuk Dari KNT</h3>

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
          <label for="nobon" class="col-md-1">No Bon</label>
          <div class="col-md-2">
            <input name="nobon" value="<?php echo $NoBon; ?>" type="text" class="form-control form-control-sm" id="" autocomplete="off" required>
          </div>
        </div>
        <div class="form-group row">
          <label for="lineno" class="col-md-1">No Line</label>
          <div class="col-md-1">
            <input name="lineno" value="<?php echo $NoLine; ?>" type="text" class="form-control form-control-sm" id="" autocomplete="off" required>
          </div>
        </div>

        <button class="btn btn-info" type="submit">Cari Data</button>
      </div>
      <!-- /.card-body -->
    </div>

    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Detail Data Greige Masuk Dari KNT</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
          <thead>
            <tr>
              <th rowspan="2" valign="middle" style="text-align: center">No</th>
              <th rowspan="2" valign="middle" style="text-align: center">TGLBuat KNT</th>
              <th rowspan="2" valign="middle" style="text-align: center">No BON</th>
              <th rowspan="2" valign="middle" style="text-align: center">Jenis Benang</th>
              <th rowspan="2" valign="middle" style="text-align: center">No. Hanger</th>
              <th rowspan="2" valign="middle" style="text-align: center">Lot</th>
              <th rowspan="2" valign="middle" style="text-align: center">Elements</th>
              <th colspan="3" valign="middle" style="text-align: center">Change Location</th>
              <th colspan="2" valign="middle" style="text-align: center">Balance</th>
              <th rowspan="2" valign="middle" style="text-align: center">Status</th>
            </tr>
            <tr>
              <th valign="middle" style="text-align: center">Berat/Kg</th>
              <th valign="middle" style="text-align: center">Awal</th>
              <th valign="middle" style="text-align: center">Akhir</th>
              <th valign="middle" style="text-align: center">Berat/Kg</th>
              <th valign="middle" style="text-align: center">Block</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $no = 1;
            $c = 0;

            $sqlDB21 = "SELECT
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
                        FROM
                          DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION
                          LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
                        WHERE
                          STOCKTRANSACTION.LOGICALWAREHOUSECODE = 'M502'
                          AND STOCKTRANSACTION.ORDERCODE = '$NoBon'
                          AND STOCKTRANSACTION.ORDERLINE = '$NoLine'
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
                          FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION";
//            $stmt1   = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));
            $stmt1   = db2_prepare($conn1, $sqlDB21);
			db2_execute($stmt1);  
            //}		
            $knitt = "";
            while ($rowdb21 = db2_fetch_assoc($stmt1)) {
              $bon = $rowdb21['ORDERCODE'] . "-" . $rowdb21['ORDERLINE'];
               $sqlDB22 = "SELECT
                            STOCKTRANSACTION.TRANSACTIONDATE,
                            STOCKTRANSACTION.LOGICALWAREHOUSECODE,
                            STOCKTRANSACTION.ITEMELEMENTCODE,
                            STOCKTRANSACTION.BASESECONDARYQUANTITY,
                            STOCKTRANSACTION.BASEPRIMARYQUANTITY,
                            STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
                            STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
                            STOCKTRANSACTION.LOTCODE
                          FROM
                            DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION
                          WHERE
                            STOCKTRANSACTION.LOGICALWAREHOUSECODE = 'M021'
                            AND STOCKTRANSACTION.TOKENCODE = 'RECEIPT'
                            AND STOCKTRANSACTION.TEMPLATECODE = '204'
                            AND STOCKTRANSACTION.ITEMELEMENTCODE = '$rowdb21[ITEMELEMENTCODE]'";
//              $stmt2   = db2_exec($conn1, $sqlDB22, array('cursor' => DB2_SCROLLABLE));
              $stmt2   = db2_prepare($conn1, $sqlDB22);
			  db2_execute($stmt2);	
              $rD = db2_fetch_assoc($stmt2);
              $awal_akhir = "SELECT
                        TEMPLATECODE,
                        WAREHOUSELOCATIONCODE,
                        TRANSACTIONDATE
                      FROM
                        DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION
                      WHERE
                        STOCKTRANSACTION.ITEMELEMENTCODE = '$rowdb21[ITEMELEMENTCODE]'
                        AND STOCKTRANSACTION.TEMPLATECODE IN ('301','302')
                      ORDER BY
                        STOCKTRANSACTION.TRANSACTIONDATE DESC
                      FETCH FIRST 2 ROWS ONLY";
//              $exce = db2_exec($conn1, $awal_akhir, array('cursor' => DB2_SCROLLABLE));
              $exce = db2_prepare($conn1, $awal_akhir);
			  db2_execute($exce);	
              $awal = "";
              $akhi = "";

              while ($row = db2_fetch_assoc($exce)) {
                if ($row['TEMPLATECODE'] == '301') {
                $awal = $row['WAREHOUSELOCATIONCODE'];
                } elseif ($row['TEMPLATECODE'] == '302') {
                $akhi = $row['WAREHOUSELOCATIONCODE'];
                }
              }
      

              $sqlDB23 = "SELECT BASEPRIMARYQUANTITYUNIT,BASESECONDARYQUANTITYUNIT,WHSLOCATIONWAREHOUSEZONECODE,WAREHOUSELOCATIONCODE 
                          FROM DB2ADMIN.BALANCE BALANCE  
                          WHERE BALANCE.LOGICALWAREHOUSECODE='M021' AND 
                          BALANCE.ELEMENTSCODE='$rowdb21[ITEMELEMENTCODE]'";
//              $stmt3   = db2_exec($conn1, $sqlDB23, array('cursor' => DB2_SCROLLABLE));
              $stmt3   = db2_prepare($conn1, $sqlDB23);
			  db2_execute($stmt3);	
              $rD1 = db2_fetch_assoc($stmt3);
              $sqlDB24 = "SELECT
                            SUM(BASEPRIMARYQUANTITYUNIT) AS BASEPRIMARYQUANTITYUNIT,
                            SUM(BASESECONDARYQUANTITYUNIT) AS BASESECONDARYQUANTITYUNIT,
                            WHSLOCATIONWAREHOUSEZONECODE,
                            WAREHOUSELOCATIONCODE,
                            COUNT(ELEMENTSCODE) AS ROL
                          FROM
                            DB2ADMIN.BALANCE BALANCE
                          WHERE
                            BALANCE.LOGICALWAREHOUSECODE = 'TR11'
                            AND BALANCE.ELEMENTSCODE = '$rowdb21[ITEMELEMENTCODE]'
                          GROUP BY
                            WHSLOCATIONWAREHOUSEZONECODE,
                            WAREHOUSELOCATIONCODE";
//              $stmt4   = db2_exec($conn1, $sqlDB24, array('cursor' => DB2_SCROLLABLE));
              $stmt4   = db2_prepare($conn1, $sqlDB24);
			  db2_execute($stmt4);	
              $rD2 = db2_fetch_assoc($stmt4);
             
                $stts = "";
                if (isset($awal) && $awal != "" && isset($akhi) && $akhi != "") {
                $stts = "<small class='badge badge-success'> OK</small>";
                } else {
                $stts = "<small class='badge badge-danger'> NOT OK</small>";
                }
            ?>
              <tr>
                <td style="text-align: center"><?php echo $no; ?></td>
                <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
                <td style="text-align: center"><?php echo $bon; ?></td>
                <td style="text-align: left"><?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?></td>
                <td style="text-align: center"><?php echo trim($rowdb21['DECOSUBCODE02']) . trim($rowdb21['DECOSUBCODE03']) . " " . $rowdb21['DECOSUBCODE04']; ?></td>
                <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
                <td style="text-align: right"><?php echo $rowdb21['ITEMELEMENTCODE']; ?></td>
                <td style="text-align: right"><?php echo number_format(round($rD['BASEPRIMARYQUANTITY'], 2), 2); ?></td>
                <!-- awal -->
                <td><?php echo $awal ?></td>
                <!-- akhir -->
                <td><?php echo $akhi ?></td>
                <td style="text-align: right"><?php echo number_format(round($rD1['BASEPRIMARYQUANTITYUNIT'], 2), 2); ?></td>
                <td><?php echo $rD1['WHSLOCATIONWAREHOUSEZONECODE'] . "-" . $rD1['WAREHOUSELOCATIONCODE']; ?></td>
                <td><?php echo $stts; ?></td>
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
            } ?>
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
              <td style="text-align: right"><strong><?php echo $TgkgKGS; ?></strong></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td style="text-align: right"><strong><?php echo $TblcKGS; ?></strong></td>
              <td>&nbsp;</td>
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