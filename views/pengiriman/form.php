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
                      <?= $action == 'add' ? 'Tambah' : 'Edit' ?> Pengiriman
                    </h4>
                    <p class="text-muted">Form untuk <?= $action == 'add' ? 'menambah' : 'mengedit' ?> data pengiriman</p>
                  </div>
                  <div>
                    <a href="?controller=pengiriman" class="btn btn-secondary">
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
                          <div class="form-group">
                            <label for="pesanan_id">Pesanan</label>
                            <select class="form-control" id="pesanan_id" name="pesanan_id" required onchange="showPesananInfo()">
                              <option value="">Pilih Pesanan</option>
                              <?php foreach ($pesanan as $item): ?>
                                <option value="<?= $item['id'] ?>" 
                                        data-customer="<?= $item['customer_nama'] ?? '' ?>" 
                                        data-total="<?= number_format($item['total'] ?? 0, 0, ',', '.') ?>"
                                        <?= ($action == 'edit' && $pengiriman['pesanan_id'] == $item['id']) ? 'selected' : '' ?>>
                                  <?= $item['kode'] ?> - <?= $item['customer_nama'] ?? 'Customer Unknown' ?> 
                                  (Rp <?= number_format($item['total'] ?? 0, 0, ',', '.') ?>)
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                          
                          <div id="pesanan-info" class="alert alert-info" style="display: none;">
                            <strong>Info Pesanan:</strong><br>
                            Customer: <span id="info-customer">-</span><br>
                            Total: Rp <span id="info-total">-</span>
                          </div>
                          
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label for="tanggal_kirim">Tanggal Kirim</label>
                                <input type="date" class="form-control" id="tanggal_kirim" name="tanggal_kirim" 
                                       value="<?= $action == 'edit' ? $pengiriman['tanggal_kirim'] : date('Y-m-d') ?>" required>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label for="driver">Driver/Supir</label>
                                <input type="text" class="form-control" id="driver" name="driver" 
                                       value="<?= $action == 'edit' ? $pengiriman['driver'] : '' ?>" 
                                       placeholder="Nama driver atau supir">
                              </div>
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label for="status">Status Pengiriman</label>
                            <select class="form-control" id="status" name="status" required>
                              <option value="">Pilih Status</option>
                              <option value="siap" <?= ($action == 'edit' && $pengiriman['status'] == 'siap') ? 'selected' : '' ?>>
                                Siap Kirim
                              </option>
                              <option value="dikirim" <?= ($action == 'edit' && $pengiriman['status'] == 'dikirim') ? 'selected' : '' ?>>
                                Sedang Dikirim
                              </option>
                              <option value="sampai" <?= ($action == 'edit' && $pengiriman['status'] == 'sampai') ? 'selected' : '' ?>>
                                Sudah Sampai
                              </option>
                            </select>
                          </div>
                          
                          <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                              <i class="mdi mdi-content-save"></i> 
                              <?= $action == 'add' ? 'Simpan' : 'Update' ?>
                            </button>
                            <a href="?controller=pengiriman" class="btn btn-secondary">
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
                        <h5 class="card-title">Informasi Pengiriman</h5>
                        <div class="mt-3">
                          <p class="text-muted">
                            <strong>Status Pengiriman:</strong><br>
                            • <span class="badge bg-secondary">Siap</span> Pesanan siap untuk dikirim<br>
                            • <span class="badge bg-warning">Dikirim</span> Sedang dalam perjalanan<br>
                            • <span class="badge bg-success">Sampai</span> Sudah sampai ke customer
                          </p>
                          <p class="text-muted">
                            <strong>Catatan:</strong><br>
                            • Hanya pesanan dengan status "Approved" yang bisa dikirim<br>
                            • Satu pesanan hanya bisa memiliki satu pengiriman aktif<br>
                            • Driver/supir bersifat opsional
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
  
  <script>
    function showPesananInfo() {
      const select = document.getElementById('pesanan_id');
      const option = select.options[select.selectedIndex];
      const infoDiv = document.getElementById('pesanan-info');
      
      if (option.value) {
        const customer = option.getAttribute('data-customer');
        const total = option.getAttribute('data-total');
        
        document.getElementById('info-customer').textContent = customer;
        document.getElementById('info-total').textContent = total;
        infoDiv.style.display = 'block';
      } else {
        infoDiv.style.display = 'none';
      }
    }
    
    // Show info on page load if editing
    document.addEventListener('DOMContentLoaded', function() {
      showPesananInfo();
    });
  </script>
  
  <?php include 'template/script.php'; ?>
</body>

</html>