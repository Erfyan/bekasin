<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="bg-slate-50 py-8 min-h-[80vh]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Profile Banner & Header -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden mb-8">
            <div class="h-32 sm:h-44 bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-800 relative"></div>
            
            <div class="px-6 sm:px-8 pb-8 pt-0 relative">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 -mt-16 sm:-mt-20 mb-6">
                    
                    <div class="flex items-end gap-4">
                        <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-3xl bg-white p-1.5 shadow-lg shrink-0">
                            <div class="w-full h-full rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-extrabold text-3xl overflow-hidden border border-emerald-200">
                                <?php if (!empty($user['avatar_url'])): ?>
                                    <img src="<?= esc($user['avatar_url']) ?>" alt="Avatar" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <?= strtoupper(substr($user['full_name'] ?? 'U', 0, 1)) ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center gap-2">
                                <h1 class="text-2xl font-extrabold text-slate-900"><?= esc($user['full_name']) ?></h1>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 font-medium">@<?= esc($user['username']) ?></p>
                            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                                <?= esc($user['city'] ?? 'Indonesia') ?>, <?= esc($user['province'] ?? '') ?>
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <?php if (session()->get('role') === 'admin'): ?>
                            <a href="<?= base_url('admin') ?>" class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-all flex items-center gap-1.5">
                                <i data-lucide="shield-check" class="w-4 h-4"></i> Admin Panel
                            </a>
                        <?php endif; ?>
                        <a href="<?= base_url('sellers/' . $user['username']) ?>" target="_blank" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition-all flex items-center gap-1.5">
                            <i data-lucide="external-link" class="w-4 h-4"></i> Lihat Toko Publik
                        </a>
                        <a href="<?= base_url('profile/settings') ?>" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-all flex items-center gap-1.5">
                            <i data-lucide="settings" class="w-4 h-4"></i> Edit Profil
                        </a>
                        <a href="<?= base_url('logout') ?>" class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 font-semibold text-xs rounded-xl transition-all flex items-center gap-1.5">
                            <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                        </a>
                    </div>
                </div>

                <!-- Stats Badges -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t border-slate-100">
                    <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100 text-center">
                        <span class="block text-2xl font-extrabold text-emerald-600"><?= $activeProductsCount ?></span>
                        <span class="text-xs text-slate-500 font-medium">Barang Dijual</span>
                    </div>
                    <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100 text-center">
                        <span class="block text-2xl font-extrabold text-slate-800"><?= $soldProductsCount ?></span>
                        <span class="text-xs text-slate-500 font-medium">Barang Terjual</span>
                    </div>
                    <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100 text-center">
                        <span class="block text-2xl font-extrabold text-amber-500 flex items-center justify-center gap-1">
                            ⭐ <?= $avgRating > 0 ? $avgRating : '-' ?>
                        </span>
                        <span class="text-xs text-slate-500 font-medium">Rating Penjual</span>
                    </div>
                    <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100 text-center">
                        <span class="block text-2xl font-extrabold text-indigo-600"><?= count($reviews) ?></span>
                        <span class="text-xs text-slate-500 font-medium">Ulasan Diterima</span>
                    </div>
                </div>

                <?php if (!empty($user['bio'])): ?>
                    <p class="text-sm text-slate-600 mt-4 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        "<?= esc($user['bio']) ?>"
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section: Barang Jualan Terakhir -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-slate-900">Barang Jualan Saya</h2>
                <a href="<?= base_url('sell/products') ?>" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">Lihat Semua →</a>
            </div>

            <?php if (empty($recentProducts)): ?>
                <div class="bg-white rounded-2xl p-8 text-center border border-slate-200/80">
                    <p class="text-sm text-slate-500 mb-3">Kamu belum memiliki barang jualan aktif.</p>
                    <a href="<?= base_url('sell/products/create') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-xs">
                        + Jual Barang Sekarang
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                    <?php foreach ($recentProducts as $p): ?>
                        <a href="<?= base_url('products/' . $p['slug']) ?>" class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-md transition-all group">
                            <div class="aspect-square bg-slate-100 relative">
                                <?php if (!empty($p['primary_image'])): ?>
                                    <img src="<?= esc($p['primary_image']) ?>" alt="Foto" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                <?php endif; ?>
                            </div>
                            <div class="p-2.5">
                                <h4 class="text-xs font-bold text-slate-800 truncate"><?= esc($p['title']) ?></h4>
                                <span class="text-xs font-extrabold text-emerald-600">Rp <?= number_format($p['price'], 0, ',', '.') ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Section: Ulasan Pembeli -->
        <div>
            <h2 class="text-lg font-bold text-slate-900 mb-4">Ulasan & Reputasi</h2>
            <?php if (empty($reviews)): ?>
                <div class="bg-white rounded-2xl p-8 text-center border border-slate-200/80">
                    <p class="text-sm text-slate-500">Belum ada ulasan yang diberikan pembeli untuk Anda.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach ($reviews as $rev): ?>
                        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-bold text-xs text-slate-800"><?= esc($rev['buyer_name']) ?></span>
                                <span class="text-amber-500 text-xs font-bold">⭐ <?= $rev['rating'] ?>/5</span>
                            </div>
                            <p class="text-xs text-slate-600 italic">"<?= esc($rev['comment']) ?>"</p>
                            <span class="text-[10px] text-slate-400 mt-2 block">Produk: <?= esc($rev['product_title']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
