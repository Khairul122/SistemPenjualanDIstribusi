<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
    <div class="me-3">
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
        <span class="icon-menu"></span>
      </button>
    </div>
    <div>
      <a class="navbar-brand brand-logo" href="?controller=dashboard">
        <img src="Logo.png" alt="SIMPD Pupuk NPK" style="max-height: 40px; width: auto;" />
      </a>
      <a class="navbar-brand brand-logo-mini" href="?controller=dashboard">
        <img src="Logo.png" alt="SIMPD" style="max-height: 35px; width: auto;" />
      </a>
    </div>
  </div>
  
  <div class="navbar-menu-wrapper d-flex align-items-top">
    <!-- Welcome Text - Desktop -->
    <ul class="navbar-nav">
      <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
        <h1 class="welcome-text mb-0" style="font-size: 1.5rem;">
          <?php 
            $jam = date('H');
            $hari = date('l');
            $tanggal = date('d F Y');
            
            // Greeting berdasarkan waktu
            if ($jam >= 5 && $jam < 12) {
              $greeting = "Selamat Pagi";
              $icon = "☀️";
            } elseif ($jam >= 12 && $jam < 15) {
              $greeting = "Selamat Siang";
              $icon = "🌞";
            } elseif ($jam >= 15 && $jam < 18) {
              $greeting = "Selamat Sore";
              $icon = "🌅";
            } else {
              $greeting = "Selamat Malam";
              $icon = "🌙";
            }
            
            // Hari dalam bahasa Indonesia
            $hari_indo = [
              'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
              'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'
            ];
            
            // Bulan dalam bahasa Indonesia
            $bulan_indo = [
              'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
              'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
              'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
              'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
            ];
            
            $tanggal_indo = str_replace(array_keys($bulan_indo), array_values($bulan_indo), $tanggal);
          ?>
          <span class="me-2"><?= $icon ?></span><?= $greeting ?>, 
          <span class="text-black fw-bold"><?= $_SESSION['nama'] ?? 'User' ?></span>
        </h1>
        <p class="text-muted mb-0 small">
          <i class="mdi mdi-calendar-clock me-1"></i>
          <?= $hari_indo[$hari] ?? $hari ?>, <?= $tanggal_indo ?>
        </p>
      </li>
    </ul>
    
    <!-- Mobile Welcome - Tablet -->
    <ul class="navbar-nav d-lg-none d-md-block">
      <li class="nav-item">
        <span class="text-dark fw-bold"><?= $_SESSION['nama'] ?? 'User' ?></span>
        <br><small class="text-muted"><?= ucfirst($_SESSION['role'] ?? 'user') ?></small>
      </li>
    </ul>
    
    <!-- Right Side Menu -->
    <ul class="navbar-nav ms-auto">
      <!-- Notifications - Desktop/Tablet -->
      <li class="nav-item dropdown d-none d-md-block">
        <a class="nav-link count-indicator" href="#" data-bs-toggle="dropdown">
          <i class="icon-bell"></i>
          <span class="count"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0">
          <a class="dropdown-item py-3 preview-item">
            <div class="preview-thumbnail">
              <div class="preview-icon bg-primary">
                <i class="mdi mdi-information mx-0"></i>
              </div>
            </div>
            <div class="preview-item-content">
              <h6 class="preview-subject fw-normal">Sistem Produksi</h6>
              <p class="fw-light small-text mb-0 text-muted">Selamat datang di SIMPD Pupuk NPK</p>
            </div>
          </a>
        </div>
      </li>
      
      <!-- Quick Actions - Desktop -->
      <li class="nav-item dropdown d-none d-lg-block">
        <a class="nav-link" href="#" data-bs-toggle="dropdown">
          <i class="mdi mdi-plus-circle-outline"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown">
          <div class="dropdown-header">Quick Actions</div>
          <a class="dropdown-item" href="?controller=pesanan&action=add">
            <i class="mdi mdi-clipboard-plus me-2"></i>Tambah Pesanan
          </a>
          <a class="dropdown-item" href="?controller=produksi&action=add">
            <i class="mdi mdi-factory me-2"></i>Tambah Produksi
          </a>
          <a class="dropdown-item" href="?controller=pengiriman&action=add">
            <i class="mdi mdi-truck me-2"></i>Tambah Pengiriman
          </a>
        </div>
      </li>
      
      <!-- User Profile Dropdown -->
      <li class="nav-item dropdown user-dropdown">
        <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
          <img class="img-xs rounded-circle" src="Logo.png" alt="Profile" 
               style="width: 35px; height: 35px; object-fit: cover;" />
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
          <div class="dropdown-header text-center">
            <img class="img-md rounded-circle mb-2" src="Logo.png" alt="Profile" 
                 style="width: 60px; height: 60px; object-fit: cover;" />
            <p class="mb-1 mt-2 fw-bold"><?= $_SESSION['nama'] ?? 'User' ?></p>
            <p class="fw-light text-muted mb-1 small"><?= $_SESSION['username'] ?? 'username' ?></p>
            <span class="badge bg-<?= $_SESSION['role'] == 'admin' ? 'primary' : 'success' ?> mb-2">
              <?= $_SESSION['role'] == 'admin' ? 'Administrator' : 'Pimpinan' ?>
            </span>
          </div>
          
          <div class="dropdown-divider"></div>
          
          <a class="dropdown-item" href="?controller=dashboard">
            <i class="dropdown-item-icon mdi mdi-view-dashboard text-primary me-2"></i>
            Dashboard
          </a>
          
          <?php if ($_SESSION['role'] == 'admin'): ?>
          <a class="dropdown-item" href="?controller=produk">
            <i class="dropdown-item-icon mdi mdi-package-variant text-success me-2"></i>
            Kelola Produk
          </a>
          <a class="dropdown-item" href="?controller=customer">
            <i class="dropdown-item-icon mdi mdi-account-multiple text-info me-2"></i>
            Kelola Customer
          </a>
          <?php endif; ?>
          
          <div class="dropdown-divider"></div>
          
          <a class="dropdown-item" href="?controller=auth&action=logout">
            <i class="dropdown-item-icon mdi mdi-power text-danger me-2"></i>
            Keluar
          </a>
        </div>
      </li>
    </ul>
    
    <!-- Mobile Menu Toggle -->
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" 
            type="button" data-bs-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>

<!-- Live Clock Script -->
<script>
function updateClock() {
  const now = new Date();
  const timeString = now.toLocaleTimeString('id-ID', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  });
  
  const clockElement = document.querySelector('.live-clock');
  if (clockElement) {
    clockElement.textContent = timeString;
  }
}

// Update clock every second
setInterval(updateClock, 1000);
updateClock(); // Initial call
</script>

<style>
.navbar-brand img {
  transition: transform 0.3s ease;
}

.navbar-brand:hover img {
  transform: scale(1.05);
}

.welcome-text {
  background: linear-gradient(45deg, #2E8B57, #228B22);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.count-indicator {
  position: relative;
}

.count {
  position: absolute;
  top: -2px;
  right: -2px;
  background: #dc3545;
  color: white;
  border-radius: 50%;
  width: 8px;
  height: 8px;
  font-size: 10px;
}

@media (max-width: 768px) {
  .welcome-text {
    font-size: 1.2rem !important;
  }
  
  .navbar-brand img {
    max-height: 30px !important;
  }
  
  .img-xs {
    width: 30px !important;
    height: 30px !important;
  }
}

@media (max-width: 576px) {
  .welcome-text {
    font-size: 1rem !important;
  }
  
  .navbar-brand img {
    max-height: 25px !important;
  }
}
</style>