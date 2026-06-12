<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Riwayat Transaksi</h1>
        <a href="<?php echo base_url('transaksi/buat'); ?>" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Transaksi Baru
        </a>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle mr-1"></i><?php echo $this->session->flashdata('success'); ?>
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle mr-1"></i><?php echo $this->session->flashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
    <?php endif; ?>

    <!-- Ringkasan status -->
    <?php
    $jml_selesai = 0; $jml_pending = 0;
    foreach ($transaksi_list as $t) {
        if ($t['status'] === 'selesai') $jml_selesai++;
        if ($t['status'] === 'pending') $jml_pending++;
    }
    ?>
    <div class="row mb-3">
        <div class="col-md-3 col-6 mb-2">
            <div class="card border-left-success shadow-sm py-2 px-3">
                <div class="text-xs text-success font-weight-bold text-uppercase">Selesai</div>
                <div class="h4 mb-0 font-weight-bold text-gray-800"><?php echo $jml_selesai; ?></div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="card border-left-warning shadow-sm py-2 px-3">
                <div class="text-xs text-warning font-weight-bold text-uppercase">Pending</div>
                <div class="h4 mb-0 font-weight-bold text-gray-800"><?php echo $jml_pending; ?></div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi</h6>
            <!-- Filter status -->
            <div>
                <button class="btn btn-sm btn-secondary btn-filter-status active" data-status="semua">Semua</button>
                <button class="btn btn-sm btn-outline-success btn-filter-status" data-status="selesai">Selesai</button>
                <button class="btn btn-sm btn-outline-warning btn-filter-status" data-status="pending">Pending</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabelTransaksi" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode</th>
                            <th>Kasir</th>
                            <th>Total</th>
                            <th>Bayar</th>
                            <th>Kembalian</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Waktu</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transaksi_list as $t): ?>
                        <tr class="row-transaksi" data-status="<?php echo $t['status']; ?>">
                            <td><code><?php echo $t['kode_transaksi']; ?></code></td>
                            <td><?php echo htmlspecialchars($t['kasir']); ?></td>
                            <td>Rp <?php echo number_format($t['total'], 0, ',', '.'); ?></td>
                            <td>
                                <?php if ($t['status'] === 'pending'): ?>
                                    <span class="text-muted">—</span>
                                <?php else: ?>
                                    Rp <?php echo number_format($t['bayar'], 0, ',', '.'); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($t['status'] === 'pending'): ?>
                                    <span class="text-muted">—</span>
                                <?php else: ?>
                                    Rp <?php echo number_format($t['kembalian'], 0, ',', '.'); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $badge_m = ['tunai'=>'success','qris'=>'info','transfer'=>'primary','debit'=>'warning'];
                                $m = $t['metode_bayar'];
                                ?>
                                <span class="badge badge-<?php echo $badge_m[$m] ?? 'secondary'; ?>">
                                    <?php echo strtoupper($m); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($t['status'] === 'selesai'): ?>
                                    <span class="badge badge-success">Selesai</span>
                                <?php elseif ($t['status'] === 'pending'): ?>
                                    <span class="badge badge-warning text-dark">Pending</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary"><?php echo ucfirst($t['status']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($t['created_at'])); ?></td>
                            <td class="text-center" style="white-space:nowrap; min-width:120px;">

                                <!-- Tombol Detail -->
                                <a href="<?php echo base_url('transaksi/detail/' . $t['id']); ?>"
                                   class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- ✅ Tombol Bayar — hanya untuk pending -->
                                <?php if ($t['status'] === 'pending'): ?>
                                <button class="btn btn-success btn-sm" title="Proses Pembayaran"
                                    onclick="bukaBayar(
                                        <?php echo $t['id']; ?>,
                                        '<?php echo addslashes($t['kode_transaksi']); ?>',
                                        <?php echo $t['total']; ?>
                                    )">
                                    <i class="fas fa-money-bill-wave"></i>
                                </button>
                                <?php endif; ?>

                                <!-- ✅ Tombol Hapus — admin only -->
                                <?php if ($this->session->userdata('role') == 'admin'): ?>
                                <a href="<?php echo base_url('transaksi/hapus/' . $t['id']); ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Hapus transaksi <?php echo addslashes($t['kode_transaksi']); ?>?\nData tidak bisa dikembalikan!')"
                                   title="Hapus Permanen">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<!-- ✅ Modal Bayar Pending -->
<div class="modal fade" id="modalBayar" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white py-3">
                <h6 class="modal-title font-weight-bold">
                    <i class="fas fa-money-bill-wave mr-2"></i>Proses Pembayaran
                </h6>
                <button class="close text-white" type="button" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formBayar" method="POST">
                <div class="modal-body">
                    <p class="text-muted small mb-1">Kode Transaksi</p>
                    <p class="font-weight-bold" id="infKode">—</p>

                    <p class="text-muted small mb-1">Total Tagihan</p>
                    <p class="h5 font-weight-bold text-primary" id="infTotal">Rp 0</p>

                    <div class="form-group mb-2">
                        <label class="small font-weight-bold">Metode Pembayaran</label>
                        <select name="metode_bayar" class="form-control form-control-sm" id="modalMetode" onchange="gantiMetodeModal()">
                            <option value="tunai">💵 Tunai</option>
                            <option value="qris">📱 QRIS</option>
                            <option value="transfer">🏦 Transfer</option>
                            <option value="debit">💳 Debit</option>
                        </select>
                    </div>

                    <!-- Input bayar: hanya tampil jika tunai -->
                    <div id="bagianBayar">
                        <div class="form-group mb-1">
                            <label class="small font-weight-bold">Jumlah Bayar (Rp)</label>
                            <input type="number" name="bayar" id="modalInputBayar" class="form-control form-control-sm"
                                   placeholder="0" min="0" oninput="hitungModalKembalian()">
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-muted">Kembalian:</span>
                            <span class="font-weight-bold" id="modalKembalian">Rp 0</span>
                        </div>
                    </div>

                    <!-- Info non-tunai: langsung bisa konfirmasi -->
                    <div id="bagianNonTunai" style="display:none;">
                        <div class="alert alert-info py-2 mb-0 small">
                            <i class="fas fa-info-circle mr-1"></i>
                            Pastikan pembayaran sudah diterima sebelum konfirmasi.
                        </div>
                        <input type="hidden" id="modalBayarHidden" name="bayar" value="0">
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <!-- ✅ TIDAK disabled dari awal -->
                    <button type="submit" class="btn btn-success btn-sm" id="btnKonfirmBayar">
                        <i class="fas fa-check mr-1"></i> Konfirmasi Bayar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// ── Filter status & pencarian ────────────────
function filterDaftarTransaksi() {
    var status = 'semua';
    var activeBtn = document.querySelector('.btn-filter-status.active');
    if (activeBtn) {
        status = activeBtn.dataset.status;
    }
    
    var pencarianInput = document.getElementById('pencarianTransaksi');
    var query = pencarianInput ? pencarianInput.value.toLowerCase().trim() : '';

    document.querySelectorAll('.row-transaksi').forEach(function(row) {
        var matchesStatus = (status === 'semua' || row.dataset.status === status);
        
        // Cari teks di seluruh kolom baris
        var matchesQuery = true;
        if (query !== '') {
            var text = row.textContent.toLowerCase();
            matchesQuery = (text.indexOf(query) > -1);
        }

        if (matchesStatus && matchesQuery) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

document.querySelectorAll('.btn-filter-status').forEach(function(btn) {
    btn.addEventListener('click', function() {
        // Update tombol aktif
        document.querySelectorAll('.btn-filter-status').forEach(function(b) {
            b.className = b.className
                .replace(' active','')
                .replace('btn-secondary','btn-outline-secondary')
                .replace('btn-outline-success btn-outline-success','btn-outline-success')
                .replace('btn-outline-warning btn-outline-warning','btn-outline-warning');
        });
        this.classList.remove('btn-outline-secondary','btn-outline-success','btn-outline-warning');
        this.classList.add('btn-secondary','active');

        filterDaftarTransaksi();
    });
});

// Event listener untuk input pencarian di navbar
document.addEventListener("DOMContentLoaded", function() {
    var pencarianInput = document.getElementById('pencarianTransaksi');
    if (pencarianInput) {
        pencarianInput.addEventListener('input', filterDaftarTransaksi);
    }
});

// ── Modal Bayar ─────────────────────────────
var _totalModal = 0;

function bukaBayar(id, kode, total) {
    _totalModal = total;

    // Isi info
    document.getElementById('infKode').textContent    = kode;
    document.getElementById('infTotal').textContent   = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('formBayar').action       = '<?php echo base_url("transaksi/bayar/"); ?>' + id;

    // Reset form
    document.getElementById('modalInputBayar').value  = '';
    document.getElementById('modalBayarHidden').value = total;
    document.getElementById('modalKembalian').textContent = 'Rp 0';
    document.getElementById('modalKembalian').className   = 'font-weight-bold text-muted';
    document.getElementById('modalMetode').value      = 'tunai';
    document.getElementById('btnKonfirmBayar').disabled   = false; // ✅ selalu aktif saat buka

    // Tampilkan bagian tunai
    gantiMetodeModal();

    $('#modalBayar').modal('show');
}

function gantiMetodeModal() {
    var metode  = document.getElementById('modalMetode').value;
    var isTunai = (metode === 'tunai');

    document.getElementById('bagianBayar').style.display    = isTunai ? '' : 'none';
    document.getElementById('bagianNonTunai').style.display = isTunai ? 'none' : '';

    if (isTunai) {
        // Tunai: input bayar aktif, hidden tidak aktif
        document.getElementById('modalInputBayar').setAttribute('name', 'bayar');
        document.getElementById('modalBayarHidden').removeAttribute('name');
        // Cek kembalian jika sudah ada input
        hitungModalKembalian();
    } else {
        // Non-tunai: hidden bayar = total, input tunai dinonaktifkan
        document.getElementById('modalInputBayar').removeAttribute('name');
        document.getElementById('modalBayarHidden').setAttribute('name', 'bayar');
        document.getElementById('modalBayarHidden').value = _totalModal;
        document.getElementById('btnKonfirmBayar').disabled = false;
    }
}

function hitungModalKembalian() {
    var bayar = parseFloat(document.getElementById('modalInputBayar').value) || 0;
    var kem   = bayar - _totalModal;
    var el    = document.getElementById('modalKembalian');

    if (bayar === 0) {
        el.textContent = 'Rp 0';
        el.className   = 'font-weight-bold text-muted';
        document.getElementById('btnKonfirmBayar').disabled = true;
    } else if (kem < 0) {
        el.textContent = '− Rp ' + Math.abs(kem).toLocaleString('id-ID');
        el.className   = 'font-weight-bold text-danger';
        document.getElementById('btnKonfirmBayar').disabled = true;
    } else {
        el.textContent = 'Rp ' + kem.toLocaleString('id-ID');
        el.className   = 'font-weight-bold text-success';
        document.getElementById('btnKonfirmBayar').disabled = false;
    }
}
</script>