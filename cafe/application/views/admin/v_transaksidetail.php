<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Transaksi</h1>
        <a href="<?php echo base_url('transaksi'); ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <!-- Struk / Receipt Card -->
            <div class="card shadow mb-4" id="struk">
                <div class="card-header py-3 bg-primary text-white text-center">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-receipt mr-2"></i>Struk Pembayaran</h5>
                    <small><?php echo $transaksi['kode_transaksi']; ?></small>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">Kasir</small>
                            <p class="mb-0 font-weight-bold"><?php echo $transaksi['kasir']; ?></p>
                        </div>
                        <div class="col-6 text-right">
                            <small class="text-muted">Waktu</small>
                            <p class="mb-0 font-weight-bold">
                                <?php echo date('d/m/Y H:i', strtotime($transaksi['created_at'])); ?>
                            </p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">Metode Bayar</small>
                            <?php 
                            $badge = ['tunai'=>'success','qris'=>'info','transfer'=>'primary','debit'=>'warning'];
                            $m = $transaksi['metode_bayar'];
                            ?>
                            <p class="mb-0">
                                <span class="badge badge-<?php echo $badge[$m] ?? 'secondary'; ?> badge-pill">
                                    <?php echo strtoupper($m); ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-6 text-right">
                            <small class="text-muted">Status</small>
                            <?php
                            $sb = ['selesai'=>'success','batal'=>'danger','pending'=>'warning'];
                            $s = $transaksi['status'];
                            ?>
                            <p class="mb-0">
                                <span class="badge badge-<?php echo $sb[$s] ?? 'secondary'; ?> badge-pill">
                                    <?php echo ucfirst($s); ?>
                                </span>
                            </p>
                        </div>
                    </div>

                    <hr>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detail as $d): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($d['nama_menu']); ?></td>
                                <td class="text-center"><?php echo $d['qty']; ?></td>
                                <td class="text-right">Rp <?php echo number_format($d['harga'], 0, ',', '.'); ?></td>
                                <td class="text-right">Rp <?php echo number_format($d['subtotal'], 0, ',', '.'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <hr>
                    <div class="row">
                        <div class="col-6 offset-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted">Total</td>
                                    <td class="text-right font-weight-bold">
                                        Rp <?php echo number_format($transaksi['total'], 0, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Bayar</td>
                                    <td class="text-right">
                                        Rp <?php echo number_format($transaksi['bayar'], 0, ',', '.'); ?>
                                    </td>
                                </tr>
                                <tr class="border-top">
                                    <td class="text-muted">Kembalian</td>
                                    <td class="text-right font-weight-bold text-success">
                                        Rp <?php echo number_format($transaksi['kembalian'], 0, ',', '.'); ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="text-center mt-3 text-muted small">
                        <i class="fas fa-heart text-danger"></i> Terima kasih telah berkunjung!
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary btn-sm" onclick="window.print()">
                        <i class="fas fa-print mr-1"></i> Cetak Struk
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->