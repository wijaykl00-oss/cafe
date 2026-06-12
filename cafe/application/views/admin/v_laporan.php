<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-chart-bar mr-2 text-primary"></i>Laporan Penjualan</h1>
        <button onclick="window.print()" class="btn btn-sm btn-success shadow-sm">
            <i class="fas fa-print mr-1"></i> Cetak Laporan
        </button>
    </div>

    <!-- Filter Tanggal -->
    <div class="card shadow mb-4">
        <div class="card-body py-3">
            <form method="GET" action="<?php echo base_url('laporan'); ?>" class="form-inline">
                <label class="mr-2 font-weight-bold">Periode:</label>
                <input type="date" name="dari" class="form-control form-control-sm mr-2"
                       value="<?php echo $dari; ?>">
                <span class="mr-2">s/d</span>
                <input type="date" name="sampai" class="form-control form-control-sm mr-2"
                       value="<?php echo $sampai; ?>">
                <button type="submit" class="btn btn-primary btn-sm mr-2">
                    <i class="fas fa-search mr-1"></i>Filter
                </button>
                <!-- Shortcut -->
                <a href="<?php echo base_url('laporan?dari='.date('Y-m-d').'&sampai='.date('Y-m-d')); ?>"
                   class="btn btn-outline-secondary btn-sm mr-1">Hari Ini</a>
                <a href="<?php echo base_url('laporan?dari='.date('Y-m-01').'&sampai='.date('Y-m-d')); ?>"
                   class="btn btn-outline-secondary btn-sm mr-1">Bulan Ini</a>
                <a href="<?php echo base_url('laporan?dari='.date('Y-01-01').'&sampai='.date('Y-12-31')); ?>"
                   class="btn btn-outline-secondary btn-sm">Tahun Ini</a>
            </form>
        </div>
    </div>

    <!-- Kartu Ringkasan -->
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pendapatan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp <?php echo number_format($ringkasan->total_pendapatan ?? 0, 0, ',', '.'); ?>
                            </div>
                        </div>
                        <div class="col-auto"><i class="fas fa-money-bill-wave fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Transaksi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($ringkasan->total_transaksi ?? 0); ?> Transaksi
                            </div>
                        </div>
                        <div class="col-auto"><i class="fas fa-receipt fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rata-rata per Transaksi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp <?php echo number_format($ringkasan->rata_rata ?? 0, 0, ',', '.'); ?>
                            </div>
                        </div>
                        <div class="col-auto"><i class="fas fa-calculator fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Penjualan -->
    <?php if (!empty($per_hari)): ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Grafik Penjualan Harian</h6>
        </div>
        <div class="card-body">
            <div class="chart-area">
                <canvas id="grafikLaporan"></canvas>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <!-- Penjualan per Hari -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Penjualan per Hari</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($per_hari)): ?>
                        <p class="text-center text-muted py-3">Tidak ada data pada periode ini.</p>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th class="text-center">Jumlah Transaksi</th>
                                    <th class="text-right">Total Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($per_hari as $row): ?>
                                <tr>
                                    <td><?php echo date('D, d M Y', strtotime($row['tgl'])); ?></td>
                                    <td class="text-center"><?php echo $row['jumlah']; ?></td>
                                    <td class="text-right">Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="font-weight-bold">
                                <tr>
                                    <td>TOTAL</td>
                                    <td class="text-center"><?php echo array_sum(array_column($per_hari, 'jumlah')); ?></td>
                                    <td class="text-right">Rp <?php echo number_format(array_sum(array_column($per_hari, 'total')), 0, ',', '.'); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Metode Bayar -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Per Metode Bayar</h6>
                </div>
                <div class="card-body">
                    <?php
                    $badge = ['tunai'=>'success','qris'=>'info','transfer'=>'primary','debit'=>'warning'];
                    foreach ($per_metode as $m):
                    ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="badge badge-<?php echo $badge[$m['metode_bayar']] ?? 'secondary'; ?> mr-2">
                                <?php echo strtoupper($m['metode_bayar']); ?>
                            </span>
                            <small class="text-muted"><?php echo $m['jumlah']; ?>x</small>
                        </div>
                        <strong>Rp <?php echo number_format($m['total'], 0, ',', '.'); ?></strong>
                    </div>
                    <?php endforeach; ?>
                    <?php if (empty($per_metode)): ?>
                        <p class="text-muted text-center">Tidak ada data</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Menu Terlaris -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Menu Terlaris</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Menu</th>
                                    <th class="text-center">Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($menu_terlaris)): ?>
                                <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada data</td></tr>
                                <?php else: ?>
                                <?php $no = 1; foreach ($menu_terlaris as $m): ?>
                                <tr>
                                    <td>
                                        <?php if ($no <= 3): ?>
                                        <span class="badge badge-<?php echo ['1'=>'warning','2'=>'secondary','3'=>'info'][$no]; ?>">
                                            #<?php echo $no; ?>
                                        </span>
                                        <?php else: echo $no; endif; ?>
                                    </td>
                                    <td class="small"><?php echo htmlspecialchars($m['nama_menu']); ?></td>
                                    <td class="text-center font-weight-bold"><?php echo $m['total_qty']; ?></td>
                                </tr>
                                <?php $no++; endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<script>
document.addEventListener("DOMContentLoaded", function() {
    <?php if (!empty($per_hari)): ?>
    var ctx = document.getElementById('grafikLaporan').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels_grafik); ?>,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: <?php echo json_encode($nilai_grafik); ?>,
                backgroundColor: 'rgba(78, 115, 223, 0.7)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') } }
            }
        }
    });
    <?php endif; ?>
});
</script>

<style>
@media print {
    .sidebar, .topbar, .scroll-to-top, nav, footer, form, button, a.btn { display: none !important; }
    #content-wrapper { margin: 0 !important; }
    .card { box-shadow: none !important; border: 1px solid #ddd !important; }
}
</style>