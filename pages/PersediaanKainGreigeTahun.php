<?php
$sqlDB21 = " SELECT ITEMTYPECODE,THN, SUM(STK.BERAT) AS KG,LISTAGG(NOITEM, ',') AS NOITEM FROM (
        SELECT SUM(b.BASEPRIMARYQUANTITYUNIT) AS BERAT,
        b.ITEMTYPECODE,SUBSTR(b.PROJECTCODE,4,2) AS THN,
        TRIM(b.DECOSUBCODE02) || TRIM(b.DECOSUBCODE03) AS NOITEM
        FROM BALANCE b 
        LEFT OUTER JOIN (
        SELECT
            STOCKTRANSACTION.ORDERCODE,      
            STOCKTRANSACTION.ORDERLINE,
            STOCKTRANSACTION.ITEMELEMENTCODE,
            ITXVIEWBUKMUTGKGKNT.PROJECTCODE
        FROM
            STOCKTRANSACTION STOCKTRANSACTION
        LEFT JOIN INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE 
        ON
            STOCKTRANSACTION.ORDERCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE
            AND 
        STOCKTRANSACTION.ORDERLINE = INTERNALDOCUMENTLINE.ORDERLINE
        LEFT JOIN ITXVIEWBUKMUTGKGKNT ITXVIEWBUKMUTGKGKNT 
        ON
            INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE = ITXVIEWBUKMUTGKGKNT.INTDOCUMENTPROVISIONALCODE
            AND 
        INTERNALDOCUMENTLINE.ORDERLINE = ITXVIEWBUKMUTGKGKNT.ORDERLINE
        WHERE
            STOCKTRANSACTION.ORDERCOUNTERCODE = 'I02M50' 
        GROUP BY
            STOCKTRANSACTION.ORDERCODE,
            STOCKTRANSACTION.ORDERLINE,
            STOCKTRANSACTION.ITEMELEMENTCODE,
            ITXVIEWBUKMUTGKGKNT.PROJECTCODE) prj ON b.ELEMENTSCODE = prj.ITEMELEMENTCODE
        LEFT OUTER JOIN (
        SELECT
            STOCKTRANSACTION.ORDERCODE,
            STOCKTRANSACTION.ORDERLINE,
            STOCKTRANSACTION.ITEMELEMENTCODE,
            STOCKTRANSACTION.PROJECTCODE
        FROM
            STOCKTRANSACTION STOCKTRANSACTION 
        WHERE
            STOCKTRANSACTION.TEMPLATECODE = 'OPN' AND STOCKTRANSACTION.ITEMTYPECODE='KGF'  
        GROUP BY
            STOCKTRANSACTION.ORDERCODE,
            STOCKTRANSACTION.ORDERLINE,
            STOCKTRANSACTION.ITEMELEMENTCODE,
            STOCKTRANSACTION.PROJECTCODE) prj1 ON b.ELEMENTSCODE = prj1.ITEMELEMENTCODE
        LEFT OUTER JOIN (
        SELECT
            STOCKTRANSACTION.ORDERCODE,
            STOCKTRANSACTION.ORDERLINE,
            STOCKTRANSACTION.ITEMELEMENTCODE,
            STOCKTRANSACTION.PROJECTCODE,
            STOCKTRANSACTION.PRODUCTIONORDERCODE,
            PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE 
        FROM
            STOCKTRANSACTION STOCKTRANSACTION
        LEFT OUTER JOIN PRODUCTIONDEMAND PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE=STOCKTRANSACTION.ORDERCODE 
        WHERE
            STOCKTRANSACTION.TEMPLATECODE = '110' AND STOCKTRANSACTION.ITEMTYPECODE='FKG'  
        GROUP BY
            STOCKTRANSACTION.ORDERCODE,
            STOCKTRANSACTION.ORDERLINE,
            STOCKTRANSACTION.ITEMELEMENTCODE,
            STOCKTRANSACTION.PROJECTCODE,
            STOCKTRANSACTION.PRODUCTIONORDERCODE,
            PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE) prj2 ON b.ELEMENTSCODE = prj2.ITEMELEMENTCODE     
        WHERE (b.ITEMTYPECODE='FKG' OR b.ITEMTYPECODE='KGF') AND b.LOGICALWAREHOUSECODE='M021' AND LENGTH(trim(b.PROJECTCODE)) = '10'
        GROUP BY b.ITEMTYPECODE,SUBSTR(b.PROJECTCODE,4,2),b.ITEMTYPECODE,b.PROJECTCODE,b.DECOSUBCODE02,b.DECOSUBCODE03 ) STK
        GROUP BY ITEMTYPECODE,THN ";
$stmt1 = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));

$rowTemp = [];
while ($rowdb21 = db2_fetch_assoc($stmt1)) {
    $rowTemp[] = $rowdb21;
}
?>

<!-- Main content -->
<div class="container-fluid">
    <div class="card card-pink">
        <div class="card-header">
            <h3 class="card-title">Stock</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-sm table-bordered table-striped" style="font-size:13px;">
                <thead>
                    <tr>
                        <th style="text-align: center">Tipe</th>
                        <th style="text-align: center">Tahun</th>
                        <th style="text-align: center">Weight (Kg)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($rowTemp as $value) {
                        if ($value['ITEMTYPECODE'] == "KGF") {
                            $jns = "KAIN";
                        } else if ($value['ITEMTYPECODE'] == "FKG") {
                            $jns = "KRAH";
                        }
                        ?>
                        <tr>
                            <td style="text-align: center"><?php echo $jns; ?></td>
                            <td style="text-align: center"><?php echo $value['THN']; ?></td>
                            <td style="text-align: right"><?php echo $value['KG']; ?></td>
                        </tr>
                        <?php
                        $totkg = $totkg + $value['KG'];
                    } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td style="text-align: center">&nbsp;</td>
                        <td style="text-align: center"><span style="text-align: right"><strong>TOTAL</strong></span>
                        </td>
                        <td style="text-align: right"><strong><?php echo round($totkg, 3); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.card-body -->
    </div>

    <div class="card card-red">
        <div class="card-header">
            <h3 class="card-title">Stock Item</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example2" class="table table-sm table-bordered table-striped" style="font-size:13px;">
                <thead>
                    <tr>
                        <th style="text-align: center">Item</th>
                        <th style="text-align: center">Weight (Kg)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rowTemp as $value) { ?>
                        <tr>
                            <td style="text-align: center"><?php echo implode(', ', explode(',', $value['NOITEM'])); ?>
                            </td>
                            <td style="text-align: right"><?php echo $value['KG']; ?></td>
                        </tr>
                        <?php $totkg2 = $totkg2 + $value['KG'];
                    } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td style="text-align: center"><span style="text-align: right"><strong>TOTAL</strong></span>
                        </td>
                        <td style="text-align: right"><strong><?php echo round($totkg, 3); ?></strong></td>
                    </tr>
                </tfoot>
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