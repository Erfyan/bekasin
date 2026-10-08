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
        <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800">
            <h2 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-4 h-4 text-emerald-400"></i> Tambah Kategori Baru
            </h2>

            <form action="<?= base_url('admin/categories/store') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Kategori <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Elektronik & Gadget" class="w-full px-3 py-2.5 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Icon Lucide</label>
                    <input type="text" name="icon" value="tag" placeholder="smartphone, laptop, shirt, tag..." class="w-full px-3 py-2.5 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Urutan Sort</label>
                    <input type="number" name="sort_order" value="0" class="w-full px-3 py-2.5 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2.5 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-emerald-500"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all">
                    Simpan Kategori
                </button>
            </form>
        </div>

        <!-- List Kategori -->
        <div class="lg:col-span-2 bg-slate-900 rounded-3xl border border-slate-800 overflow-hidden">
            <div class="p-4 border-b border-slate-800">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Daftar Kategori Aktif</h3>
            </div>
            
            <div class="divide-y divide-slate-800">
                <?php foreach ($categories as $cat): ?>
                    <div class="p-4 flex items-center justify-between gap-4 text-xs">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-sm shrink-0">
                                <i data-lucide="<?= esc($cat['icon'] ?? 'tag') ?>" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <strong class="text-white block text-sm"><?= esc($cat['name']) ?></strong>
                                <span class="text-slate-500 text-[11px]">Slug: <?= esc($cat['slug']) ?> • Urutan: <?= $cat['sort_order'] ?></span>
                            </div>
                        </div>

                        <div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?= $cat['is_active'] ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' ?>">
                                <?= $cat['is_active'] ? 'Aktif' : 'Non-aktif' ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
