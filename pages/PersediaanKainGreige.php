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
                    <th rowspan="2" style="text-align: center">Langganan</th>
                    <th rowspan="2" style="text-align: center">Buyer</th>
                    <th rowspan="2" style="text-align: center">Project Akhir</th>
                    <th rowspan="2" style="text-align: center">Project Awal</th>
                    <th rowspan="2" style="text-align: center">Tipe</th>
                    <th rowspan="2" style="text-align: center">No Item</th>
                    <th rowspan="2" style="text-align: center">Item Description</th>
                    <th colspan="4" style="text-align: center">Jenis Benang</th>
                    <th rowspan="2" style="text-align: center">Prod. Order</th>
                    <th rowspan="2" style="text-align: center">Lot</th>
                    <th rowspan="2" style="text-align: center">Roll</th>
                    <th rowspan="2" style="text-align: center">Weight</th>
                    <th rowspan="2" style="text-align: center">Satuan</th>
                    <th rowspan="2" style="text-align: center">Zone</th>
                    <th rowspan="2" style="text-align: center">Lokasi</th>
                  </tr>
                  <tr>
                    <th style="text-align: center">1</th>
                    <th style="text-align: center">2</th>
                    <th style="text-align: center">3</th>
                    <th style="text-align: center">4</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php			
  set_time_limit(0);	  
   $no=1;   
   $c=0;
	//if($Zone=="" and $Lokasi==""){
	//	echo"<script>alert('Zone atau Lokasi belum dipilih');</script>";
	//}else {
	$sqlDB21 = " SELECT
              SUM(b.BASEPRIMARYQUANTITYUNIT) AS BERAT,
              SUM(b.BASESECONDARYQUANTITYUNIT) AS YD,
              COUNT(b.BASESECONDARYQUANTITYUNIT) AS ROLL,
              b.LOTCODE,
              b.PROJECTCODE,
              b.ITEMTYPECODE,
              b.DECOSUBCODE01,
              b.DECOSUBCODE02,
              b.DECOSUBCODE03,
              b.DECOSUBCODE04,
              b.DECOSUBCODE05,
              b.DECOSUBCODE06,
              b.DECOSUBCODE07,
              b.DECOSUBCODE08,
              b.BASEPRIMARYUNITCODE,
              b.BASESECONDARYUNITCODE,
              b.WHSLOCATIONWAREHOUSEZONECODE,
              b.WAREHOUSELOCATIONCODE,
              prj.PROJECTCODE AS PROJAWAL,
              prj1.PROJECTCODE AS PROJAWAL1,
              prj2.PRODUCTIONORDERCODE,
              prj2.ORIGDLVSALORDLINESALORDERCODE
            FROM
              BALANCE b
              LEFT OUTER JOIN (
                SELECT
                  STOCKTRANSACTION.ORDERCODE,
                  STOCKTRANSACTION.ORDERLINE,
                  STOCKTRANSACTION.ITEMELEMENTCODE,
                  ITXVIEWBUKMUTGKGKNT.PROJECTCODE
                FROM
                  STOCKTRANSACTION STOCKTRANSACTION
                  LEFT JOIN INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON STOCKTRANSACTION.ORDERCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE
                  AND STOCKTRANSACTION.ORDERLINE = INTERNALDOCUMENTLINE.ORDERLINE
                  LEFT JOIN ITXVIEWBUKMUTGKGKNT ITXVIEWBUKMUTGKGKNT ON INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE = ITXVIEWBUKMUTGKGKNT.INTDOCUMENTPROVISIONALCODE
                  AND INTERNALDOCUMENTLINE.ORDERLINE = ITXVIEWBUKMUTGKGKNT.ORDERLINE
                WHERE
                  STOCKTRANSACTION.ORDERCOUNTERCODE = 'I02M50'
                GROUP BY
                  STOCKTRANSACTION.ORDERCODE,
                  STOCKTRANSACTION.ORDERLINE,
                  STOCKTRANSACTION.ITEMELEMENTCODE,
                  ITXVIEWBUKMUTGKGKNT.PROJECTCODE
              ) prj ON b.ELEMENTSCODE = prj.ITEMELEMENTCODE
              LEFT OUTER JOIN (
                SELECT
                  STOCKTRANSACTION.ORDERCODE,
                  STOCKTRANSACTION.ORDERLINE,
                  STOCKTRANSACTION.ITEMELEMENTCODE,
                  STOCKTRANSACTION.PROJECTCODE
                FROM
                  STOCKTRANSACTION STOCKTRANSACTION
                WHERE
                  STOCKTRANSACTION.TEMPLATECODE = 'OPN'
                  AND STOCKTRANSACTION.ITEMTYPECODE = 'KGF'
                GROUP BY
                  STOCKTRANSACTION.ORDERCODE,
                  STOCKTRANSACTION.ORDERLINE,
                  STOCKTRANSACTION.ITEMELEMENTCODE,
                  STOCKTRANSACTION.PROJECTCODE
              ) prj1 ON b.ELEMENTSCODE = prj1.ITEMELEMENTCODE
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
                  LEFT OUTER JOIN PRODUCTIONDEMAND PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = STOCKTRANSACTION.ORDERCODE
                WHERE
                  STOCKTRANSACTION.TEMPLATECODE = '110'
                  AND STOCKTRANSACTION.ITEMTYPECODE = 'FKG'
                GROUP BY
                  STOCKTRANSACTION.ORDERCODE,
                  STOCKTRANSACTION.ORDERLINE,
                  STOCKTRANSACTION.ITEMELEMENTCODE,
                  STOCKTRANSACTION.PROJECTCODE,
                  STOCKTRANSACTION.PRODUCTIONORDERCODE,
                  PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE
              ) prj2 ON b.ELEMENTSCODE = prj2.ITEMELEMENTCODE
            WHERE
              (
                b.ITEMTYPECODE = 'FKG'
                OR b.ITEMTYPECODE = 'KGF'
              )
              AND b.LOGICALWAREHOUSECODE = 'M021'
            GROUP BY
              b.ITEMTYPECODE,
              b.DECOSUBCODE01,
              b.DECOSUBCODE02,
              b.DECOSUBCODE03,
              b.DECOSUBCODE04,
              b.DECOSUBCODE05,
              b.DECOSUBCODE06,
              b.DECOSUBCODE07,
              b.DECOSUBCODE08,
              b.PROJECTCODE,
              b.LOTCODE,
              b.BASEPRIMARYUNITCODE,
              b.BASESECONDARYUNITCODE,
              b.WHSLOCATIONWAREHOUSEZONECODE,
              b.WAREHOUSELOCATIONCODE,
              prj.PROJECTCODE,
              prj1.PROJECTCODE,
              prj2.PRODUCTIONORDERCODE,
              prj2.ORIGDLVSALORDLINESALORDERCODE ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){
	$itemNo=trim($rowdb21['DECOSUBCODE02'])."".trim($rowdb21['DECOSUBCODE03'])." ".trim($rowdb21['DECOSUBCODE04']);
	if($rowdb21['ITEMTYPECODE']=="KGF"){$jns="KAIN";}else if($rowdb21['ITEMTYPECODE']=="FKG"){$jns="KRAH";}
	if($rowdb21['PROJAWAL']!=""){
		$proj=$rowdb21['PROJAWAL'];}
	else if($rowdb21['PROJAWAL1']!=""){
		$proj=$rowdb21['PROJAWAL1'];}
	else if($rowdb27['PROJECTCODE']!=""){ 
		$proj=$rowdb27['PROJECTCODE']; }
	else if($rowdb27['ORIGDLVSALORDLINESALORDERCODE']!=""){ 
		$proj=$rowdb27['ORIGDLVSALORDLINESALORDERCODE']; }
	else{ 
		$proj=$rowdb21['LOTCODE']; }	
	$sqlDB22 = "SELECT
                SALESORDER.CODE,
                SALESORDER.EXTERNALREFERENCE,
                SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE,
                ITXVIEWAKJ.LEGALNAME1,
                ITXVIEWAKJ.ORDERPARTNERBRANDCODE,
                ITXVIEWAKJ.LONGDESCRIPTION
              FROM
                DB2ADMIN.SALESORDER SALESORDER
                LEFT OUTER JOIN DB2ADMIN.ITXVIEWAKJ ITXVIEWAKJ ON SALESORDER.CODE = ITXVIEWAKJ.CODE
              WHERE
                SALESORDER.CODE = '$rowdb21[PROJECTCODE]'";
	$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
	$rowdb22 = db2_fetch_assoc($stmt2);		
	if($rowdb22['LEGALNAME1']==""){$langganan="";}else{$langganan=$rowdb22['LEGALNAME1'];}
	if($rowdb22['ORDERPARTNERBRANDCODE']==""){$buyer="";}else{$buyer=$rowdb22['LONGDESCRIPTION'];}	
	if($rowdb22['EXTERNALREFERENCE']!=""){
		$PO=$rowdb22['EXTERNALREFERENCE'];
	}else{
		$PO=$rowdb26['EXTERNALREFERENCE'];
	}
	$sqlDB23 = "SELECT
                p.SUBCODE01,
                p.SUBCODE02,
                p.SUBCODE03,
                p.SUBCODE04,
                p.SUBCODE05,
                p.SUBCODE06,
                p.SUBCODE07,
                p.LONGDESCRIPTION
              FROM
                (
                  SELECT
                      p2.ITEMTYPEAFICODE,
                      p2.SUBCODE01,
                      p2.SUBCODE02,
                      p2.SUBCODE03,
                      p2.SUBCODE04,
                      p2.SUBCODE05,
                      p2.SUBCODE06,
                      p2.SUBCODE07
              FROM
                PRODUCTIONDEMAND p
                LEFT OUTER JOIN PRODUCTIONRESERVATION p2 ON p.CODE = p2.ORDERCODE
              WHERE
                p.ITEMTYPEAFICODE = 'KGF'
                AND p.SUBCODE01 = '".trim($rowdb21[' DECOSUBCODE01 '])."'
                AND p.SUBCODE02 = '".trim($rowdb21[' DECOSUBCODE02 '])."'
                AND p.SUBCODE03 = '".trim($rowdb21[' DECOSUBCODE03 '])."'
                AND p.SUBCODE04 = '".trim($rowdb21[' DECOSUBCODE04 '])."'
                AND (
                  p.PROJECTCODE = '".trim($proj)."'
                  OR p.ORIGDLVSALORDLINESALORDERCODE = '".trim($proj)."')) 
                  a
                LEFT OUTER JOIN PRODUCT p ON p.ITEMTYPECODE = 'GYR'
                AND p.SUBCODE01 = a.SUBCODE01
                AND p.SUBCODE02 = a.SUBCODE02
                AND p.SUBCODE03 = a.SUBCODE03
                AND p.SUBCODE04 = a.SUBCODE04
                AND p.SUBCODE05 = a.SUBCODE05
                AND p.SUBCODE06 = a.SUBCODE06
                AND p.SUBCODE07 = a.SUBCODE07
              GROUP BY
                p.SUBCODE01,
                p.SUBCODE02,
                p.SUBCODE03,
                p.SUBCODE04,
                p.SUBCODE05,
                p.SUBCODE06,
                p.SUBCODE07,
                p.LONGDESCRIPTION ";
$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
$ai=0;
$a[0]="";
$a[1]="";
$a[2]="";
$a[3]="";		
while($rowdb23 = db2_fetch_assoc($stmt3)){
	$a[$ai]=$rowdb23['LONGDESCRIPTION'];
	$ai++;
}	
	$sqlDB25 = "  SELECT
                  ORDERITEMORDERPARTNERLINK.ORDPRNCUSTOMERSUPPLIERCODE,
                  ORDERITEMORDERPARTNERLINK.LONGDESCRIPTION
                FROM
                  DB2ADMIN.ORDERITEMORDERPARTNERLINK ORDERITEMORDERPARTNERLINK
                WHERE
                  ORDERITEMORDERPARTNERLINK.ITEMTYPEAFICODE = '$rowdb21[ITEMTYPECODE]'
                  AND ORDERITEMORDERPARTNERLINK.ORDPRNCUSTOMERSUPPLIERCODE = '$rowdb22[ORDPRNCUSTOMERSUPPLIERCODE]'
                  AND ORDERITEMORDERPARTNERLINK.SUBCODE01 = '$rowdb21[DECOSUBCODE01]'
                  AND ORDERITEMORDERPARTNERLINK.SUBCODE02 = '$rowdb21[DECOSUBCODE02]'
                  AND ORDERITEMORDERPARTNERLINK.SUBCODE03 = '$rowdb21[DECOSUBCODE03]'
                  AND ORDERITEMORDERPARTNERLINK.SUBCODE04 = '$rowdb21[DECOSUBCODE04]'
                  AND ORDERITEMORDERPARTNERLINK.SUBCODE05 = '$rowdb21[DECOSUBCODE05]'
                  AND ORDERITEMORDERPARTNERLINK.SUBCODE06 = '$rowdb21[DECOSUBCODE06]'
                  AND ORDERITEMORDERPARTNERLINK.SUBCODE07 = '$rowdb21[DECOSUBCODE07]'
                  AND ORDERITEMORDERPARTNERLINK.SUBCODE08 = '$rowdb21[DECOSUBCODE08]'";
	$stmt5   = db2_exec($conn1,$sqlDB25, array('cursor'=>DB2_SCROLLABLE));
	$rowdb25 = db2_fetch_assoc($stmt5);	
	if($rowdb25['LONGDESCRIPTION']!=""){
		$item=$rowdb25['LONGDESCRIPTION'];
	}else{
		$item=trim($rowdb21['DECOSUBCODE02'])."".trim($rowdb21['DECOSUBCODE03']);
	}
	$sqlDB26 = " SELECT
  SALESORDERLINE.EXTERNALREFERENCE
FROM
  DB2ADMIN.SALESORDERLINE
WHERE
  SALESORDERLINE.ITEMTYPEAFICODE = '$rowdb21[ITEMTYPECODE]'
  AND SALESORDERLINE.PROJECTCODE = '$rowdb21[PROJECTCODE]'
  AND SALESORDERLINE.SUBCODE01 = '$rowdb21[DECOSUBCODE01]'
  AND SALESORDERLINE.SUBCODE02 = '$rowdb21[DECOSUBCODE02]'
  AND SALESORDERLINE.SUBCODE03 = '$rowdb21[DECOSUBCODE03]'
  AND SALESORDERLINE.SUBCODE04 = '$rowdb21[DECOSUBCODE04]'
  AND SALESORDERLINE.SUBCODE05 = '$rowdb21[DECOSUBCODE05]'
  AND SALESORDERLINE.SUBCODE06 = '$rowdb21[DECOSUBCODE06]'
  AND SALESORDERLINE.SUBCODE07 = '$rowdb21[DECOSUBCODE07]'
  AND SALESORDERLINE.SUBCODE08 = '$rowdb21[DECOSUBCODE08]'
LIMIT 1";
	$stmt6   = db2_exec($conn1,$sqlDB26, array('cursor'=>DB2_SCROLLABLE));
	$rowdb26 = db2_fetch_assoc($stmt6);
	if($rowdb22['EXTERNALREFERENCE']!=""){
		$PO=$rowdb22['EXTERNALREFERENCE'];
	}else{
		$PO=$rowdb26['EXTERNALREFERENCE'];
	}
	$sqlDB27 = " SELECT PROJECTCODE, ORIGDLVSALORDLINESALORDERCODE FROM PRODUCTIONDEMAND  WHERE CODE ='$rowdb21[LOTCODE]' ";
	$stmt7   = db2_exec($conn1,$sqlDB27, array('cursor'=>DB2_SCROLLABLE));
	$rowdb27 = db2_fetch_assoc($stmt7);
  

  $sqlDB28 = " SELECT DISTINCT
    TRIM(p.SUMMARIZEDDESCRIPTION) as SUMMARIZEDDESCRIPTION
    FROM
      FULLITEMKEYDECODER p
    WHERE
      p.SUBCODE02 = '$rowdb21[DECOSUBCODE02]'
      AND p.SUBCODE03 = '$rowdb21[DECOSUBCODE03]'
      AND p.SUBCODE04 = '$rowdb21[DECOSUBCODE04]'
      AND p.ITEMTYPECODE IN ('KGF', 'FKG')";
	$stmt8   = db2_exec($conn1,$sqlDB28, array('cursor'=>DB2_SCROLLABLE));
	$rowdb28 = db2_fetch_assoc($stmt8);


		
?>
	  <tr>
      <td style="text-align: left"><?php echo $langganan; ?></td>
      <td style="text-align: left"><?php echo $buyer; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['PROJECTCODE']; ?></td>
      <td style="text-align: center"><?php  if($rowdb21['PROJAWAL']!=""){echo $rowdb21['PROJAWAL'];}else if($rowdb21['PROJAWAL1']!=""){echo $rowdb21['PROJAWAL1'];}else if($rowdb27['PROJECTCODE']!=""){ echo $rowdb27['PROJECTCODE']; }else if($rowdb27['ORIGDLVSALORDLINESALORDERCODE']!=""){ echo $rowdb27['ORIGDLVSALORDLINESALORDERCODE']; }else if($rowdb21['ORIGDLVSALORDLINESALORDERCODE']!=""){ echo $rowdb21['ORIGDLVSALORDLINESALORDERCODE'];}else{ echo $rowdb21['LOTCODE']; }  ?></td>
      <td style="text-align: center"><?php echo $jns; ?></td>
      <td style="text-align: center"><?php echo $itemNo; ?></td>
      <td style="text-align: center"><?php echo $rowdb28['SUMMARIZEDDESCRIPTION']; ?></td>
      <td style="text-align: left"><?php echo $a[0]; ?></td>
      <td style="text-align: left"><?php echo $a[1]; ?></td>
      <td style="text-align: left"><?php echo $a[2]; ?></td>
      <td style="text-align: left"><?php echo $a[3]; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['PRODUCTIONORDERCODE'];?></td>
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE'];?></td>
      <td style="text-align: center"><?php echo $rowdb21['ROLL'];?></td>
      <td style="text-align: right"><?php echo $rowdb21['BERAT'];?></td>
      <td style="text-align: center"><?php echo $rowdb21['BASEPRIMARYUNITCODE'];?></td>
      <td style="text-align: center"><?php echo $rowdb21['WHSLOCATIONWAREHOUSEZONECODE'];?></td>
      <td style="text-align: center"><?php echo $rowdb21['WAREHOUSELOCATIONCODE'];?></td>
      </tr>				  
<?php	$no++;
		$totrol=$totrol+$rowdb21['ROLL'];
		$totkg=$totkg+$rowdb21['BERAT'];
	} ?>
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
                    <td style="text-align: right">&nbsp;</td>
                    <td style="text-align: right">&nbsp;</td>
                    <td style="text-align: right">&nbsp;</td>
                    <td style="text-align: right">&nbsp;</td>
                    <td style="text-align: right">&nbsp;</td>
                    <td style="text-align: right"><strong>TOTAL</strong></td>
                    <td style="text-align: right"><strong><?php echo $totrol; ?></strong></td>
                    <td style="text-align: right"><strong><?php echo round($totkg,3); ?></strong></td>
                    <td style="text-align: center"><strong>KGs</strong></td>
                    <td style="text-align: right">&nbsp;</td>
                    <td style="text-align: center">&nbsp;</td>
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