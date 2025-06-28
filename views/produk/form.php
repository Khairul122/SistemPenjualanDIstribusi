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
                      <?= $action == 'add' ? 'Tambah' : 'Edit' ?> Produk
                    </h4>
                    <p class="text-muted">Form untuk <?= $action == 'add' ? 'menambah' : 'mengedit' ?> data produk</p>
                  </div>
                  <div>
                    <a href="?controller=produk" class="btn btn-secondary">
                      <i class="mdi mdi-arrow-left"></i> Kembali
                    </a>
                  </div>
                </div>
                
                <div class="row mt-4">
                  <div class="col-lg-8">
                    <div class="card">
                      <div class="card-body">
                        <form method="POST">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label for="kode">Kode Produk</label>
                                <input type="text" class="form-control" id="kode" name="kode" 
                                       value="<?= $action == 'add' ? $kode : $produk['kode'] ?>" 
                                       <?= $action == 'add' ? 'readonly' : '' ?> required>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label for="nama">Nama Produk</label>
                                <input type="text" class="form-control" id="nama" name="nama" 
                                       value="<?= $action == 'edit' ? $produk['nama'] : '' ?>" 
                                       placeholder="Contoh: NPK 15-15-15" required>
                              </div>
                            </div>
                          </div>
                          
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label for="formula">Formula</label>
                                <input type="text" class="form-control" id="formula" name="formula" 
                                       value="<?= $action == 'edit' ? $produk['formula'] : '' ?>" 
                                       placeholder="Contoh: 15-15-15" required>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label for="harga">Harga (Rp)</label>
                                <input type="number" class="form-control" id="harga" name="harga" 
                                       value="<?= $action == 'edit' ? $produk['harga'] : '' ?>" 
                                       placeholder="Harga per ton" required>
                              </div>
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label for="stok">Stok (ton)</label>
                            <input type="number" class="form-control" id="stok" name="stok" 
                                   value="<?= $action == 'edit' ? $produk['stok'] : '0' ?>" 
                                   placeholder="Jumlah stok dalam ton" required>
                          </div>
                          
                          <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                              <i class="mdi mdi-content-save"></i> 
                              <?= $action == 'add' ? 'Simpan' : 'Update' ?>
                            </button>
                            <a href="?controller=produk" class="btn btn-secondary">
                              <i class="mdi mdi-cancel"></i> Batal
                            </a>
                          </div>
                        </form>
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