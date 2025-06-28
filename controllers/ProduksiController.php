<?php
class ProduksiController {
    private $produksiModel;
    
    public function __construct() {
        $this->checkAuth();
        $this->produksiModel = new ProduksiModel();
    }
    
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=auth');
            exit;
        }
    }
    
    public function index() {
        $produksi = $this->produksiModel->getAll();
        $data = ['produksi' => $produksi];
        loadView('produksi/index', $data);
    }
    
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'produk_id' => $_POST['produk_id'],
                'tanggal' => $_POST['tanggal'],
                'jumlah' => $_POST['jumlah'],
                'shift' => $_POST['shift'],
                'status' => $_POST['status']
            ];
            
            if ($this->produksiModel->create($data)) {
                if ($data['status'] == 'completed') {
                    $this->produksiModel->updateStokProduk($data['produk_id'], $data['jumlah']);
                }
                $_SESSION['success'] = 'Data produksi berhasil ditambahkan';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan data produksi';
            }
            
            header('Location: ?controller=produksi');
            exit;
        } else {
            $produk = $this->produksiModel->getAllProduk();
            $data = ['produk' => $produk, 'action' => 'add'];
            loadView('produksi/form', $data);
        }
    }
    
    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $produksi = $this->produksiModel->getById($id);
            $oldStatus = $produksi['status'];
            $oldJumlah = $produksi['jumlah'];
            
            $data = [
                'produk_id' => $_POST['produk_id'],
                'tanggal' => $_POST['tanggal'],
                'jumlah' => $_POST['jumlah'],
                'shift' => $_POST['shift'],
                'status' => $_POST['status']
            ];
            
            if ($this->produksiModel->update($id, $data)) {
                if ($oldStatus == 'completed' && $data['status'] != 'completed') {
                    $this->produksiModel->updateStokProduk($data['produk_id'], -$oldJumlah);
                } elseif ($oldStatus != 'completed' && $data['status'] == 'completed') {
                    $this->produksiModel->updateStokProduk($data['produk_id'], $data['jumlah']);
                } elseif ($oldStatus == 'completed' && $data['status'] == 'completed') {
                    $selisih = $data['jumlah'] - $oldJumlah;
                    $this->produksiModel->updateStokProduk($data['produk_id'], $selisih);
                }
                
                $_SESSION['success'] = 'Data produksi berhasil diupdate';
            } else {
                $_SESSION['error'] = 'Gagal mengupdate data produksi';
            }
            
            header('Location: ?controller=produksi');
            exit;
        } else {
            $produksi = $this->produksiModel->getById($id);
            if (!$produksi) {
                $_SESSION['error'] = 'Data produksi tidak ditemukan';
                header('Location: ?controller=produksi');
                exit;
            }
            
            $produk = $this->produksiModel->getAllProduk();
            $data = ['produksi' => $produksi, 'produk' => $produk, 'action' => 'edit'];
            loadView('produksi/form', $data);
        }
    }
    
    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        $produksi = $this->produksiModel->getById($id);
        if ($produksi && $produksi['status'] == 'completed') {
            $this->produksiModel->updateStokProduk($produksi['produk_id'], -$produksi['jumlah']);
        }
        
        if ($this->produksiModel->delete($id)) {
            $_SESSION['success'] = 'Data produksi berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus data produksi';
        }
        
        header('Location: ?controller=produksi');
        exit;
    }
}
?>