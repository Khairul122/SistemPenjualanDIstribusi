<?php
class PesananController {
    private $pesananModel;
    
    public function __construct() {
        $this->checkAuth();
        $this->pesananModel = new PesananModel();
    }
    
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=auth');
            exit;
        }
    }
    
    public function index() {
        $pesanan = $this->pesananModel->getAll();
        $data = ['pesanan' => $pesanan];
        loadView('pesanan/index', $data);
    }
    
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->pesananModel->checkKodeExists($_POST['kode'])) {
                $_SESSION['error'] = 'Kode pesanan sudah digunakan';
                header('Location: ?controller=pesanan&action=add');
                exit;
            }
            
            $total = 0;
            $detailItems = [];
            if (isset($_POST['produk_id']) && is_array($_POST['produk_id'])) {
                foreach ($_POST['produk_id'] as $key => $produk_id) {
                    if (!empty($produk_id) && !empty($_POST['jumlah'][$key]) && !empty($_POST['harga'][$key])) {
                        $jumlah = $_POST['jumlah'][$key];
                        $harga = $_POST['harga'][$key];
                        
                        // Cek stok jika status approved/selesai
                        if ($_POST['status'] == 'approved' || $_POST['status'] == 'selesai') {
                            if (!$this->pesananModel->checkStokCukup($produk_id, $jumlah)) {
                                $_SESSION['error'] = 'Stok produk tidak mencukupi untuk pesanan ini';
                                header('Location: ?controller=pesanan&action=add');
                                exit;
                            }
                        }
                        
                        $subtotal = $jumlah * $harga;
                        $total += $subtotal;
                        
                        $detailItems[] = [
                            'produk_id' => $produk_id,
                            'jumlah' => $jumlah,
                            'harga' => $harga,
                            'subtotal' => $subtotal
                        ];
                    }
                }
            }
            
            $data = [
                'kode' => $_POST['kode'],
                'customer_id' => $_POST['customer_id'],
                'tanggal' => $_POST['tanggal'],
                'total' => $total,
                'status' => $_POST['status']
            ];
            
            if ($this->pesananModel->create($data)) {
                $pesanan_id = $this->pesananModel->getLastId();
                
                foreach ($detailItems as $item) {
                    $detail = [
                        'pesanan_id' => $pesanan_id,
                        'produk_id' => $item['produk_id'],
                        'jumlah' => $item['jumlah'],
                        'harga' => $item['harga'],
                        'subtotal' => $item['subtotal']
                    ];
                    $this->pesananModel->createDetail($detail);
                    
                    // Kurangi stok jika status approved/selesai
                    if ($_POST['status'] == 'approved' || $_POST['status'] == 'selesai') {
                        $this->pesananModel->updateStokProduk($item['produk_id'], $item['jumlah']);
                    }
                }
                
                $_SESSION['success'] = 'Pesanan berhasil ditambahkan';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan pesanan';
            }
            
            header('Location: ?controller=pesanan');
            exit;
        } else {
            $kode = $this->pesananModel->generateKode();
            $customer = $this->pesananModel->getAllCustomer();
            $produk = $this->pesananModel->getAllProduk();
            $data = ['kode' => $kode, 'customer' => $customer, 'produk' => $produk, 'action' => 'add'];
            loadView('pesanan/form', $data);
        }
    }
    
    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $pesanan = $this->pesananModel->getById($id);
            $oldStatus = $pesanan['status'];
            $oldDetail = $this->pesananModel->getDetailPesanan($id);
            
            // Jika status berubah dari approved/selesai ke pending, kembalikan stok
            if (($oldStatus == 'approved' || $oldStatus == 'selesai') && $_POST['status'] == 'pending') {
                foreach ($oldDetail as $detail) {
                    $this->pesananModel->restoreStokProduk($detail['produk_id'], $detail['jumlah']);
                }
            }
            
            $total = 0;
            $detailBaru = [];
            if (isset($_POST['produk_id']) && is_array($_POST['produk_id'])) {
                foreach ($_POST['produk_id'] as $key => $produk_id) {
                    if (!empty($produk_id) && !empty($_POST['jumlah'][$key]) && !empty($_POST['harga'][$key])) {
                        $jumlah = $_POST['jumlah'][$key];
                        $harga = $_POST['harga'][$key];
                        
                        // Cek stok jika status approved/selesai
                        if (($_POST['status'] == 'approved' || $_POST['status'] == 'selesai') && $oldStatus == 'pending') {
                            if (!$this->pesananModel->checkStokCukup($produk_id, $jumlah)) {
                                $_SESSION['error'] = 'Stok produk tidak mencukupi untuk pesanan ini';
                                header('Location: ?controller=pesanan&action=edit&id=' . $id);
                                exit;
                            }
                        }
                        
                        $subtotal = $jumlah * $harga;
                        $total += $subtotal;
                        
                        $detailBaru[] = [
                            'produk_id' => $produk_id,
                            'jumlah' => $jumlah,
                            'harga' => $harga,
                            'subtotal' => $subtotal
                        ];
                    }
                }
            }
            
            $data = [
                'customer_id' => $_POST['customer_id'],
                'tanggal' => $_POST['tanggal'],
                'total' => $total,
                'status' => $_POST['status']
            ];
            
            if ($this->pesananModel->update($id, $data)) {
                $this->pesananModel->deleteDetail($id);
                
                foreach ($detailBaru as $detail) {
                    $detailData = [
                        'pesanan_id' => $id,
                        'produk_id' => $detail['produk_id'],
                        'jumlah' => $detail['jumlah'],
                        'harga' => $detail['harga'],
                        'subtotal' => $detail['subtotal']
                    ];
                    $this->pesananModel->createDetail($detailData);
                }
                
                // Kurangi stok jika status berubah dari pending ke approved/selesai
                if ($oldStatus == 'pending' && ($_POST['status'] == 'approved' || $_POST['status'] == 'selesai')) {
                    foreach ($detailBaru as $detail) {
                        $this->pesananModel->updateStokProduk($detail['produk_id'], $detail['jumlah']);
                    }
                }
                
                $_SESSION['success'] = 'Pesanan berhasil diupdate';
            } else {
                $_SESSION['error'] = 'Gagal mengupdate pesanan';
            }
            
            header('Location: ?controller=pesanan');
            exit;
        } else {
            $pesanan = $this->pesananModel->getById($id);
            if (!$pesanan) {
                $_SESSION['error'] = 'Pesanan tidak ditemukan';
                header('Location: ?controller=pesanan');
                exit;
            }
            
            $customer = $this->pesananModel->getAllCustomer();
            $produk = $this->pesananModel->getAllProduk();
            $detail = $this->pesananModel->getDetailPesanan($id);
            $data = ['pesanan' => $pesanan, 'customer' => $customer, 'produk' => $produk, 'detail' => $detail, 'action' => 'edit'];
            loadView('pesanan/form', $data);
        }
    }
    
    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        // Ambil data pesanan dan detail untuk restore stok jika perlu
        $pesanan = $this->pesananModel->getById($id);
        $detail = $this->pesananModel->getDetailPesanan($id);
        
        // Jika pesanan sudah approved/selesai, kembalikan stok
        if ($pesanan && ($pesanan['status'] == 'approved' || $pesanan['status'] == 'selesai')) {
            foreach ($detail as $item) {
                $this->pesananModel->restoreStokProduk($item['produk_id'], $item['jumlah']);
            }
        }
        
        $this->pesananModel->deleteDetail($id);
        
        if ($this->pesananModel->delete($id)) {
            $_SESSION['success'] = 'Pesanan berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus pesanan';
        }
        
        header('Location: ?controller=pesanan');
        exit;
    }
}
?>