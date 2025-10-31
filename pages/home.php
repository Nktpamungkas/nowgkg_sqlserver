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

<style>
  .chart-head { text-align:center; }
  .chart-legend {
    display:inline-flex; gap:12px; align-items:center; justify-content:center; 
    flex-wrap:wrap; margin-top:6px; font-size:12px;
  }
  .legend-swatch {
    display:inline-block; width:14px; height:10px; margin-right:6px; 
    border-radius:2px; vertical-align:middle; border:1px solid #999;
  }
  .legend-line {
    width:18px; height:0; border-top:3px solid #dc3545;
    margin-right:6px; display:inline-block; vertical-align:middle;
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

    <div class="row">
      <div class="col-md-12">
        <div class="card card-warning">
          <div class="card-header">
            <h3 class="card-title">STATUS RAK</h3>
          </div>
          <div class="card-body">
            <div id="chartsByLetter" class="row"></div>
          </div>
        </div>
      </div>
    </div>

    <?php
      // === AMBIL DATA DARI DB2 ===
      $sql = "
        SELECT TRIM(b.WAREHOUSELOCATIONCODE) AS location,
              COUNT(b.ELEMENTSCODE)         AS roll,
              SUM(b.BASEPRIMARYQUANTITYUNIT) AS weight
        FROM BALANCE b
        WHERE b.ITEMTYPECODE='KGF' AND b.LOGICALWAREHOUSECODE='M021'
        GROUP BY TRIM(b.WAREHOUSELOCATIONCODE)
        ORDER BY location
      ";
      $stmt = db2_exec($conn1, $sql, ['cursor'=>DB2_SCROLLABLE]);

      $groups = []; // ['A' => [ ['loc'=>'A01', 'roll'=>.., 'weight'=>..], ... ], 'B'=>...]
      while ($r = db2_fetch_assoc($stmt)) {
        $loc = trim($r['LOCATION'] ?? '');
        if ($loc === '') continue;
        $first = strtoupper($loc[0]);
        if (!isset($groups[$first])) $groups[$first] = [];
        $groups[$first][] = [
          'loc'    => $loc,
          'roll'   => (int)$r['ROLL'],
          'weight' => (float)$r['WEIGHT'],
        ];
      }

      // --- AMBIL CAPACITY DARI SQL SERVER ---
      $capMap = [];
      $sqlCap = "SELECT RTRIM(location) AS location, max_capacity
                FROM dbnow_gkg.dbnow_gkg.tbl_master_rak";
      $capStmt = sqlsrv_query($con, $sqlCap);
      if ($capStmt) {
        while ($row = sqlsrv_fetch_array($capStmt, SQLSRV_FETCH_ASSOC)) {
          $capMap[$row['location']] = (float)$row['max_capacity'];
        }
      }

      // urutkan huruf A..Z
      ksort($groups);

      // siapkan payload untuk JS (lengkap dengan capacity)
      $chartPayload = [];
      foreach ($groups as $letter => $rows) {
        // urutkan label per lokasi
        usort($rows, function($a,$b){ return strcmp($a['loc'],$b['loc']); });

        $labels  = array_column($rows,'loc');
        $rolls   = array_column($rows,'roll');
        $weights = array_column($rows,'weight');

        // susun capacity per label; default 900 jika tidak ada di tabel
        $caps = [];
        foreach ($labels as $loc) {
          $caps[] = isset($capMap[$loc]) ? (float)$capMap[$loc] : 900.0;
        }

        // hitung yMax: ambil maksimum dari roll, weight, capacity
        $maxVal = 0;
        if ($rolls)   $maxVal = max($maxVal, max($rolls));
        if ($weights) $maxVal = max($maxVal, max($weights));
        if ($caps)    $maxVal = max($maxVal, max($caps));
        // bulatkan ke atas kelipatan 200 (min 200)
        $yMax = max(200, (int)ceil($maxVal / 200) * 200);

        $chartPayload[] = [
          'letter'  => $letter,
          'labels'  => $labels,
          'rolls'   => $rolls,
          'weights' => $weights,
          'caps'    => $caps,
          'yMax'    => $yMax
        ];
      }
    ?>
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

<script>
// ===== util: format angka =====
function fmt(n, d){
  return Number(n||0).toLocaleString(undefined,{minimumFractionDigits:d,maximumFractionDigits:d});
}

// ===== buat legend HTML (fixed, tidak ikut scroll) =====
function renderLegendHTML(chart, holder){
  var html = '';
  chart.data.datasets.forEach(function(ds){
    if (ds.type === 'line') {
      html += '<span><i class="legend-line" style="border-top-color:'+(ds.borderColor||'#dc3545')+'"></i>'
           +  (ds.label||'') + '</span>';
    } else {
      var bg = (typeof ds.backgroundColor === 'string') ? ds.backgroundColor
               : (Array.isArray(ds.backgroundColor) ? ds.backgroundColor[0] : '#888');
      var bd = ds.borderColor || '#999';
      html += '<span><i class="legend-swatch" style="background:'+bg+';border-color:'+bd+'"></i>'
           +  (ds.label||'') + '</span>';
    }
  });
  holder.innerHTML = html;
}

// ===== hitung lebar canvas agar bisa scroll (adaptif utk data sedikit) =====
function canvasWidthFor(labels){
  const n = (labels && labels.length) || 0;
  if (n <= 1) return 520;   // 1 kategori
  if (n === 2) return 620;  // 2 kategori
  if (n === 3) return 760;  // 3 kategori
  // normal: skala dinamis utk banyak kategori
  var avgLen = labels.reduce((s,x)=>s+String(x).length,0) / n;
  var perCat = Math.max(10, Math.min(30, 7 * avgLen)); // 10..30 px per kategori
  return Math.max(900, Math.min(40000, Math.round(n * perCat)));
}

// ===== factory kolom (2 grafik per baris), header fixed + canvas scroll =====
function makeCol(letter){
  var col = document.createElement('div');
  col.className = 'col-lg-6 mb-4';

  var card = document.createElement('div'); card.className = 'card';

  var head = document.createElement('div');
  head.className = 'card-header py-2';
  head.innerHTML = '<strong>Group: ' + letter + '</strong>';

  var body = document.createElement('div'); body.className = 'card-body';

  var fixed = document.createElement('div');
  fixed.className = 'chart-head';
  fixed.innerHTML = [
    '<div class="chart-title">Lokasi Awal "' + letter + '"</div>',
    '<div class="chart-legend" id="legend-' + letter + '"></div>'
  ].join('');

  var scroller = document.createElement('div');
  scroller.style.height = '340px';
  scroller.style.overflowX = 'auto';
  scroller.style.overflowY = 'hidden';

  var inner = document.createElement('div');
  inner.style.display = 'inline-block';
  inner.style.verticalAlign = 'top';

  var canvas = document.createElement('canvas');
  canvas.style.width = '100%';
  canvas.height = 320;

  inner.appendChild(canvas);
  scroller.appendChild(inner);
  body.appendChild(fixed);
  body.appendChild(scroller);

  card.appendChild(head);
  card.appendChild(body);
  col.appendChild(card);

  return { col, fixed, scroller, inner, canvas };
}

(function(){
  var payload = <?php echo json_encode($chartPayload, JSON_NUMERIC_CHECK); ?>;
  var container = document.getElementById('chartsByLetter');
  container.innerHTML = '';

  payload.forEach(function(g){
    if (!g.labels || !g.labels.length) return;

    var ui = makeCol(g.letter);
    container.appendChild(ui.col);

    // lebar inner besar → scroller akan punya horizontal scrollbar
    var targetW = canvasWidthFor(g.labels);
    ui.inner.style.width = targetW + 'px';

    var nCat = g.labels.length;
    var catPct  = nCat <= 3 ? 0.6 : 0.8;
    var barPct  = 0.95;
    var thick   = nCat <= 3 ? 28 : null; 

    var ctx = ui.canvas.getContext('2d');
    var chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: g.labels,
        datasets: [
          Object.assign({
            label: 'Roll',
            data: g.rolls,
            backgroundColor: 'rgba(54,162,235,0.35)',
            borderColor:     'rgba(54,162,235,1)',
            borderWidth: 1,
            categoryPercentage: catPct,
            barPercentage: barPct,
            maxBarThickness: 22
          }, thick ? { barThickness: thick } : {}),
          Object.assign({
            label: 'Weight (kg)',
            data: g.weights,
            backgroundColor: 'rgba(255,159,64,0.30)',
            borderColor:     'rgba(255,159,64,1)',
            borderWidth: 1,
            categoryPercentage: catPct,
            barPercentage: barPct,
            maxBarThickness: 22
          }, thick ? { barThickness: thick } : {}),
          {
            type: 'line',
            label: 'Max Capacity (kg)',
            data: g.caps,
            borderColor: 'rgba(220,53,69,1)',
            backgroundColor: 'rgba(220,53,69,0.05)',
            borderWidth: 2,
            pointRadius: 0,
            lineTension: 0,
            fill: false
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        // Title & legend canvas dimatikan → kita render HTML fixed di atas scroller
        title:  { display: false },
        legend: { display: false },
        tooltips: {
          mode: 'index', intersect: false,
          callbacks: {
            title: function(items, data){
              var i = items && items[0] ? items[0].index : 0;
              return data.labels[i];
            },
            label: function(t, data){
              var ds  = data.datasets[t.datasetIndex];
              var dps = ds.label.indexOf('Weight') !== -1 || ds.label.indexOf('Capacity') !== -1 ? 2 : 0;
              return ds.label + ': ' + fmt(t.yLabel, dps);
            }
          }
        },
        scales: {
          xAxes: [{
            gridLines: { display: false },
            ticks: {
              autoSkip: false,
              maxRotation: 60,
              minRotation: 60,
              fontSize: 11
            }
          }],
          yAxes: [{
            ticks: {
              beginAtZero: true,
              min: 0,
              max: g.yMax,
              stepSize: 200,
              callback: function(v){ return v.toLocaleString(); }
            },
            gridLines: { drawOnChartArea: true }
          }]
        },
        layout: { padding: {left:0,right:12,top:0,bottom:0} }
      }
    });

    // render legend HTML fixed (di tengah, tak ikut scroll)
    renderLegendHTML(chart, document.getElementById('legend-' + g.letter));
  });
})();
</script>