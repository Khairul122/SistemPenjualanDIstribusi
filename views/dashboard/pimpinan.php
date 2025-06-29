<?php include('template/header.php'); ?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">
              <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                  <div>
                    <h4 class="card-title mb-0">Dashboard Pimpinan</h4>
                    <p class="text-muted">Laporan Eksekutif Produksi dan Distribusi Pupuk NPK</p>
                  </div>
                  <div>
                    <span class="badge bg-success">
                      <i class="mdi mdi-account-star"></i> <?= $_SESSION['nama'] ?? 'Pimpinan' ?>
                    </span>
                  </div>
                </div>
                
                <!-- KPI Cards -->
                <div class="row mt-4">
                  <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card bg-gradient-primary text-white">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="icon-wrapper text-white me-3">
                            <i class="mdi mdi-currency-usd mdi-36px"></i>
                          </div>
                          <div>
                            <h6 class="card-title mb-1">Omzet Bulan Ini</h6>
                            <h3 class="mb-0">Rp <?= number_format($omzetBulanIni ?? 0, 0, ',', '.') ?></h3>
                            <small class="text-light">
                              <i class="mdi mdi-trending-up"></i> +15% dari bulan lalu
                            </small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card bg-gradient-success text-white">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="icon-wrapper text-white me-3">
                            <i class="mdi mdi-chart-line mdi-36px"></i>
                          </div>
                          <div>
                            <h6 class="card-title mb-1">Target Produksi</h6>
                            <h3 class="mb-0"><?= $targetProduksi['persentase'] ?? 0 ?>%</h3>
                            <small class="text-light">
                              <?= $targetProduksi['total'] ?? 0 ?> dari <?= $targetProduksi['target'] ?? 800 ?> ton
                            </small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card bg-gradient-warning text-white">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="icon-wrapper text-white me-3">
                            <i class="mdi mdi-truck mdi-36px"></i>
                          </div>
                          <div>
                            <h6 class="card-title mb-1">Pengiriman</h6>
                            <h3 class="mb-0"><?= $pengirimanBulanIni ?? 0 ?></h3>
                            <small class="text-light">Bulan ini</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card bg-gradient-info text-white">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="icon-wrapper text-white me-3">
                            <i class="mdi mdi-account-star mdi-36px"></i>
                          </div>
                          <div>
                            <h6 class="card-title mb-1">Customer Aktif</h6>
                            <h3 class="mb-0"><?= $customerAktif ?? 0 ?></h3>
                            <small class="text-light">
                              <i class="mdi mdi-trending-up"></i> +8 customer baru
                            </small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Charts and Analytics -->
                <div class="row">
                  <div class="col-lg-8 mb-4">
                    <div class="card">
                      <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Tren Produksi & Penjualan</h5>
                        <div class="btn-group btn-group-sm" role="group">
                          <button type="button" class="btn btn-outline-primary active">7 Hari</button>
                          <button type="button" class="btn btn-outline-primary">30 Hari</button>
                          <button type="button" class="btn btn-outline-primary">3 Bulan</button>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="chart-container" style="height: 300px;">
                          <canvas id="productionTrendChart"></canvas>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-lg-4 mb-4">
                    <div class="card">
                      <div class="card-header">
                        <h5 class="card-title mb-0">Top Produk Performance</h5>
                      </div>
                      <div class="card-body">
                        <?php if (!empty($topProduk)): ?>
                          <?php foreach ($topProduk as $index => $produk): ?>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                              <div class="d-flex align-items-center">
                                <div class="me-3">
                                  <span class="badge bg-<?= $index == 0 ? 'success' : ($index == 1 ? 'warning' : 'info') ?> rounded-pill">
                                    #<?= $index + 1 ?>
                                  </span>
                                </div>
                                <div>
                                  <span class="fw-bold"><?= $produk['nama'] ?? 'Unknown' ?></span>
                                  <br><small class="text-muted"><?= $produk['total'] ?? 0 ?> ton</small>
                                </div>
                              </div>
                              <div class="text-end">
                                <span class="text-<?= $index == 0 ? 'success' : ($index == 1 ? 'warning' : 'info') ?> fw-bold">
                                  <?php
                                    $total = array_sum(array_column($topProduk, 'total'));
                                    $percentage = $total > 0 ? round(($produk['total'] / $total) * 100, 1) : 0;
                                    echo $percentage;
                                  ?>%
                                </span>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <div class="text-center text-muted">
                            <i class="mdi mdi-chart-pie mdi-48px mb-2"></i>
                            <p>Belum ada data produksi</p>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Key Metrics -->
                <div class="row">
                  <div class="col-lg-8 mb-4">
                    <div class="card">
                      <div class="card-header">
                        <h5 class="card-title mb-0">Key Performance Indicators</h5>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-4 text-center">
                            <div class="border-end pe-3">
                              <h6 class="text-muted mb-2">Efisiensi Produksi</h6>
                              <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: <?= $ringkasanOperasional['efisiensi'] ?>%"></div>
                              </div>
                              <h4 class="text-success mb-1"><?= $ringkasanOperasional['efisiensi'] ?>%</h4>
                              <small class="text-muted">Target: 90%</small>
                            </div>
                          </div>
                          <div class="col-md-4 text-center">
                            <div class="border-end pe-3">
                              <h6 class="text-muted mb-2">On-Time Delivery</h6>
                              <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-primary" style="width: <?= $ringkasanOperasional['onTimeDelivery'] ?>%"></div>
                              </div>
                              <h4 class="text-primary mb-1"><?= $ringkasanOperasional['onTimeDelivery'] ?>%</h4>
                              <small class="text-muted">Target: 95%</small>
                            </div>
                          </div>
                          <div class="col-md-4 text-center">
                            <div>
                              <h6 class="text-muted mb-2">Customer Satisfaction</h6>
                              <div class="d-flex justify-content-center align-items-center mb-2">
                                <span class="text-warning me-1">
                                  <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="mdi mdi-star<?= $i <= floor($ringkasanOperasional['customerSatisfaction']) ? '' : '-outline' ?>"></i>
                                  <?php endfor; ?>
                                </span>
                              </div>
                              <h4 class="text-warning mb-1"><?= $ringkasanOperasional['customerSatisfaction'] ?>/5</h4>
                              <small class="text-muted">Feedback customer</small>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-lg-4 mb-4">
                    <div class="card">
                      <div class="card-header">
                        <h5 class="card-title mb-0">Quick Stats</h5>
                      </div>
                      <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                          <div class="d-flex align-items-center">
                            <i class="mdi mdi-clock-outline text-primary me-2"></i>
                            <span>Rata-rata Lead Time</span>
                          </div>
                          <span class="fw-bold">3.2 hari</span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                          <div class="d-flex align-items-center">
                            <i class="mdi mdi-trending-up text-success me-2"></i>
                            <span>Growth Rate</span>
                          </div>
                          <span class="fw-bold text-success">+15.2%</span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                          <div class="d-flex align-items-center">
                            <i class="mdi mdi-warehouse text-warning me-2"></i>
                            <span>Inventory Turnover</span>
                          </div>
                          <span class="fw-bold">4.5x</span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="d-flex align-items-center">
                            <i class="mdi mdi-cash-multiple text-info me-2"></i>
                            <span>Profit Margin</span>
                          </div>
                          <span class="fw-bold text-info">23.8%</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Chart Script -->
  <script>
    // Sample chart for production trend
    if (document.getElementById('productionTrendChart')) {
      const ctx = document.getElementById('productionTrendChart').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
          datasets: [{
            label: 'Produksi (ton)',
            data: [120, 135, 110, 150, 145, 160, 140],
            borderColor: '#28a745',
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            tension: 0.4
          }, {
            label: 'Penjualan (ton)',
            data: [100, 125, 105, 140, 130, 145, 135],
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top',
            }
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    }
  </script>
  
  <?php include 'template/script.php'; ?>
</body>

</html>