<?php
class PengirimanModel {
    private $db;
    
    public function __construct() {
        $this->db = $GLOBALS['db'];
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT pg.*, p.kode as pesanan_kode, c.nama as customer_nama, u.nama as user_nama 
                                 FROM pengiriman pg 
                                 LEFT JOIN pesanan p ON pg.pesanan_id = p.id 
                                 LEFT JOIN customer c ON p.customer_id = c.id 
                                 LEFT JOIN users u ON pg.created_by = u.id 
                                 ORDER BY pg.tanggal_kirim DESC, pg.created_at DESC");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT pg.*, p.kode as pesanan_kode, c.nama as customer_nama, c.alamat as customer_alamat 
                                   FROM pengiriman pg 
                                   LEFT JOIN pesanan p ON pg.pesanan_id = p.id 
                                   LEFT JOIN customer c ON p.customer_id = c.id 
                                   WHERE pg.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO pengiriman (pesanan_id, tanggal_kirim, driver, status, created_by) 
                                   VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['pesanan_id'],
            $data['tanggal_kirim'],
            $data['driver'],
            $data['status'],
            $_SESSION['user_id']
        ]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE pengiriman SET pesanan_id = ?, tanggal_kirim = ?, driver = ?, status = ? 
                                   WHERE id = ?");
        return $stmt->execute([
            $data['pesanan_id'],
            $data['tanggal_kirim'],
            $data['driver'],
            $data['status'],
            $id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM pengiriman WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getPesananApproved() {
        $stmt = $this->db->query("SELECT p.id, p.kode, c.nama as customer_nama, p.total 
                                 FROM pesanan p 
                                 LEFT JOIN customer c ON p.customer_id = c.id 
                                 WHERE p.status = 'approved' 
                                 AND p.id NOT IN (SELECT pesanan_id FROM pengiriman WHERE status != 'sampai') 
                                 ORDER BY p.tanggal ASC");
        return $stmt->fetchAll();
    }
    
    public function getPesananById($id) {
        $stmt = $this->db->prepare("SELECT p.*, c.nama as customer_nama, c.alamat as customer_alamat 
                                   FROM pesanan p 
                                   LEFT JOIN customer c ON p.customer_id = c.id 
                                   WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function checkPesananSudahDikirim($pesanan_id, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM pengiriman 
                                       WHERE pesanan_id = ? AND id != ? AND status != 'sampai'");
            $stmt->execute([$pesanan_id, $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM pengiriman 
                                       WHERE pesanan_id = ? AND status != 'sampai'");
            $stmt->execute([$pesanan_id]);
        }
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }
}
?>