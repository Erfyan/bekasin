<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Masuk — Bekasin') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen bg-gray-50 flex flex-col items-center justify-center py-10 px-4" style="font-family: 'Inter', sans-serif;">

    <div class="w-full max-w-sm text-center mb-5">
        <a href="<?= base_url('/') ?>" class="inline-block">
            <span class="text-xl font-bold text-emerald-600">bekasin</span>
        </a>
        <p class="text-xs text-gray-500 mt-1">Marketplace Barang Bekas</p>
    </div>

    <div class="w-full max-w-sm">
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="flex items-center gap-2 p-3 mb-3 text-xs text-red-700 bg-red-50 border border-red-200 rounded-lg">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="flex items-center gap-2 p-3 mb-3 text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg">
                <i data-lucide="check-circle-2" class="w-4 h-4 shrink-0"></i>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <!-- Card -->
        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
            <?= $this->renderSection('content') ?>
        </div>

        <div class="text-center mt-4 text-xs text-gray-400">
            <a href="<?= base_url('/') ?>" class="hover:text-emerald-600 inline-flex items-center gap-1">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) lucide.createIcons();
        });
    </script>
</body>
</html>
