<?php
// Load TCPDF configuration
require_once dirname(__DIR__) . '/config/tcpdf_config.php';

class LaporanController
{
    private $model;
    private $config;

    public function __construct()
    {
        $this->model = new LaporanModel();
        $this->config = $this->getCompanyConfig();
    }

    public function index()
    {
        loadView('laporan/index', ['title' => 'Laporan Sistem']);
    }

    public function produksi()
    {
        $dates = $this->getDateRange();
        $pdf = $this->generateProduksiReport($dates['dari'], $dates['sampai']);
        $this->outputPDF($pdf, 'Laporan_Produksi');
    }

    public function distribusi()
    {
        $dates = $this->getDateRange();
        $pdf = $this->generateDistribusiReport($dates['dari'], $dates['sampai']);
        $this->outputPDF($pdf, 'Laporan_Distribusi');
    }

    private function getCompanyConfig()
    {
        return [
            'name' => 'Pabrik Dharmas Plant KUD Lubuk Karya',
            'address' => 'Koto Besar, Kec. Koto Besar, Kabupaten Dharmasraya, Sumatera Barat'
        ];
    }

    private function getDateRange()
    {
        return [
            'dari' => $this->cleanInput($_POST['tanggal_dari'] ?? null),
            'sampai' => $this->cleanInput($_POST['tanggal_sampai'] ?? null)
        ];
    }

    private function cleanInput($input)
    {
        return empty($input) ? null : $input;
    }

    private function formatPeriode($tanggal_dari, $tanggal_sampai)
    {
        if ($tanggal_dari && $tanggal_sampai) {
            return 'Periode: ' . date('d F Y', strtotime($tanggal_dari)) . ' - ' . date('d F Y', strtotime($tanggal_sampai));
        } elseif ($tanggal_dari) {
            return 'Tanggal: ' . date('d F Y', strtotime($tanggal_dari));
        }
        return 'Semua Data';
    }

    private function createPDF($title, $subtitle)
    {
        $pdf = new PDFReport('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->company_name = $this->config['name'];
        $pdf->company_address = $this->config['address'];
        $pdf->header_title = $title;
        $pdf->header_subtitle = $subtitle;
        $pdf->SetMargins(15, 70, 15);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(20);
        $pdf->SetAutoPageBreak(TRUE, 25);
        $pdf->AddPage();
        
        return $pdf;
    }

    private function outputPDF($pdf, $filename)
    {
        ob_end_clean();
        $pdf->Output($filename . '_' . date('Y-m-d') . '.pdf', 'I');
        exit;
    }

    private function generateProduksiReport($tanggal_dari, $tanggal_sampai)
    {
        $data = $this->model->getDataProduksi($tanggal_dari, $tanggal_sampai);
        $pdf = $this->createPDF('LAPORAN PRODUKSI PUPUK NPK', $this->formatPeriode($tanggal_dari, $tanggal_sampai));

        $tableConfig = $this->getProduksiTableConfig();
        $this->renderTable($pdf, $data, $tableConfig);
        
        // Calculate center position for total
        $total_width = array_sum(array_column($tableConfig['headers'], 'width'));
        $page_width = $pdf->getPageWidth() - 30;
        $center_x = ($page_width - $total_width) / 2 + 15;
        
        $this->addTableTotal($pdf, $data, 'TOTAL PRODUKSI', 'jumlah', ' kg', 185, 55, $center_x);
        
        // Add signature manually
        $this->addSignatureArea($pdf);
        
        return $pdf;
    }

    private function generateDistribusiReport($tanggal_dari, $tanggal_sampai)
    {
        $data = $this->model->getDataDistribusi($tanggal_dari, $tanggal_sampai);
        $pdf = $this->createPDF('LAPORAN DISTRIBUSI PUPUK NPK', $this->formatPeriode($tanggal_dari, $tanggal_sampai));

        $tableConfig = $this->getDistribusiTableConfig();
        $this->renderTable($pdf, $data, $tableConfig);
        
        // Calculate center position for total
        $total_width = array_sum(array_column($tableConfig['headers'], 'width'));
        $page_width = $pdf->getPageWidth() - 30;
        $center_x = ($page_width - $total_width) / 2 + 15;
        
        $this->addTableTotal($pdf, $data, 'TOTAL DISTRIBUSI', 'total', 'Rp ', 200, 60, $center_x);
        
        // Add signature manually
        $this->addSignatureArea($pdf);
        
        return $pdf;
    }

    private function addSignatureArea($pdf)
    {
        // Space setelah tabel
        $pdf->Ln(20);
        
        // Position signature di kanan
        $x_signature = $pdf->getPageWidth() - 80;
        $pdf->SetXY($x_signature, $pdf->GetY());
        
        // Tanggal dan tempat
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 5, 'Dharmasraya, ' . date('d F Y'), 0, 1, 'L');
        
        // Text "Pimpinan"
        $pdf->SetXY($x_signature, $pdf->GetY() + 2);
        $pdf->Cell(0, 5, 'Pimpinan', 0, 1, 'L');
        
        // Space untuk tanda tangan tanpa kotak (hanya space kosong)
        $pdf->SetXY($x_signature, $pdf->GetY() + 30);
        
        // Garis bawah untuk nama
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(60, 5, '________________________', 0, 1, 'L');
        
        // Nama pimpinan di bawah garis (align kiri)
        $pdf->SetXY($x_signature, $pdf->GetY());
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(60, 5, $this->model->getPimpinan(), 0, 1, 'L');
    }

    private function getProduksiTableConfig()
    {
        return [
            'headers' => [
                ['width' => 15, 'text' => 'No'],
                ['width' => 25, 'text' => 'Tanggal'],
                ['width' => 40, 'text' => 'Kode Produk'],
                ['width' => 80, 'text' => 'Nama Produk'],
                ['width' => 25, 'text' => 'Jumlah'],
                ['width' => 25, 'text' => 'Shift'],
                ['width' => 30, 'text' => 'Status']
            ],
            'columns' => [
                ['field' => 'no', 'width' => 15, 'align' => 'C'],
                ['field' => 'tanggal', 'width' => 25, 'align' => 'C', 'format' => 'date'],
                ['field' => 'kode', 'width' => 40, 'align' => 'C'],
                ['field' => 'nama', 'width' => 80, 'align' => 'L'],
                ['field' => 'jumlah', 'width' => 25, 'align' => 'R', 'format' => 'number'],
                ['field' => 'shift', 'width' => 25, 'align' => 'C', 'format' => 'ucfirst'],
                ['field' => 'status', 'width' => 30, 'align' => 'C', 'format' => 'ucfirst']
            ]
        ];
    }

    private function getDistribusiTableConfig()
    {
        return [
            'headers' => [
                ['width' => 15, 'text' => 'No'],
                ['width' => 35, 'text' => 'Kode Pesanan'],
                ['width' => 60, 'text' => 'Nama Customer'],
                ['width' => 60, 'text' => 'Alamat'],
                ['width' => 30, 'text' => 'Tanggal Kirim'],
                ['width' => 35, 'text' => 'Total'],
                ['width' => 25, 'text' => 'Status']
            ],
            'columns' => [
                ['field' => 'no', 'width' => 15, 'align' => 'C'],
                ['field' => 'kode', 'width' => 35, 'align' => 'C'],
                ['field' => 'customer_nama', 'width' => 60, 'align' => 'L'],
                ['field' => 'alamat', 'width' => 60, 'align' => 'L'],
                ['field' => 'tanggal_kirim', 'width' => 30, 'align' => 'C', 'format' => 'date'],
                ['field' => 'total', 'width' => 35, 'align' => 'R', 'format' => 'currency'],
                ['field' => 'status', 'width' => 25, 'align' => 'C', 'format' => 'ucfirst']
            ]
        ];
    }

    private function renderTable($pdf, $data, $config)
    {
        // Hitung total width tabel
        $total_width = array_sum(array_column($config['headers'], 'width'));
        $page_width = $pdf->getPageWidth() - 30; // margin kiri + kanan
        $center_x = ($page_width - $total_width) / 2 + 15; // 15 = margin kiri
        
        // Set posisi X untuk center tabel
        $pdf->SetX($center_x);
        
        $this->createTableHeader($pdf, $config['headers'], $center_x);

        $no = 1;
        foreach ($data as $row) {
            $row['no'] = $no++;
            $row['status'] = $row['status'] ?? 'pending';
            $this->createTableRow($pdf, $no, $row, $config['columns'], $center_x);
        }
    }

    private function createTableHeader($pdf, $headers, $center_x = null)
    {
        if ($center_x) {
            $pdf->SetX($center_x);
        }
        
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(220, 220, 220);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetLineWidth(0.3);
        
        foreach ($headers as $header) {
            $pdf->Cell($header['width'], 10, $header['text'], 1, 0, 'C', true);
        }
        $pdf->Ln();
    }

    private function createTableRow($pdf, $no, $data, $columns, $center_x = null)
    {
        if ($center_x) {
            $pdf->SetX($center_x);
        }
        
        $fill = $no % 2 == 0;
        $pdf->SetFillColor($fill ? 245 : 255, $fill ? 245 : 255, $fill ? 245 : 255);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetLineWidth(0.2);

        foreach ($columns as $column) {
            $value = $data[$column['field']] ?? '';
            if (isset($column['format'])) {
                $value = $this->formatValue($value, $column['format']);
            }
            $pdf->Cell($column['width'], 8, $value, 1, 0, $column['align'], $fill);
        }
        $pdf->Ln();
    }

    private function formatValue($value, $format)
    {
        switch ($format) {
            case 'date':
                return $value ? date('d/m/Y', strtotime($value)) : '-';
            case 'number':
                return number_format($value) . ' kg';
            case 'currency':
                return 'Rp ' . number_format($value);
            case 'ucfirst':
                return ucfirst($value);
            default:
                return $value;
        }
    }

    private function addTableTotal($pdf, $data, $label, $field, $suffix, $label_width, $total_width, $center_x = null)
    {
        if ($center_x) {
            $pdf->SetX($center_x);
        }
        
        $total = array_sum(array_column($data, $field));
        $total_text = str_starts_with($suffix, 'Rp') ? $suffix . number_format($total) : number_format($total) . $suffix;
        
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetLineWidth(0.5);
        $pdf->Cell($label_width, 10, $label, 1, 0, 'R', true);
        $pdf->Cell($total_width, 10, $total_text, 1, 1, 'R', true);
    }
}
?>