<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Semua Transaksi Marketplace</h1>
            <p class="text-xs text-slate-400 mt-1">Audit seluruh aktivitas deal dan pergerakan transaksi C2C.</p>
        </div>

        <form action="<?= base_url('admin/transactions') ?>" method="GET">
            <input 
                type="text" 
                name="q" 
                value="<?= esc($search ?? '') ?>" 
                placeholder="Cari kode transaksi..." 
                class="px-4 py-2 text-xs bg-slate-900 border border-slate-800 rounded-xl text-white placeholder:text-slate-500 focus:outline-none"
            >
        </form>
    </div>

    <!-- Table Transactions -->
    <div class="bg-slate-900 rounded-3xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/60 text-slate-400 border-b border-slate-800 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="p-4">Kode Transaksi</th>
                        <th class="p-4">Barang</th>
                        <th class="p-4">Penjual & Pembeli</th>
                        <th class="p-4">Harga Sepakat</th>
                        <th class="p-4">Metode & Bayar</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-slate-300">
                    <?php if (empty($transactions)): ?>
                        <tr><td colspan="6" class="p-6 text-center text-slate-500">Tidak ada transaksi ditemukan.</td></tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $t): ?>
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="p-4">
                                    <span class="font-mono text-emerald-400 font-bold block">#<?= esc($t['transaction_code']) ?></span>
                                    <span class="text-slate-500 text-[10px]"><?= date('d M Y H:i', strtotime($t['created_at'])) ?></span>
                                </td>
                                <td class="p-4 font-semibold text-white"><?= esc($t['product_title']) ?></td>
                                <td class="p-4">
                                    <span>Penjual: <strong><?= esc($t['seller_name']) ?></strong></span>
                                    <span class="block text-slate-400 text-[11px]">Pembeli: <strong><?= esc($t['buyer_name']) ?></strong></span>
                                </td>
                                <td class="p-4 font-bold text-white">Rp <?= number_format($t['agreed_price'], 0, ',', '.') ?></td>
                                <td class="p-4">
                                    <span class="capitalize block"><?= esc($t['delivery_method']) ?></span>
                                    <span class="text-[10px] text-slate-400 font-mono uppercase">Bayar: <?= esc($t['payment_status']) ?></span>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $t['status'] === 'completed' ? 'bg-emerald-500/20 text-emerald-400' : ($t['status'] === 'cancelled' ? 'bg-rose-500/20 text-rose-400' : 'bg-amber-500/20 text-amber-400') ?>">
                                        <?= esc($t['status']) ?>
                                    </span>
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
