<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Menu</h1>
        <a href="<?php echo base_url('menu'); ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-warning">
                    <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-edit mr-2"></i>Edit Data Menu</h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo base_url('menu/update/' . $menu['id']); ?>"
                          method="POST" enctype="multipart/form-data">

                        <!-- Preview Gambar -->
                        <div class="form-group text-center">
                            <?php if (!empty($menu['gambar'])): ?>
                                <img id="previewEdit"
                                     src="<?php echo base_url('uploads/' . $menu['gambar']); ?>"
                                     style="max-height:180px; border-radius:10px; object-fit:cover; margin-bottom:10px;">
                            <?php else: ?>
                                <img id="previewEdit" src="" style="max-height:180px; display:none; border-radius:10px; object-fit:cover; margin-bottom:10px;">
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Ganti Foto Menu</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="gambar" id="inputGambarEdit" accept="image/*">
                                <label class="custom-file-label" for="inputGambarEdit">Pilih foto baru (opsional)...</label>
                            </div>
                            <small class="text-muted">Kosongkan jika tidak ingin mengganti foto</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control"
                                   value="<?php echo htmlspecialchars($menu['nama']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-control" required>
                                <option value="Makanan" <?php echo $menu['kategori']=='Makanan'?'selected':''; ?>>Makanan</option>
                                <option value="Minuman" <?php echo $menu['kategori']=='Minuman'?'selected':''; ?>>Minuman</option>
                                <option value="Snack"   <?php echo $menu['kategori']=='Snack'?'selected':''; ?>>Snack</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Harga <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                <input type="number" name="harga" class="form-control"
                                       value="<?php echo $menu['harga']; ?>" min="1" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Status</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="isActive"
                                       name="is_active" value="1"
                                       <?php echo $menu['is_active'] ? 'checked' : ''; ?>>
                                <label class="custom-control-label" for="isActive">Menu Aktif</label>
                            </div>
                        </div>

                        <div class="text-right">
                            <a href="<?php echo base_url('menu'); ?>" class="btn btn-secondary mr-2">Batal</a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<script>
document.getElementById('inputGambarEdit').addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(ev) {
            var img = document.getElementById('previewEdit');
            img.src = ev.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(file);
        e.target.nextElementSibling.textContent = file.name;
    }
});
</script>