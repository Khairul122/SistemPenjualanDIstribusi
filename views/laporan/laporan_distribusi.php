<?php include 'header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6>Laporan Distribusi - <?= ucfirst($periode) ?></h6>
                    <a href="?controller=laporan" class="btn btn-secondary btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <strong>Periode:</strong> <?= ucfirst($periode) ?> - <?= $tanggal ?><br>
                                <strong>Total Pengiriman:</strong> <?= $total ?> pengiriman<br>
                                <strong>Sudah Sampai:</strong> <?= $sampai ?> pengiriman<br>
                                <strong>Tingkat Keberhasilan:</strong> <?= $total > 0 ? number_format(($sampai / $total) * 100, 1) : 0 ?>%
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($data)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Kirim</th>
                                    <th>Kode Pesanan</th>
                                    <th>Customer</th>
                                    <th>Driver</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($data as $item): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= date('d/m/Y', strtotime($item['tanggal_kirim'])) ?></td>
                                    <td><?= $item['pesanan_kode'] ?? 'Pesanan Dihapus' ?></td>
                                    <td><?= $item['customer_nama'] ?? '-' ?></td>
                                    <td><?= $item['driver'] ?? '-' ?></td>
                                    <td>
                                        <?php if ($item['status'] == 'sampai'): ?>
                                            <span class="badge bg-success">Sampai</span>
                                        <?php elseif ($item['status'] == 'dalam_perjalanan'): ?>
                                            <span class="badge bg-warning">Dalam Perjalanan</span>
                                        <?php elseif ($item['status'] == 'siap_kirim'): ?>
                                            <span class="badge bg-info">Siap Kirim</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= ucfirst($item['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle"></i>
                        Tidak ada data distribusi dalam periode yang dipilih
                    </div>
                    <?php endif; ?>
                    
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card border-info">
                                <div class="card-body">
                                    <h6 class="card-title">Total Pengiriman</h6>
                                    <div class="text-center">
                                        <h4 class="text-info"><?= $total ?></h4>
                                        <small>Pengiriman</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card border-success">
                                <div class="card-body">
                                    <h6 class="card-title">Sudah Sampai</h6>
                                    <div class="text-center">
                                        <h4 class="text-success"><?= $sampai ?></h4>
                                        <small>Pengiriman</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h6 class="card-title">Dalam Proses</h6>
                                    <div class="text-center">
                                        <h4 class="text-warning"><?= $total - $sampai ?></h4>
                                        <small>Pengiriman</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 class="card-title">Tingkat Keberhasilan Distribusi</h6>
                                    <div class="text-center">
                                        <?php $percentage = $total > 0 ? ($sampai / $total) * 100 : 0; ?>
                                        <h4 class="text-primary"><?= number_format($percentage, 1) ?>%</h4>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-primary" style="width: <?= $percentage ?>%">
                                                <?= number_format($percentage, 1) ?>%
                                            </div>
                                        </div>
                                        <small class="text-muted mt-2 d-block">
                                            <?= $sampai ?> dari <?= $total ?> pengiriman berhasil sampai tujuan
                                        </small>
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