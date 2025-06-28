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
                    <h4 class="card-title mb-0">Data Produk</h4>
                    <p class="text-muted">Kelola data produk pupuk NPK</p>
                  </div>
                  <div>
                    <a href="?controller=produk&action=add" class="btn btn-primary">
                      <i class="mdi mdi-plus"></i> Tambah Produk
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
                                <th>Kode</th>
                                <th>Nama Produk</th>
                                <th>Formula</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php if (!empty($produk)): ?>
                                <?php $no = 1; foreach ($produk as $item): ?>
                                  <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $item['kode'] ?></td>
                                    <td><?= $item['nama'] ?></td>
                                    <td><?= $item['formula'] ?></td>
                                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                    <td><?= $item['stok'] ?> ton</td>
                                    <td>
                                      <a href="?controller=produk&action=edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="mdi mdi-pencil"></i>
                                      </a>
                                      <a href="?controller=produk&action=delete&id=<?= $item['id'] ?>" 
                                         class="btn btn-sm btn-danger"
                                         onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                        <i class="mdi mdi-delete"></i>
                                      </a>
                                    </td>
                                  </tr>
                                <?php endforeach; ?>
                              <?php else: ?>
                                <tr>
                                  <td colspan="7" class="text-center">Belum ada data produk</td>
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