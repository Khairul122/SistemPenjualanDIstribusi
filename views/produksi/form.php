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
                      <?= $action == 'add' ? 'Tambah' : 'Edit' ?> Data Produksi
                    </h4>
                    <p class="text-muted">Form untuk <?= $action == 'add' ? 'menambah' : 'mengedit' ?> jadwal produksi</p>
                  </div>
                  <div>
                    <a href="?controller=produksi" class="btn btn-secondary">
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
                                <label for="produk_id">Produk</label>
                                <select class="form-control" id="produk_id" name="produk_id" required>
                                  <option value="">Pilih Produk</option>
                                  <?php foreach ($produk as $item): ?>
                                    <option value="<?= $item['id'] ?>" 
                                            <?= ($action == 'edit' && $produksi['produk_id'] == $item['id']) ? 'selected' : '' ?>>
                                      <?= $item['nama'] ?> (<?= $item['formula'] ?>)
                                    </option>
                                  <?php endforeach; ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label for="tanggal">Tanggal Produksi</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                       value="<?= $action == 'edit' ? $produksi['tanggal'] : date('Y-m-d') ?>" required>
                              </div>
                            </div>
                          </div>
                          
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label for="jumlah">Jumlah (ton)</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" 
                                       value="<?= $action == 'edit' ? $produksi['jumlah'] : '' ?>" 
                                       placeholder="Jumlah dalam ton" step="0.1" required>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label for="shift">Shift</label>
                                <select class="form-control" id="shift" name="shift" required>
                                  <option value="">Pilih Shift</option>
                                  <option value="pagi" <?= ($action == 'edit' && $produksi['shift'] == 'pagi') ? 'selected' : '' ?>>
                                    Pagi (07:00 - 15:00)
                                  </option>
                                  <option value="siang" <?= ($action == 'edit' && $produksi['shift'] == 'siang') ? 'selected' : '' ?>>
                                    Siang (15:00 - 23:00)
                                  </option>
                                  <option value="malam" <?= ($action == 'edit' && $produksi['shift'] == 'malam') ? 'selected' : '' ?>>
                                    Malam (23:00 - 07:00)
                                  </option>
                                </select>
                              </div>
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                              <option value="">Pilih Status</option>
                              <option value="draft" <?= ($action == 'edit' && $produksi['status'] == 'draft') ? 'selected' : '' ?>>
                                Draft
                              </option>
                              <option value="approved" <?= ($action == 'edit' && $produksi['status'] == 'approved') ? 'selected' : '' ?>>
                                Approved
                              </option>
                              <option value="completed" <?= ($action == 'edit' && $produksi['status'] == 'completed') ? 'selected' : '' ?>>
                                Completed
                              </option>
                            </select>
                            <small class="form-text text-muted">
                              Status "Completed" akan menambah stok produk secara otomatis
                            </small>
                          </div>
                          
                          <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                              <i class="mdi mdi-content-save"></i> 
                              <?= $action == 'add' ? 'Simpan' : 'Update' ?>
                            </button>
                            <a href="?controller=produksi" class="btn btn-secondary">
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
                            <strong>Shift Kerja:</strong><br>
                            • Pagi: 07:00 - 15:00<br>
                            • Siang: 15:00 - 23:00<br>
                            • Malam: 23:00 - 07:00
                          </p>
                          <p class="text-muted">
                            <strong>Status:</strong><br>
                            • Draft: Rencana produksi<br>
                            • Approved: Disetujui untuk diproduksi<br>
                            • Completed: Produksi selesai
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