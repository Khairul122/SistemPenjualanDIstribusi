<?php
class DashboardController {
    
    public function __construct() {
        $this->checkAuth();
    }
    
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=auth');
            exit;
        }
    }
    
    public function index() {
        $role = $_SESSION['role'];
        
        if ($role == 'admin') {
            $this->admin();
        } elseif ($role == 'pimpinan') {
            $this->pimpinan();
        } else {
            header('Location: ?controller=auth&action=logout');
            exit;
        }
    }
    
    public function admin() {
        if ($_SESSION['role'] != 'admin') {
            header('Location: ?controller=dashboard');
            exit;
        }
        
        $data = $this->getAdminData();
        loadView('dashboard/admin', $data);
    }
    
    public function pimpinan() {
        if ($_SESSION['role'] != 'pimpinan') {
            header('Location: ?controller=dashboard');
            exit;
        }
        
        $data = $this->getPimpinanData();
        loadView('dashboard/pimpinan', $data);
    }
    
    private function getAdminData() {
        try {
            $db = $GLOBALS['db'];
            
            $totalCustomer = $this->getCount('customer');
            $totalProduk = $this->getCount('produk');
            $pesananPending = $this->getCountWhere('pesanan', 'status', 'pending');
            $produksiHariIni = $this->getCountWhere('produksi', 'tanggal', date('Y-m-d'));
            
            $pesananTerbaru = $this->getPesananTerbaru();
            $statusProduksi = $this->getStatusProduksi();
            
            return [
                'totalCustomer' => $totalCustomer ?: 0,
                'totalProduk' => $totalProduk ?: 0,
                'pesananPending' => $pesananPending ?: 0,
                'produksiHariIni' => $produksiHariIni ?: 0,
                'pesananTerbaru' => $pesananTerbaru ?: [],
                'statusProduksi' => $statusProduksi ?: []
            ];
        } catch (Exception $e) {
            return [
                'totalCustomer' => 0,
                'totalProduk' => 0,
                'pesananPending' => 0,
                'produksiHariIni' => 0,
                'pesananTerbaru' => [],
                'statusProduksi' => []
            ];
        }
    }
    
    private function getPimpinanData() {
        try {
            $omzetBulanIni = $this->getOmzetBulanIni();
            $targetProduksi = $this->getTargetProduksi();
            $pengirimanBulanIni = $this->getCountThisMonth('pengiriman');
            $customerAktif = $this->getCount('customer');
            
            $topProduk = $this->getTopProduk();
            $ringkasanOperasional = $this->getRingkasanOperasional();
            
            return [
                'omzetBulanIni' => $omzetBulanIni ?: 0,
                'targetProduksi' => $targetProduksi ?: ['total' => 0, 'target' => 800, 'persentase' => 0],
                'pengirimanBulanIni' => $pengirimanBulanIni ?: 0,
                'customerAktif' => $customerAktif ?: 0,
                'topProduk' => $topProduk ?: [],
                'ringkasanOperasional' => $ringkasanOperasional
            ];
        } catch (Exception $e) {
            return [
                'omzetBulanIni' => 0,
                'targetProduksi' => ['total' => 0, 'target' => 800, 'persentase' => 0],
                'pengirimanBulanIni' => 0,
                'customerAktif' => 0,
                'topProduk' => [],
                'ringkasanOperasional' => ['efisiensi' => 0, 'onTimeDelivery' => 0, 'customerSatisfaction' => 0]
            ];
        }
    }
    
    private function getCount($table) {
        try {
            $db = $GLOBALS['db'];
            $stmt = $db->query("SELECT COUNT(*) as total FROM $table");
            $result = $stmt->fetch();
            return $result['total'];
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getCountWhere($table, $column, $value) {
        try {
            $db = $GLOBALS['db'];
            $stmt = $db->prepare("SELECT COUNT(*) as total FROM $table WHERE $column = ?");
            $stmt->execute([$value]);
            $result = $stmt->fetch();
            return $result['total'];
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getCountThisMonth($table) {
        try {
            $db = $GLOBALS['db'];
            $stmt = $db->query("SELECT COUNT(*) as total FROM $table WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())");
            $result = $stmt->fetch();
            return $result['total'];
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getPesananTerbaru() {
        try {
            $db = $GLOBALS['db'];
            $stmt = $db->query("SELECT p.kode, c.nama as customer, p.tanggal, p.total, p.status 
                               FROM pesanan p 
                               LEFT JOIN customer c ON p.customer_id = c.id 
                               ORDER BY p.created_at DESC LIMIT 5");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getStatusProduksi() {
        try {
            $db = $GLOBALS['db'];
            $stmt = $db->query("SELECT pr.nama, p.status 
                               FROM produksi p 
                               LEFT JOIN produk pr ON p.produk_id = pr.id 
                               ORDER BY p.created_at DESC LIMIT 5");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getOmzetBulanIni() {
        try {
            $db = $GLOBALS['db'];
            $stmt = $db->query("SELECT COALESCE(SUM(total), 0) as omzet 
                               FROM pesanan 
                               WHERE MONTH(tanggal) = MONTH(NOW()) 
                               AND YEAR(tanggal) = YEAR(NOW())
                               AND status != 'pending'");
            $result = $stmt->fetch();
            return $result['omzet'];
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getTargetProduksi() {
        try {
            $db = $GLOBALS['db'];
            $target = 800;
            $stmt = $db->query("SELECT COALESCE(SUM(jumlah), 0) as total 
                               FROM produksi 
                               WHERE MONTH(tanggal) = MONTH(NOW()) 
                               AND YEAR(tanggal) = YEAR(NOW())");
            $result = $stmt->fetch();
            $persentase = $target > 0 ? round(($result['total'] / $target) * 100, 1) : 0;
            
            return [
                'total' => $result['total'],
                'target' => $target,
                'persentase' => $persentase
            ];
        } catch (Exception $e) {
            return ['total' => 0, 'target' => 800, 'persentase' => 0];
        }
    }
    
    private function getTopProduk() {
        try {
            $db = $GLOBALS['db'];
            $stmt = $db->query("SELECT pr.nama, COALESCE(SUM(p.jumlah), 0) as total 
                               FROM produk pr 
                               LEFT JOIN produksi p ON pr.id = p.produk_id 
                               AND MONTH(p.tanggal) = MONTH(NOW()) 
                               AND YEAR(p.tanggal) = YEAR(NOW())
                               GROUP BY pr.id, pr.nama 
                               ORDER BY total DESC LIMIT 3");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getRingkasanOperasional() {
        return [
            'efisiensi' => 92.5,
            'onTimeDelivery' => 96.2,
            'customerSatisfaction' => 4.7
        ];
    }
}
?>