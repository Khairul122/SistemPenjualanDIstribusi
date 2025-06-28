<?php
class ProdukController {
    private $produkModel;
    
    public function __construct() {
        $this->checkAuth();
        $this->produkModel = new ProdukModel();
    }
    
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=auth');
            exit;
        }
    }
    
    public function index() {
        $produk = $this->produkModel->getAll();
        $data = ['produk' => $produk];
        loadView('produk/index', $data);
    }
    
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'kode' => $_POST['kode'],
                'nama' => $_POST['nama'],
                'formula' => $_POST['formula'],
                'harga' => $_POST['harga'],
                'stok' => $_POST['stok']
            ];
            
            if ($this->produkModel->create($data)) {
                $_SESSION['success'] = 'Produk berhasil ditambahkan';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan produk';
            }
            
            header('Location: ?controller=produk');
            exit;
        } else {
            $kode = $this->produkModel->generateKode();
            $data = ['kode' => $kode, 'action' => 'add'];
            loadView('produk/form', $data);
        }
    }
    
    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'kode' => $_POST['kode'],
                'nama' => $_POST['nama'],
                'formula' => $_POST['formula'],
                'harga' => $_POST['harga'],
                'stok' => $_POST['stok']
            ];
            
            if ($this->produkModel->update($id, $data)) {
                $_SESSION['success'] = 'Produk berhasil diupdate';
            } else {
                $_SESSION['error'] = 'Gagal mengupdate produk';
            }
            
            header('Location: ?controller=produk');
            exit;
        } else {
            $produk = $this->produkModel->getById($id);
            if (!$produk) {
                $_SESSION['error'] = 'Produk tidak ditemukan';
                header('Location: ?controller=produk');
                exit;
            }
            
            $data = ['produk' => $produk, 'action' => 'edit'];
            loadView('produk/form', $data);
        }
    }
    
    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        if ($this->produkModel->delete($id)) {
            $_SESSION['success'] = 'Produk berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus produk';
        }
        
        header('Location: ?controller=produk');
        exit;
    }
}
?>