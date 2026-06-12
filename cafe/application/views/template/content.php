<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="<?php echo base_url('transaksi/buat'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Transaksi Baru
        </a>
    </div>

    <!-- Content Row - Summary Cards -->
    <div class="row">

        <!-- Total Penjualan Hari Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Penjualan Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp <?php echo number_format($penjualan_hari_ini ?? 0, 0, ',', '.'); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Penjualan Bulan Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Penjualan Bulan Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp <?php echo number_format($penjualan_bulan_ini ?? 0, 0, ',', '.'); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Transaksi Hari Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Transaksi Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $jumlah_transaksi ?? 0; ?> Transaksi
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Menu Aktif -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Menu Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $total_menu ?? 0; ?> Menu
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-utensils fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row - Charts -->
    <div class="row">

        <!-- Grafik Penjualan 7 Hari -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Penjualan 7 Hari Terakhir</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="grafikPenjualan"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart Kategori -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Penjualan per Kategori</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="grafikKategori"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2"><i class="fas fa-circle text-primary"></i> Makanan</span>
                        <span class="mr-2"><i class="fas fa-circle text-success"></i> Minuman</span>
                        <span class="mr-2"><i class="fas fa-circle text-info"></i> Snack</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row - Tabel Transaksi Terbaru -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Transaksi Terbaru</h6>
                    <a href="<?php echo base_url('transaksi'); ?>" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Kode Transaksi</th>
                                    <th>Kasir</th>
                                    <th>Total</th>
                                    <th>Metode Bayar</th>
                                    <th>Status</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($transaksi_terbaru)): ?>
                                    <?php foreach ($transaksi_terbaru as $t): ?>
                                    <tr>
                                        <td><?php echo $t['kode_transaksi']; ?></td>
                                        <td><?php echo $t['nama']; ?></td>
                                        <td>Rp <?php echo number_format($t['total'], 0, ',', '.'); ?></td>
                                        <td>
                                            <?php 
                                            $badge = ['tunai'=>'success','qris'=>'info','transfer'=>'primary','debit'=>'warning'];
                                            $m = $t['metode_bayar'];
                                            ?>
                                            <span class="badge badge-<?php echo $badge[$m] ?? 'secondary'; ?>">
                                                <?php echo strtoupper($m); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $sb = ['selesai'=>'success','batal'=>'danger','pending'=>'warning'];
                                            $s = $t['status'];
                                            ?>
                                            <span class="badge badge-<?php echo $sb[$s] ?? 'secondary'; ?>">
                                                <?php echo ucfirst($s); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($t['created_at'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada transaksi hari ini</td>
                                    </tr>
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
    // Data dari PHP untuk chart
    var labelsPenjualan = <?php echo json_encode($label_grafik ?? ['Sen','Sel','Rab','Kam','Jum','Sab','Min']); ?>;
    var dataPenjualan   = <?php echo json_encode($data_grafik ?? [0,0,0,0,0,0,0]); ?>;
    var dataKategori    = <?php echo json_encode($data_kategori ?? [0,0,0]); ?>;

    // Grafik Area - Penjualan 7 Hari
    var ctx1 = document.getElementById('grafikPenjualan').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: labelsPenjualan,
            datasets: [{
                label: 'Penjualan (Rp)',
                data: dataPenjualan,
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') } }
            },
            plugins: { legend: { display: false } }
        }
    });

    // Grafik Pie - Kategori
    var ctx2 = document.getElementById('grafikKategori').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Makanan', 'Minuman', 'Snack'],
            datasets: [{
                data: dataKategori,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                hoverBorderColor: 'rgba(234, 236, 244, 1)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ctx.label + ': Rp ' + ctx.raw.toLocaleString('id-ID') } }
            },
            cutout: '70%'
        }
    });
});
</script>