<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="bg-slate-50 py-8 min-h-[80vh]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs & Header -->
        <nav class="flex items-center gap-2 text-sm text-slate-500 mb-4 font-medium">
            <a href="<?= base_url('/') ?>" class="hover:text-emerald-600 transition-colors">Beranda</a>
            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
            <span class="text-slate-800 font-semibold">Transaksi Saya</span>
        </nav>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-6 flex items-center gap-2.5">
            <span class="p-2 rounded-2xl bg-emerald-50 text-emerald-600 inline-flex items-center justify-center">
                <i data-lucide="receipt" class="w-6 h-6"></i>
            </span>
            Riwayat Kesepakatan & Transaksi
        </h1>

        <!-- Transaction List -->
        <?php if (empty($transactions)): ?>
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 max-w-lg mx-auto my-6">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="shopping-bag" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Belum Ada Transaksi</h3>
                <p class="text-sm text-slate-500 mt-1 mb-6">
                    Transaksi akan tercatat otomatis saat penawaran harga disetujui antara Anda dan mitra jual/beli.
                </p>
                <a href="<?= base_url('products') ?>" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 text-white font-semibold text-sm rounded-xl hover:bg-emerald-700 transition-all shadow-md shadow-emerald-600/20">
                    <i data-lucide="compass" class="w-4 h-4"></i> Jelajahi Barang Bekas
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php 
                $userId = (int) session()->get('user_id');
                foreach ($transactions as $t): 
                    $isBuyer = ($t['buyer_id'] == $userId);
                ?>
                    <a href="<?= base_url('transactions/' . $t['id']) ?>" class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 group block">
                        
                        <!-- Thumbnail & Details -->
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                                <?php if (!empty($t['product_image'])): ?>
                                    <img src="<?= esc($t['product_image']) ?>" alt="Foto" class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>

                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-mono font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                                        #<?= esc($t['transaction_code']) ?>
                                    </span>
                                    <span class="text-xs text-slate-400">
                                        <?= date('d M Y, H:i', strtotime($t['created_at'])) ?>
                                    </span>
                                </div>

                                <h3 class="font-bold text-slate-900 group-hover:text-emerald-600 transition-colors line-clamp-1">
                                    <?= esc($t['product_title']) ?>
                                </h3>

                                <div class="text-xs text-slate-500 mt-0.5">
                                    <span><?= $isBuyer ? 'Penjual: <strong>' . esc($t['seller_name']) . '</strong>' : 'Pembeli: <strong>' . esc($t['buyer_name']) . '</strong>' ?></span>
                                    <span>•</span>
                                    <span class="capitalize">Metode: <?= esc($t['delivery_method']) ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Price & Status Badge -->
                        <div class="flex items-center sm:flex-col sm:items-end justify-between w-full sm:w-auto gap-2">
                            <span class="text-lg font-extrabold text-emerald-600">
                                Rp <?= number_format($t['agreed_price'], 0, ',', '.') ?>
                            </span>

                            <div>
                                <?php if ($t['status'] === 'completed'): ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">
                                        <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i> Selesai
                                    </span>
                                <?php elseif ($t['status'] === 'cancelled'): ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-rose-50 text-rose-700 text-xs font-bold rounded-full border border-rose-200">
                                        <i data-lucide="x-circle" class="w-3.5 h-3.5 text-rose-600"></i> Dibatalkan
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-full border border-amber-200">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600"></i> Berlangsung
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>
<?= $this->endSection() ?>
