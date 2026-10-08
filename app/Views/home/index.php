<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section (Simple) -->
<section class="bg-white rounded-xl border border-gray-200 p-6 sm:p-10 mb-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
        Jual Beli Barang Bekas, <span class="text-emerald-600">Mudah & Aman</span>
    </h1>
    <p class="text-gray-500 text-sm mb-5 max-w-lg">
        Temukan barang bekas berkualitas dari orang di sekitarmu. Tanpa perantara, langsung antar pengguna.
    </p>

    <!-- Search -->
    <form action="<?= base_url('products') ?>" method="GET" class="flex gap-2 max-w-lg">
        <div class="flex-1 relative">
            <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
            <input 
                type="text" 
                name="keyword" 
                placeholder="Cari MacBook, Kamera, Jaket..." 
                class="w-full pl-9 pr-3 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none"
            >
        </div>
        <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg">
            Cari
        </button>
    </form>

    <div class="flex gap-4 mt-4 text-xs text-gray-400">
        <a href="<?= base_url('sell/products/create') ?>" class="inline-flex items-center gap-1 text-emerald-600 font-semibold hover:underline">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
            Jual Barang Anda
        </a>
    </div>
</section>

<!-- Kategori -->
<section class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-900">Kategori</h2>
        <a href="<?= base_url('categories') ?>" class="text-xs text-emerald-600 hover:underline font-medium">Lihat Semua</a>
    </div>

    <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-7 gap-2">
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
            <a href="<?= base_url('categories/' . esc($cat['slug'])) ?>" class="flex flex-col items-center gap-1.5 p-3 bg-white rounded-lg border border-gray-200 hover:border-emerald-400 hover:bg-emerald-50 transition-colors text-center">
                <i data-lucide="<?= $categoryIcons[$cat['slug']] ?? 'tag' ?>" class="w-5 h-5 text-gray-500"></i>
                <span class="text-[11px] font-medium text-gray-700 truncate w-full"><?= esc($cat['name']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- Produk Terbaru -->
<section class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-900">Barang Terbaru</h2>
        <a href="<?= base_url('products?sort=latest') ?>" class="text-xs text-emerald-600 hover:underline font-medium">Lihat Semua</a>
    </div>

    <?php if (!empty($latestProducts)): ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            <?php foreach ($latestProducts as $product): ?>
                <?= view('components/product_card', ['product' => $product]) ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <?= view('components/empty_state', [
            'title' => 'Belum ada produk',
            'description' => 'Jadilah orang pertama yang menjual barang bekas di sini!',
            'ctaText' => 'Jual Barang',
            'ctaUrl' => base_url('sell/products/create'),
            'icon' => 'tag'
        ]) ?>
    <?php endif; ?>
</section>

<!-- Produk Populer -->
<?php if (!empty($popularProducts)): ?>
<section class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-900">Populer</h2>
        <a href="<?= base_url('products?sort=popular') ?>" class="text-xs text-emerald-600 hover:underline font-medium">Lihat Semua</a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
        <?php foreach ($popularProducts as $product): ?>
            <?= view('components/product_card', ['product' => $product]) ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Cara Kerja (Simple) -->
<section class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
    <h2 class="text-lg font-bold text-gray-900 mb-4">Cara Kerja</h2>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="flex gap-3">
            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold shrink-0">1</div>
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Cari & Temukan</h3>
                <p class="text-xs text-gray-500 mt-0.5">Jelajahi barang bekas berkualitas di sekitarmu.</p>
            </div>
        </div>
        <div class="flex gap-3">
            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold shrink-0">2</div>
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Chat & Nego</h3>
                <p class="text-xs text-gray-500 mt-0.5">Hubungi penjual langsung lewat chat internal.</p>
            </div>
        </div>
        <div class="flex gap-3">
            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold shrink-0">3</div>
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Deal & Transaksi</h3>
                <p class="text-xs text-gray-500 mt-0.5">COD atau kirim, selesaikan transaksi dengan aman.</p>
            </div>
        </div>
    </div>
</section>

<!-- Wishlist AJAX -->
<script>
function toggleWishlist(productId, btn) {
    fetch(`<?= base_url('api/wishlist/toggle/') ?>/${productId}`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            btn.classList.toggle('text-red-500', data.action === 'added');
            btn.classList.toggle('text-gray-400', data.action !== 'added');
        }
    })
    .catch(() => {});
}
</script>

<?= $this->endSection() ?>
