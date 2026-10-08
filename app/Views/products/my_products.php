<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="bg-slate-50 py-8 min-h-[80vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header & Top Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <nav class="flex items-center gap-2 text-sm text-slate-500 mb-2 font-medium">
                    <a href="<?= base_url('/') ?>" class="hover:text-emerald-600 transition-colors">Beranda</a>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
                    <span class="text-slate-800 font-semibold">Barang Jualan Saya</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Barang Jualan</h1>
                <p class="text-sm text-slate-500 mt-1">Pantau status iklan, respon tawaran harga masuk, atau perbarui data barang bekasmu.</p>
            </div>

            <a href="<?= base_url('sell/products/create') ?>" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-2xl shadow-lg shadow-emerald-600/25 hover:shadow-xl hover:shadow-emerald-600/30 active:scale-98 transition-all">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>+ Jual Barang Baru</span>
            </a>
        </div>

        <!-- Filter Status Tab Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-3 mb-6 scrollbar-none">
            <?php 
            $tabs = [
                'all'      => 'Semua Barang',
                'active'   => 'Aktif Dijual',
                'sold'     => 'Terjual (Sold)',
                'archived' => 'Diarsipkan',
                'draft'    => 'Draf',
            ];
            foreach ($tabs as $key => $label):
                $isActive = ($currentStatus === $key);
            ?>
                <a 
                    href="<?= base_url('sell/products' . ($key !== 'all' ? '?status=' . $key : '')) ?>" 
                    class="px-4 py-2 text-sm font-semibold rounded-xl whitespace-nowrap transition-all <?= $isActive ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' ?>"
                >
                    <?= $label ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Product Listings -->
        <?php if (empty($products)): ?>
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 max-w-lg mx-auto my-6">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="package-open" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Belum Ada Barang</h3>
                <p class="text-sm text-slate-500 mt-1 mb-6">
                    <?= $currentStatus !== 'all' ? 'Tidak ada barang dengan status ' . esc($currentStatus) : 'Kamu belum memasang iklan barang bekas apapun.' ?>
                </p>
                <a href="<?= base_url('sell/products/create') ?>" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 text-white font-semibold text-sm rounded-xl hover:bg-emerald-700 transition-all">
                    <i data-lucide="plus" class="w-4 h-4"></i> Mulai Pasang Iklan Sekarang
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php foreach ($products as $p): ?>
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all overflow-hidden flex flex-col justify-between group">
                        <div>
                            <!-- Thumbnail & Status Badge -->
                            <div class="relative aspect-video w-full bg-slate-100 overflow-hidden">
                                <?php if (!empty($p['primary_image'])): ?>
                                    <img src="<?= esc($p['primary_image']) ?>" alt="<?= esc($p['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <i data-lucide="image" class="w-12 h-12"></i>
                                    </div>
                                <?php endif; ?>

                                <!-- Status Badge -->
                                <div class="absolute top-3 left-3">
                                    <?php if ($p['status'] === 'active'): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500/90 backdrop-blur text-white shadow-xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> Aktif
                                        </span>
                                    <?php elseif ($p['status'] === 'sold'): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-900/90 backdrop-blur text-white shadow-xs">
                                            ✓ Terjual
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/90 backdrop-blur text-white shadow-xs">
                                            <?= ucfirst($p['status']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Pending Offers Alert -->
                                <?php if (!empty($p['pending_offers_count']) && (int)$p['pending_offers_count'] > 0): ?>
                                    <a href="<?= base_url('offers') ?>" class="absolute top-3 right-3 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-600 text-white shadow-md animate-bounce">
                                        <i data-lucide="tag" class="w-3 h-3"></i> <?= $p['pending_offers_count'] ?> Nego Masuk
                                    </a>
                                <?php endif; ?>
                            </div>

                            <!-- Content Details -->
                            <div class="p-5">
                                <div class="flex items-center justify-between text-xs text-slate-400 mb-1">
                                    <span><?= esc($p['category_name']) ?></span>
                                    <span class="flex items-center gap-1"><i data-lucide="eye" class="w-3 h-3"></i> <?= number_format($p['views_count'] ?? 0) ?> views</span>
                                </div>

                                <a href="<?= base_url('products/' . $p['slug']) ?>" class="block font-bold text-slate-900 hover:text-emerald-600 transition-colors line-clamp-1 mb-2">
                                    <?= esc($p['title']) ?>
                                </a>

                                <div class="text-lg font-extrabold text-emerald-600 mb-3">
                                    <?= ((float)$p['price'] === 0.0) ? 'GRATIS' : 'Rp ' . number_format($p['price'], 0, ',', '.') ?>
                                </div>

                                <div class="flex items-center gap-2 text-xs text-slate-500">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                                    <span class="truncate"><?= esc($p['city']) ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Actions Footer -->
                        <div class="px-5 py-3.5 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between gap-2">
                            <!-- Toggle Status Form -->
                            <form action="<?= base_url('sell/products/' . $p['id'] . '/status') ?>" method="POST" class="inline">
                                <?= csrf_field() ?>
                                <?php if ($p['status'] === 'active'): ?>
                                    <input type="hidden" name="status" value="sold">
                                    <button type="submit" class="text-xs font-semibold text-slate-600 hover:text-emerald-700 bg-white hover:bg-emerald-50 px-2.5 py-1.5 rounded-lg border border-slate-200 transition-colors" title="Tandai Terjual">
                                        ✓ Set Terjual
                                    </button>
                                <?php else: ?>
                                    <input type="hidden" name="status" value="active">
                                    <button type="submit" class="text-xs font-semibold text-emerald-700 hover:bg-emerald-100 bg-emerald-50 px-2.5 py-1.5 rounded-lg border border-emerald-200 transition-colors" title="Aktifkan Kembali">
                                        ↻ Aktifkan
                                    </button>
                                <?php endif; ?>
                            </form>

                            <div class="flex items-center gap-1.5">
                                <a href="<?= base_url('sell/products/' . $p['id'] . '/edit') ?>" class="p-2 text-slate-600 hover:text-emerald-600 hover:bg-white rounded-lg transition-colors" title="Edit Iklan">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>

                                <form action="<?= base_url('sell/products/' . $p['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus iklan ini?');" class="inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-white rounded-lg transition-colors" title="Hapus Iklan">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>
<?= $this->endSection() ?>
