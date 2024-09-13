<?php
$User = isset($_POST['user']) ? $_POST['user'] : '';
$Awal = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
?>
<title>nilo</title>
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
                    <label for="tgl_awal" class="col-md-1">Tgl Awal</label>
                    <div class="col-md-2">
                        <div class="input-group date" id="datepicker1" data-target-input="nearest">
                            <div class="input-group-prepend" data-target="#datepicker1" data-toggle="datetimepicker">
                                <span class="input-group-text btn-info">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input name="tgl_awal" value="<?php echo $Awal; ?>" type="text"
                                class="form-control form-control-sm" id="" autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="user" class="col-md-1">User</label>
                    <div class="col-md-2">
                        <input name="user" value="<?php echo $User; ?>" type="text"
                            class="form-control form-control-sm" id="" autocomplete="off">
                    </div>
                </div>
                <button class="btn btn-info" type="submit" value="Cari" name="cari">Cari Data</button>
            </div>

            <!-- /.card-body -->

        </div>
    </form>

    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">Data Change Location GKG</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example19" class="table table-sm table-bordered table-striped">
                <thead>
                    <tr>
                        <th valign="middle" style="text-align: center">Article Group</th>
                        <th valign="middle" style="text-align: center">Article Code</th>
                        <th valign="middle" style="text-align: center">Variant</th>
                        <th valign="middle" style="text-align: center">Lgl whs</th>
                        <th valign="middle" style="text-align: center">phy whs</th>
                        <th valign="middle" style="text-align: center">Project</th>
                        <th valign="middle" style="text-align: center">Date / Time</th>
                        <th valign="middle" style="text-align: center">User</th>
                        <th valign="middle" style="text-align: center">Lokasi Awal</th>
                        <th valign="middle" style="text-align: center">Lokasi Terakhir</th>
                        <th valign="middle" style="text-align: center">Quantity</th>
                        <th valign="middle" style="text-align: center">LOT</th>
                        <th valign="middle" style="text-align: center">Elements</th>
                        <th valign="middle" style="text-align: center">Base Primary Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($Awal != "") {
                        $tanggal_tambah_1_hari = date('Y-m-d', strtotime($Awal . ' +1 day'));
                        $tanggal_waktu1 = $Awal . " " . "07:00:00";
                        $tanggal_waktu2 = $tanggal_tambah_1_hari . " " . "07:00:00";

                        $WhereUser = $User != "" ? " AND CREATIONUSER LIKE '%$User%' " : "";
                        $queryDB2 = "SELECT DISTINCT 
                                        ITEMELEMENTCODE
                                    FROM
                                        STOCKTRANSACTION
                                    WHERE
                                        ITEMTYPECODE = 'KGF'
                                        AND LOGICALWAREHOUSECODE = 'M021'
                                        AND (TRIM(TRANSACTIONDATE) || ' ' || TRIM(TRANSACTIONTIME) BETWEEN '$tanggal_waktu1' AND '$tanggal_waktu2') 
                                        $WhereUser";
                        $stmt = db2_exec($conn1, $queryDB2, array('cursor' => DB2_SCROLLABLE));

                        $totalQuality = 0;
                        $totalBasePrimaryQuantity = 0;
                        while ($rowdb2 = db2_fetch_assoc($stmt)) {

                            $queryDB21 = "SELECT DISTINCT
                                        s.DECOSUBCODE02,
                                        s.DECOSUBCODE03,
                                        s.DECOSUBCODE04,
                                        s.LOGICALWAREHOUSECODE ,
                                        s.PHYSICALWAREHOUSECODE,
                                        s.PROJECTCODE,
                                        s.TRANSACTIONDATE || ' ' || SEBELUM.TRANSACTIONTIME AS TANGGAL_WAKTU,
                                        s.CREATIONUSER,
                                        SEBELUM.WAREHOUSELOCATIONCODE AS SEBELUM,
                                        s.WAREHOUSELOCATIONCODE AS SESUDAH,
                                        s.QUALITYLEVELCODE,
                                        s.LOTCODE,
                                        s.ITEMELEMENTCODE,
                                        s.BASEPRIMARYQUANTITY,
                                        SEBELUM.NUMBEROFPIECES 
                                    FROM
                                        STOCKTRANSACTION s
                                    LEFT JOIN (
                                        SELECT
                                            DISTINCT
                                            s.TRANSACTIONDATE,
                                            s.TRANSACTIONTIME,
                                            s.TRANSACTIONNUMBER,
                                            s.DECOSUBCODE02,
                                            s.DECOSUBCODE03,
                                            s.DECOSUBCODE04,
                                            s.WHSLOCATIONWAREHOUSEZONECODE,
                                            s.WAREHOUSELOCATIONCODE,
                                            e.NUMBEROFPIECES 
                                        FROM
                                            STOCKTRANSACTION s
                                            LEFT JOIN ELEMENTSINSPECTION e 
                                                ON s.ITEMELEMENTCODE = e.ELEMENTCODE 
                                        WHERE
                                            s.TEMPLATECODE = '301'
                                            AND s.ITEMTYPECODE = 'KGF'
                                            AND s.LOGICALWAREHOUSECODE = 'M021'
                                            AND (TRIM(s.TRANSACTIONDATE) || ' ' || TRIM(s.TRANSACTIONTIME) BETWEEN '$tanggal_waktu1' AND '$tanggal_waktu2')
                                            AND s.ITEMELEMENTCODE = '$rowdb2[ITEMELEMENTCODE]' $WhereProject
                                        ORDER BY
                                            s.TRANSACTIONDATE DESC
                                        LIMIT 1) AS SEBELUM ON
                                        SEBELUM.TRANSACTIONNUMBER = S.TRANSACTIONNUMBER
                                    WHERE
                                        s.TEMPLATECODE = '302'
                                        AND s.ITEMTYPECODE = 'KGF'
                                        AND s.LOGICALWAREHOUSECODE = 'M021'
                                        AND (TRIM(s.TRANSACTIONDATE) || ' ' || TRIM(s.TRANSACTIONTIME) BETWEEN '$tanggal_waktu1' AND '$tanggal_waktu2')
                                        AND s.ITEMELEMENTCODE = '$rowdb2[ITEMELEMENTCODE]' $WhereProject
                                    LIMIT 1";
                            $stmt1 = db2_exec($conn1, $queryDB21, array('cursor' => DB2_SCROLLABLE));
                            $rowdb21 = db2_fetch_assoc($stmt1);

                            if ($rowdb21 != NULL) {
                                ?>
                                <tr>
                                    <td><?= $rowdb21['DECOSUBCODE02'] ?></td>
                                    <td><?= $rowdb21['DECOSUBCODE03'] ?></td>
                                    <td><?= $rowdb21['DECOSUBCODE04'] ?></td>
                                    <td><?= $rowdb21['LOGICALWAREHOUSECODE'] ?></td>
                                    <td><?= $rowdb21['PHYSICALWAREHOUSECODE'] ?></td>
                                    <td><?= $rowdb21['PROJECTCODE'] ?></td>
                                    <td><?= $rowdb21['TANGGAL_WAKTU'] ?></td>
                                    <td><?= $rowdb21['CREATIONUSER'] ?></td>
                                    <td><?= $rowdb21['SEBELUM'] ?></td>
                                    <td><?= $rowdb21['SESUDAH'] ?></td>
                                    <td><?= $rowdb21['NUMBEROFPIECES'] ?? 1 ?></td>
                                    <td><?= $rowdb21['LOTCODE'] ?></td>
                                    <td><?= $rowdb21['ITEMELEMENTCODE'] ?></td>
                                    <td><?= $rowdb21['BASEPRIMARYQUANTITY'] ?></td>
                                </tr>
                                <?php
                            }
                            $totalQuality += $rowdb21['NUMBEROFPIECES'] ?? 1;
                            $totalBasePrimaryQuantity += $rowdb21['BASEPRIMARYQUANTITY'];
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td style="text-align: right">&nbsp;</td>
                        <td style="text-align: right">&nbsp;</td>
                        <td style="text-align: right">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td><?= $totalQuality ?></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td><?= $totalBasePrimaryQuantity ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
</div><!-- /.container-fluid -->