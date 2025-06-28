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
                    <h4 class="card-title mb-0">Data Produksi</h4>
                    <p class="text-muted">Kelola jadwal dan hasil produksi pupuk NPK</p>
                  </div>
                  <div>
                    <a href="?controller=produksi&action=add" class="btn btn-primary">
                      <i class="mdi mdi-plus"></i> Tambah Produksi
                    </a>
                  </div>
                </div>
                
                <?php if (isset($_SESSION['success'])): ?>
                  <div class="alert alert-success mt-3">
                    <?= $_SESSION['success'] ?>
                  </div>
                  <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                  <div class="alert alert-danger mt-3">
                    <?= $_SESSION['error'] ?>
                  </div>
                  <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                
                <div class="row mt-4">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th>Formula</th>
                                <th>Jumlah</th>
                                <th>Shift</th>
                                <th>Status</th>
                                <th>Dibuat Oleh</th>
                                <th>Aksi</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php if (!empty($produksi)): ?>
                                <?php $no = 1; foreach ($produksi as $item): ?>
                                  <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= date('d/m/Y', strtotime($item['tanggal'])) ?></td>
                                    <td><?= $item['produk_nama'] ?? 'Produk Dihapus' ?></td>
                                    <td><?= $item['formula'] ?? '-' ?></td>
                                    <td><?= $item['jumlah'] ?> ton</td>
                                    <td>
                                      <span class="badge bg-<?= $item['shift'] == 'pagi' ? 'info' : ($item['shift'] == 'siang' ? 'warning' : 'dark') ?>">
                                        <?= ucfirst($item['shift']) ?>
                                      </span>
                                    </td>
                                    <td>
                                      <span class="badge bg-<?= $item['status'] == 'completed' ? 'success' : ($item['status'] == 'approved' ? 'warning' : 'secondary') ?>">
                                        <?= ucfirst($item['status']) ?>
                                      </span>
                                    </td>
                                    <td><?= $item['user_nama'] ?? 'Unknown' ?></td>
                                    <td>
                                      <a href="?controller=produksi&action=edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="mdi mdi-pencil"></i>
                                      </a>
                                      <a href="?controller=produksi&action=delete&id=<?= $item['id'] ?>" 
                                         class="btn btn-sm btn-danger"
                                         onclick="return confirm('Yakin ingin menghapus data produksi ini?')">
                                        <i class="mdi mdi-delete"></i>
                                      </a>
                                    </td>
                                  </tr>
                                <?php endforeach; ?>
                              <?php else: ?>
                                <tr>
                                  <td colspan="9" class="text-center">Belum ada data produksi</td>
                                </tr>
                              <?php endif; ?>
                            </tbody>
                          </table>
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