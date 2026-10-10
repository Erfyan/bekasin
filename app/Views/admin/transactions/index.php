<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Semua Transaksi Marketplace</h1>
            <p class="text-xs text-slate-400 mt-1">Audit seluruh aktivitas deal dan pergerakan transaksi C2C.</p>
        </div>

        <form action="<?= base_url('admin/transactions') ?>" method="GET" class="flex flex-wrap items-center gap-2">
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 text-xs bg-slate-900 border border-slate-800 rounded-xl text-slate-300 focus:outline-none">
                <option value="all" <?= ($status ?? 'all') === 'all' ? 'selected' : '' ?>>Semua Status</option>
                <option value="deal_agreed" <?= ($status ?? '') === 'deal_agreed' ? 'selected' : '' ?>>Deal Agreed</option>
                <option value="waiting_payment" <?= ($status ?? '') === 'waiting_payment' ? 'selected' : '' ?>>Menunggu Bayar</option>
                <option value="paid_confirmed" <?= ($status ?? '') === 'paid_confirmed' ? 'selected' : '' ?>>Sudah Bayar</option>
                <option value="processing" <?= ($status ?? '') === 'processing' ? 'selected' : '' ?>>Diproses</option>
                <option value="completed" <?= ($status ?? '') === 'completed' ? 'selected' : '' ?>>Selesai</option>
                <option value="cancelled" <?= ($status ?? '') === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
            </select>
            <div class="relative">
                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-500 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input 
                    type="text" 
                    name="q" 
                    value="<?= esc($search ?? '') ?>" 
                    placeholder="Kode, barang, pembeli..." 
                    class="pl-8 pr-3 py-2 text-xs bg-slate-900 border border-slate-800 rounded-xl text-white placeholder:text-slate-500 focus:outline-none focus:border-emerald-500"
                >
            </div>
            <button type="submit" class="px-3 py-2 bg-slate-800 text-xs text-slate-300 font-semibold rounded-xl hover:bg-slate-750">
                Cari
            </button>
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
                        <th class="p-4 text-right">Kelola Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-slate-300">
                    <?php if (empty($transactions)): ?>
                        <tr><td colspan="7" class="p-6 text-center text-slate-500">Tidak ada transaksi ditemukan.</td></tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $t): ?>
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="p-4 whitespace-nowrap">
                                    <span class="font-mono text-emerald-400 font-bold block">#<?= esc($t['transaction_code']) ?></span>
                                    <span class="text-slate-500 text-[10px]"><?= date('d M Y H:i', strtotime($t['created_at'])) ?></span>
                                </td>
                                <td class="p-4 font-semibold text-white max-w-xs truncate"><?= esc($t['product_title']) ?></td>
                                <td class="p-4 whitespace-nowrap">
                                    <span>Penjual: <strong><?= esc($t['seller_name']) ?></strong></span>
                                    <span class="block text-slate-400 text-[11px]">Pembeli: <strong><?= esc($t['buyer_name']) ?></strong></span>
                                </td>
                                <td class="p-4 whitespace-nowrap font-bold text-white">Rp <?= number_format($t['agreed_price'], 0, ',', '.') ?></td>
                                <td class="p-4 whitespace-nowrap">
                                    <span class="capitalize block"><?= esc($t['delivery_method']) ?></span>
                                    <span class="text-[10px] text-slate-400 font-mono uppercase">Bayar: <?= esc($t['payment_status']) ?></span>
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <?php if ($t['status'] === 'completed'): ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                            Selesai
                                        </span>
                                    <?php elseif ($t['status'] === 'cancelled'): ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-500/20 text-rose-400 border border-rose-500/30">
                                            Dibatalkan
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                            <?= esc(str_replace('_', ' ', $t['status'])) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-right whitespace-nowrap">
                                    <form action="<?= base_url('admin/transactions/' . $t['id'] . '/status') ?>" method="POST" class="inline-flex items-center gap-1">
                                        <?= csrf_field() ?>
                                        <select name="status" class="px-2 py-1 text-[11px] bg-slate-950 border border-slate-800 rounded-lg text-slate-300 focus:outline-none">
                                            <option value="deal_agreed" <?= $t['status'] === 'deal_agreed' ? 'selected' : '' ?>>Deal Agreed</option>
                                            <option value="waiting_payment" <?= $t['status'] === 'waiting_payment' ? 'selected' : '' ?>>Waiting Payment</option>
                                            <option value="paid_confirmed" <?= $t['status'] === 'paid_confirmed' ? 'selected' : '' ?>>Paid Confirmed</option>
                                            <option value="processing" <?= $t['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                            <option value="completed" <?= $t['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                            <option value="cancelled" <?= $t['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                        <button type="submit" class="px-2 py-1 text-[11px] font-bold rounded-lg bg-emerald-600/20 text-emerald-400 hover:bg-emerald-600/30 transition-colors" title="Simpan status">
                                            Ubah
                                        </button>
                                    </form>
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
