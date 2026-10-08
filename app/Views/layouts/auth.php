<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= esc($title ?? 'Masuk — Bekasin') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/lucide@latest"></script>
    <style>
        .mobile-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .mobile-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .mobile-scroll::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.15);
            border-radius: 4px;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-gray-800 flex items-center justify-center sm:p-4 font-sans antialiased selection:bg-emerald-500 selection:text-white" style="font-family: 'Inter', sans-serif;">

    <!-- Background Decorative Lighting (Desktop Only) -->
    <div class="fixed inset-0 pointer-events-none hidden sm:block overflow-hidden">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-teal-600/20 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-slate-800/40 rounded-full blur-3xl"></div>
    </div>

    <!-- Outer Phone Container -->
    <div class="w-full sm:max-w-[430px] h-screen sm:h-[890px] sm:max-h-[94vh] bg-gray-50 sm:rounded-[50px] sm:shadow-[0_25px_80px_rgba(0,0,0,0.65),0_0_0_10px_#1e293b,0_0_0_13px_#0f172a] overflow-hidden flex flex-col relative z-10 border border-slate-800/40">

        <!-- Phone Simulated Status Bar (Desktop Only) -->
        <div class="hidden sm:flex items-center justify-between px-7 pt-3.5 pb-1 bg-gray-50 text-slate-900 text-xs font-semibold select-none shrink-0 z-50">
            <span id="phone-clock" class="tracking-tight text-[13px] font-bold">09:41</span>
            
            <!-- Dynamic Island Pill -->
            <div class="h-4 w-24 bg-black rounded-full flex items-center justify-end px-2 gap-1.5 shadow-inner">
                <div class="w-1.5 h-1.5 rounded-full bg-slate-900 border border-slate-800"></div>
                <div class="w-2 h-2 rounded-full bg-blue-950/80 border border-blue-900/60"></div>
            </div>

            <!-- Status Icons -->
            <div class="flex items-center gap-1.5 text-slate-800">
                <i data-lucide="signal" class="w-3.5 h-3.5"></i>
                <i data-lucide="wifi" class="w-3.5 h-3.5"></i>
                <div class="w-5 h-2.5 border border-slate-800 rounded-sm p-0.5 flex items-center">
                    <div class="h-full w-3 bg-slate-900 rounded-xs"></div>
                </div>
            </div>
        </div>

        <!-- Scrollable Auth Screen -->
        <div class="flex-1 overflow-y-auto overflow-x-hidden mobile-scroll px-5 py-6 flex flex-col justify-center">
            
            <!-- Logo Header -->
            <div class="text-center mb-6">
                <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-2 group">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white font-black text-lg shadow-md group-hover:scale-105 transition-transform">
                        B
                    </div>
                    <span class="text-2xl font-black tracking-tight text-emerald-600">bekasin</span>
                </a>
                <p class="text-xs text-gray-500 mt-1">Marketplace Barang Bekas Terpercaya</p>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="flex items-center gap-2 p-3 mb-4 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-xl shadow-xs">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-red-600"></i>
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="flex items-center gap-2 p-3 mb-4 text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl shadow-xs">
                    <i data-lucide="check-circle-2" class="w-4 h-4 shrink-0 text-emerald-600"></i>
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
            <?php endif; ?>

            <!-- Form Card -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm">
                <?= $this->renderSection('content') ?>
            </div>

            <div class="text-center mt-5 text-xs text-gray-400">
                <a href="<?= base_url('/') ?>" class="hover:text-emerald-600 inline-flex items-center gap-1 font-medium transition-colors">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        </div>

        <!-- Simulated Home Gesture Indicator Bar (Bottom) -->
        <div class="hidden sm:block absolute bottom-1.5 left-1/2 -translate-x-1/2 z-50 pointer-events-none">
            <div class="w-32 h-1 bg-slate-900/30 rounded-full"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) lucide.createIcons();

            // Update Phone Clock
            const clockEl = document.getElementById('phone-clock');
            if (clockEl) {
                const updateClock = () => {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const mins = String(now.getMinutes()).padStart(2, '0');
                    clockEl.textContent = `${hours}:${mins}`;
                };
                updateClock();
                setInterval(updateClock, 30000);
            }
        });
    </script>
</body>
</html>
