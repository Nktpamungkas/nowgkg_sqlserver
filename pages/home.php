<style>
        .chart {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: Arial, sans-serif;
        }
        .node {
            background-color: #2e74b5;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            width: 200px;
            margin: 10px;
        }
        .connector {
            width: 2px;
            height: 20px;
            background-color: black;
            margin: 0 auto;
        }
        .children {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 20px;
        }
        .horizontal-line {
            width: 80%;
            height: 2px;
            background-color: black;
            margin: 0 auto;
        }
</style>


<?php
			  $sqlDB22 = "SELECT
					SUM(CASE WHEN a.VALUESTRING = '1' THEN b.BASEPRIMARYQUANTITYUNIT ELSE 0 END) AS STOCK_MATI,
					SUM(CASE WHEN a.VALUESTRING = '2' THEN b.BASEPRIMARYQUANTITYUNIT ELSE 0 END) AS CANCEL_ORDER,
					SUM(CASE WHEN a.VALUESTRING = '3' THEN b.BASEPRIMARYQUANTITYUNIT ELSE 0 END) AS PO_SELESAI,
					SUM(CASE WHEN a.VALUESTRING = '4' THEN b.BASEPRIMARYQUANTITYUNIT ELSE 0 END) AS BS_KNT,
					SUM(CASE WHEN a.VALUESTRING = '5' THEN b.BASEPRIMARYQUANTITYUNIT ELSE 0 END) AS BS_RMP,
					SUM(CASE WHEN a.VALUESTRING IN ('1', '2', '3', '4', '5') THEN b.BASEPRIMARYQUANTITYUNIT ELSE 0 END) AS SEMUA
				FROM
					STOCKTRANSACTION s
				LEFT JOIN ADSTORAGE a ON
					a.UNIQUEID = s.ABSUNIQUEID
					AND a.FIELDNAME = 'KategoriStokGKG'
				LEFT JOIN STOCKTRANSACTION s1 ON s.TRANSACTIONNUMBER = s1.TRANSACTIONNUMBER
				LEFT JOIN BALANCE b ON s1.ITEMELEMENTCODE = b.ELEMENTSCODE AND b.ITEMTYPECODE ='KGF' AND b.LOGICALWAREHOUSECODE = 'M021'
				WHERE
					a.VALUESTRING > '0' AND b.ELEMENTSCODE IS NOT NULL";
              $stmt2   = db2_exec($conn1, $sqlDB22, array('cursor' => DB2_SCROLLABLE));
              $rowdb22 = db2_fetch_assoc($stmt2);
		$sqlDB21 = "SELECT 
					sum(BASEPRIMARYQUANTITYUNIT) AS KG_STOCK 
					FROM BALANCE WHERE ITEMTYPECODE ='KGF' AND LOGICALWAREHOUSECODE ='M021' ";
					  $stmt1   = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));
					  $rowdb21 = db2_fetch_assoc($stmt1);
?>
<!-- Main content -->
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>MK</h3>

                <p>Mutasi Kain</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="MutasiKain" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>IKG</h3>

                <p>Identifikasi Kain Greige</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="IdentifikasiKainGreige" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>PK</h3>

                <p>Persediaan Kain</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="PersediaanKainGreige" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>CS</h3>

                <p>Check Stock</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="CheckStock" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
		<div class="row">  
		<div class="col-md-8">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Detail Persediaan Kain Dept GKG</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="chart">
			<div class="node">Data Persediaan Kain Dept GKG<br><?php echo number_format($rowdb21['KG_STOCK'],2);?> KG</div>
			<div class="connector"></div>
			<div class="horizontal-line" style="width: 85.00%;"></div>
			<div class="children">
				<div>
					<div class="connector"></div>
					<div class="node">Stock Mati<br><?php echo number_format($rowdb22['STOCK_MATI'],2);?> KG</div>
				</div>
				<div>
					<div class="connector"></div>
					<div class="node">Cancel Order<br><?php echo number_format($rowdb22['CANCEL_ORDER'],2);?> KG</div>
				</div>
				<div>
					<div class="connector"></div>
					<div class="node">PO Selesai<br><?php echo number_format($rowdb22['PO_SELESAI'],2);?> KG</div>
				</div>
				<div>
					<div class="connector"></div>
					<div class="node">BS KNT<br><?php echo number_format($rowdb22['BS_KNT'],2);?> KG</div>
				</div>
				<div>
					<div class="connector"></div>
					<div class="node">BS RMP<br><?php echo number_format($rowdb22['BS_RMP'],2);?> KG</div>
				</div>
			</div>
		</div>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
		<div class="col-md-4">
        <div class="card card-warning">
          <div class="card-header">
            <h3 class="card-title">Detail Persediaan Kain Dept GKG</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="chart-container">
        		<canvas id="stokChart"></canvas>
    		</div>
          </div>
          <!-- /.card-body -->
        </div>
      </div>	
		</div>	
        <div class="row">  
		<div class="col-md-8">
        <div class="card card-danger">
          <div class="card-header">
            <h3 class="card-title">Data Stock Item  di Dept. GKG</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
          <table id="example3" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
              <thead>
                <tr>
                  <th valign="middle" style="text-align: center">NO</th>
                  <th valign="middle" style="text-align: center">BUYER</th>
                  <th valign="middle" style="text-align: center">ITEM</th>
                  <th valign="middle" style="text-align: center">QTY</th>
                  <th valign="middle" style="text-align: center">STATUS</th>
                </tr>
              </thead>
              <tbody>
                <?php

                $noCT = 1;
                $c = 0;

//              $sqlDB23 = " SELECT b.DECOSUBCODE02, b.DECOSUBCODE03, SUM(b.BASEPRIMARYQUANTITYUNIT) AS QTY, a.VALUESTRING, ab.LONGDESCRIPTION
//						FROM STOCKTRANSACTION s
//						LEFT JOIN ADSTORAGE a 
//							ON a.UNIQUEID = s.ABSUNIQUEID
//							AND a.FIELDNAME = 'KategoriStokGKG'
//						LEFT JOIN STOCKTRANSACTION s1 
//							ON s.TRANSACTIONNUMBER = s1.TRANSACTIONNUMBER
//						LEFT JOIN BALANCE b 
//							ON s1.ITEMELEMENTCODE = b.ELEMENTSCODE AND b.ITEMTYPECODE ='KGF'
//						LEFT JOIN (
//							SELECT
//								SALESORDER.CODE,
//								SALESORDER.EXTERNALREFERENCE,
//								SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE,
//								ITXVIEWAKJ.LEGALNAME1,
//								ITXVIEWAKJ.ORDERPARTNERBRANDCODE,
//								ITXVIEWAKJ.LONGDESCRIPTION
//							FROM DB2ADMIN.SALESORDER SALESORDER
//							LEFT JOIN DB2ADMIN.ITXVIEWAKJ ITXVIEWAKJ 
//								ON SALESORDER.CODE = ITXVIEWAKJ.CODE
//						) ab 
//							ON ab.CODE = b.PROJECTCODE
//						WHERE 
//							b.ITEMTYPECODE ='KGF' AND b.LOGICALWAREHOUSECODE ='M021' AND
//							b.ELEMENTSCODE IS NOT NULL
//						GROUP BY 
//							b.DECOSUBCODE02, 
//							b.DECOSUBCODE03, 
//							a.VALUESTRING, 
//							ab.LONGDESCRIPTION
//						HAVING 
//							SUM(b.BASEPRIMARYQUANTITYUNIT) > 5000";
				$sqlDB23 = " 
							SELECT b.DECOSUBCODE02, b.DECOSUBCODE03, SUM(b.BASEPRIMARYQUANTITYUNIT) AS QTY
							, sk.VALUESTRING
							, ab.LONGDESCRIPTION
							FROM balance b
													LEFT JOIN (
											SELECT
												SALESORDER.CODE,
												SALESORDER.EXTERNALREFERENCE,
												SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE,
												ITXVIEWAKJ.LEGALNAME1,
												ITXVIEWAKJ.ORDERPARTNERBRANDCODE,
												ITXVIEWAKJ.LONGDESCRIPTION
											FROM DB2ADMIN.SALESORDER SALESORDER
											LEFT JOIN DB2ADMIN.ITXVIEWAKJ ITXVIEWAKJ 
												ON SALESORDER.CODE = ITXVIEWAKJ.CODE
										) ab 
											ON ab.CODE = b.PROJECTCODE
							LEFT JOIN (
							SELECT
							SUM(b.BASEPRIMARYQUANTITYUNIT) AS KG,a.VALUESTRING, b.ELEMENTSCODE, s.TRANSACTIONNUMBER
							FROM
								STOCKTRANSACTION s
							LEFT JOIN ADSTORAGE a ON
								a.UNIQUEID = s.ABSUNIQUEID
								AND a.FIELDNAME = 'KategoriStokGKG'
							LEFT JOIN STOCKTRANSACTION s1 ON s.TRANSACTIONNUMBER = s1.TRANSACTIONNUMBER
							LEFT JOIN BALANCE b ON s1.ITEMELEMENTCODE = b.ELEMENTSCODE AND b.ITEMTYPECODE ='KGF' AND b.LOGICALWAREHOUSECODE = 'M021'
							WHERE
								a.VALUESTRING > '0' AND b.ELEMENTSCODE IS NOT NULL
							GROUP BY 
							s.TRANSACTIONNUMBER
							, a.VALUESTRING
							, b.ELEMENTSCODE
							) sk ON sk.ELEMENTSCODE =b.ELEMENTSCODE
							WHERE b.ITEMTYPECODE = 'KGF' AND b.LOGICALWAREHOUSECODE = 'M021' 
				--			AND b.DECOSUBCODE02='BFT' AND b.DECOSUBCODE03='20081' 
							GROUP BY b.DECOSUBCODE02, b.DECOSUBCODE03
				--			, b.PROJECTCODE
							, ab.LONGDESCRIPTION
							, sk.VALUESTRING
				--			HAVING 
				--			SUM(b.BASEPRIMARYQUANTITYUNIT) > 5000
				";  
                $stmt3   = db2_exec($conn1, $sqlDB23, array('cursor' => DB2_SCROLLABLE));
                while ($rowdb23 = db2_fetch_assoc($stmt3)) {                  
                  if ($rowdb23['VALUESTRING'] == "1") {
                    $sts24 = "<small class='badge badge-danger'>Stok Mati</small>";
                  } else if ($rowdb23['VALUESTRING'] == "2") {
                    $sts24 = "<small class='badge badge-warning'>Cancel Order</small>";                  
				  } else if ($rowdb23['VALUESTRING'] == "3") {
                    $sts24 = "<small class='badge badge-success'>PO Selesai</small>";                  
				  } else if ($rowdb23['VALUESTRING'] == "4") {
                    $sts24 = "<small class='badge badge-info'>BS KNT</small>";                  
				  } else if ($rowdb23['VALUESTRING'] == "5") {
                    $sts24 = "<small class='badge badge-primary'>BS RMP</small>";                  
				  } else {
					$sts24 = "";  
				  }
				$item = trim($rowdb23['DECOSUBCODE02']).trim($rowdb23['DECOSUBCODE03']);	
                    
                ?>
                  <tr>
                    <td style="text-align: center"><?php echo $noCT; ?></td>
                    <td style="text-align: left"><span style="text-align: center"><?php echo $rowdb23['LONGDESCRIPTION']; ?></span></td>
                    <td style="text-align: center"><span style="text-align: center"><?php echo $item; ?></span></td>
                    <td style="text-align: right"><?php echo number_format($rowdb23['QTY'],2); ?></td>
                    <td style="text-align: center"><span style="text-align: right"><?php echo $sts24; ?></span></td>
                  </tr>

                <?php
                  
                  $noCT++;
                } ?>
              </tbody>
              
            </table>  
          </div>
          <!-- /.card-body -->
        </div>
      </div>
			
		</div>
      </div><!-- /.container-fluid -->
    <!-- /.content -->
<script>
        const ctx = document.getElementById('stokChart').getContext('2d');

        const dataStok = {
            labels: ["Stock Mati", "Cancel Order", "PO Selesai", "BS KNT", "BS RMP"],
            datasets: [{
                data: [<?php echo $rowdb22['STOCK_MATI'];?>, <?php echo $rowdb22['CANCEL_ORDER'];?>, <?php echo $rowdb22['PO_SELESAI'];?>, <?php echo $rowdb22['BS_KNT'];?>, <?php echo $rowdb22['BS_RMP'];?>], // Sesuai data
                backgroundColor: ["#4A90E2", "#50C878", "#F4A100", "#FF0000", "#9B11E0"],
                hoverOffset: 4
            }]
        };

        const stokChart = new Chart(ctx, {
            type: 'pie',
            data: dataStok,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        });
    </script>