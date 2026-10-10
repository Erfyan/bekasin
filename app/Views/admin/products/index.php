<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Moderasi Iklan Barang</h1>
            <p class="text-xs text-slate-400 mt-1">Review, moderasi, atau hapus listing barang yang melanggar aturan.</p>
        </div>

        <form action="<?= base_url('admin/products') ?>" method="GET" class="flex flex-wrap items-center gap-2">
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 text-xs bg-slate-900 border border-slate-800 rounded-xl text-slate-300 focus:outline-none">
                <option value="all" <?= ($status ?? 'all') === 'all' ? 'selected' : '' ?>>Semua Status</option>
                <option value="active" <?= ($status ?? '') === 'active' ? 'selected' : '' ?>>Aktif</option>
                <option value="sold" <?= ($status ?? '') === 'sold' ? 'selected' : '' ?>>Terjual</option>
                <option value="archived" <?= ($status ?? '') === 'archived' ? 'selected' : '' ?>>Diarsipkan</option>
                <option value="draft" <?= ($status ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
            </select>
            <div class="relative">
                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-500 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input 
                    type="text" 
                    name="q" 
                    value="<?= esc($search ?? '') ?>" 
                    placeholder="Cari judul, penjual..." 
                    class="pl-8 pr-3 py-2 text-xs bg-slate-900 border border-slate-800 rounded-xl text-white placeholder:text-slate-500 focus:outline-none focus:border-emerald-500"
                >
            </div>
            <button type="submit" class="px-3 py-2 bg-slate-800 text-xs text-slate-300 font-semibold rounded-xl hover:bg-slate-750">
                Cari
            </button>
        </form>
    </div>

    <!-- Table Products -->
    <div class="bg-slate-900 rounded-3xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/60 text-slate-400 border-b border-slate-800 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="p-4">Barang</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Penjual</th>
                        <th class="p-4">Harga</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Moderasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-slate-300">
                    <?php if (empty($products)): ?>
                        <tr><td colspan="6" class="p-6 text-center text-slate-500">Tidak ada barang ditemukan.</td></tr>
                    <?php else: ?>
                        <?php foreach ($products as $p): ?>
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <?php if (!empty($p['primary_image'])): ?>
                                            <img src="<?= esc($p['primary_image']) ?>" alt="" class="w-10 h-10 rounded-xl object-cover bg-slate-800 shrink-0 border border-slate-800">
                                        <?php else: ?>
                                            <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-slate-500 shrink-0">
                                                <i data-lucide="package" class="w-5 h-5"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="min-w-0">
                                            <a href="<?= base_url('products/' . $p['slug']) ?>" target="_blank" class="font-bold text-white hover:text-emerald-400 transition-colors block line-clamp-1" title="<?= esc($p['title']) ?>">
                                                <?= esc($p['title']) ?> ↗
                                            </a>
                                            <span class="text-[11px] text-slate-500">Dilihat <?= number_format($p['views_count'] ?? 0) ?>x • <?= date('d M Y', strtotime($p['created_at'])) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-lg bg-slate-800 text-slate-300 text-[11px]">
                                        <?= esc($p['category_name']) ?>
                                    </span>
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <span class="font-medium text-white block"><?= esc($p['seller_name']) ?></span>
                                    <span class="text-[10px] text-slate-500">@<?= esc($p['seller_username'] ?? '') ?></span>
                                </td>
                                <td class="p-4 whitespace-nowrap font-bold text-emerald-400">
                                    Rp <?= number_format($p['price'], 0, ',', '.') ?>
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <?php if ($p['status'] === 'active'): ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                            Aktif
                                        </span>
                                    <?php elseif ($p['status'] === 'sold'): ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                            Terjual
                                        </span>
                                    <?php elseif ($p['status'] === 'archived'): ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                            Diarsipkan
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-800 text-slate-400">
                                            <?= esc($p['status']) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <?php if ($p['status'] === 'active'): ?>
                                            <form action="<?= base_url('admin/products/' . $p['id'] . '/moderate') ?>" method="POST" class="inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="archived">
                                                <button type="submit" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-amber-500/20 text-amber-400 hover:bg-amber-500/30 transition-colors" title="Arsipkan iklan">
                                                    Arsipkan
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form action="<?= base_url('admin/products/' . $p['id'] . '/moderate') ?>" method="POST" class="inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="active">
                                                <button type="submit" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-emerald-500/20 text-emerald-400 hover:bg-emerald-500/30 transition-colors" title="Aktifkan iklan">
                                                    Aktifkan
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <form action="<?= base_url('admin/products/' . $p['id'] . '/delete') ?>" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus iklan <?= esc(addslashes($p['title'])) ?>?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-rose-500/20 text-rose-400 hover:bg-rose-500/30 transition-colors flex items-center gap-1" title="Hapus permanen">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($pager): ?>
            <div class="p-4 border-t border-slate-800">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
