<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="space-y-8">
    
    <!-- Welcome Header -->
    <div>
        <h1 class="text-2xl font-extrabold text-white tracking-tight">Ikhtisar Platform Marketplace</h1>
        <p class="text-xs text-slate-400 mt-1">Data dan performa real-time platform C2C Bekasin-Aja.</p>
    </div>

    <!-- Stat Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Users -->
        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 block mb-1">Total Pengguna</span>
                <span class="text-2xl font-extrabold text-white"><?= number_format($totalUsers) ?></span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center border border-blue-500/20">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Total Products -->
        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 block mb-1">Barang Aktif Dijual</span>
                <span class="text-2xl font-extrabold text-emerald-400"><?= number_format($activeProducts) ?></span>
                <span class="text-[10px] text-slate-500 block">dari <?= number_format($totalProducts) ?> total</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center border border-emerald-500/20">
                <i data-lucide="package" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 block mb-1">Total Transaksi</span>
                <span class="text-2xl font-extrabold text-teal-400"><?= number_format($totalTransactions) ?></span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-500/10 text-teal-400 flex items-center justify-center border border-teal-500/20">
                <i data-lucide="receipt" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Pending Reports -->
        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 block mb-1">Laporan Tertunda</span>
                <span class="text-2xl font-extrabold text-rose-400"><?= number_format($pendingReports) ?></span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-500/10 text-rose-400 flex items-center justify-center border border-rose-500/20">
                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- 2 Column Grids: Transaksi & Pengguna Baru -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Transaksi Terbaru -->
        <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-bold text-white flex items-center gap-2">
                    <i data-lucide="receipt" class="w-4 h-4 text-emerald-400"></i> Transaksi Terbaru
                </h2>
                <a href="<?= base_url('admin/transactions') ?>" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300">Semua →</a>
            </div>

            <div class="divide-y divide-slate-800/80">
                <?php if (empty($recentTransactions)): ?>
                    <p class="text-xs text-slate-500 py-4">Belum ada transaksi.</p>
                <?php else: ?>
                    <?php foreach ($recentTransactions as $rt): ?>
                        <div class="py-3 flex items-center justify-between gap-3 text-xs">
                            <div class="min-w-0">
                                <span class="font-mono text-[10px] text-slate-400">#<?= esc($rt['transaction_code']) ?></span>
                                <h4 class="font-bold text-white truncate"><?= esc($rt['product_title']) ?></h4>
                                <span class="text-slate-400 text-[11px]"><?= esc($rt['seller_name']) ?> ➔ <?= esc($rt['buyer_name']) ?></span>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-bold text-emerald-400 block">Rp <?= number_format($rt['agreed_price'], 0, ',', '.') ?></span>
                                <span class="text-[10px] uppercase font-bold text-slate-400"><?= esc($rt['status']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pengguna Terdaftar Baru -->
        <div class="p-6 rounded-3xl bg-slate-900 border border-slate-800">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-bold text-white flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-blue-400"></i> Pengguna Baru
                </h2>
                <a href="<?= base_url('admin/users') ?>" class="text-xs font-semibold text-blue-400 hover:text-blue-300">Semua →</a>
            </div>

            <div class="divide-y divide-slate-800/80">
                <?php if (empty($recentUsers)): ?>
                    <p class="text-xs text-slate-500 py-4">Belum ada pengguna.</p>
                <?php else: ?>
                    <?php foreach ($recentUsers as $ru): ?>
                        <div class="py-3 flex items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold text-xs shrink-0">
                                    <?= strtoupper(substr($ru['full_name'], 0, 1)) ?>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-white truncate"><?= esc($ru['full_name']) ?></h4>
                                    <span class="text-slate-400 text-[11px]">@<?= esc($ru['username']) ?> • <?= esc($ru['city'] ?? '-') ?></span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 text-slate-300 uppercase">
                                    <?= esc($ru['role']) ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>
<?= $this->endSection() ?>
