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
                      <?= $action == 'add' ? 'Tambah' : 'Edit' ?> Pesanan
                    </h4>
                    <p class="text-muted">Form untuk <?= $action == 'add' ? 'menambah' : 'mengedit' ?> pesanan customer</p>
                  </div>
                  <div>
                    <a href="?controller=pesanan" class="btn btn-secondary">
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
                  <div class="col-12">
                    <div class="card">
                      <div class="card-body">
                        <form method="POST">
                          <div class="row">
                            <div class="col-md-3">
                              <div class="form-group">
                                <label for="kode">Kode Pesanan</label>
                                <input type="text" class="form-control" id="kode" name="kode" 
                                       value="<?= $action == 'add' ? $kode : $pesanan['kode'] ?>" 
                                       <?= $action == 'add' ? 'readonly' : 'readonly' ?> required>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label for="customer_id">Customer</label>
                                <select class="form-control" id="customer_id" name="customer_id" required>
                                  <option value="">Pilih Customer</option>
                                  <?php foreach ($customer as $item): ?>
                                    <option value="<?= $item['id'] ?>" 
                                            <?= ($action == 'edit' && $pesanan['customer_id'] == $item['id']) ? 'selected' : '' ?>>
                                      <?= $item['kode'] ?> - <?= $item['nama'] ?>
                                    </option>
                                  <?php endforeach; ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label for="tanggal">Tanggal Pesanan</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                       value="<?= $action == 'edit' ? $pesanan['tanggal'] : date('Y-m-d') ?>" required>
                              </div>
                            </div>
                            <div class="col-md-2">
                              <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                  <option value="pending" <?= ($action == 'edit' && $pesanan['status'] == 'pending') ? 'selected' : '' ?>>
                                    Pending
                                  </option>
                                  <option value="approved" <?= ($action == 'edit' && $pesanan['status'] == 'approved') ? 'selected' : '' ?>>
                                    Approved
                                  </option>
                                  <option value="selesai" <?= ($action == 'edit' && $pesanan['status'] == 'selesai') ? 'selected' : '' ?>>
                                    Selesai
                                  </option>
                                </select>
                              </div>
                            </div>
                          </div>
                          
                          <hr>
                          <h5>Detail Pesanan</h5>
                          
                          <div id="detail-container">
                            <?php if ($action == 'edit' && !empty($detail)): ?>
                              <?php foreach ($detail as $index => $item): ?>
                                <div class="row detail-row mb-3">
                                  <div class="col-md-4">
                                    <label>Produk</label>
                                    <select class="form-control produk-select" name="produk_id[]" onchange="updateHarga(this)">
                                      <option value="">Pilih Produk</option>
                                      <?php foreach ($produk as $p): ?>
                                        <option value="<?= $p['id'] ?>" data-harga="<?= $p['harga'] ?>" 
                                                <?= ($item['produk_id'] == $p['id']) ? 'selected' : '' ?>>
                                          <?= $p['nama'] ?> (<?= $p['formula'] ?>) - Stok: <?= $p['stok'] ?>
                                        </option>
                                      <?php endforeach; ?>
                                    </select>
                                  </div>
                                  <div class="col-md-2">
                                    <label>Jumlah (ton)</label>
                                    <input type="number" class="form-control jumlah-input" name="jumlah[]" 
                                           value="<?= $item['jumlah'] ?>" step="0.1" onchange="hitungSubtotal(this)">
                                  </div>
                                  <div class="col-md-3">
                                    <label>Harga per ton</label>
                                    <input type="number" class="form-control harga-input" name="harga[]" 
                                           value="<?= $item['harga'] ?>" onchange="hitungSubtotal(this)">
                                  </div>
                                  <div class="col-md-2">
                                    <label>Subtotal</label>
                                    <input type="text" class="form-control subtotal-display" readonly 
                                           value="Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>">
                                  </div>
                                  <div class="col-md-1">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-sm d-block" onclick="hapusDetail(this)">
                                      <i class="mdi mdi-delete"></i>
                                    </button>
                                  </div>
                                </div>
                              <?php endforeach; ?>
                            <?php else: ?>
                              <div class="row detail-row mb-3">
                                <div class="col-md-4">
                                  <label>Produk</label>
                                  <select class="form-control produk-select" name="produk_id[]" onchange="updateHarga(this)">
                                    <option value="">Pilih Produk</option>
                                    <?php foreach ($produk as $p): ?>
                                      <option value="<?= $p['id'] ?>" data-harga="<?= $p['harga'] ?>">
                                        <?= $p['nama'] ?> (<?= $p['formula'] ?>) - Stok: <?= $p['stok'] ?>
                                      </option>
                                    <?php endforeach; ?>
                                  </select>
                                </div>
                                <div class="col-md-2">
                                  <label>Jumlah (ton)</label>
                                  <input type="number" class="form-control jumlah-input" name="jumlah[]" 
                                         step="0.1" onchange="hitungSubtotal(this)">
                                </div>
                                <div class="col-md-3">
                                  <label>Harga per ton</label>
                                  <input type="number" class="form-control harga-input" name="harga[]" 
                                         onchange="hitungSubtotal(this)">
                                </div>
                                <div class="col-md-2">
                                  <label>Subtotal</label>
                                  <input type="text" class="form-control subtotal-display" readonly>
                                </div>
                                <div class="col-md-1">
                                  <label>&nbsp;</label>
                                  <button type="button" class="btn btn-danger btn-sm d-block" onclick="hapusDetail(this)">
                                    <i class="mdi mdi-delete"></i>
                                  </button>
                                </div>
                              </div>
                            <?php endif; ?>
                          </div>
                          
                          <div class="row mb-3">
                            <div class="col-12">
                              <button type="button" class="btn btn-success btn-sm" onclick="tambahDetail()">
                                <i class="mdi mdi-plus"></i> Tambah Item
                              </button>
                            </div>
                          </div>
                          
                          <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                              <div class="card">
                                <div class="card-body">
                                  <h5>Total Pesanan: <span id="total-pesanan">Rp 0</span></h5>
                                </div>
                              </div>
                            </div>
                          </div>
                          
                          <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                              <i class="mdi mdi-content-save"></i> 
                              <?= $action == 'add' ? 'Simpan' : 'Update' ?>
                            </button>
                            <a href="?controller=pesanan" class="btn btn-secondary">
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
  
  <script>
    function updateHarga(select) {
      const harga = select.options[select.selectedIndex].getAttribute('data-harga');
      const row = select.closest('.detail-row');
      const hargaInput = row.querySelector('.harga-input');
      if (harga) {
        hargaInput.value = harga;
        hitungSubtotal(hargaInput);
      }
    }
    
    function hitungSubtotal(input) {
      const row = input.closest('.detail-row');
      const jumlah = parseFloat(row.querySelector('.jumlah-input').value) || 0;
      const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
      const subtotal = jumlah * harga;
      
      row.querySelector('.subtotal-display').value = 'Rp ' + subtotal.toLocaleString('id-ID');
      hitungTotal();
    }
    
    function hitungTotal() {
      let total = 0;
      document.querySelectorAll('.detail-row').forEach(row => {
        const jumlah = parseFloat(row.querySelector('.jumlah-input').value) || 0;
        const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
        total += jumlah * harga;
      });
      
      document.getElementById('total-pesanan').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
    
    function tambahDetail() {
      const container = document.getElementById('detail-container');
      const newRow = container.querySelector('.detail-row').cloneNode(true);
      
      newRow.querySelectorAll('input').forEach(input => input.value = '');
      newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
      
      container.appendChild(newRow);
    }
    
    function hapusDetail(btn) {
      const rows = document.querySelectorAll('.detail-row');
      if (rows.length > 1) {
        btn.closest('.detail-row').remove();
        hitungTotal();
      }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
      hitungTotal();
    });
  </script>
  
  <?php include 'template/script.php'; ?>
</body>

</html>