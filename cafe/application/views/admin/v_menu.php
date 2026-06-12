<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Menu</h1>
        <?php if ($this->session->userdata('role') == 'admin'): ?>
        <button class="btn btn-primary btn-sm shadow-sm" data-toggle="modal" data-target="#modalTambahMenu">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Menu
        </button>
        <?php endif; ?>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle mr-2"></i><?php echo $this->session->flashdata('success'); ?>
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle mr-2"></i><?php echo $this->session->flashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
    <?php endif; ?>

    <!-- Grid Menu Cards -->
    <div class="row" id="gridMenu">
        <?php foreach ($menu_list as $m): ?>
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4 item-menu" data-kat="<?php echo $m['kategori']; ?>">
            <div class="card shadow h-100">
                <!-- Gambar Menu -->
                <div style="height:180px; overflow:hidden; background:#f8f9fc; display:flex; align-items:center; justify-content:center;">
                    <?php if (!empty($m['gambar'])): ?>
                        <img src="<?php echo base_url('uploads/' . $m['gambar']); ?>"
                             alt="<?php echo htmlspecialchars($m['nama']); ?>"
                             style="width:100%; height:180px; object-fit:cover;">
                    <?php else: ?>
                        <?php
                        $icons  = ['Makanan'=>'fa-hamburger','Minuman'=>'fa-glass-cheers','Snack'=>'fa-cookie'];
                        $colors = ['Makanan'=>'#4e73df','Minuman'=>'#1cc88a','Snack'=>'#36b9cc'];
                        $icon   = $icons[$m['kategori']]  ?? 'fa-utensils';
                        $color  = $colors[$m['kategori']] ?? '#858796';
                        ?>
                        <i class="fas <?php echo $icon; ?> fa-4x" style="color:<?php echo $color; ?>; opacity:0.4;"></i>
                    <?php endif; ?>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="font-weight-bold mb-1"><?php echo htmlspecialchars($m['nama']); ?></h6>
                            <?php
                            $cat_badge = ['Makanan'=>'primary','Minuman'=>'success','Snack'=>'info'];
                            ?>
                            <span class="badge badge-<?php echo $cat_badge[$m['kategori']] ?? 'secondary'; ?>">
                                <?php echo $m['kategori']; ?>
                            </span>
                        </div>
                        <span class="badge badge-<?php echo $m['is_active'] ? 'success' : 'secondary'; ?>">
                            <?php echo $m['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                        </span>
                    </div>
                    <p class="text-primary font-weight-bold mt-2 mb-0">
                        Rp <?php echo number_format($m['harga'], 0, ',', '.'); ?>
                    </p>
                </div>
                <?php if ($this->session->userdata('role') == 'admin'): ?>
                <div class="card-footer p-2 d-flex" style="gap:6px;">
                    <a href="<?php echo base_url('menu/edit/' . $m['id']); ?>"
                       class="btn btn-warning btn-sm flex-fill">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="<?php echo base_url('menu/hapus/' . $m['id']); ?>"
                       class="btn btn-danger btn-sm flex-fill"
                       onclick="return confirm('Yakin hapus menu <?php echo addslashes($m['nama']); ?>?')">
                        <i class="fas fa-trash"></i> Hapus
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>
<!-- /.container-fluid -->

<!-- Modal Tambah Menu -->
<?php if ($this->session->userdata('role') == 'admin'): ?>
<div class="modal fade" id="modalTambahMenu" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus mr-2"></i>Tambah Menu Baru</h5>
                <button class="close text-white" type="button" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="<?php echo base_url('menu/tambah'); ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Foto Menu</label>
                        <div class="text-center mb-2">
                            <img id="previewTambah" src="" alt="Preview"
                                 style="max-height:150px; display:none; border-radius:8px; object-fit:cover;">
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="gambar" id="inputGambarTambah"
                                   accept="image/*">
                            <label class="custom-file-label" for="inputGambarTambah">Pilih foto...</label>
                        </div>
                        <small class="text-muted">Format: JPG, PNG, GIF, WEBP. Maks 2MB</small>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Menu <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" placeholder="contoh: Nasi Goreng Spesial" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Makanan">Makanan</option>
                            <option value="Minuman">Minuman</option>
                            <option value="Snack">Snack</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                            <input type="number" name="harga" class="form-control" placeholder="0" min="1" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
// Preview gambar saat pilih file
document.getElementById('inputGambarTambah').addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(ev) {
            var img = document.getElementById('previewTambah');
            img.src = ev.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(file);
        e.target.nextElementSibling.textContent = file.name;
    }
});
</script>