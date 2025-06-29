<?php include 'header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6>Laporan Produksi - <?= ucfirst($periode) ?></h6>
                    <a href="?controller=laporan" class="btn btn-secondary btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <strong>Periode:</strong> <?= ucfirst($periode) ?> - <?= $tanggal ?><br>
                                <strong>Total Produksi:</strong> <?= number_format($total, 0, ',', '.') ?> ton<br>
                                <strong>Jumlah Batch:</strong> <?= count($data) ?> batch<br>
                                <strong>Status Completed:</strong> <?= $completed ?> batch
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($data)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th>Formula</th>
                                    <th>Jumlah (ton)</th>
                                    <th>Shift</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($data as $item): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= date('d/m/Y', strtotime($item['tanggal'])) ?></td>
                                    <td><?= $item['produk_nama'] ?? 'Produk Dihapus' ?></td>
                                    <td><?= $item['formula'] ?? '-' ?></td>
                                    <td class="text-end"><?= number_format($item['jumlah'], 1, ',', '.') ?></td>
                                    <td><?= ucfirst($item['shift']) ?></td>
                                    <td>
                                        <?php if ($item['status'] == 'completed'): ?>
                                            <span class="badge bg-success">Completed</span>
                                        <?php elseif ($item['status'] == 'processing'): ?>
                                            <span class="badge bg-warning">Processing</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= ucfirst($item['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">Total:</th>
                                    <th class="text-end"><?= number_format($total, 1, ',', '.') ?></th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle"></i>
                        Tidak ada data produksi dalam periode yang dipilih
                    </div>
                    <?php endif; ?>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 class="card-title">Statistik Produksi</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="text-center">
                                                <h4 class="text-primary"><?= count($data) ?></h4>
                                                <small>Total Batch</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center">
                                                <h4 class="text-success"><?= $completed ?></h4>
                                                <small>Completed</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-body">
                                    <h6 class="card-title">Tingkat Penyelesaian</h6>
                                    <div class="text-center">
                                        <?php $percentage = count($data) > 0 ? ($completed / count($data)) * 100 : 0; ?>
                                        <h4 class="text-success"><?= number_format($percentage, 1) ?>%</h4>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" style="width: <?= $percentage ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>