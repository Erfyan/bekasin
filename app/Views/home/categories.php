<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-8">
    <div class="flex items-center gap-2 text-xs text-slate-400 mb-2">
        <a href="<?= base_url('/') ?>" class="hover:text-emerald-600">Beranda</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-slate-700 font-semibold">Semua Kategori</span>
    </div>
    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Jelajahi Kategori Barang</h1>
    <p class="text-xs sm:text-sm text-slate-500 mt-1">Pilih kategori barang bekas yang ingin Anda cari</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
    <?php 
    $categoryIcons = [
        'elektronik'       => 'tv',
        'laptop-komputer'  => 'laptop',
        'handphone'        => 'smartphone',
        'gaming'           => 'gamepad-2',
        'fashion'          => 'shirt',
        'furniture'        => 'sofa',
        'rumah-tangga'     => 'home',
        'kendaraan'        => 'car',
        'buku'             => 'book-open',
        'hobi-koleksi'     => 'camera',
        'peralatan'        => 'wrench',
        'lainnya'          => 'box',
    ];
    ?>
    <?php foreach ($categories as $cat): ?>
        <a href="<?= base_url('categories/' . esc($cat['slug'])) ?>" class="group flex items-start gap-4 p-5 bg-white rounded-2xl border border-slate-200/80 hover:border-emerald-300 hover:shadow-xl transition-all">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:scale-105 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <i data-lucide="<?= $categoryIcons[$cat['slug']] ?? 'tag' ?>" class="w-7 h-7"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-bold text-sm text-slate-900 group-hover:text-emerald-600 transition-colors truncate">
                    <?= esc($cat['name']) ?>
                </h3>
                <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                    <?= esc($cat['description'] ?? 'Koleksi pilihan barang bekas berkualitas kategori ' . $cat['name']) ?>
                </p>
                <div class="mt-3 inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-600">
                    <span><?= (int) ($cat['products_count'] ?? 0) ?> Barang Aktif</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>
