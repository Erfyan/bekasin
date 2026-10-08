<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-900 via-teal-900 to-slate-900 text-white p-8 sm:p-12 lg:p-16 mb-12 shadow-2xl">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(16,185,129,0.15),transparent_50%)]"></div>
    <div class="relative z-10 max-w-3xl">
        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-bold tracking-wide uppercase mb-4 backdrop-blur">
            <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
            Marketplace C2C Indonesia
        </span>
        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight sm:leading-none mb-4">
            Barang Lama, <br>
            <span class="bg-gradient-to-r from-emerald-400 to-teal-300 bg-clip-text text-transparent">Manfaat Baru.</span>
        </h1>
        <p class="text-slate-300 text-sm sm:text-base mb-8 max-w-2xl leading-relaxed">
            Temukan ribuan barang bekas berkualitas dari orang di sekitarmu secara transparan, aman, dan tanpa biaya perantara yang mahal.
        </p>

        <!-- Big Search Form -->
        <form action="<?= base_url('products') ?>" method="GET" class="bg-white/10 backdrop-blur-md p-2 rounded-2xl border border-white/20 flex flex-col sm:flex-row gap-2 shadow-lg mb-6">
            <div class="flex-1 flex items-center px-3 bg-white rounded-xl">
                <i data-lucide="search" class="w-5 h-5 text-slate-400 mr-2"></i>
                <input 
                    type="text" 
                    name="keyword" 
                    placeholder="Mau cari apa hari ini? Contoh: MacBook Air M1, Kamera Mirrorless..." 
                    class="w-full py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none bg-transparent"
                >
            </div>
            <button type="submit" class="px-6 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-slate-900 hover:text-white font-extrabold text-sm rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                <span>Cari Barang</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </button>
        </form>

        <!-- Hero CTAs & Quick Stats -->
        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-300">
            <a href="<?= base_url('sell/products/create') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-slate-900 font-bold rounded-xl hover:bg-slate-100 transition-colors shadow-sm">
                <i data-lucide="plus-circle" class="w-4 h-4 text-emerald-600"></i>
                <span>Jual Barang Anda Sekarang</span>
            </a>
            <div class="flex items-center gap-4 border-l border-white/20 pl-4 py-1">
                <div>
                    <span class="font-extrabold text-white">100%</span> Aman & Transparan
                </div>
                <div>
                    <span class="font-extrabold text-white">COD</span> / Rekber Internal
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Kategori Pilihan -->
<section class="mb-14">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900">Kategori Pilihan</h2>
            <p class="text-xs sm:text-sm text-slate-500">Temukan barang impian berdasarkan kategori</p>
        </div>
        <a href="<?= base_url('categories') ?>" class="text-xs sm:text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
            <span>Lihat Semua</span>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3 sm:gap-4">
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
            <a href="<?= base_url('categories/' . esc($cat['slug'])) ?>" class="group flex flex-col items-center justify-center p-4 bg-white rounded-2xl border border-slate-200/80 hover:border-emerald-300 hover:shadow-lg transition-all text-center">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                    <i data-lucide="<?= $categoryIcons[$cat['slug']] ?? 'tag' ?>" class="w-6 h-6"></i>
                </div>
                <span class="text-xs font-bold text-slate-700 group-hover:text-emerald-600 transition-colors truncate w-full">
                    <?= esc($cat['name']) ?>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- Section Produk Terbaru -->
<section class="mb-14">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900">Barang Bekas Terbaru</h2>
            <p class="text-xs sm:text-sm text-slate-500">Listing yang baru saja di-upload oleh penjual</p>
        </div>
        <a href="<?= base_url('products?sort=latest') ?>" class="text-xs sm:text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
            <span>Lihat Semua</span>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
        </a>
    </div>

    <?php if (!empty($latestProducts)): ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            <?php foreach ($latestProducts as $product): ?>
                <?= view('components/product_card', ['product' => $product]) ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <?= view('components/empty_state', [
            'title' => 'Belum ada produk terbaru',
            'description' => 'Jadilah orang pertama yang menjual barang bekas berkualitas di sini!',
            'ctaText' => 'Jual Barang Sekarang',
            'ctaUrl' => base_url('sell/products/create'),
            'icon' => 'tag'
        ]) ?>
    <?php endif; ?>
</section>

<!-- Section Produk Populer -->
<?php if (!empty($popularProducts)): ?>
<section class="mb-14">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900">Paling Banyak Dilihat</h2>
            <p class="text-xs sm:text-sm text-slate-500">Barang-barang favorit yang sedang ramai peminat</p>
        </div>
        <a href="<?= base_url('products?sort=popular') ?>" class="text-xs sm:text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
            <span>Lihat Semua</span>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        <?php foreach ($popularProducts as $product): ?>
            <?= view('components/product_card', ['product' => $product]) ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Section Cara Kerja Marketplace Bekasin-Aja -->
<section class="my-16 p-8 sm:p-12 bg-white rounded-3xl border border-slate-200 shadow-sm">
    <div class="text-center max-w-xl mx-auto mb-12">
        <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Mudah & Aman</span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">Bagaimana Bekasin-Aja Bekerja?</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-2">Hanya butuh 5 langkah mudah untuk menjual dan membeli barang bekas</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="flex flex-col items-center text-center p-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-extrabold text-lg mb-3 shadow-xs">1</div>
            <h3 class="font-bold text-sm text-slate-900 mb-1">Temukan Barang</h3>
            <p class="text-xs text-slate-500">Jelajahi foto asli, kondisi transparan, dan lokasi penjual terdekat.</p>
        </div>
        <div class="flex flex-col items-center text-center p-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center font-extrabold text-lg mb-3 shadow-xs">2</div>
            <h3 class="font-bold text-sm text-slate-900 mb-1">Hubungi Penjual</h3>
            <p class="text-xs text-slate-500">Gunakan fitur private chat internal tanpa perlu membagikan kontak pribadi.</p>
        </div>
        <div class="flex flex-col items-center text-center p-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center font-extrabold text-lg mb-3 shadow-xs">3</div>
            <h3 class="font-bold text-sm text-slate-900 mb-1">Nego Harga</h3>
            <p class="text-xs text-slate-500">Kirim penawaran harga secara formal langsung ke dashboard penjual.</p>
        </div>
        <div class="flex flex-col items-center text-center p-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center font-extrabold text-lg mb-3 shadow-xs">4</div>
            <h3 class="font-bold text-sm text-slate-900 mb-1">Deal & Transaksi</h3>
            <p class="text-xs text-slate-500">Pilih metode COD (ketemuan) atau pengiriman kurir yang disepakati.</p>
        </div>
        <div class="flex flex-col items-center text-center p-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center font-extrabold text-lg mb-3 shadow-xs">5</div>
            <h3 class="font-bold text-sm text-slate-900 mb-1">Barang Berpindah</h3>
            <p class="text-xs text-slate-500">Selesaikan konfirmasi transaksi dan beri rating untuk membangun reputasi komunitas.</p>
        </div>
    </div>
</section>

<!-- Section Trust & Keamanan Transparan -->
<section class="p-8 sm:p-12 bg-gradient-to-tr from-slate-900 to-emerald-950 rounded-3xl text-white shadow-xl mb-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
        <div>
            <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Keamanan Komunitas C2C</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold mt-2 leading-tight">
                Marketplace Nyata dengan Standar Kejujuran Tinggi
            </h2>
            <p class="text-slate-300 text-xs sm:text-sm mt-3 leading-relaxed">
                Setiap listing wajib mencantumkan riwayat fisik barang seperti bekas goresan, fungsi kelistrikan, dan kelengkapan. Pembeli dapat memeriksa profil dan ulasan asli sebelum bertransaksi.
            </p>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 bg-white/10 backdrop-blur rounded-2xl border border-white/10">
                <i data-lucide="check-check" class="w-6 h-6 text-emerald-400 mb-2"></i>
                <div class="font-bold text-sm">Kondisi Transparan</div>
                <div class="text-[11px] text-slate-300 mt-1">Seller wajib mencatat minus dan goresan barang</div>
            </div>
            <div class="p-4 bg-white/10 backdrop-blur rounded-2xl border border-white/10">
                <i data-lucide="star" class="w-6 h-6 text-amber-400 mb-2"></i>
                <div class="font-bold text-sm">Ulasan Terverifikasi</div>
                <div class="text-[11px] text-slate-300 mt-1">Review hanya bisa diberikan setelah transaksi selesai</div>
            </div>
            <div class="p-4 bg-white/10 backdrop-blur rounded-2xl border border-white/10">
                <i data-lucide="shield-alert" class="w-6 h-6 text-rose-400 mb-2"></i>
                <div class="font-bold text-sm">Fitur Lapor Cepat</div>
                <div class="text-[11px] text-slate-300 mt-1">Moderator menindak cepat listing yang melanggar</div>
            </div>
            <div class="p-4 bg-white/10 backdrop-blur rounded-2xl border border-white/10">
                <i data-lucide="map-pin" class="w-6 h-6 text-teal-400 mb-2"></i>
                <div class="font-bold text-sm">COD Wilayah Terdekat</div>
                <div class="text-[11px] text-slate-300 mt-1">Filter kota & provinsi untuk transaksi langsung yang aman</div>
            </div>
        </div>
    </div>
</section>

<!-- Wishlist AJAX Handler Script -->
<script>
function toggleWishlist(productId, btn) {
    fetch(`<?= base_url('api/wishlist/toggle/') ?>/${productId}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            if (data.action === 'added') {
                btn.classList.remove('text-slate-400');
                btn.classList.add('text-rose-500');
            } else {
                btn.classList.remove('text-rose-500');
                btn.classList.add('text-slate-400');
            }
        }
    })
    .catch(() => {});
}
</script>

<?= $this->endSection() ?>
