<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Kategori Marketplace</h1>
            <p class="text-xs text-slate-400 mt-1">Kelola taksonomi kategori barang bekas Bekasin-Aja.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Form Tambah Kategori -->
        <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800 h-fit">
            <h2 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-4 h-4 text-emerald-400"></i> Tambah Kategori Baru
            </h2>

            <form action="<?= base_url('admin/categories/store') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Kategori <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Elektronik & Gadget" class="w-full px-3 py-2.5 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white placeholder:text-slate-600 focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Icon Lucide</label>
                    <input type="text" name="icon" value="tag" placeholder="smartphone, camera, shirt, tag..." class="w-full px-3 py-2.5 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white placeholder:text-slate-600 focus:outline-none focus:border-emerald-500">
                    <p class="text-[10px] text-slate-500 mt-1">Gunakan nama ikon dari pustaka lucide.dev</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Urutan Tampilan (Sort Order)</label>
                    <input type="number" name="sort_order" value="0" class="w-full px-3 py-2.5 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi kategori..." class="w-full px-3 py-2.5 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white placeholder:text-slate-600 focus:outline-none focus:border-emerald-500"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center justify-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Simpan Kategori
                </button>
            </form>
        </div>

        <!-- List Kategori -->
        <div class="lg:col-span-2 bg-slate-900 rounded-3xl border border-slate-800 overflow-hidden">
            <div class="p-4 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Daftar Kategori (<?= count($categories) ?>)</h3>
            </div>
            
            <div class="divide-y divide-slate-800">
                <?php if (empty($categories)): ?>
                    <div class="p-6 text-center text-slate-500 text-xs">Belum ada kategori terdaftar.</div>
                <?php else: ?>
                    <?php foreach ($categories as $cat): ?>
                        <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs hover:bg-slate-800/30 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-sm shrink-0 border border-purple-500/30">
                                    <i data-lucide="<?= esc($cat['icon'] ?? 'tag') ?>" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <strong class="text-white block text-sm"><?= esc($cat['name']) ?></strong>
                                    <div class="flex items-center gap-2 text-slate-400 text-[11px] mt-0.5">
                                        <span class="font-mono text-slate-500">/<?= esc($cat['slug']) ?></span>
                                        <span>•</span>
                                        <span>Urutan: <?= $cat['sort_order'] ?? 0 ?></span>
                                        <?php if (isset($cat['products_count'])): ?>
                                            <span>•</span>
                                            <span class="text-emerald-400 font-medium"><?= (int)$cat['products_count'] ?> barang</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($cat['description'])): ?>
                                        <p class="text-[11px] text-slate-400 mt-1 line-clamp-1"><?= esc($cat['description']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 self-end sm:self-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?= !empty($cat['is_active']) ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-400 border border-rose-500/30' ?>">
                                    <?= !empty($cat['is_active']) ? 'Aktif' : 'Non-aktif' ?>
                                </span>

                                <button 
                                    type="button" 
                                    onclick="openEditCategory(<?= htmlspecialchars(json_encode($cat), ENT_QUOTES, 'UTF-8') ?>)"
                                    class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition-colors flex items-center gap-1"
                                    title="Edit Kategori"
                                >
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit
                                </button>

                                <form action="<?= base_url('admin/categories/' . $cat['id'] . '/delete') ?>" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori <?= esc(addslashes($cat['name'])) ?>?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-rose-500/20 text-rose-400 hover:bg-rose-500/30 transition-colors flex items-center gap-1" title="Hapus Kategori">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<!-- Modal Edit Kategori -->
<div id="modal-edit-category" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs hidden">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl relative">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                <i data-lucide="edit-3" class="w-4 h-4 text-blue-400"></i> Edit Kategori
            </h3>
            <button type="button" onclick="closeEditCategory()" class="text-slate-400 hover:text-white p-1">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="form-edit-category" method="POST" class="space-y-4 mt-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Kategori <span class="text-rose-400">*</span></label>
                <input type="text" id="edit-cat-name" name="name" required class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Icon Lucide</label>
                    <input type="text" id="edit-cat-icon" name="icon" class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Urutan Sort</label>
                    <input type="number" id="edit-cat-sort" name="sort_order" class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Deskripsi Singkat</label>
                <textarea id="edit-cat-desc" name="description" rows="2" class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500"></textarea>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" id="edit-cat-active" name="is_active" value="1" class="w-4 h-4 rounded border-slate-800 text-emerald-600 focus:ring-emerald-500 bg-slate-950">
                <label for="edit-cat-active" class="text-xs font-semibold text-slate-300 select-none">Status Kategori Aktif</label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-800">
                <button type="button" onclick="closeEditCategory()" class="px-4 py-2 text-xs font-bold rounded-xl text-slate-400 hover:bg-slate-800 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-bold rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition-colors shadow-xs">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditCategory(cat) {
    const modal = document.getElementById('modal-edit-category');
    const form = document.getElementById('form-edit-category');
    
    form.action = '<?= base_url('admin/categories') ?>/' + cat.id + '/update';
    document.getElementById('edit-cat-name').value = cat.name || '';
    document.getElementById('edit-cat-icon').value = cat.icon || 'tag';
    document.getElementById('edit-cat-sort').value = cat.sort_order || 0;
    document.getElementById('edit-cat-desc').value = cat.description || '';
    document.getElementById('edit-cat-active').checked = (cat.is_active == 1 || cat.is_active === true || cat.is_active === 't');

    modal.classList.remove('hidden');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closeEditCategory() {
    document.getElementById('modal-edit-category').classList.add('hidden');
}
</script>
<?= $this->endSection() ?>
