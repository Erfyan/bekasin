<?php
/**
 * Komponen Kartu Produk — Bekasin
 * Parameter: $product (array)
 */

$conditionLabels = [
    'like_new'     => ['text' => 'Seperti Baru', 'class' => 'bg-green-50 text-green-700'],
    'very_good'    => ['text' => 'Sangat Baik', 'class' => 'bg-teal-50 text-teal-700'],
    'good'         => ['text' => 'Baik', 'class' => 'bg-blue-50 text-blue-700'],
    'fair'         => ['text' => 'Cukup', 'class' => 'bg-amber-50 text-amber-700'],
    'needs_repair' => ['text' => 'Perlu Servis', 'class' => 'bg-red-50 text-red-700'],
];

$cond = $conditionLabels[$product['condition'] ?? 'good'] ?? ['text' => 'Bekas', 'class' => 'bg-gray-100 text-gray-600'];
$isSold = ($product['status'] ?? 'active') === 'sold';
$isReserved = ($product['status'] ?? 'active') === 'reserved';
?>

<div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
    
    <!-- Image -->
    <div class="relative aspect-square w-full bg-gray-100 overflow-hidden">
        <a href="<?= base_url('products/' . esc($product['slug'])) ?>" class="block w-full h-full">
            <?php if (!empty($product['primary_image'])): ?>
                <img 
                    src="<?= esc($product['primary_image']) ?>" 
                    alt="<?= esc($product['title']) ?>" 
                    loading="lazy"
                    class="w-full h-full object-cover"
                >
            <?php else: ?>
                <div class="w-full h-full flex items-center justify-center text-gray-300">
                    <i data-lucide="image" class="w-10 h-10"></i>
                </div>
            <?php endif; ?>
        </a>

        <!-- Status Overlay -->
        <?php if ($isSold): ?>
            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                <span class="px-2.5 py-1 bg-red-600 text-white text-xs font-bold rounded">TERJUAL</span>
            </div>
        <?php elseif ($isReserved): ?>
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                <span class="px-2.5 py-1 bg-amber-500 text-white text-xs font-bold rounded">DIPESAN</span>
            </div>
        <?php endif; ?>

        <!-- Condition Badge -->
        <span class="absolute top-2 left-2 px-2 py-0.5 text-[10px] font-semibold rounded <?= $cond['class'] ?>">
            <?= $cond['text'] ?>
        </span>

        <!-- Wishlist -->
        <?php if (session()->get('is_logged_in')): ?>
            <button 
                type="button" 
                onclick="toggleWishlist(<?= (int) $product['id'] ?>, this)"
                class="absolute top-2 right-2 p-1.5 bg-white/80 hover:bg-white text-gray-400 hover:text-red-500 rounded-full shadow-sm transition-colors"
                title="Favorit"
            >
                <i data-lucide="heart" class="w-3.5 h-3.5"></i>
            </button>
        <?php endif; ?>
    </div>

    <!-- Content -->
    <div class="p-3">
        <a href="<?= base_url('products/' . esc($product['slug'])) ?>" class="block text-sm font-medium text-gray-800 hover:text-emerald-600 line-clamp-2 leading-snug mb-1.5">
            <?= esc($product['title']) ?>
        </a>

        <div class="text-sm font-bold text-emerald-600 mb-2">
            Rp <?= number_format((float) ($product['price'] ?? 0), 0, ',', '.') ?>
        </div>

        <div class="flex items-center justify-between text-[11px] text-gray-400 pt-2 border-t border-gray-100">
            <div class="flex items-center gap-1 truncate">
                <i data-lucide="map-pin" class="w-3 h-3 shrink-0"></i>
                <span class="truncate"><?= esc($product['city'] ?? 'Indonesia') ?></span>
            </div>
            <?php if (($product['delivery_method'] ?? 'both') === 'meetup'): ?>
                <span class="text-[10px] text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded">COD</span>
            <?php elseif (($product['delivery_method'] ?? 'both') === 'shipping'): ?>
                <span class="text-[10px] text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded">Kirim</span>
            <?php else: ?>
                <span class="text-[10px] text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">COD/Kirim</span>
            <?php endif; ?>
        </div>
    </div>
</div>
