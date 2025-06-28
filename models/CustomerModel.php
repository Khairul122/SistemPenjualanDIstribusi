<?php
class CustomerModel {
    private $db;
    
    public function __construct() {
        $this->db = $GLOBALS['db'];
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM customer ORDER BY nama ASC");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM customer WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO customer (kode, nama, alamat, telepon) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['kode'],
            $data['nama'],
            $data['alamat'],
            $data['telepon']
        ]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE customer SET kode = ?, nama = ?, alamat = ?, telepon = ? WHERE id = ?");
        return $stmt->execute([
            $data['kode'],
            $data['nama'],
            $data['alamat'],
            $data['telepon'],
            $id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM customer WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function generateKode() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM customer");
        $result = $stmt->fetch();
        $number = str_pad($result['total'] + 1, 3, '0', STR_PAD_LEFT);
        return 'CST' . $number;
    }
    
    public function checkKodeExists($kode, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM customer WHERE kode = ? AND id != ?");
            $stmt->execute([$kode, $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM customer WHERE kode = ?");
            $stmt->execute([$kode]);
        }
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }
}
?>