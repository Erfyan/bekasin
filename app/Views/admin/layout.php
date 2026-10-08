<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Panel — Bekasin-Aja') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="flex min-h-full font-sans antialiased text-slate-100 selection:bg-emerald-500 selection:text-white bg-slate-950">

    <!-- Sidebar Desktop -->
    <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col justify-between shrink-0 hidden md:flex min-h-screen">
        <div>
            <!-- Brand -->
            <div class="h-18 flex items-center px-6 border-b border-slate-800">
                <a href="<?= base_url('admin') ?>" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white font-bold shadow-md">
                        <i data-lucide="shield" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <span class="text-base font-extrabold tracking-tight text-white">Admin Panel</span>
                        <span class="block text-[9px] text-emerald-400 font-bold uppercase tracking-wider -mt-1">Bekasin-Aja</span>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1 text-sm font-medium">
                <a href="<?= base_url('admin') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 text-emerald-400"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('admin/products') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                    <i data-lucide="package" class="w-4 h-4 text-teal-400"></i>
                    <span>Moderasi Barang</span>
                </a>
                <a href="<?= base_url('admin/users') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                    <i data-lucide="users" class="w-4 h-4 text-blue-400"></i>
                    <span>Pengguna</span>
                </a>
                <a href="<?= base_url('admin/categories') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                    <i data-lucide="tags" class="w-4 h-4 text-purple-400"></i>
                    <span>Kategori</span>
                </a>
                <a href="<?= base_url('admin/transactions') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                    <i data-lucide="receipt" class="w-4 h-4 text-emerald-400"></i>
                    <span>Transaksi</span>
                </a>
                <a href="<?= base_url('admin/reports') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-400"></i>
                    <span>Laporan Aduan</span>
                </a>
                <a href="<?= base_url('admin/audit-logs') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                    <i data-lucide="scroll-text" class="w-4 h-4 text-amber-400"></i>
                    <span>Audit Log</span>
                </a>
            </nav>
        </div>

        <!-- Back to Marketplace & Logout -->
        <div class="p-4 border-t border-slate-800 space-y-2">
            <a href="<?= base_url('/') ?>" target="_blank" class="flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-400 hover:text-emerald-400 rounded-lg hover:bg-slate-800/60 transition-colors">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                <span>Buka Marketplace ↗</span>
            </a>
            <a href="<?= base_url('logout') ?>" class="w-full flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-rose-400 hover:text-rose-300 rounded-lg hover:bg-rose-500/10 transition-colors">
                <i data-lucide="log-out" class="w-4 h-4"></i>
                <span>Keluar (Logout)</span>
            </a>
        </div>
    </aside>

    <!-- Main Admin Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Navbar -->
        <header class="h-18 bg-slate-900/80 backdrop-blur border-b border-slate-800 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <a href="<?= base_url('admin') ?>" class="md:hidden flex items-center gap-2">
                    <span class="font-extrabold text-white text-sm">Bekasin-Aja Admin</span>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <span class="text-xs font-bold text-white block"><?= esc(session()->get('full_name')) ?></span>
                    <span class="text-[10px] text-emerald-400 uppercase font-bold tracking-wider">Super Administrator</span>
                </div>
                <div class="w-9 h-9 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-sm border border-emerald-500/30">
                    <?= strtoupper(substr(session()->get('full_name') ?? 'A', 0, 1)) ?>
                </div>
                <a href="<?= base_url('logout') ?>" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-rose-400 hover:text-white bg-rose-500/10 hover:bg-rose-600 rounded-xl border border-rose-500/30 transition-all ml-1" title="Keluar dari Admin Panel">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">Logout</span>
                </a>
            </div>
        </header>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="mx-4 sm:mx-8 mt-4 p-4 rounded-2xl bg-emerald-950/80 border border-emerald-500/30 text-emerald-300 text-xs font-semibold flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="mx-4 sm:mx-8 mt-4 p-4 rounded-2xl bg-rose-950/80 border border-rose-500/30 text-rose-300 text-xs font-semibold flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <!-- Page Section Body -->
        <main class="flex-1 p-4 sm:p-8 bg-slate-950">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
