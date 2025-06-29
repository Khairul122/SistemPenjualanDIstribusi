<?php
class PengirimanController {
    private $pengirimanModel;
    
    public function __construct() {
        $this->checkAuth();
        $this->pengirimanModel = new PengirimanModel();
    }
    
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=auth');
            exit;
        }
    }
    
    public function index() {
        $pengiriman = $this->pengirimanModel->getAll();
        $data = ['pengiriman' => $pengiriman];
        loadView('pengiriman/index', $data);
    }
    
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->pengirimanModel->checkPesananSudahDikirim($_POST['pesanan_id'])) {
                $_SESSION['error'] = 'Pesanan ini sudah dalam proses pengiriman';
                header('Location: ?controller=pengiriman&action=add');
                exit;
            }
            
            $data = [
                'pesanan_id' => $_POST['pesanan_id'],
                'tanggal_kirim' => $_POST['tanggal_kirim'],
                'driver' => $_POST['driver'],
                'status' => $_POST['status']
            ];
            
            if ($this->pengirimanModel->create($data)) {
                $_SESSION['success'] = 'Data pengiriman berhasil ditambahkan';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan data pengiriman';
            }
            
            header('Location: ?controller=pengiriman');
            exit;
        } else {
            $pesanan = $this->pengirimanModel->getPesananApproved();
            $data = ['pesanan' => $pesanan, 'action' => 'add'];
            loadView('pengiriman/form', $data);
        }
    }
    
    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->pengirimanModel->checkPesananSudahDikirim($_POST['pesanan_id'], $id)) {
                $_SESSION['error'] = 'Pesanan ini sudah dalam proses pengiriman lain';
                header('Location: ?controller=pengiriman&action=edit&id=' . $id);
                exit;
            }
            
            $data = [
                'pesanan_id' => $_POST['pesanan_id'],
                'tanggal_kirim' => $_POST['tanggal_kirim'],
                'driver' => $_POST['driver'],
                'status' => $_POST['status']
            ];
            
            if ($this->pengirimanModel->update($id, $data)) {
                $_SESSION['success'] = 'Data pengiriman berhasil diupdate';
            } else {
                $_SESSION['error'] = 'Gagal mengupdate data pengiriman';
            }
            
            header('Location: ?controller=pengiriman');
            exit;
        } else {
            $pengiriman = $this->pengirimanModel->getById($id);
            if (!$pengiriman) {
                $_SESSION['error'] = 'Data pengiriman tidak ditemukan';
                header('Location: ?controller=pengiriman');
                exit;
            }
            
            $pesanan = $this->pengirimanModel->getPesananApproved();
            $pesananCurrent = $this->pengirimanModel->getPesananById($pengiriman['pesanan_id']);
            if ($pesananCurrent) {
                $pesanan[] = $pesananCurrent;
            }
            
            $data = ['pengiriman' => $pengiriman, 'pesanan' => $pesanan, 'action' => 'edit'];
            loadView('pengiriman/form', $data);
        }
    }
    
    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        if ($this->pengirimanModel->delete($id)) {
            $_SESSION['success'] = 'Data pengiriman berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus data pengiriman';
        }
        
        header('Location: ?controller=pengiriman');
        exit;
    }
}
?>