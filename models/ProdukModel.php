<?php
class ProdukModel {
    private $db;
    
    public function __construct() {
        $this->db = $GLOBALS['db'];
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM produk ORDER BY nama ASC");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM produk WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO produk (kode, nama, formula, harga, stok) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['kode'],
            $data['nama'],
            $data['formula'],
            $data['harga'],
            $data['stok']
        ]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE produk SET kode = ?, nama = ?, formula = ?, harga = ?, stok = ? WHERE id = ?");
        return $stmt->execute([
            $data['kode'],
            $data['nama'],
            $data['formula'],
            $data['harga'],
            $data['stok'],
            $id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM produk WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function generateKode() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM produk");
        $result = $stmt->fetch();
        $number = str_pad($result['total'] + 1, 3, '0', STR_PAD_LEFT);
        return 'PRD' . $number;
    }
}
?>