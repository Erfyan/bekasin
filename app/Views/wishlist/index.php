<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="bg-slate-50 py-8 min-h-[80vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Header -->
        <nav class="flex items-center gap-2 text-sm text-slate-500 mb-4 font-medium">
            <a href="<?= base_url('/') ?>" class="hover:text-emerald-600 transition-colors">Beranda</a>
            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
            <span class="text-slate-800 font-semibold">Wishlist Favorit</span>
        </nav>

        <div class="flex items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span class="p-2 rounded-2xl bg-rose-50 text-rose-500 inline-flex items-center justify-center">
                        <i data-lucide="heart" class="w-6 h-6 fill-rose-500"></i>
                    </span>
                    Barang Favorit Saya
                </h1>
                <p class="text-sm text-slate-500 mt-1">Daftar barang bekas incaran yang kamu simpan.</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-slate-200 text-slate-700">
                <?= count($items) ?> Barang Disimpan
            </span>
        </div>

        <?php if (empty($items)): ?>
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 max-w-lg mx-auto my-6">
                <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="heart-crack" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Belum Ada Barang Favorit</h3>
                <p class="text-sm text-slate-500 mt-1 mb-6">
                    Kamu belum menyimpan barang apapun ke daftar wishlist. Jelajahi katalog sekarang!
                </p>
                <a href="<?= base_url('products') ?>" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 text-white font-semibold text-sm rounded-xl hover:bg-emerald-700 transition-all shadow-md shadow-emerald-600/20">
                    <i data-lucide="compass" class="w-4 h-4"></i> Jelajahi Barang Bekas
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                <?php foreach ($items as $item): ?>
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all overflow-hidden flex flex-col justify-between group relative" id="wishlist-item-<?= $item['id'] ?>">
                        <div>
                            <!-- Image Thumbnail -->
                            <div class="relative aspect-square w-full bg-slate-100 overflow-hidden">
                                <?php if (!empty($item['primary_image'])): ?>
                                    <img src="<?= esc($item['primary_image']) ?>" alt="<?= esc($item['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <i data-lucide="image" class="w-12 h-12"></i>
                                    </div>
                                <?php endif; ?>

                                <!-- Remove Wishlist Button -->
                                <button 
                                    type="button" 
                                    onclick="removeWishlist(<?= $item['product_id'] ?>, <?= $item['id'] ?>)" 
                                    class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-white/90 backdrop-blur text-rose-500 hover:text-rose-700 shadow-md flex items-center justify-center hover:scale-110 active:scale-95 transition-all" 
                                    title="Hapus dari Favorit"
                                >
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>

                                <?php if ($item['product_status'] === 'sold'): ?>
                                    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center">
                                        <span class="px-3 py-1 bg-slate-900 text-white font-bold text-xs rounded-full uppercase tracking-wider">
                                            Sudah Terjual
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Details -->
                            <div class="p-4">
                                <span class="text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md inline-block mb-1.5">
                                    <?= esc($item['seller_name']) ?>
                                </span>

                                <a href="<?= base_url('products/' . $item['slug']) ?>" class="block font-bold text-sm text-slate-900 hover:text-emerald-600 transition-colors line-clamp-2 mb-2">
                                    <?= esc($item['title']) ?>
                                </a>

                                <div class="text-base font-extrabold text-slate-900 mb-2">
                                    <?= ((float)$item['price'] === 0.0) ? 'GRATIS' : 'Rp ' . number_format($item['price'], 0, ',', '.') ?>
                                </div>

                                <div class="flex items-center gap-1 text-xs text-slate-500">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                                    <span class="truncate"><?= esc($item['city']) ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 pt-0">
                            <a href="<?= base_url('products/' . $item['slug']) ?>" class="block w-full py-2 text-center text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-colors">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>
async function removeWishlist(productId, itemId) {
    if (!confirm('Hapus barang ini dari daftar favorit Anda?')) return;

    try {
        const res = await fetch(`<?= base_url('api/wishlist/toggle/') ?>${productId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            }
        });
        const data = await res.json();
        if (data.success) {
            const el = document.getElementById(`wishlist-item-${itemId}`);
            if (el) {
                el.classList.add('opacity-0', 'scale-90');
                setTimeout(() => el.remove(), 250);
            }
        }
    } catch (e) {
        console.error(e);
    }
}
</script>
<?= $this->endSection() ?>
