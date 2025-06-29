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
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Laporan Sistem Produksi dan Distribusi Pupuk NPK</h4>
                  <p class="card-description">Pabrik Dharmas Plant KUD Lubuk Karya</p>
                  
                  <div class="row">
                    
                    <!-- Laporan Produksi -->
                    <div class="col-md-6 col-xl-6">
                      <div class="card shadow-sm p-3 mb-4 border-primary">
                        <h5 class="card-title text-primary">
                          <i class="mdi mdi-factory"></i> Laporan Produksi Pupuk NPK
                        </h5>
                        
                        <form action="?controller=laporan&action=produksi" method="POST" target="_blank">
                          <div class="form-group">
                            <label>Dari Tanggal <small>(Opsional)</small></label>
                            <input type="date" name="tanggal_dari" class="form-control">
                          </div>
                          <div class="form-group">
                            <label>Sampai Tanggal <small>(Opsional)</small></label>
                            <input type="date" name="tanggal_sampai" class="form-control">
                          </div>
                          <button type="submit" class="btn btn-primary w-100">
                            <i class="mdi mdi-file-pdf"></i> Cetak Laporan Produksi
                          </button>
                        </form>
                      </div>
                    </div>

                    <!-- Laporan Distribusi -->
                    <div class="col-md-6 col-xl-6">
                      <div class="card shadow-sm p-3 mb-4 border-success">
                        <h5 class="card-title text-success">
                          <i class="mdi mdi-truck-delivery"></i> Laporan Distribusi Pupuk NPK
                        </h5>
                        
                        <form action="?controller=laporan&action=distribusi" method="POST" target="_blank">
                          <div class="form-group">
                            <label>Dari Tanggal <small>(Opsional)</small></label>
                            <input type="date" name="tanggal_dari" class="form-control">
                          </div>
                          <div class="form-group">
                            <label>Sampai Tanggal <small>(Opsional)</small></label>
                            <input type="date" name="tanggal_sampai" class="form-control">
                          </div>
                          <button type="submit" class="btn btn-success w-100">
                            <i class="mdi mdi-file-pdf"></i> Cetak Laporan Distribusi
                          </button>
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