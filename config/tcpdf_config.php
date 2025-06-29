<?php
// File: config/pdf_config.php

// Load TCPDF dengan pengecekan
if (!class_exists('TCPDF')) {
    $tcpdf_paths = [
        dirname(__DIR__) . '/TCPDF/tcpdf.php',
        dirname(__DIR__) . '/tcpdf/tcpdf.php',
        dirname(__DIR__) . '/vendor/tecnickcom/tcpdf/tcpdf.php'
    ];

    foreach ($tcpdf_paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            break;
        }
    }

    if (!class_exists('TCPDF')) {
        die('TCPDF library tidak ditemukan! Silakan download TCPDF dan letakkan di folder TCPDF/');
    }
}

// Custom PDF Report class
if (!class_exists('PDFReport')) {
    class PDFReport extends TCPDF
    {
        public $header_title;
        public $header_subtitle;
        public $company_name;
        public $company_address;

        public function Header()
        {
            // Company Header
            $this->SetFont('helvetica', 'B', 16);
            $this->Cell(0, 7, $this->company_name, 0, 1, 'C');
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 5, $this->company_address, 0, 1, 'C');
            
            // Line separator
            $this->Line(10, 30, $this->getPageWidth() - 10, 30);
            
            // Report title
            $this->Ln(10);
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(0, 8, $this->header_title, 0, 1, 'C');
            $this->SetFont('helvetica', '', 11);
            $this->Cell(0, 8, $this->header_subtitle, 0, 1, 'C');
            $this->Ln(3);
        }

        public function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('helvetica', 'I', 8);
            $this->Cell(0, 10, 'Halaman ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages() . ' - Dicetak: ' . date('d-m-Y H:i:s'), 0, false, 'C');
        }
    }
}
?>