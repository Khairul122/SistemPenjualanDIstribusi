<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="?controller=dashboard">
        <i class="mdi mdi-grid-large menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    
    <?php if ($_SESSION['role'] == 'admin'): ?>
    <li class="nav-item">
      <a class="nav-link" href="?controller=produk">
        <i class="mdi mdi-package-variant menu-icon"></i>
        <span class="menu-title">Data Produk</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="?controller=produksi">
        <i class="mdi mdi-factory menu-icon"></i>
        <span class="menu-title">Data Produksi</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="?controller=customer">
        <i class="mdi mdi-account-multiple menu-icon"></i>
        <span class="menu-title">Data Customer</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="?controller=pesanan">
        <i class="mdi mdi-clipboard-text menu-icon"></i>
        <span class="menu-title">Data Pesanan</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="?controller=pengiriman">
        <i class="mdi mdi-truck menu-icon"></i>
        <span class="menu-title">Data Pengiriman</span>
      </a>
    </li>
    <?php endif; ?>
    
    <?php if ($_SESSION['role'] == 'pimpinan'): ?>
    
    <li class="nav-item">
      <a class="nav-link" href="?controller=laporan">
        <i class="mdi mdi-chart-line menu-icon"></i>
        <span class="menu-title">Laporan</span>
      </a>
    </li>
    <?php endif; ?>
    
    <li class="nav-item">
      <a class="nav-link" href="?controller=auth&action=logout">
        <i class="mdi mdi-logout menu-icon"></i>
        <span class="menu-title">Logout</span>
      </a>
    </li>
  </ul>
</nav>