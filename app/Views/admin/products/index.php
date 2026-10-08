<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Moderasi Iklan Barang</h1>
            <p class="text-xs text-slate-400 mt-1">Review, moderasi, atau hapus listing barang yang melanggar aturan.</p>
        </div>

        <form action="<?= base_url('admin/products') ?>" method="GET" class="flex items-center gap-2">
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 text-xs bg-slate-900 border border-slate-800 rounded-xl text-slate-300 focus:outline-none">
                <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>Semua Status</option>
                <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Aktif</option>
                <option value="sold" <?= $status === 'sold' ? 'selected' : '' ?>>Terjual</option>
                <option value="archived" <?= $status === 'archived' ? 'selected' : '' ?>>Diarsipkan</option>
            </select>
            <input 
                type="text" 
                name="q" 
                value="<?= esc($search ?? '') ?>" 
                placeholder="Cari judul..." 
                class="px-4 py-2 text-xs bg-slate-900 border border-slate-800 rounded-xl text-white placeholder:text-slate-500 focus:outline-none"
            >
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
                                    <a href="<?= base_url('products/' . $p['slug']) ?>" target="_blank" class="font-bold text-white hover:text-emerald-400 transition-colors block line-clamp-1">
                                        <?= esc($p['title']) ?> ↗
                                    </a>
                                    <span class="text-[11px] text-slate-400">Dilihat <?= number_format($p['views_count']) ?>x</span>
                                </td>
                                <td class="p-4"><?= esc($p['category_name']) ?></td>
                                <td class="p-4"><?= esc($p['seller_name']) ?></td>
                                <td class="p-4 font-bold text-emerald-400">Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $p['status'] === 'active' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-slate-800 text-slate-400' ?>">
                                        <?= esc($p['status']) ?>
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <form action="<?= base_url('admin/products/' . $p['id'] . '/moderate') ?>" method="POST" class="inline">
                                            <?= csrf_field() ?>
                                            <?php if ($p['status'] === 'active'): ?>
                                                <input type="hidden" name="action" value="archived">
                                                <button type="submit" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-amber-500/20 text-amber-400 hover:bg-amber-500/30">
                                                    Arsipkan
                                                </button>
                                            <?php else: ?>
                                                <input type="hidden" name="action" value="active">
                                                <button type="submit" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-emerald-500/20 text-emerald-400 hover:bg-emerald-500/30">
                                                    Aktifkan
                                                </button>
                                            <?php endif; ?>
                                        </form>

                                        <form action="<?= base_url('admin/products/' . $p['id'] . '/moderate') ?>" method="POST" class="inline" onsubmit="return confirm('Hapus permanen barang ini?');">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="deleted">
                                            <button type="submit" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-rose-500/20 text-rose-400 hover:bg-rose-500/30">
                                                Hapus
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
