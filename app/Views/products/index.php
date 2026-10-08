<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumbs -->
<div class="flex items-center gap-2 text-xs text-slate-400 mb-4">
    <a href="<?= base_url('/') ?>" class="hover:text-emerald-600">Beranda</a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
    <span class="text-slate-700 font-semibold">Katalog Produk</span>
    <?php if (!empty($currentCategory)): ?>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-emerald-700 font-bold"><?= esc($currentCategory['name']) ?></span>
    <?php endif; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    
    <!-- Sidebar Filters (Desktop & Mobile Filter Modal Trigger) -->
    <aside class="lg:col-span-1">
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 sticky top-24 shadow-sm space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
                    <i data-lucide="sliders-horizontal" class="w-4 h-4 text-emerald-600"></i>
                    <span>Filter Pencarian</span>
                </h3>
                <a href="<?= base_url('products') ?>" class="text-[11px] font-bold text-slate-400 hover:text-rose-600 transition-colors">
                    Reset
                </a>
            </div>

            <form action="<?= base_url('products') ?>" method="GET" class="space-y-5 text-xs">
                
                <!-- Keyword Filter -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-[11px]">Kata Kunci</label>
                    <input 
                        type="text" 
                        name="keyword" 
                        value="<?= esc($filters['keyword'] ?? '') ?>" 
                        placeholder="Contoh: Kamera, iPad..."
                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                    >
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-[11px]">Kategori</label>
                    <select name="category" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= esc($cat['slug']) ?>" <?= ($filters['category'] ?? '') === $cat['slug'] ? 'selected' : '' ?>>
                                <?= esc($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Condition Filter -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-[11px]">Kondisi Barang</label>
                    <select name="condition" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="">Semua Kondisi</option>
                        <option value="like_new" <?= ($filters['condition'] ?? '') === 'like_new' ? 'selected' : '' ?>>Seperti Baru (Like New)</option>
                        <option value="very_good" <?= ($filters['condition'] ?? '') === 'very_good' ? 'selected' : '' ?>>Sangat Baik (Very Good)</option>
                        <option value="good" <?= ($filters['condition'] ?? '') === 'good' ? 'selected' : '' ?>>Kondisi Baik (Good)</option>
                        <option value="fair" <?= ($filters['condition'] ?? '') === 'fair' ? 'selected' : '' ?>>Cukup (Fair)</option>
                        <option value="needs_repair" <?= ($filters['condition'] ?? '') === 'needs_repair' ? 'selected' : '' ?>>Perlu Servis / Minus</option>
                    </select>
                </div>

                <!-- Price Range Filter -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-[11px]">Rentang Harga (Rp)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input 
                            type="number" 
                            name="min_price" 
                            value="<?= esc($filters['min_price'] ?? '') ?>" 
                            placeholder="Min"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none text-[11px]"
                        >
                        <input 
                            type="number" 
                            name="max_price" 
                            value="<?= esc($filters['max_price'] ?? '') ?>" 
                            placeholder="Maks"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none text-[11px]"
                        >
                    </div>
                </div>

                <!-- City / Location Filter -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-[11px]">Lokasi Kota</label>
                    <input 
                        type="text" 
                        name="city" 
                        value="<?= esc($filters['city'] ?? '') ?>" 
                        placeholder="Contoh: Bandung, Surabaya..."
                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                    >
                </div>

                <!-- Delivery Method Filter -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-[11px]">Metode Transaksi</label>
                    <select name="delivery_method" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="">Semua Metode</option>
                        <option value="meetup" <?= ($filters['delivery_method'] ?? '') === 'meetup' ? 'selected' : '' ?>>Ketemuan (COD)</option>
                        <option value="shipping" <?= ($filters['delivery_method'] ?? '') === 'shipping' ? 'selected' : '' ?>>Pengiriman Kurir</option>
                    </select>
                </div>

                <!-- Sort Filter -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-[11px]">Urutkan</label>
                    <select name="sort" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="latest" <?= ($filters['sort'] ?? '') === 'latest' ? 'selected' : '' ?>>Terbaru</option>
                        <option value="popular" <?= ($filters['sort'] ?? '') === 'popular' ? 'selected' : '' ?>>Paling Populer</option>
                        <option value="price_low" <?= ($filters['sort'] ?? '') === 'price_low' ? 'selected' : '' ?>>Harga Terendah</option>
                        <option value="price_high" <?= ($filters['sort'] ?? '') === 'price_high' ? 'selected' : '' ?>>Harga Tertinggi</option>
                    </select>
                </div>

                <button 
                    type="submit" 
                    class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow-xs transition-colors flex items-center justify-center gap-2"
                >
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    <span>Terapkan Filter</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Product Grid & Pagination -->
    <section class="lg:col-span-3">
        
        <!-- Header Info & Sorting Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-200">
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">
                    <?= !empty($currentCategory) ? esc($currentCategory['name']) : 'Semua Barang Bekas' ?>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Menampilkan hasil pencarian barang bekas berkualitas
                </p>
            </div>
        </div>

        <!-- Product Cards -->
        <?php if (!empty($products)): ?>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <?php foreach ($products as $product): ?>
                    <?= view('components/product_card', ['product' => $product]) ?>
                <?php endforeach; ?>
            </div>

            <!-- Server-Side Pagination -->
            <div class="mt-10 flex items-center justify-center">
                <?= $pager->links('default', 'default_full') ?>
            </div>
        <?php else: ?>
            <?= view('components/empty_state', [
                'title' => 'Tidak Ada Barang Ditemukan',
                'description' => 'Coba ubah kata kunci pencarian atau bersihkan filter yang Anda gunakan.',
                'ctaText' => 'Lihat Semua Barang',
                'ctaUrl' => base_url('products'),
                'icon' => 'search-x'
            ]) ?>
        <?php endif; ?>

    </section>

</div>

<?= $this->endSection() ?>
