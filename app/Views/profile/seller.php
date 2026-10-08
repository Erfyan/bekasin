<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="bg-slate-50 py-8 min-h-[80vh]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Seller Storefront Hero -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden mb-8">
            <div class="h-32 sm:h-40 bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-800"></div>
            
            <div class="px-6 sm:px-8 pb-8 pt-0">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 -mt-16 sm:-mt-18 mb-6">
                    <div class="flex items-end gap-4">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl bg-white p-1.5 shadow-lg shrink-0">
                            <div class="w-full h-full rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-extrabold text-2xl sm:text-3xl overflow-hidden border border-emerald-200">
                                <?php if (!empty($seller['avatar_url'])): ?>
                                    <img src="<?= esc($seller['avatar_url']) ?>" alt="Avatar" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <?= strtoupper(substr($seller['full_name'] ?? 'U', 0, 1)) ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center gap-2">
                                <h1 class="text-2xl font-extrabold text-slate-900"><?= esc($seller['full_name']) ?></h1>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Penjual Terverifikasi
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 font-medium">@<?= esc($seller['username']) ?> • Bergabung sejak <?= date('M Y', strtotime($seller['created_at'])) ?></p>
                            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                                <?= esc($seller['city'] ?? 'Indonesia') ?>, <?= esc($seller['province'] ?? '') ?>
                            </p>
                        </div>
                    </div>

                    <!-- Contact or WhatsApp CTA -->
                    <?php if (!empty($seller['phone'])): ?>
                        <?php 
                        $waPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $seller['phone']));
                        ?>
                        <a href="https://wa.me/<?= $waPhone ?>" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-600/20 transition-all">
                            <i data-lucide="phone" class="w-4 h-4"></i> Hubungi WhatsApp
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Seller Stats Badges -->
                <div class="grid grid-cols-3 gap-3 pt-4 border-t border-slate-100 max-w-md">
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-center">
                        <span class="block text-xl font-extrabold text-emerald-600"><?= count($products) ?></span>
                        <span class="text-[11px] text-slate-500 font-medium">Barang Aktif</span>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-center">
                        <span class="block text-xl font-extrabold text-slate-800"><?= $soldCount ?></span>
                        <span class="text-[11px] text-slate-500 font-medium">Terjual</span>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-center">
                        <span class="block text-xl font-extrabold text-amber-500">⭐ <?= $avgRating > 0 ? $avgRating : '5.0' ?></span>
                        <span class="text-[11px] text-slate-500 font-medium">Rating Toko</span>
                    </div>
                </div>

                <?php if (!empty($seller['bio'])): ?>
                    <p class="text-sm text-slate-600 mt-4 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        "<?= esc($seller['bio']) ?>"
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section: Semua Barang Dijual oleh Seller -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-slate-900">Etalase Barang Bekas (<?= count($products) ?>)</h2>
            </div>

            <?php if (empty($products)): ?>
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 max-w-md mx-auto">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="package-open" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Tidak Ada Barang Aktif</h3>
                    <p class="text-xs text-slate-500 mt-1">Penjual saat ini belum memiliki barang bekas yang aktif dijual.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                    <?php foreach ($products as $p): ?>
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all overflow-hidden flex flex-col justify-between group">
                            <a href="<?= base_url('products/' . $p['slug']) ?>" class="block">
                                <div class="relative aspect-square w-full bg-slate-100 overflow-hidden">
                                    <?php if (!empty($p['primary_image'])): ?>
                                        <img src="<?= esc($p['primary_image']) ?>" alt="<?= esc($p['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <?php endif; ?>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-bold text-sm text-slate-900 line-clamp-2 mb-1.5 group-hover:text-emerald-600 transition-colors">
                                        <?= esc($p['title']) ?>
                                    </h3>
                                    <div class="text-base font-extrabold text-emerald-600">
                                        <?= ((float)$p['price'] === 0.0) ? 'GRATIS' : 'Rp ' . number_format($p['price'], 0, ',', '.') ?>
                                    </div>
                                    <span class="text-xs text-slate-400 mt-1 block flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-3 h-3"></i> <?= esc($p['city']) ?>
                                    </span>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
