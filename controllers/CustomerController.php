<?php
class CustomerController {
    private $customerModel;
    
    public function __construct() {
        $this->checkAuth();
        $this->customerModel = new CustomerModel();
    }
    
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=auth');
            exit;
        }
    }
    
    public function index() {
        $customer = $this->customerModel->getAll();
        $data = ['customer' => $customer];
        loadView('customer/index', $data);
    }
    
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->customerModel->checkKodeExists($_POST['kode'])) {
                $_SESSION['error'] = 'Kode customer sudah digunakan';
                header('Location: ?controller=customer&action=add');
                exit;
            }
            
            $data = [
                'kode' => $_POST['kode'],
                'nama' => $_POST['nama'],
                'alamat' => $_POST['alamat'],
                'telepon' => $_POST['telepon']
            ];
            
            if ($this->customerModel->create($data)) {
                $_SESSION['success'] = 'Customer berhasil ditambahkan';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan customer';
            }
            
            header('Location: ?controller=customer');
            exit;
        } else {
            $kode = $this->customerModel->generateKode();
            $data = ['kode' => $kode, 'action' => 'add'];
            loadView('customer/form', $data);
        }
    }
    
    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->customerModel->checkKodeExists($_POST['kode'], $id)) {
                $_SESSION['error'] = 'Kode customer sudah digunakan';
                header('Location: ?controller=customer&action=edit&id=' . $id);
                exit;
            }
            
            $data = [
                'kode' => $_POST['kode'],
                'nama' => $_POST['nama'],
                'alamat' => $_POST['alamat'],
                'telepon' => $_POST['telepon']
            ];
            
            if ($this->customerModel->update($id, $data)) {
                $_SESSION['success'] = 'Customer berhasil diupdate';
            } else {
                $_SESSION['error'] = 'Gagal mengupdate customer';
            }
            
            header('Location: ?controller=customer');
            exit;
        } else {
            $customer = $this->customerModel->getById($id);
            if (!$customer) {
                $_SESSION['error'] = 'Customer tidak ditemukan';
                header('Location: ?controller=customer');
                exit;
            }
            
            $data = ['customer' => $customer, 'action' => 'edit'];
            loadView('customer/form', $data);
        }
    }
    
    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        if ($this->customerModel->delete($id)) {
            $_SESSION['success'] = 'Customer berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus customer';
        }
        
        header('Location: ?controller=customer');
        exit;
    }
}
?>