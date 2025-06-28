<?php
class PesananModel {
    private $db;
    
    public function __construct() {
        $this->db = $GLOBALS['db'];
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT p.*, c.nama as customer_nama, u.nama as user_nama 
                                 FROM pesanan p 
                                 LEFT JOIN customer c ON p.customer_id = c.id 
                                 LEFT JOIN users u ON p.created_by = u.id 
                                 ORDER BY p.tanggal DESC, p.created_at DESC");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT p.*, c.nama as customer_nama, c.alamat as customer_alamat 
                                   FROM pesanan p 
                                   LEFT JOIN customer c ON p.customer_id = c.id 
                                   WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO pesanan (kode, customer_id, tanggal, total, status, created_by) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['kode'],
            $data['customer_id'],
            $data['tanggal'],
            $data['total'],
            $data['status'],
            $_SESSION['user_id']
        ]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE pesanan SET customer_id = ?, tanggal = ?, total = ?, status = ? 
                                   WHERE id = ?");
        return $stmt->execute([
            $data['customer_id'],
            $data['tanggal'],
            $data['total'],
            $data['status'],
            $id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM pesanan WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getAllCustomer() {
        $stmt = $this->db->query("SELECT id, kode, nama FROM customer ORDER BY nama ASC");
        return $stmt->fetchAll();
    }
    
    public function generateKode() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM pesanan");
        $result = $stmt->fetch();
        $number = str_pad($result['total'] + 1, 3, '0', STR_PAD_LEFT);
        return 'PO' . date('Y') . $number;
    }
    
    public function checkKodeExists($kode, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM pesanan WHERE kode = ? AND id != ?");
            $stmt->execute([$kode, $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM pesanan WHERE kode = ?");
            $stmt->execute([$kode]);
        }
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }
    
    public function getLastId() {
        return $this->db->lastInsertId();
    }
    
    public function getDetailPesanan($pesanan_id) {
        $stmt = $this->db->prepare("SELECT dp.*, p.nama as produk_nama, p.formula 
                                   FROM detail_pesanan dp 
                                   LEFT JOIN produk p ON dp.produk_id = p.id 
                                   WHERE dp.pesanan_id = ?");
        $stmt->execute([$pesanan_id]);
        return $stmt->fetchAll();
    }
    
    public function createDetail($data) {
        $stmt = $this->db->prepare("INSERT INTO detail_pesanan (pesanan_id, produk_id, jumlah, harga, subtotal) 
                                   VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['pesanan_id'],
            $data['produk_id'],
            $data['jumlah'],
            $data['harga'],
            $data['subtotal']
        ]);
    }
    
    public function deleteDetail($pesanan_id) {
        $stmt = $this->db->prepare("DELETE FROM detail_pesanan WHERE pesanan_id = ?");
        return $stmt->execute([$pesanan_id]);
    }
    
    public function getAllProduk() {
        $stmt = $this->db->query("SELECT id, nama, formula, harga, stok FROM produk ORDER BY nama ASC");
        return $stmt->fetchAll();
    }
    
    public function updateStokProduk($produk_id, $jumlah) {
        $stmt = $this->db->prepare("UPDATE produk SET stok = stok - ? WHERE id = ?");
        return $stmt->execute([$jumlah, $produk_id]);
    }
    
    public function restoreStokProduk($produk_id, $jumlah) {
        $stmt = $this->db->prepare("UPDATE produk SET stok = stok + ? WHERE id = ?");
        return $stmt->execute([$jumlah, $produk_id]);
    }
    
    public function checkStokCukup($produk_id, $jumlah) {
        $stmt = $this->db->prepare("SELECT stok FROM produk WHERE id = ?");
        $stmt->execute([$produk_id]);
        $result = $stmt->fetch();
        return $result && $result['stok'] >= $jumlah;
    }
}
?>