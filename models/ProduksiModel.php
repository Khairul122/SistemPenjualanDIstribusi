<?php
class ProduksiModel {
    private $db;
    
    public function __construct() {
        $this->db = $GLOBALS['db'];
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT p.*, pr.nama as produk_nama, pr.formula, u.nama as user_nama 
                                 FROM produksi p 
                                 LEFT JOIN produk pr ON p.produk_id = pr.id 
                                 LEFT JOIN users u ON p.created_by = u.id 
                                 ORDER BY p.tanggal DESC, p.created_at DESC");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT p.*, pr.nama as produk_nama 
                                   FROM produksi p 
                                   LEFT JOIN produk pr ON p.produk_id = pr.id 
                                   WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO produksi (produk_id, tanggal, jumlah, shift, status, created_by) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['produk_id'],
            $data['tanggal'],
            $data['jumlah'],
            $data['shift'],
            $data['status'],
            $_SESSION['user_id']
        ]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE produksi SET produk_id = ?, tanggal = ?, jumlah = ?, shift = ?, status = ? 
                                   WHERE id = ?");
        return $stmt->execute([
            $data['produk_id'],
            $data['tanggal'],
            $data['jumlah'],
            $data['shift'],
            $data['status'],
            $id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM produksi WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getAllProduk() {
        $stmt = $this->db->query("SELECT id, nama, formula FROM produk ORDER BY nama ASC");
        return $stmt->fetchAll();
    }
    
    public function updateStokProduk($produk_id, $jumlah) {
        $stmt = $this->db->prepare("UPDATE produk SET stok = stok + ? WHERE id = ?");
        return $stmt->execute([$jumlah, $produk_id]);
    }
}
?>