<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-cash-register mr-2 text-primary"></i>Transaksi</h1>
        <a href="<?php echo base_url('transaksi'); ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-list fa-sm"></i> Riwayat
        </a>
    </div>

    <!-- Alert -->
    <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle mr-2"></i><?php echo $this->session->flashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-warning alert-dismissible fade show">
        <i class="fas fa-clock mr-2"></i><?php echo $this->session->flashdata('success'); ?>
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
    <?php endif; ?>

    <!-- STRUK setelah berhasil bayar -->
    <?php if (!empty($struk)): ?>
    <div class="alert alert-success">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h5 class="font-weight-bold"><i class="fas fa-check-circle mr-2"></i>Transaksi Berhasil!</h5>
                <p class="mb-1">Kode: <strong><?php echo $struk['kode']; ?></strong> | Kasir: <?php echo $struk['kasir']; ?> | <?php echo $struk['waktu']; ?></p>
                <table class="table table-sm table-borderless mb-1" style="max-width:400px;">
                    <?php foreach ($struk['detail'] as $d): ?>
                    <tr>
                        <td class="py-0"><?php echo $d['nama_menu']; ?> x<?php echo $d['qty']; ?></td>
                        <td class="py-0 text-right">Rp <?php echo number_format($d['subtotal'],0,',','.'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="border-top font-weight-bold">
                        <td class="py-0">Total</td>
                        <td class="py-0 text-right">Rp <?php echo number_format($struk['total'],0,',','.'); ?></td>
                    </tr>
                    <tr>
                        <td class="py-0">Bayar (<?php echo strtoupper($struk['metode']); ?>)</td>
                        <td class="py-0 text-right">Rp <?php echo number_format($struk['bayar'],0,',','.'); ?></td>
                    </tr>
                    <tr class="text-success font-weight-bold">
                        <td class="py-0">Kembalian</td>
                        <td class="py-0 text-right">Rp <?php echo number_format($struk['kembalian'],0,',','.'); ?></td>
                    </tr>
                </table>
            </div>
            <button onclick="window.print()" class="btn btn-outline-success btn-sm"><i class="fas fa-print"></i> Cetak</button>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <!-- Panel Kiri: Daftar Menu -->
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:6px;">
                        <h6 class="m-0 font-weight-bold text-primary">Pilih Menu</h6>
                        <div>
                            <button class="btn btn-sm btn-secondary mr-1 btn-filter active" onclick="filterMenu('semua',this)">Semua</button>
                            <button class="btn btn-sm btn-outline-primary mr-1 btn-filter" onclick="filterMenu('Makanan',this)">Makanan</button>
                            <button class="btn btn-sm btn-outline-success mr-1 btn-filter" onclick="filterMenu('Minuman',this)">Minuman</button>
                            <button class="btn btn-sm btn-outline-info btn-filter" onclick="filterMenu('Snack',this)">Snack</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row" id="daftarMenu">
                        <?php foreach ($menu_list as $m): ?>
                        <div class="col-md-4 col-6 mb-3 item-menu" data-kat="<?php echo $m['kategori']; ?>">
                            <div class="card h-100 border"
                                 style="cursor:pointer; transition:transform 0.15s, box-shadow 0.15s;"
                                 onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.15)'"
                                 onmouseout="this.style.transform=''; this.style.boxShadow=''"
                                 onclick="tambahKeranjang('<?php echo $m['id']; ?>','<?php echo addslashes($m['nama']); ?>',<?php echo $m['harga']; ?>)">
                                <div style="height:90px; overflow:hidden; background:#f8f9fc; display:flex; align-items:center; justify-content:center; border-radius:4px 4px 0 0;">
                                    <?php if (!empty($m['gambar'])): ?>
                                        <img src="<?php echo base_url('uploads/' . $m['gambar']); ?>"
                                             style="width:100%; height:90px; object-fit:cover;">
                                    <?php else: ?>
                                        <?php
                                        $icons  = ['Makanan'=>'fa-hamburger','Minuman'=>'fa-glass-cheers','Snack'=>'fa-cookie'];
                                        $colors = ['Makanan'=>'#4e73df','Minuman'=>'#1cc88a','Snack'=>'#36b9cc'];
                                        ?>
                                        <i class="fas <?php echo $icons[$m['kategori']] ?? 'fa-utensils'; ?> fa-2x"
                                           style="color:<?php echo $colors[$m['kategori']] ?? '#858796'; ?>; opacity:0.4;"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body text-center p-2">
                                    <p class="mb-0 small font-weight-bold" style="font-size:0.78rem; line-height:1.2;">
                                        <?php echo htmlspecialchars($m['nama']); ?>
                                    </p>
                                    <p class="text-primary small mb-0 font-weight-bold" style="font-size:0.78rem;">
                                        Rp <?php echo number_format($m['harga'],0,',','.'); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Kanan: Keranjang -->
        <div class="col-lg-5">
            <div class="card shadow" style="position:sticky; top:20px;">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-shopping-cart mr-2"></i>Pesanan</h6>
                    <button class="btn btn-sm btn-light" type="button" onclick="kosongkanKeranjang()">
                        <i class="fas fa-trash text-danger"></i> Kosongkan
                    </button>
                </div>

                <div style="min-height:180px; max-height:320px; overflow-y:auto;">
                    <div id="keranjangKosong" class="text-center text-muted py-5">
                        <i class="fas fa-shopping-cart fa-3x mb-3 text-gray-300"></i>
                        <p class="mb-0">Belum ada pesanan</p>
                        <small>Klik menu untuk menambahkan</small>
                    </div>
                    <table class="table table-sm mb-0" id="tabelKeranjang" style="display:none;">
                        <thead class="thead-light">
                            <tr>
                                <th>Menu</th>
                                <th width="95">Qty</th>
                                <th width="95" class="text-right">Sub</th>
                                <th width="30"></th>
                            </tr>
                        </thead>
                        <tbody id="isiKeranjang"></tbody>
                    </table>
                </div>

                <div class="px-3 py-2 bg-light border-top border-bottom">
                    <div class="d-flex justify-content-between">
                        <strong>TOTAL</strong>
                        <strong class="text-primary" id="totalHarga">Rp 0</strong>
                    </div>
                </div>

                <div class="card-body pb-3">
                    <form action="<?php echo base_url('transaksi/simpan'); ?>" method="POST" id="formTransaksi">
                        <input type="hidden" name="items_json" id="itemsJson" value="[]">
                        <input type="hidden" name="status" id="inputStatus" value="selesai">

                        <!-- ✅ Metode Pembayaran dengan real-time toggle -->
                        <div class="form-group mb-2">
                            <label class="small font-weight-bold">Metode Pembayaran</label>
                            <select name="metode_bayar" id="selectMetode" class="form-control form-control-sm"
                                    onchange="gantiMetode()">
                                <option value="tunai">💵 Tunai</option>
                                <option value="qris">📱 QRIS</option>
                                <option value="transfer">🏦 Transfer</option>
                                <option value="debit">💳 Debit</option>
                            </select>
                        </div>

                        <!-- Bagian Tunai: input jumlah bayar -->
                        <div id="bagianTunai">
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold">Jumlah Bayar (Rp)</label>
                                <input type="number" id="inputBayar" name="bayar"
                                       class="form-control form-control-sm"
                                       placeholder="Masukkan jumlah bayar"
                                       min="0" oninput="hitungKembalian()">
                            </div>
                            <div id="btnUangPas" class="mb-2"></div>
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">Kembalian:</small>
                                <small id="kembalian" class="font-weight-bold text-success">Rp 0</small>
                            </div>
                        </div>

                        <!-- ✅ Bagian Non-Tunai: info konfirmasi, bayar = total otomatis -->
                        <div id="bagianNonTunai" style="display:none;">
                            <!-- QRIS -->
                            <div id="infoQris" style="display:none;">
                                <div class="text-center p-3 border rounded mb-2" style="background:#f8f9fc;">
                                    <div class="my-2 p-3 border rounded bg-white" id="qrisDinamisContainer" style="min-height:100px; display:flex; align-items:center; justify-content:center;">
                                        <div class="text-muted text-center" id="qrisDinamisPlaceholder">
                                            <i class="fas fa-magic text-primary fa-2x mb-2"></i>
                                            <p class="small mb-0 font-weight-bold text-dark">QR Code Dinamis (Midtrans)</p>
                                            <p class="small text-muted mb-0" style="font-size: 0.75rem;">Akan dibuat setelah menekan tombol Proses Pembayaran</p>
                                        </div>
                                    </div>
                                    <p class="small text-muted mb-0">Nominal: <span class="text-primary font-weight-bold classNominal" id="nominalQris">Rp 0</span></p>
                                </div>
                            </div>
                            <!-- Transfer -->
                            <div id="infoTransfer" style="display:none;">
                                <div class="alert alert-primary py-2 mb-2 small">
                                    <i class="fas fa-university mr-1"></i>
                                    <strong>Transfer Bank</strong><br>
                                    Nominal: <strong id="nominalTransfer">Rp 0</strong><br>
                                    <span class="text-muted">Pastikan sudah diterima sebelum konfirmasi.</span>
                                </div>
                            </div>
                            <!-- Debit -->
                            <div id="infoDebit" style="display:none;">
                                <div class="alert alert-warning py-2 mb-2 small">
                                    <i class="fas fa-credit-card mr-1"></i>
                                    <strong>Kartu Debit</strong><br>
                                    Nominal: <strong id="nominalDebit">Rp 0</strong><br>
                                    <span class="text-muted">Proses mesin EDC, lalu konfirmasi.</span>
                                </div>
                            </div>
                            <!-- Input bayar tersembunyi = total -->
                            <input type="number" name="bayar" id="bayarNonTunai" value="0" style="display:none;">
                        </div>

                        <!-- Tombol Proses Pembayaran -->
                        <button type="button" id="btnBayar" class="btn btn-success btn-block mb-2"
                                onclick="prosesTransaksi('selesai')" disabled>
                            <i class="fas fa-check-circle mr-2"></i>Proses Pembayaran
                        </button>

                        <!-- ✅ Tombol Simpan Pending -->
                        <button type="button" id="btnPending" class="btn btn-warning btn-block btn-sm"
                                onclick="prosesTransaksi('pending')" disabled>
                            <i class="fas fa-clock mr-2"></i>Simpan Pending (Belum Bayar)
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<!-- Modal QRIS Dinamis -->
<div class="modal fade" id="modalQrisDinamis" tabindex="-1" role="dialog" aria-labelledby="modalQrisDinamisLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalQrisDinamisLabel"><i class="fas fa-qrcode mr-2"></i>Pembayaran QRIS Dinamis</h5>
            </div>
            <div class="modal-body text-center py-4">
                <h6 class="text-muted mb-2">Kode Transaksi: <strong id="modalTrxKode">-</strong></h6>
                <h4 class="font-weight-bold text-primary mb-3" id="modalTrxTotal">Rp 0</h4>
                
                <div id="modalQrCodeContainer" class="my-3 p-3 border rounded d-inline-block bg-white shadow-sm" style="min-width: 220px; min-height: 220px;">
                    <div id="modalQrCodeLoading" class="py-5">
                        <i class="fas fa-spinner fa-spin fa-2x text-primary mb-2"></i>
                        <p class="small text-muted mb-0">Membuat QR Code...</p>
                    </div>
                    <img id="modalQrCodeImg" src="" alt="QR Code Midtrans" class="img-fluid" style="display: none; max-width: 250px;">
                </div>
                
                <div class="mt-3">
                    <p class="mb-1 font-weight-bold text-dark" id="statusMessage">
                        <i class="fas fa-sync fa-spin mr-1 text-primary"></i> Menghubungi Midtrans API...
                    </p>
                    <small class="text-muted">Halaman ini akan otomatis diperbarui setelah pembayaran diterima.</small>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="cancelQrisPolling()"><i class="fas fa-times mr-1"></i> Batal</button>
                <button type="button" class="btn btn-warning btn-sm" onclick="konfirmasiManual()"><i class="fas fa-check mr-1"></i> Konfirmasi Manual (Uang Masuk)</button>
            </div>
        </div>
    </div>
</div>

<script>
var keranjang  = {};
var totalHarga = 0;

// ── Tambah ke keranjang ──────────────────────
function tambahKeranjang(id, nama, harga) {
    id = String(id);
    if (keranjang[id]) {
        keranjang[id].qty++;
    } else {
        keranjang[id] = { nama: nama, harga: parseFloat(harga), qty: 1 };
    }
    renderKeranjang();
}

// ── Render keranjang ─────────────────────────
function renderKeranjang() {
    var tbody    = document.getElementById('isiKeranjang');
    var kosong   = document.getElementById('keranjangKosong');
    var tabel    = document.getElementById('tabelKeranjang');
    var btnBayar    = document.getElementById('btnBayar');
    var btnPending  = document.getElementById('btnPending');

    tbody.innerHTML = '';
    totalHarga = 0;
    var keys = Object.keys(keranjang);

    if (keys.length === 0) {
        kosong.style.display = '';
        tabel.style.display  = 'none';
        btnBayar.disabled    = true;
        btnPending.disabled  = true;
        document.getElementById('btnUangPas').innerHTML = '';
    } else {
        kosong.style.display = 'none';
        tabel.style.display  = '';
        btnBayar.disabled    = false;
        btnPending.disabled  = false;

        keys.forEach(function(id) {
            var item = keranjang[id];
            var sub  = item.harga * item.qty;
            totalHarga += sub;

            var tr = document.createElement('tr');
            tr.innerHTML =
                '<td class="align-middle small" style="max-width:100px; word-break:break-word;">' + item.nama + '</td>' +
                '<td class="align-middle">' +
                    '<div class="input-group input-group-sm">' +
                        '<div class="input-group-prepend">' +
                            '<button class="btn btn-outline-secondary" type="button" onclick="ubahQty(\'' + id + '\',-1)">−</button>' +
                        '</div>' +
                        '<input type="text" class="form-control text-center px-0" value="' + item.qty + '" readonly style="min-width:30px;">' +
                        '<div class="input-group-append">' +
                            '<button class="btn btn-outline-secondary" type="button" onclick="ubahQty(\'' + id + '\',1)">+</button>' +
                        '</div>' +
                    '</div>' +
                '</td>' +
                '<td class="align-middle text-right small" style="white-space:nowrap;">Rp ' + sub.toLocaleString('id-ID') + '</td>' +
                '<td class="align-middle">' +
                    '<button class="btn btn-danger btn-sm" type="button" onclick="hapusItem(\'' + id + '\')"><i class="fas fa-times"></i></button>' +
                '</td>';
            tbody.appendChild(tr);
        });

        // Tombol uang pas (hanya untuk tunai)
        if (document.getElementById('selectMetode').value === 'tunai') {
            var nominals = buatNominal(totalHarga);
            var html = '<div class="d-flex flex-wrap" style="gap:4px;">';
            nominals.forEach(function(n) {
                html += '<button type="button" class="btn btn-outline-secondary btn-sm" onclick="isiNominal(' + n + ')">Rp ' + n.toLocaleString('id-ID') + '</button>';
            });
            html += '</div>';
            document.getElementById('btnUangPas').innerHTML = html;
        }
    }

    document.getElementById('totalHarga').textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
    updateNominalNonTunai();
    hitungKembalian();
    updateItemsJson();
}

function buatNominal(total) {
    var steps = [1000, 2000, 5000, 10000, 20000, 50000, 100000];
    var hasil = new Set();
    hasil.add(total);
    steps.forEach(function(s) {
        var n = Math.ceil(total / s) * s;
        if (n >= total) hasil.add(n);
    });
    return Array.from(hasil).sort(function(a,b){return a-b;}).slice(0,5);
}

// ── Aksi keranjang ───────────────────────────
function ubahQty(id, delta) {
    id = String(id);
    if (!keranjang[id]) return;
    keranjang[id].qty += delta;
    if (keranjang[id].qty <= 0) delete keranjang[id];
    renderKeranjang();
}
function hapusItem(id) { delete keranjang[String(id)]; renderKeranjang(); }
function kosongkanKeranjang() { keranjang = {}; renderKeranjang(); }
function isiNominal(n) { document.getElementById('inputBayar').value = n; hitungKembalian(); }

// ── Kembalian ────────────────────────────────
function hitungKembalian() {
    var bayar = parseFloat(document.getElementById('inputBayar').value) || 0;
    var kem   = bayar - totalHarga;
    var el    = document.getElementById('kembalian');
    if (kem < 0) {
        el.textContent = '− Rp ' + Math.abs(kem).toLocaleString('id-ID');
        el.className   = 'font-weight-bold text-danger';
    } else {
        el.textContent = 'Rp ' + kem.toLocaleString('id-ID');
        el.className   = 'font-weight-bold text-success';
    }
}

function gantiMetode() {
    var metode = document.getElementById('selectMetode').value;
    var isTunai = (metode === 'tunai');

    document.getElementById('bagianTunai').style.display    = isTunai ? '' : 'none';
    document.getElementById('bagianNonTunai').style.display = isTunai ? 'none' : '';

    // Sembunyikan semua info non-tunai dulu
    ['infoQris','infoTransfer','infoDebit'].forEach(function(id) {
        document.getElementById(id).style.display = 'none';
    });

    if (!isTunai) {
        var map = { 'qris':'infoQris', 'transfer':'infoTransfer', 'debit':'infoDebit' };
        if (map[metode]) document.getElementById(map[metode]).style.display = '';

        // Set bayar = total otomatis untuk non-tunai
        document.getElementById('bayarNonTunai').value = totalHarga;
        // Hapus name dari input tunai agar tidak konflik
        document.getElementById('inputBayar').removeAttribute('name');
        document.getElementById('bayarNonTunai').setAttribute('name','bayar');
    } else {
        document.getElementById('inputBayar').setAttribute('name','bayar');
        document.getElementById('bayarNonTunai').removeAttribute('name');
    }

    updateNominalNonTunai();
}


function updateNominalNonTunai() {
    var fmt = 'Rp ' + totalHarga.toLocaleString('id-ID');
    var ids = ['nominalQris','nominalTransfer','nominalDebit'];
    ids.forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.textContent = fmt;
    });
    document.querySelectorAll('.classNominal').forEach(function(el) {
        el.textContent = fmt;
    });
    document.getElementById('bayarNonTunai').value = totalHarga;
}

// ── Update JSON ──────────────────────────────
function updateItemsJson() {
    var items = [];
    Object.keys(keranjang).forEach(function(id) {
        items.push({ menu_id: id, qty: keranjang[id].qty });
    });
    document.getElementById('itemsJson').value = JSON.stringify(items);
}

// Global variables for QRIS dynamic payment
var qrisInterval = null;
var currentQrisKode = null;

function buatQrisDinamis() {
    // Show modal and reset status
    $('#modalQrisDinamis').modal('show');
    $('#modalQrCodeLoading').show();
    $('#modalQrCodeImg').hide().attr('src', '');
    $('#modalTrxKode').text('-');
    $('#modalTrxTotal').text('Rp ' + totalHarga.toLocaleString('id-ID'));
    $('#statusMessage').html('<i class="fas fa-spinner fa-spin mr-1 text-primary"></i> Menghubungi Midtrans API...');

    updateItemsJson();
    var itemsJson = document.getElementById('itemsJson').value;

    $.ajax({
        url: '<?php echo base_url("transaksi/buat_qris"); ?>',
        method: 'POST',
        data: { items_json: itemsJson },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#modalQrCodeLoading').hide();
                $('#modalQrCodeImg').attr('src', response.qr_url).show();
                $('#modalTrxKode').text(response.kode_transaksi);
                currentQrisKode = response.kode_transaksi;
                $('#statusMessage').html('<i class="fas fa-sync fa-spin mr-1 text-primary"></i> Menunggu pembayaran dari pelanggan...');
                
                // Start polling payment status
                startQrisPolling(response.kode_transaksi);
            } else {
                $('#modalQrisDinamis').modal('hide');
                alert(response.message || 'Gagal membuat transaksi QRIS.');
            }
        },
        error: function(xhr, status, error) {
            $('#modalQrisDinamis').modal('hide');
            alert('Koneksi error: Gagal menghubungi server untuk membuat QRIS.');
            console.error(error);
        }
    });
}

function startQrisPolling(kode) {
    if (qrisInterval) clearInterval(qrisInterval);

    qrisInterval = setInterval(function() {
        $.ajax({
            url: '<?php echo base_url("transaksi/cek_status_pembayaran/"); ?>' + kode,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (response.status === 'selesai') {
                        clearInterval(qrisInterval);
                        $('#statusMessage').html('<i class="fas fa-check-circle mr-1 text-success"></i> Pembayaran Berhasil!');
                        setTimeout(function() {
                            $('#modalQrisDinamis').modal('hide');
                            window.location.reload(); // Reload will load the flash receipt
                        }, 1500);
                    } else if (response.status === 'batal') {
                        clearInterval(qrisInterval);
                        $('#statusMessage').html('<i class="fas fa-times-circle mr-1 text-danger"></i> Pembayaran Dibatalkan/Kedaluwarsa');
                        alert('Transaksi QRIS kedaluwarsa atau dibatalkan.');
                        $('#modalQrisDinamis').modal('hide');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Polling error:', error);
            }
        });
    }, 3000);
}

function cancelQrisPolling() {
    if (qrisInterval) clearInterval(qrisInterval);
    $('#modalQrisDinamis').modal('hide');
    alert('Proses QRIS dibatalkan. Pesanan tersimpan di keranjang.');
}

function konfirmasiManual() {
    if (!currentQrisKode) return;
    if (confirm('Apakah Anda yakin ingin mengonfirmasi pembayaran QRIS ini secara MANUAL?\n\nGunakan ini jika pelanggan sudah sukses transfer/bayar tapi sistem belum terupdate.')) {
        if (qrisInterval) clearInterval(qrisInterval);
        
        $('#statusMessage').html('<i class="fas fa-spinner fa-spin mr-1 text-warning"></i> Mengonfirmasi pembayaran...');
        
        $.ajax({
            url: '<?php echo base_url("transaksi/konfirmasi_manual_qris/"); ?>' + currentQrisKode,
            method: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#statusMessage').html('<i class="fas fa-check-circle mr-1 text-success"></i> Konfirmasi Sukses!');
                    setTimeout(function() {
                        $('#modalQrisDinamis').modal('hide');
                        window.location.reload();
                    }, 1500);
                } else {
                    alert(response.message || 'Gagal konfirmasi manual.');
                    startQrisPolling(currentQrisKode);
                }
            },
            error: function(xhr, status, error) {
                alert('Gagal menghubungi server untuk konfirmasi manual.');
                startQrisPolling(currentQrisKode);
            }
        });
    }
}

function prosesTransaksi(status) {
    if (Object.keys(keranjang).length === 0) {
        alert('Pilih menu terlebih dahulu!');
        return;
    }

    var metode = document.getElementById('selectMetode').value;

    if (status === 'selesai' && metode === 'tunai') {
        var bayar = parseFloat(document.getElementById('inputBayar').value) || 0;
        if (bayar <= 0) {
            alert('Isi jumlah bayar terlebih dahulu!');
            document.getElementById('inputBayar').focus();
            return;
        }
        if (bayar < totalHarga) {
            alert('Jumlah bayar kurang!\nTotal: Rp ' + totalHarga.toLocaleString('id-ID') + '\nBayar: Rp ' + bayar.toLocaleString('id-ID'));
            return;
        }
    }

    // Dynamic QRIS processing
    if (status === 'selesai' && metode === 'qris') {
        buatQrisDinamis();
        return;
    }

    document.getElementById('inputStatus').value = status;
    updateItemsJson();

    var msg = status === 'pending'
        ? 'Simpan pesanan ini sebagai PENDING (belum bayar)?'
        : 'Konfirmasi pembayaran ' + metode.toUpperCase() + '?';

    if (confirm(msg)) {
        document.getElementById('formTransaksi').submit();
    }
}

// ── Filter kategori ──────────────────────────
function filterMenu(kat, btn) {
    document.querySelectorAll('.btn-filter').forEach(function(b) {
        b.className = b.className.replace(' active','').replace('btn-secondary','btn-outline-secondary');
    });
    btn.className = btn.className.replace('btn-outline-secondary','btn-secondary') + ' active';
    document.querySelectorAll('.item-menu').forEach(function(el) {
        el.style.display = (kat === 'semua' || el.dataset.kat === kat) ? '' : 'none';
    });
}
</script>