<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Masuk — Bekasin') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans antialiased selection:bg-emerald-500 selection:text-white" style="font-family: 'Inter', sans-serif;">

    <!-- Logo & Header -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center px-4">
        <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-2 group mb-2">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white font-black text-lg shadow-md shadow-emerald-600/20 group-hover:scale-105 transition-transform">
                B
            </div>
            <span class="text-2xl font-black tracking-tight text-emerald-600">bekasin</span>
        </a>
        <p class="text-xs text-gray-500">Marketplace Barang Bekas Terpercaya</p>
    </div>

    <!-- Auth Card Container -->
    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="flex items-center gap-3 p-3.5 mb-4 text-xs font-medium text-red-700 bg-red-50 border border-red-200/80 rounded-2xl shadow-xs">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-red-600"></i>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="flex items-center gap-3 p-3.5 mb-4 text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-200/80 rounded-2xl shadow-xs">
                <i data-lucide="check-circle-2" class="w-4 h-4 shrink-0 text-emerald-600"></i>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <!-- Form Card -->
        <div class="bg-white py-8 px-6 sm:px-10 rounded-3xl sm:border border-gray-200/80 shadow-sm">
            <?= $this->renderSection('content') ?>
        </div>

        <div class="text-center mt-6 text-xs text-gray-500">
            <a href="<?= base_url('/') ?>" class="hover:text-emerald-600 inline-flex items-center gap-1.5 font-medium transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Kembali ke Beranda Bekasin</span>
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
