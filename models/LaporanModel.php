<?php
class LaporanModel
{
    private $db;

    public function __construct()
    {
        $this->db = $GLOBALS['db'];
    }

    public function getDataProduksi($tanggal_dari = null, $tanggal_sampai = null)
    {
        $query = "SELECT p.tanggal, pr.kode, pr.nama, p.jumlah, p.shift, p.status
                  FROM produksi p 
                  LEFT JOIN produk pr ON p.produk_id = pr.id 
                  WHERE " . $this->buildDateFilter('p.tanggal', $tanggal_dari, $tanggal_sampai) . "
                  ORDER BY p.tanggal DESC, p.shift ASC";
        
        return $this->executeQuery($query, $this->buildDateParams($tanggal_dari, $tanggal_sampai));
    }

    public function getDataDistribusi($tanggal_dari = null, $tanggal_sampai = null)
    {
        $query = "SELECT ps.kode, c.nama as customer_nama, c.alamat, 
                         pg.tanggal_kirim, ps.total, pg.status
                  FROM pesanan ps
                  LEFT JOIN customer c ON ps.customer_id = c.id
                  LEFT JOIN pengiriman pg ON ps.id = pg.pesanan_id
                  WHERE " . $this->buildDateFilter('ps.tanggal', $tanggal_dari, $tanggal_sampai) . "
                  ORDER BY ps.tanggal DESC";
        
        return $this->executeQuery($query, $this->buildDateParams($tanggal_dari, $tanggal_sampai));
    }

    public function getPimpinan()
    {
        $stmt = $this->db->prepare("SELECT nama FROM users WHERE role = ? LIMIT 1");
        $stmt->execute(['pimpinan']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['nama'] ?? 'Pimpinan';
    }

    private function buildDateFilter($dateColumn, $tanggal_dari, $tanggal_sampai)
    {
        if ($tanggal_dari && $tanggal_sampai) {
            return "DATE($dateColumn) BETWEEN ? AND ?";
        } elseif ($tanggal_dari) {
            return "DATE($dateColumn) = ?";
        }
        return "1=1";
    }

    private function buildDateParams($tanggal_dari, $tanggal_sampai)
    {
        if ($tanggal_dari && $tanggal_sampai) {
            return [$tanggal_dari, $tanggal_sampai];
        } elseif ($tanggal_dari) {
            return [$tanggal_dari];
        }
        return [];
    }

    private function executeQuery($query, $params = [])
    {
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>