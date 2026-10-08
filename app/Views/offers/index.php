<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="bg-slate-50 py-8 min-h-[80vh]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Header -->
        <nav class="flex items-center gap-2 text-sm text-slate-500 mb-4 font-medium">
            <a href="<?= base_url('/') ?>" class="hover:text-emerald-600 transition-colors">Beranda</a>
            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
            <span class="text-slate-800 font-semibold">Tawar & Nego</span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span class="p-2 rounded-2xl bg-teal-50 text-teal-600 inline-flex items-center justify-center">
                        <i data-lucide="percent" class="w-6 h-6"></i>
                    </span>
                    Kelola Penawaran & Nego
                </h1>
                <p class="text-sm text-slate-500 mt-1">Pantau proses negosiasi harga barang bekas dari pembeli atau tawaran yang kamu ajukan.</p>
            </div>
        </div>

        <!-- Tabs Switcher: Tawaran Masuk vs Tawaran Saya -->
        <div class="flex border-b border-slate-200 mb-6 gap-6">
            <a href="<?= base_url('offers?tab=received') ?>" class="pb-3 text-sm font-bold border-b-2 flex items-center gap-2 transition-colors <?= $tab === 'received' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-800' ?>">
                <i data-lucide="inbox" class="w-4 h-4"></i>
                <span>Tawaran Masuk (Sebagai Penjual)</span>
            </a>
            <a href="<?= base_url('offers?tab=sent') ?>" class="pb-3 text-sm font-bold border-b-2 flex items-center gap-2 transition-colors <?= $tab === 'sent' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-800' ?>">
                <i data-lucide="send" class="w-4 h-4"></i>
                <span>Tawaran Saya (Sebagai Pembeli)</span>
            </a>
        </div>

        <!-- Offers List -->
        <?php if (empty($offers)): ?>
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 max-w-lg mx-auto my-6">
                <div class="w-16 h-16 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="tag" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Tidak Ada Penawaran</h3>
                <p class="text-sm text-slate-500 mt-1 mb-6">
                    <?= $tab === 'received' ? 'Belum ada calon pembeli yang mengajukan penawaran harga untuk barang jualanmu.' : 'Kamu belum pernah mengajukan penawaran harga ke penjual manapun.' ?>
                </p>
                <a href="<?= base_url('products') ?>" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 text-white font-semibold text-sm rounded-xl hover:bg-emerald-700 transition-all shadow-md shadow-emerald-600/20">
                    <i data-lucide="compass" class="w-4 h-4"></i> Jelajahi Barang Bekas
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($offers as $o): ?>
                    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                        
                        <!-- Product & User Info -->
                        <div class="flex items-start gap-4">
                            <div class="w-20 h-20 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                                <?php if (!empty($o['product_image'])): ?>
                                    <img src="<?= esc($o['product_image']) ?>" alt="Barang" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <i data-lucide="image" class="w-6 h-6"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div>
                                <a href="<?= base_url('products/' . $o['product_slug']) ?>" class="font-bold text-slate-900 hover:text-emerald-600 transition-colors line-clamp-1">
                                    <?= esc($o['product_title']) ?>
                                </a>

                                <div class="flex items-center gap-2 text-xs text-slate-500 mt-1">
                                    <span>Harga Iklan: <strong class="text-slate-700">Rp <?= number_format($o['original_price'], 0, ',', '.') ?></strong></span>
                                    <span>•</span>
                                    <span><?= $tab === 'received' ? 'Dari: <strong>' . esc($o['buyer_name']) . '</strong>' : 'Penjual: <strong>' . esc($o['seller_name']) . '</strong>' ?></span>
                                </div>

                                <?php if (!empty($o['notes'])): ?>
                                    <p class="text-xs text-slate-600 bg-slate-50 p-2 rounded-lg border border-slate-100 mt-2 italic">
                                        "<?= esc($o['notes']) ?>"
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Price Comparison & Actions -->
                        <div class="flex flex-col sm:items-end gap-3 w-full sm:w-auto shrink-0 pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                            <div>
                                <span class="text-[11px] font-semibold text-slate-400 block sm:text-right">Harga yang Ditawar:</span>
                                <span class="text-xl font-extrabold text-emerald-600">
                                    Rp <?= number_format($o['offered_price'], 0, ',', '.') ?>
                                </span>
                            </div>

                            <!-- Status Badge / Action Buttons -->
                            <div>
                                <?php if ($o['status'] === 'pending'): ?>
                                    <?php if ($tab === 'received'): ?>
                                        <div class="flex items-center gap-2">
                                            <!-- Terima Tawaran -->
                                            <form action="<?= base_url('offers/' . $o['id'] . '/accept') ?>" method="POST" class="inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center gap-1.5">
                                                    <i data-lucide="check" class="w-3.5 h-3.5"></i> Terima Nego
                                                </button>
                                            </form>

                                            <!-- Tolak -->
                                            <form action="<?= base_url('offers/' . $o['id'] . '/reject') ?>" method="POST" class="inline" onsubmit="return confirm('Tolak tawaran ini?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs rounded-xl border border-rose-200 transition-all flex items-center gap-1">
                                                    <i data-lucide="x" class="w-3.5 h-3.5"></i> Tolak
                                                </button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-full border border-amber-200">
                                            <i data-lucide="clock" class="w-3 h-3"></i> Menunggu Respon Penjual
                                        </span>
                                    <?php endif; ?>
                                <?php elseif ($o['status'] === 'accepted'): ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">
                                        <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i> Disetujui
                                    </span>
                                <?php elseif ($o['status'] === 'rejected'): ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-rose-50 text-rose-700 text-xs font-bold rounded-full border border-rose-200">
                                        <i data-lucide="x-circle" class="w-3.5 h-3.5 text-rose-600"></i> Ditolak
                                    </span>
                                <?php elseif ($o['status'] === 'countered'): ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full border border-indigo-200">
                                        <i data-lucide="repeat" class="w-3.5 h-3.5 text-indigo-600"></i> Ditawar Balik: Rp <?= number_format($o['counter_price'] ?? 0, 0, ',', '.') ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>
<?= $this->endSection() ?>
