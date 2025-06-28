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
                    <h4 class="card-title mb-0">Dashboard Administrator</h4>
                    <p class="text-muted">Sistem Informasi Manajemen Produksi dan Distribusi Pupuk NPK</p>
                  </div>
                </div>
                
                <div class="row mt-4">
                  <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="icon-wrapper bg-primary text-white rounded-circle p-3 me-3">
                            <i class="mdi mdi-account-multiple mdi-24px"></i>
                          </div>
                          <div>
                            <h6 class="card-title">Total Customer</h6>
                            <h3 class="mb-0"><?= $totalCustomer ?? 0 ?></h3>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="icon-wrapper bg-success text-white rounded-circle p-3 me-3">
                            <i class="mdi mdi-package-variant mdi-24px"></i>
                          </div>
                          <div>
                            <h6 class="card-title">Total Produk</h6>
                            <h3 class="mb-0"><?= $totalProduk ?? 0 ?></h3>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="icon-wrapper bg-warning text-white rounded-circle p-3 me-3">
                            <i class="mdi mdi-clipboard-list mdi-24px"></i>
                          </div>
                          <div>
                            <h6 class="card-title">Pesanan Pending</h6>
                            <h3 class="mb-0"><?= $pesananPending ?? 0 ?></h3>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="icon-wrapper bg-info text-white rounded-circle p-3 me-3">
                            <i class="mdi mdi-factory mdi-24px"></i>
                          </div>
                          <div>
                            <h6 class="card-title">Produksi Hari Ini</h6>
                            <h3 class="mb-0"><?= $produksiHariIni ?? 0 ?></h3>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-lg-8 mb-4">
                    <div class="card">
                      <div class="card-body">
                        <h4 class="card-title">Pesanan Terbaru</h4>
                        <div class="table-responsive">
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th>Kode</th>
                                <th>Customer</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php if (!empty($pesananTerbaru)): ?>
                                <?php foreach ($pesananTerbaru as $pesanan): ?>
                                  <tr>
                                    <td><?= $pesanan['kode'] ?? '-' ?></td>
                                    <td><?= $pesanan['customer'] ?? '-' ?></td>
                                    <td><?= $pesanan['tanggal'] ?? '-' ?></td>
                                    <td>Rp <?= number_format($pesanan['total'] ?? 0, 0, ',', '.') ?></td>
                                    <td>
                                      <span class="badge bg-<?= $pesanan['status'] == 'pending' ? 'warning' : ($pesanan['status'] == 'approved' ? 'success' : 'secondary') ?>">
                                        <?= ucfirst($pesanan['status'] ?? 'unknown') ?>
                                      </span>
                                    </td>
                                  </tr>
                                <?php endforeach; ?>
                              <?php else: ?>
                                <tr>
                                  <td colspan="5" class="text-center">Belum ada data pesanan</td>
                                </tr>
                              <?php endif; ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-lg-4 mb-4">
                    <div class="card">
                      <div class="card-body">
                        <h4 class="card-title">Status Produksi</h4>
                        <div class="mt-3">
                          <?php if (!empty($statusProduksi)): ?>
                            <?php foreach ($statusProduksi as $produksi): ?>
                              <div class="d-flex justify-content-between align-items-center mb-3">
                                <span><?= $produksi['nama'] ?? 'Produk Unknown' ?></span>
                                <span class="badge bg-<?= $produksi['status'] == 'completed' ? 'success' : ($produksi['status'] == 'approved' ? 'warning' : 'secondary') ?>">
                                  <?= ucfirst($produksi['status'] ?? 'draft') ?>
                                </span>
                              </div>
                            <?php endforeach; ?>
                          <?php else: ?>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                              <span>NPK 15-15-15</span>
                              <span class="badge bg-success">Completed</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                              <span>NPK 16-16-16</span>
                              <span class="badge bg-warning">In Progress</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                              <span>NPK 20-10-10</span>
                              <span class="badge bg-secondary">Draft</span>
                            </div>
                          <?php endif; ?>
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
  <?php include 'template/script.php'; ?>
</body>

</html>