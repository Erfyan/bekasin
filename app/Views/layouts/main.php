<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= esc($title ?? 'Bekasin — Marketplace Barang Bekas') ?></title>
    <meta name="description" content="<?= esc($metaDescription ?? 'Jual beli barang bekas berkualitas, aman, dan mudah. Barang Lama, Manfaat Baru.') ?>">
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
    <div class="w-full sm:max-w-[430px] h-screen sm:h-[890px] sm:max-h-[94vh] bg-white sm:rounded-[50px] sm:shadow-[0_25px_80px_rgba(0,0,0,0.65),0_0_0_10px_#1e293b,0_0_0_13px_#0f172a] overflow-hidden flex flex-col relative z-10 border border-slate-800/40">

        <!-- Phone Simulated Status Bar (Desktop Only) -->
        <div class="hidden sm:flex items-center justify-between px-7 pt-3.5 pb-1 bg-white text-slate-900 text-xs font-semibold select-none shrink-0 z-50">
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

        <!-- App Header -->
        <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-gray-100 shrink-0">
            <div class="px-4 py-2.5">
                <div class="flex items-center justify-between gap-2">

                    <!-- Logo -->
                    <a href="<?= base_url('/') ?>" class="flex items-center gap-1.5 shrink-0 group">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white font-black text-sm shadow-sm group-hover:scale-105 transition-transform">
                            B
                        </div>
                        <span class="text-lg font-black tracking-tight text-emerald-600">bekasin</span>
                    </a>

                    <!-- Header Search Bar -->
                    <div class="flex-1 relative">
                        <form action="<?= base_url('products') ?>" method="GET" class="relative">
                            <i data-lucide="search" class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input 
                                type="text" 
                                id="header-search-input"
                                name="q" 
                                value="<?= esc(request()->getGet('q') ?? '') ?>"
                                placeholder="Cari barang..." 
                                class="w-full pl-8 pr-3 py-1.5 text-xs bg-gray-100 rounded-full border border-gray-200/80 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                                autocomplete="off"
                            >
                        </form>
                        <!-- Live Search Dropdown -->
                        <div id="live-search-dropdown" class="hidden absolute top-full left-0 right-0 mt-1.5 bg-white rounded-xl shadow-xl border border-gray-200/80 overflow-hidden z-50">
                            <div id="live-search-results" class="max-h-60 overflow-y-auto divide-y divide-gray-100 text-xs"></div>
                        </div>
                    </div>

                    <!-- Header Actions -->
                    <div class="flex items-center gap-1 shrink-0">
                        <?php if (session()->get('is_logged_in')): ?>
                            <?php if (session()->get('role') === 'admin'): ?>
                                <a href="<?= base_url('admin') ?>" class="p-1.5 text-purple-600 hover:bg-purple-50 rounded-full" title="Admin Panel">
                                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                                </a>
                            <?php endif; ?>
                            <a href="<?= base_url('notifications') ?>" class="p-1.5 text-gray-600 hover:text-emerald-600 hover:bg-gray-50 rounded-full relative" title="Notifikasi">
                                <i data-lucide="bell" class="w-4 h-4"></i>
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('login') ?>" class="px-2.5 py-1 text-xs font-semibold text-emerald-600 hover:bg-emerald-50 rounded-full transition-colors">
                                Masuk
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Scrollable App Screen -->
        <div class="flex-1 overflow-y-auto overflow-x-hidden mobile-scroll bg-gray-50 flex flex-col relative pb-20">

            <!-- Flash Messages -->
            <div class="px-4 pt-3 shrink-0">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="flex items-center gap-2 p-2.5 mb-2 text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl shadow-xs">
                        <i data-lucide="check-circle-2" class="w-4 h-4 shrink-0 text-emerald-600"></i>
                        <span><?= session()->getFlashdata('success') ?></span>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="flex items-center gap-2 p-2.5 mb-2 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-xl shadow-xs">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-red-600"></i>
                        <span><?= session()->getFlashdata('error') ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Page Content -->
            <main class="flex-1 px-4 py-2">
                <?= $this->renderSection('content') ?>
            </main>

            <!-- Compact Mobile Footer -->
            <footer class="bg-white border-t border-gray-200/80 mt-8 py-6 px-4 text-center">
                <div class="flex items-center justify-center gap-1 text-emerald-600 font-bold text-sm mb-1">
                    <span>bekasin</span>
                </div>
                <p class="text-[11px] text-gray-500 mb-3">Marketplace Barang Bekas Terpercaya</p>
                <div class="flex items-center justify-center gap-3 text-[11px] text-gray-500 mb-3">
                    <a href="<?= base_url('products') ?>" class="hover:text-emerald-600">Katalog</a>
                    <span>•</span>
                    <a href="<?= base_url('categories') ?>" class="hover:text-emerald-600">Kategori</a>
                    <span>•</span>
                    <a href="<?= base_url('sell/products/create') ?>" class="hover:text-emerald-600">Jual Barang</a>
                </div>
                <div class="text-[10px] text-gray-400">
                    &copy; <?= date('Y') ?> Bekasin. All rights reserved.
                </div>
            </footer>
        </div>

        <!-- Mobile Bottom App Bar -->
        <nav class="absolute bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-gray-200/80 px-3 pt-1.5 pb-2 flex items-center justify-around shadow-lg">
            <a href="<?= base_url('/') ?>" class="flex flex-col items-center gap-0.5 text-gray-500 hover:text-emerald-600 text-[10px] font-medium transition-colors">
                <i data-lucide="home" class="w-4 h-4"></i>
                <span>Beranda</span>
            </a>
            <a href="<?= base_url('products') ?>" class="flex flex-col items-center gap-0.5 text-gray-500 hover:text-emerald-600 text-[10px] font-medium transition-colors">
                <i data-lucide="grid-2x2" class="w-4 h-4"></i>
                <span>Katalog</span>
            </a>

            <!-- Center Action (Jual / Admin) -->
            <?php if (session()->get('role') === 'admin'): ?>
                <a href="<?= base_url('admin') ?>" class="flex flex-col items-center gap-0.5 text-purple-600 text-[10px] font-bold -mt-4 group">
                    <div class="w-11 h-11 rounded-full bg-purple-600 text-white flex items-center justify-center shadow-lg shadow-purple-600/30 group-hover:scale-105 transition-transform">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <span>Admin</span>
                </a>
            <?php else: ?>
                <a href="<?= base_url('sell/products/create') ?>" class="flex flex-col items-center gap-0.5 text-emerald-600 text-[10px] font-bold -mt-4 group">
                    <div class="w-11 h-11 rounded-full bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center shadow-lg shadow-emerald-600/30 group-hover:scale-105 transition-transform">
                        <i data-lucide="plus" class="w-5 h-5"></i>
                    </div>
                    <span>Jual</span>
                </a>
            <?php endif; ?>

            <a href="<?= base_url('messages') ?>" class="flex flex-col items-center gap-0.5 text-gray-500 hover:text-emerald-600 text-[10px] font-medium transition-colors">
                <i data-lucide="message-square" class="w-4 h-4"></i>
                <span>Chat</span>
            </a>
            <a href="<?= base_url(session()->get('is_logged_in') ? 'profile' : 'login') ?>" class="flex flex-col items-center gap-0.5 text-gray-500 hover:text-emerald-600 text-[10px] font-medium transition-colors">
                <i data-lucide="user" class="w-4 h-4"></i>
                <span><?= session()->get('is_logged_in') ? 'Saya' : 'Masuk' ?></span>
            </a>
        </nav>

        <!-- Simulated Home Gesture Indicator Bar (Bottom) -->
        <div class="hidden sm:block absolute bottom-0.5 left-1/2 -translate-x-1/2 z-50 pointer-events-none">
            <div class="w-32 h-1 bg-slate-900/30 rounded-full"></div>
        </div>
    </div>

    <!-- Scripts -->
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

            // Live Search
            const searchInput = document.getElementById('header-search-input');
            const searchDropdown = document.getElementById('live-search-dropdown');
            const searchResults = document.getElementById('live-search-results');
            let debounceTimer;

            if (searchInput && searchDropdown && searchResults) {
                searchInput.addEventListener('input', (e) => {
                    clearTimeout(debounceTimer);
                    const query = e.target.value.trim();
                    if (query.length < 2) { searchDropdown.classList.add('hidden'); return; }

                    debounceTimer = setTimeout(() => {
                        fetch(`<?= base_url('api/search/live') ?>?q=${encodeURIComponent(query)}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data.status === 'success' && data.results.length > 0) {
                                    searchResults.innerHTML = data.results.map(item => `
                                        <a href="${item.url}" class="flex items-center gap-2.5 p-2 hover:bg-emerald-50/60 transition-colors">
                                            <img src="${item.image || '<?= base_url('assets/images/placeholder.png') ?>'}" class="w-9 h-9 rounded-lg object-cover bg-gray-100 shrink-0">
                                            <div class="flex-1 min-w-0">
                                                <div class="text-xs font-semibold text-gray-800 truncate">${item.title}</div>
                                                <div class="text-[11px] text-emerald-600 font-bold">${item.price_formatted}</div>
                                            </div>
                                        </a>
                                    `).join('');
                                    searchDropdown.classList.remove('hidden');
                                } else {
                                    searchResults.innerHTML = `<div class="p-3 text-center text-xs text-gray-400">Tidak ditemukan untuk "${query}"</div>`;
                                    searchDropdown.classList.remove('hidden');
                                }
                            })
                            .catch(() => searchDropdown.classList.add('hidden'));
                    }, 300);
                });

                document.addEventListener('click', (e) => {
                    if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
                        searchDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>
