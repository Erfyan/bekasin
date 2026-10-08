<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$conditionLabels = [
    'like_new'     => ['text' => 'Seperti Baru (Like New)', 'desc' => 'Hampir tidak ada tanda pemakaian, fungsi 100% prima, kelengkapan utuh.'],
    'very_good'    => ['text' => 'Sangat Baik (Very Good)', 'desc' => 'Tanda pemakaian sangat minim/wajar, semua fungsi normal tanpa kendala.'],
    'good'         => ['text' => 'Kondisi Baik (Good)', 'desc' => 'Terdapat baret halus pemakaian wajar, semua fitur berfungsi normal.'],
    'fair'         => ['text' => 'Kondisi Cukup (Fair)', 'desc' => 'Ada lecet/tanda pemakaian nyata, namun fungsi dasar tetap bekerja.'],
    'needs_repair' => ['text' => 'Perlu Servis / Minus', 'desc' => 'Ada bagian atau fungsi yang rusak/tidak lengkap, cocok untuk teknisi/kanibal.'],
];
$condInfo = $conditionLabels[$product['condition'] ?? 'good'] ?? ['text' => 'Bekas', 'desc' => ''];
$isOwner = (session()->get('user_id') == $product['user_id']);
?>

<!-- Breadcrumbs -->
<div class="flex items-center gap-2 text-xs text-slate-400 mb-6">
    <a href="<?= base_url('/') ?>" class="hover:text-emerald-600">Beranda</a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
    <a href="<?= base_url('products') ?>" class="hover:text-emerald-600">Katalog</a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
    <a href="<?= base_url('categories/' . esc($product['category_slug'])) ?>" class="hover:text-emerald-600"><?= esc($product['category_name']) ?></a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
    <span class="text-slate-700 font-semibold truncate max-w-xs"><?= esc($product['title']) ?></span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">
    
    <!-- Bagian Kiri: Galeri Foto Produk & Info Detail (Col 7) -->
    <div class="lg:col-span-7 space-y-6">
        
        <!-- Image Gallery Slider -->
        <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-xs space-y-4">
            <div class="relative aspect-4/3 w-full bg-slate-100 rounded-2xl overflow-hidden">
                <img 
                    id="main-product-image"
                    src="<?= !empty($product['primary_image']) ? esc($product['primary_image']) : base_url('assets/images/placeholder.png') ?>" 
                    alt="<?= esc($product['title']) ?>" 
                    class="w-full h-full object-contain"
                >
                <?php if ($product['status'] === 'sold'): ?>
                    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center">
                        <span class="px-5 py-2 bg-rose-600 text-white font-extrabold text-sm rounded-xl tracking-wider uppercase shadow-lg">BARANG INI SUDAH TERJUAL</span>
                    </div>
                <?php elseif ($product['status'] === 'reserved'): ?>
                    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center">
                        <span class="px-5 py-2 bg-amber-500 text-white font-extrabold text-sm rounded-xl tracking-wider uppercase shadow-lg">SEDANG DIPESAN / PROSES DEAL</span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Thumbnail List -->
            <?php if (!empty($product['images']) && count($product['images']) > 1): ?>
                <div class="flex items-center gap-3 overflow-x-auto pb-2">
                    <?php foreach ($product['images'] as $img): ?>
                        <button 
                            type="button" 
                            onclick="document.getElementById('main-product-image').src = '<?= esc($img['image_path']) ?>'"
                            class="w-16 h-16 rounded-xl border-2 border-transparent hover:border-emerald-500 overflow-hidden bg-slate-50 shrink-0 transition-all"
                        >
                            <img src="<?= esc($img['image_path']) ?>" class="w-full h-full object-cover">
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Transparansi Kondisi Fisik Barang (Wajib di Bekasin-Aja) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                    <i data-lucide="clipboard-check" class="w-5 h-5 text-emerald-600"></i>
                    <span>Lembar Kondisi & Transparansi Fisik</span>
                </h3>
                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-lg border border-emerald-200">
                    <?= esc($condInfo['text']) ?>
                </span>
            </div>
            <p class="text-xs text-slate-500"><?= esc($condInfo['desc']) ?></p>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                <div class="p-3 rounded-2xl <?= !empty($product['has_scratches']) ? 'bg-amber-50 text-amber-900 border border-amber-200' : 'bg-slate-50 text-slate-700 border border-slate-100' ?>">
                    <div class="text-[11px] text-slate-400 font-semibold">Bekas Goresan</div>
                    <div class="font-bold text-xs mt-1"><?= !empty($product['has_scratches']) ? 'Ada Baret / Gores' : 'Mulus / Tidak Ada' ?></div>
                </div>
                <div class="p-3 rounded-2xl <?= !empty($product['has_damages']) ? 'bg-rose-50 text-rose-900 border border-rose-200' : 'bg-slate-50 text-slate-700 border border-slate-100' ?>">
                    <div class="text-[11px] text-slate-400 font-semibold">Kerusakan Fisik</div>
                    <div class="font-bold text-xs mt-1"><?= !empty($product['has_damages']) ? 'Ada Bagian Rusak' : 'Fisik Utuh' ?></div>
                </div>
                <div class="p-3 rounded-2xl <?= !empty($product['is_functional']) ? 'bg-emerald-50 text-emerald-900 border border-emerald-200' : 'bg-rose-50 text-rose-900 border border-rose-200' ?>">
                    <div class="text-[11px] text-slate-400 font-semibold">Fungsi Mesin / Fitur</div>
                    <div class="font-bold text-xs mt-1"><?= !empty($product['is_functional']) ? '100% Berfungsi Normal' : 'Ada Kendala / Minus' ?></div>
                </div>
                <div class="p-3 rounded-2xl <?= !empty($product['was_repaired']) ? 'bg-amber-50 text-amber-900 border border-amber-200' : 'bg-slate-50 text-slate-700 border border-slate-100' ?>">
                    <div class="text-[11px] text-slate-400 font-semibold">Riwayat Servis</div>
                    <div class="font-bold text-xs mt-1"><?= !empty($product['was_repaired']) ? 'Pernah Diservis' : 'Segel / Belum Servis' ?></div>
                </div>
            </div>

            <?php if (!empty($product['completeness_notes'])): ?>
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Catatan Kelengkapan & Aksesori:</div>
                    <p class="text-xs text-slate-700 leading-relaxed"><?= esc($product['completeness_notes']) ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Deskripsi Lengkap Produk -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-3">
            <h3 class="font-extrabold text-base text-slate-900">Deskripsi Barang</h3>
            <div class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                <?= esc($product['description']) ?>
            </div>
        </div>

    </div>

    <!-- Bagian Kanan: Harga, Info Penjual, dan Aksi Nego/Chat (Col 5) -->
    <div class="lg:col-span-5 space-y-6">
        
        <!-- Box Utama Harga & CTA -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-5">
            <div>
                <div class="flex items-center justify-between text-xs text-slate-400 mb-1">
                    <span>Dipasang pada <?= date('d M Y', strtotime($product['created_at'])) ?></span>
                    <span class="flex items-center gap-1"><i data-lucide="eye" class="w-3.5 h-3.5"></i> <?= (int) $product['views_count'] ?> kali dilihat</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 leading-snug">
                    <?= esc($product['title']) ?>
                </h1>
            </div>

            <!-- Price Display -->
            <div class="p-4 bg-emerald-50/70 border border-emerald-100 rounded-2xl flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider">Harga Penjual</div>
                    <div class="text-2xl sm:text-3xl font-black text-emerald-600 tracking-tight">
                        Rp <?= number_format((float) $product['price'], 0, ',', '.') ?>
                    </div>
                </div>
                <div class="text-right">
                    <span class="px-2.5 py-1 bg-white text-emerald-700 font-bold text-[11px] rounded-lg border border-emerald-200 shadow-2xs">
                        Bisa Nego
                    </span>
                </div>
            </div>

            <!-- Lokasi & Metode Pengiriman -->
            <div class="space-y-2.5 text-xs text-slate-600 border-y border-slate-100 py-4">
                <div class="flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-slate-400 shrink-0"></i>
                    <span>Lokasi: <strong class="text-slate-800"><?= esc($product['city']) ?>, <?= esc($product['province']) ?></strong></span>
                </div>
                <?php if (!empty($product['meetup_location'])): ?>
                    <div class="flex items-center gap-2">
                        <i data-lucide="navigation" class="w-4 h-4 text-slate-400 shrink-0"></i>
                        <span>Titik COD: <strong class="text-slate-800"><?= esc($product['meetup_location']) ?></strong></span>
                    </div>
                <?php endif; ?>
                <div class="flex items-center gap-2">
                    <i data-lucide="truck" class="w-4 h-4 text-slate-400 shrink-0"></i>
                    <span>Metode: <strong class="text-slate-800"><?= $product['delivery_method'] === 'meetup' ? 'Ketemuan (COD)' : ($product['delivery_method'] === 'shipping' ? 'Pengiriman Kurir' : 'COD / Pengiriman Kurir') ?></strong></span>
                </div>
            </div>

            <!-- Tombol Aksi Buyer / Seller -->
            <div class="space-y-3 pt-2">
                <?php if ($isOwner): ?>
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-center text-xs text-slate-600 font-semibold mb-2">
                        Ini adalah barang jualan Anda.
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="<?= base_url('sell/products/' . (int) $product['id'] . '/edit') ?>" class="py-3 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl text-center flex items-center justify-center gap-2">
                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                            <span>Edit Iklan</span>
                        </a>
                        <a href="<?= base_url('sell/products') ?>" class="py-3 px-4 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs rounded-xl text-center flex items-center justify-center gap-2">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                            <span>Kelola Jualan</span>
                        </a>
                    </div>
                <?php elseif ($product['status'] === 'active'): ?>
                    <?php if (session()->get('is_logged_in')): ?>
                        <!-- Trigger Modal Nego Harga -->
                        <button 
                            type="button" 
                            onclick="document.getElementById('offer-modal').classList.remove('hidden')"
                            class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm rounded-xl shadow-md shadow-emerald-600/20 active:scale-98 transition-all flex items-center justify-center gap-2"
                        >
                            <i data-lucide="tag" class="w-4 h-4"></i>
                            <span>Ajukan Penawaran Harga (Nego)</span>
                        </button>

                        <!-- Start Chat with Seller -->
                        <form action="<?= base_url('messages/start/' . (int) $product['id']) ?>" method="POST">
                            <?= csrf_field() ?>
                            <button 
                                type="submit" 
                                class="w-full py-3.5 px-4 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-sm rounded-xl shadow-xs active:scale-98 transition-all flex items-center justify-center gap-2"
                            >
                                <i data-lucide="message-square" class="w-4 h-4"></i>
                                <span>Chat dengan Penjual</span>
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="<?= base_url('login') ?>" class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm rounded-xl text-center shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center gap-2">
                            <i data-lucide="log-in" class="w-4 h-4"></i>
                            <span>Masuk untuk Menawar & Chat</span>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Wishlist & Report Secondary CTAs -->
                <div class="flex items-center justify-between pt-2">
                    <?php if (session()->get('is_logged_in') && !$isOwner): ?>
                        <button 
                            type="button" 
                            onclick="toggleWishlist(<?= (int) $product['id'] ?>, this)"
                            class="text-xs font-semibold text-slate-500 hover:text-rose-600 flex items-center gap-1.5 transition-colors"
                        >
                            <i data-lucide="heart" class="w-4 h-4"></i>
                            <span>Simpan ke Favorit</span>
                        </button>
                        <button 
                            type="button" 
                            onclick="document.getElementById('report-modal').classList.remove('hidden')"
                            class="text-xs font-semibold text-slate-400 hover:text-rose-600 flex items-center gap-1.5 transition-colors"
                        >
                            <i data-lucide="flag" class="w-3.5 h-3.5"></i>
                            <span>Laporkan Iklan</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Kartu Profil Penjual (Seller Card) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
            <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Profil Penjual</h4>
            
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-black text-lg overflow-hidden shrink-0 border border-emerald-200">
                    <?php if (!empty($product['seller_avatar'])): ?>
                        <img src="<?= esc($product['seller_avatar']) ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <?= strtoupper(substr($product['seller_name'] ?? 'P', 0, 1)) ?>
                    <?php endif; ?>
                </div>

                <div class="flex-1 min-w-0">
                    <a href="<?= base_url('sellers/' . esc($product['seller_username'])) ?>" class="font-extrabold text-slate-900 hover:text-emerald-600 transition-colors text-sm truncate block">
                        <?= esc($product['seller_name']) ?>
                    </a>
                    <div class="text-xs text-slate-400 mt-0.5">@<?= esc($product['seller_username']) ?></div>
                    
                    <div class="flex items-center gap-3 mt-2 text-xs">
                        <div class="flex items-center gap-1 font-bold text-amber-500">
                            <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 stroke-amber-400"></i>
                            <span><?= $avgRating > 0 ? $avgRating : 'Baru' ?></span>
                        </div>
                        <span class="text-slate-300">•</span>
                        <span class="text-slate-500"><?= $reviewsCount ?> Ulasan</span>
                        <span class="text-slate-300">•</span>
                        <span class="text-slate-500">Member sejak <?= date('Y', strtotime($product['seller_joined_at'])) ?></span>
                    </div>
                </div>
            </div>

            <a href="<?= base_url('sellers/' . esc($product['seller_username'])) ?>" class="block w-full py-2.5 px-4 bg-slate-50 hover:bg-slate-100 text-slate-700 text-center font-bold text-xs rounded-xl border border-slate-200 transition-colors">
                Lihat Profil & Semua Barang Penjual
            </a>
        </div>

    </div>

</div>

<!-- Modal Ajukan Nego / Offer -->
<?php if (session()->get('is_logged_in') && !$isOwner): ?>
<div id="offer-modal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-extrabold text-base text-slate-900">Ajukan Penawaran Harga</h3>
            <button type="button" onclick="document.getElementById('offer-modal').classList.add('hidden')" class="p-2 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Harga pasang: <strong>Rp <?= number_format((float) $product['price'], 0, ',', '.') ?></strong>. Tawarkan harga terbaik Anda yang masuk akal bagi penjual.
        </p>

        <form action="<?= base_url('products/' . (int) $product['id'] . '/offer') ?>" method="POST" class="space-y-4 text-xs">
            <?= csrf_field() ?>

            <div>
                <label class="block font-bold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Harga Tawaran Anda (Rp)</label>
                <input 
                    type="number" 
                    name="offered_price" 
                    required 
                    min="1000"
                    placeholder="Contoh: <?= (int) ($product['price'] * 0.9) ?>"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold text-sm"
                >
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Pesan Tambahan (Opsional)</label>
                <textarea 
                    name="notes" 
                    rows="2" 
                    placeholder="Contoh: Bisa langsung COD sore ini di daerah Jaksel..."
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                ></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('offer-modal').classList.add('hidden')" class="px-4 py-2 text-slate-600 font-bold hover:bg-slate-100 rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow-xs">Kirim Tawaran</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Laporkan Iklan -->
<div id="report-modal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-extrabold text-base text-slate-900">Laporkan Iklan Ini</h3>
            <button type="button" onclick="document.getElementById('report-modal').classList.add('hidden')" class="p-2 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="<?= base_url('reports') ?>" method="POST" class="space-y-4 text-xs">
            <?= csrf_field() ?>
            <input type="hidden" name="target_type" value="product">
            <input type="hidden" name="target_id" value="<?= (int) $product['id'] ?>">

            <div>
                <label class="block font-bold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Alasan Laporan</label>
                <select name="reason" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <option value="penipuan">Dugaan Penipuan / Scam</option>
                    <option value="barang_ilegal">Barang Terlarang / Ilegal</option>
                    <option value="informasi_palsu">Informasi atau Foto Palsu</option>
                    <option value="spam">Spam / Iklan Duplikat</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Penjelasan Masalah</label>
                <textarea name="description" required rows="3" placeholder="Jelaskan secara singkat detail masalah yang Anda temukan..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('report-modal').classList.add('hidden')" class="px-4 py-2 text-slate-600 font-bold hover:bg-slate-100 rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-extrabold rounded-xl shadow-xs">Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
