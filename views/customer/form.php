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
                    <h4 class="card-title mb-0">
                      <?= $action == 'add' ? 'Tambah' : 'Edit' ?> Customer
                    </h4>
                    <p class="text-muted">Form untuk <?= $action == 'add' ? 'menambah' : 'mengedit' ?> data customer</p>
                  </div>
                  <div>
                    <a href="?controller=customer" class="btn btn-secondary">
                      <i class="mdi mdi-arrow-left"></i> Kembali
                    </a>
                  </div>
                </div>
                
                <?php if (isset($_SESSION['error'])): ?>
                  <div class="alert alert-danger mt-3">
                    <?= $_SESSION['error'] ?>
                  </div>
                  <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                
                <div class="row mt-4">
                  <div class="col-lg-8">
                    <div class="card">
                      <div class="card-body">
                        <form method="POST">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label for="kode">Kode Customer</label>
                                <input type="text" class="form-control" id="kode" name="kode" 
                                       value="<?= $action == 'add' ? $kode : $customer['kode'] ?>" 
                                       <?= $action == 'add' ? 'readonly' : '' ?> required>
                                <small class="form-text text-muted">Kode customer unik (auto generate untuk data baru)</small>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label for="nama">Nama Customer</label>
                                <input type="text" class="form-control" id="nama" name="nama" 
                                       value="<?= $action == 'edit' ? $customer['nama'] : '' ?>" 
                                       placeholder="Contoh: Toko Tani Jaya" required>
                              </div>
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="4" 
                                      placeholder="Alamat lengkap customer" required><?= $action == 'edit' ? $customer['alamat'] : '' ?></textarea>
                          </div>
                          
                          <div class="form-group">
                            <label for="telepon">Telepon</label>
                            <input type="text" class="form-control" id="telepon" name="telepon" 
                                   value="<?= $action == 'edit' ? $customer['telepon'] : '' ?>" 
                                   placeholder="Nomor telepon atau HP">
                            <small class="form-text text-muted">Format: 08xxxxxxxxxx atau (021) xxxxxxx</small>
                          </div>
                          
                          <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                              <i class="mdi mdi-content-save"></i> 
                              <?= $action == 'add' ? 'Simpan' : 'Update' ?>
                            </button>
                            <a href="?controller=customer" class="btn btn-secondary">
                              <i class="mdi mdi-cancel"></i> Batal
                            </a>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-lg-4">
                    <div class="card">
                      <div class="card-body">
                        <h5 class="card-title">Informasi</h5>
                        <div class="mt-3">
                          <p class="text-muted">
                            <strong>Kode Customer:</strong><br>
                            Kode unik untuk setiap customer dengan format CST001, CST002, dst.
                          </p>
                          <p class="text-muted">
                            <strong>Data Customer:</strong><br>
                            • Nama: Nama perusahaan atau toko<br>
                            • Alamat: Alamat lengkap untuk pengiriman<br>
                            • Telepon: Kontak untuk koordinasi
                          </p>
                          <p class="text-muted">
                            <strong>Catatan:</strong><br>
                            Data customer akan digunakan untuk pembuatan pesanan dan pengiriman pupuk NPK.
                          </p>
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