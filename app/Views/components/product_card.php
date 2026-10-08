<?php
/**
 * Komponen Kartu Produk Marketplace Bekasin-Aja
 * Parameter: $product (array)
 */

$conditionLabels = [
    'like_new'     => ['text' => 'Seperti Baru', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
    'very_good'    => ['text' => 'Sangat Baik', 'class' => 'bg-teal-50 text-teal-700 border-teal-200'],
    'good'         => ['text' => 'Kondisi Baik', 'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
    'fair'         => ['text' => 'Cukup', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
    'needs_repair' => ['text' => 'Perlu Servis', 'class' => 'bg-rose-50 text-rose-700 border-rose-200'],
];

$cond = $conditionLabels[$product['condition'] ?? 'good'] ?? ['text' => 'Bekas', 'class' => 'bg-slate-100 text-slate-700 border-slate-200'];
$isSold = ($product['status'] ?? 'active') === 'sold';
$isReserved = ($product['status'] ?? 'active') === 'reserved';
?>

<div class="group relative flex flex-col bg-white rounded-2xl border border-slate-200/80 hover:border-emerald-300 hover:shadow-xl hover:shadow-emerald-950/5 transition-all duration-200 overflow-hidden">
    
    <!-- Thumbnail Image & Badges -->
    <div class="relative aspect-square w-full bg-slate-100 overflow-hidden">
        <a href="<?= base_url('products/' . esc($product['slug'])) ?>" class="block w-full h-full">
            <?php if (!empty($product['primary_image'])): ?>
                <img 
                    src="<?= esc($product['primary_image']) ?>" 
                    alt="<?= esc($product['title']) ?>" 
                    loading="lazy"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                >
            <?php else: ?>
                <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                    <i data-lucide="image" class="w-12 h-12 stroke-[1.5]"></i>
                    <span class="text-[11px] font-medium mt-1">Foto Belum Tersedia</span>
                </div>
            <?php endif; ?>
        </a>

        <!-- Overlay Status (Sold / Reserved) -->
        <?php if ($isSold): ?>
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center pointer-events-none">
                <span class="px-3.5 py-1.5 bg-rose-600 text-white font-extrabold text-xs tracking-wider rounded-lg uppercase shadow-md">TERJUAL</span>
            </div>
        <?php elseif ($isReserved): ?>
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center pointer-events-none">
                <span class="px-3 py-1 bg-amber-500 text-white font-extrabold text-xs tracking-wider rounded-lg uppercase shadow-md">DIPESAN</span>
            </div>
        <?php endif; ?>

        <!-- Condition Badge -->
        <div class="absolute top-3 left-3 pointer-events-none">
            <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg border shadow-xs backdrop-blur <?= $cond['class'] ?>">
                <?= $cond['text'] ?>
            </span>
        </div>

        <!-- Wishlist Button -->
        <?php if (session()->get('is_logged_in')): ?>
            <button 
                type="button" 
                onclick="toggleWishlist(<?= (int) $product['id'] ?>, this)"
                class="absolute top-3 right-3 p-2 bg-white/90 hover:bg-white text-slate-400 hover:text-rose-500 rounded-full shadow-md backdrop-blur transition-all active:scale-90"
                title="Simpan ke Favorit"
            >
                <i data-lucide="heart" class="w-4 h-4"></i>
            </button>
        <?php endif; ?>
    </div>

    <!-- Content Detail -->
    <div class="flex flex-col flex-1 p-4">
        <!-- Title -->
        <a href="<?= base_url('products/' . esc($product['slug'])) ?>" class="block font-semibold text-slate-900 hover:text-emerald-600 text-sm line-clamp-2 leading-snug mb-2 transition-colors">
            <?= esc($product['title']) ?>
        </a>

        <!-- Price -->
        <div class="text-base font-extrabold text-emerald-600 tracking-tight mb-2">
            Rp <?= number_format((float) ($product['price'] ?? 0), 0, ',', '.') ?>
        </div>

        <div class="mt-auto pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
            <!-- Location -->
            <div class="flex items-center gap-1 truncate">
                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                <span class="truncate"><?= esc($product['city'] ?? 'Indonesia') ?></span>
            </div>

            <!-- Delivery Method -->
            <div class="flex items-center gap-1 font-medium text-slate-500 shrink-0">
                <?php if (($product['delivery_method'] ?? 'both') === 'meetup'): ?>
                    <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-[10px]">COD</span>
                <?php elseif (($product['delivery_method'] ?? 'both') === 'shipping'): ?>
                    <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-[10px]">Kirim</span>
                <?php else: ?>
                    <span class="bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded text-[10px]">COD / Kirim</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
