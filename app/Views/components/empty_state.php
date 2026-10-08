<?php
/**
 * Komponen Empty State Bekasin-Aja
 * Parameter: $title, $description, $ctaText, $ctaUrl, $icon
 */
?>
<div class="flex flex-col items-center justify-center text-center p-12 bg-white rounded-3xl border border-dashed border-slate-200 my-6">
    <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
        <i data-lucide="<?= esc($icon ?? 'package-open') ?>" class="w-8 h-8"></i>
    </div>
    <h3 class="text-base font-bold text-slate-900 mb-1"><?= esc($title ?? 'Belum ada data') ?></h3>
    <p class="text-xs text-slate-500 max-w-sm mb-6 leading-relaxed"><?= esc($description ?? 'Belum ada item yang dapat ditampilkan saat ini.') ?></p>
    <?php if (!empty($ctaUrl)): ?>
        <a href="<?= esc($ctaUrl) ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors">
            <span><?= esc($ctaText ?? 'Kembali ke Katalog') ?></span>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
    <?php endif; ?>
</div>
