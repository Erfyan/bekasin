<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Autentikasi — Bekasin-Aja') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <script defer src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="flex flex-col min-h-full font-sans antialiased text-slate-800 selection:bg-emerald-500 selection:text-white justify-center py-12 sm:px-6 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-6">
        <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-2 group">
            <div class="w-12 h-12 rounded-2xl bg-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-600/30 group-hover:scale-105 transition-transform">
                <i data-lucide="package-search" class="w-6 h-6"></i>
            </div>
            <span class="text-2xl font-extrabold bg-gradient-to-r from-emerald-600 to-teal-700 bg-clip-text text-transparent">Bekasin-Aja</span>
        </a>
        <p class="text-xs text-slate-500 mt-2 font-medium">Marketplace Barang Bekas C2C Indonesia</p>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <!-- Flash Message Alerts -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="flex items-center gap-3 p-4 mb-4 text-xs font-semibold text-rose-800 bg-rose-50 border border-rose-200 rounded-2xl shadow-xs">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                <div><?= session()->getFlashdata('error') ?></div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="flex items-center gap-3 p-4 mb-4 text-xs font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-2xl shadow-xs">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                <div><?= session()->getFlashdata('success') ?></div>
            </div>
        <?php endif; ?>

        <!-- Card Container -->
        <div class="bg-white py-8 px-6 sm:px-10 shadow-xl shadow-slate-200/50 rounded-3xl border border-slate-200/80">
            <?= $this->renderSection('content') ?>
        </div>

        <div class="text-center mt-6 text-xs text-slate-400">
            <a href="<?= base_url('/') ?>" class="hover:text-emerald-600 inline-flex items-center gap-1">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
