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
                    <h4 class="card-title mb-0">Data Customer</h4>
                    <p class="text-muted">Kelola data customer dan distributor pupuk NPK</p>
                  </div>
                  <div>
                    <a href="?controller=customer&action=add" class="btn btn-primary">
                      <i class="mdi mdi-plus"></i> Tambah Customer
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
                                <th>Nama Customer</th>
                                <th>Alamat</th>
                                <th>Telepon</th>
                                <th>Tanggal Daftar</th>
                                <th>Aksi</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php if (!empty($customer)): ?>
                                <?php $no = 1; foreach ($customer as $item): ?>
                                  <tr>
                                    <td><?= $no++ ?></td>
                                    <td><span class="badge bg-info"><?= $item['kode'] ?></span></td>
                                    <td><?= $item['nama'] ?></td>
                                    <td><?= substr($item['alamat'], 0, 50) ?><?= strlen($item['alamat']) > 50 ? '...' : '' ?></td>
                                    <td><?= $item['telepon'] ?? '-' ?></td>
                                    <td><?= date('d/m/Y', strtotime($item['created_at'])) ?></td>
                                    <td>
                                      <a href="?controller=customer&action=edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="mdi mdi-pencil"></i>
                                      </a>
                                      <a href="?controller=customer&action=delete&id=<?= $item['id'] ?>" 
                                         class="btn btn-sm btn-danger"
                                         onclick="return confirm('Yakin ingin menghapus customer ini?')">
                                        <i class="mdi mdi-delete"></i>
                                      </a>
                                    </td>
                                  </tr>
                                <?php endforeach; ?>
                              <?php else: ?>
                                <tr>
                                  <td colspan="7" class="text-center">Belum ada data customer</td>
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