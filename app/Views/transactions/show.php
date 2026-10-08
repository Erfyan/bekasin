<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="bg-slate-50 py-8 min-h-[80vh]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Header -->
        <nav class="flex items-center gap-2 text-sm text-slate-500 mb-4 font-medium">
            <a href="<?= base_url('/') ?>" class="hover:text-emerald-600 transition-colors">Beranda</a>
            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
            <a href="<?= base_url('transactions') ?>" class="hover:text-emerald-600 transition-colors">Transaksi</a>
            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
            <span class="text-slate-800 font-semibold">#<?= esc($transaction['transaction_code']) ?></span>
        </nav>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden mb-6">
            
            <!-- Header Status Bar -->
            <div class="p-6 bg-gradient-to-r <?= $transaction['status'] === 'completed' ? 'from-emerald-600 to-teal-700' : ($transaction['status'] === 'cancelled' ? 'from-rose-600 to-rose-700' : 'from-slate-900 to-slate-800') ?> text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-emerald-200 block mb-1">Kode Transaksi</span>
                    <h1 class="text-xl sm:text-2xl font-extrabold font-mono"><?= esc($transaction['transaction_code']) ?></h1>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-white/20 backdrop-blur">
                        Status: <?= strtoupper($transaction['status']) ?>
                    </span>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-8">
                
                <!-- Product Card Summary -->
                <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                    <div class="w-20 h-20 rounded-xl bg-slate-200 overflow-hidden shrink-0 border border-slate-300">
                        <?php if (!empty($transaction['product_image'])): ?>
                            <img src="<?= esc($transaction['product_image']) ?>" alt="Foto Barang" class="w-full h-full object-cover">
                        <?php endif; ?>
                    </div>
                    <div class="min-w-0 flex-1">
                        <a href="<?= base_url('products/' . $transaction['product_slug']) ?>" target="_blank" class="font-bold text-slate-900 hover:text-emerald-600 transition-colors line-clamp-1">
                            <?= esc($transaction['product_title']) ?>
                        </a>
                        <div class="text-xs text-slate-500 mt-1">
                            <span>Harga Kesepakatan: <strong class="text-emerald-600 text-sm">Rp <?= number_format($transaction['agreed_price'], 0, ',', '.') ?></strong></span>
                        </div>
                    </div>
                </div>

                <!-- Step Tracker -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-2xl border <?= $transaction['status'] !== 'cancelled' ? 'border-emerald-500 bg-emerald-50/50' : 'border-slate-200 bg-slate-50' ?>">
                        <span class="text-xs font-bold text-emerald-700 flex items-center gap-1.5 mb-1">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> 1. Penawaran Disetujui
                        </span>
                        <p class="text-xs text-slate-500">Kesepakatan harga telah diterima.</p>
                    </div>

                    <div class="p-4 rounded-2xl border <?= $transaction['payment_status'] === 'paid' ? 'border-emerald-500 bg-emerald-50/50' : 'border-slate-200 bg-slate-50' ?>">
                        <span class="text-xs font-bold <?= $transaction['payment_status'] === 'paid' ? 'text-emerald-700' : 'text-slate-700' ?> flex items-center gap-1.5 mb-1">
                            <i data-lucide="credit-card" class="w-4 h-4"></i> 2. Pembayaran / COD
                        </span>
                        <p class="text-xs text-slate-500">Status: <strong><?= strtoupper($transaction['payment_status']) ?></strong></p>
                    </div>

                    <div class="p-4 rounded-2xl border <?= $transaction['status'] === 'completed' ? 'border-emerald-500 bg-emerald-50/50' : 'border-slate-200 bg-slate-50' ?>">
                        <span class="text-xs font-bold <?= $transaction['status'] === 'completed' ? 'text-emerald-700' : 'text-slate-700' ?> flex items-center gap-1.5 mb-1">
                            <i data-lucide="package-check" class="w-4 h-4"></i> 3. Selesai
                        </span>
                        <p class="text-xs text-slate-500">Barang diterima & dana diserahkan.</p>
                    </div>
                </div>

                <!-- Detail Pihak Terlibat -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Informasi Penjual</h4>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1 text-sm">
                            <strong class="text-slate-900 block font-bold"><?= esc($transaction['seller_name']) ?></strong>
                            <p class="text-xs text-slate-500">No HP: <?= esc($transaction['seller_phone'] ?? '-') ?></p>
                            <p class="text-xs text-slate-500">Lokasi: <?= esc($transaction['seller_city'] ?? '-') ?></p>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Informasi Pembeli</h4>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1 text-sm">
                            <strong class="text-slate-900 block font-bold"><?= esc($transaction['buyer_name']) ?></strong>
                            <p class="text-xs text-slate-500">No HP: <?= esc($transaction['buyer_phone'] ?? '-') ?></p>
                            <p class="text-xs text-slate-500">Lokasi: <?= esc($transaction['buyer_city'] ?? '-') ?></p>
                        </div>
                    </div>
                </div>

                <!-- Actions: Complete or Cancel -->
                <?php if ($transaction['status'] === 'confirmed'): ?>
                    <div class="p-6 rounded-2xl bg-amber-50 border border-amber-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <h4 class="font-bold text-amber-900 text-sm">Konfirmasi Penyelesaian</h4>
                            <p class="text-xs text-amber-800 mt-0.5">Klik tombol di samping setelah barang diterima dan pembayaran diselesaikan secara aman.</p>
                        </div>

                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <form action="<?= base_url('transactions/' . $transaction['id'] . '/complete') ?>" method="POST" class="w-full sm:w-auto">
                                <?= csrf_field() ?>
                                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center justify-center gap-1.5">
                                    <i data-lucide="check" class="w-4 h-4"></i> Tandai Transaksi Selesai
                                </button>
                            </form>

                            <form action="<?= base_url('transactions/' . $transaction['id'] . '/cancel') ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini?');" class="w-full sm:w-auto">
                                <?= csrf_field() ?>
                                <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-rose-100 hover:bg-rose-200 text-rose-700 font-bold text-xs rounded-xl transition-all">
                                    Batalkan
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Review Section (When Completed & isBuyer) -->
                <?php if ($transaction['status'] === 'completed'): ?>
                    <div class="pt-6 border-t border-slate-100">
                        <h3 class="text-base font-bold text-slate-900 mb-3 flex items-center gap-2">
                            <i data-lucide="star" class="w-5 h-5 text-amber-500 fill-amber-500"></i> Ulasan Transaksi
                        </h3>

                        <?php if ($review): ?>
                            <div class="p-4 bg-emerald-50/60 rounded-2xl border border-emerald-200">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-amber-500 font-bold text-sm">⭐ <?= $review['rating'] ?>/5 Bintang</span>
                                    <span class="text-xs text-slate-400">• Diberikan oleh pembeli</span>
                                </div>
                                <p class="text-xs text-slate-700 italic">"<?= esc($review['comment']) ?>"</p>
                            </div>
                        <?php elseif ($isBuyer): ?>
                            <!-- Form Beri Ulasan -->
                            <form action="<?= base_url('transactions/' . $transaction['id'] . '/review') ?>" method="POST" class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-4">
                                <?= csrf_field() ?>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Beri Rating untuk Penjual (1-5 Bintang)</label>
                                    <div class="flex items-center gap-3">
                                        <?php for ($i = 5; $i >= 1; $i--): ?>
                                            <label class="flex items-center gap-1 text-xs font-bold text-slate-700 cursor-pointer">
                                                <input type="radio" name="rating" value="<?= $i ?>" <?= $i === 5 ? 'checked' : '' ?> class="text-amber-500 focus:ring-amber-500">
                                                <span><?= $i ?> ⭐</span>
                                            </label>
                                        <?php endfor; ?>
                                    </div>
                                </div>

                                <div>
                                    <label for="comment" class="block text-xs font-bold text-slate-700 mb-1">Komentar & Pengalaman Transaksi</label>
                                    <textarea id="comment" name="comment" rows="2" required placeholder="Contoh: Penjual sangat ramah, barang mulus sesuai deskripsi, mantap!" class="w-full px-4 py-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                                </div>

                                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all">
                                    Kirim Ulasan
                                </button>
                            </form>
                        <?php else: ?>
                            <p class="text-xs text-slate-400">Menunggu pembeli memberikan ulasan.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
