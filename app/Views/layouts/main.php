<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Bekasin — Marketplace Barang Bekas') ?></title>
    <meta name="description" content="<?= esc($metaDescription ?? 'Jual beli barang bekas berkualitas, aman, dan mudah. Barang Lama, Manfaat Baru.') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen bg-gray-50 text-gray-800 flex flex-col font-sans antialiased selection:bg-emerald-500 selection:text-white" style="font-family: 'Inter', sans-serif;">

    <!-- Top Announcement Bar (Desktop/Tablet) -->
    <div class="hidden sm:block bg-slate-900 text-slate-300 text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Bekasin</span>
                <span>Marketplace jual beli barang bekas berkualitas & terpercaya #1 di Indonesia</span>
            </div>
            <div class="flex items-center gap-4 text-slate-400">
                <a href="<?= base_url('categories') ?>" class="hover:text-emerald-400 transition-colors">Semua Kategori</a>
                <span>•</span>
                <a href="<?= base_url('sell/products/create') ?>" class="hover:text-emerald-400 transition-colors">Cara Menjual</a>
                <span>•</span>
                <span class="text-slate-500">COD Aman & Langsung</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-gray-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-3 sm:gap-6">

                <!-- Brand Logo -->
                <a href="<?= base_url('/') ?>" class="flex items-center gap-2 shrink-0 group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white font-black text-base shadow-md shadow-emerald-600/20 group-hover:scale-105 transition-transform">
                        B
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-black tracking-tight text-emerald-600 leading-tight">bekasin</span>
                        <span class="text-[10px] font-semibold text-gray-400 -mt-1 hidden sm:inline">marketplace</span>
                    </div>
                </a>

                <!-- Search Bar (Desktop / Tablet) -->
                <div class="flex-1 max-w-2xl relative hidden md:block">
                    <form action="<?= base_url('products') ?>" method="GET" class="relative">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input 
                            type="text" 
                            id="header-search-input"
                            name="q" 
                            value="<?= esc(request()->getGet('q') ?? '') ?>"
                            placeholder="Cari barang bekas (MacBook, iPhone, Kamera, Sepatu, dll)..." 
                            class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-100/80 rounded-full border border-gray-200 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                            autocomplete="off"
                        >
                    </form>
                    <!-- Live Search Dropdown -->
                    <div id="live-search-dropdown" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50">
                        <div id="live-search-results" class="max-h-80 overflow-y-auto divide-y divide-gray-100 text-sm"></div>
                    </div>
                </div>

                <!-- Navigation Actions -->
                <nav class="flex items-center gap-1.5 sm:gap-3">
                    
                    <!-- Desktop Explore Links -->
                    <div class="hidden lg:flex items-center gap-1 text-sm font-semibold text-gray-600 mr-2">
                        <a href="<?= base_url('products') ?>" class="px-3 py-2 rounded-lg hover:text-emerald-600 hover:bg-emerald-50/60 transition-colors">Katalog</a>
                        <a href="<?= base_url('categories') ?>" class="px-3 py-2 rounded-lg hover:text-emerald-600 hover:bg-emerald-50/60 transition-colors">Kategori</a>
                    </div>

                    <?php if (session()->get('is_logged_in')): ?>
                        <?php if (session()->get('role') === 'admin' || strtolower((string)session()->get('email')) === 'admin@bekasin.com'): ?>
                            <a href="<?= base_url('admin') ?>" class="px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl flex items-center gap-1.5 shadow-sm transition-all" title="Buka Admin Panel">
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                                <span class="hidden sm:inline">Admin Panel</span>
                            </a>
                        <?php endif; ?>

                        <!-- Quick Icons (Desktop/Tablet) -->
                        <div class="hidden sm:flex items-center gap-1">
                            <a href="<?= base_url('wishlist') ?>" class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/60 rounded-xl transition-colors" title="Favorit Saya">
                                <i data-lucide="heart" class="w-5 h-5"></i>
                            </a>
                            <a href="<?= base_url('messages') ?>" class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/60 rounded-xl transition-colors" title="Pesan Chat">
                                <i data-lucide="message-square" class="w-5 h-5"></i>
                            </a>
                            <a href="<?= base_url('notifications') ?>" class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/60 rounded-xl transition-colors relative" title="Notifikasi">
                                <i data-lucide="bell" class="w-5 h-5"></i>
                            </a>
                        </div>

                        <!-- User Profile Link -->
                        <a href="<?= base_url('profile') ?>" class="flex items-center gap-2 p-1.5 sm:px-3 sm:py-1.5 text-gray-700 hover:text-emerald-600 hover:bg-gray-100/80 rounded-xl transition-all">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold ring-2 ring-emerald-500/20 overflow-hidden shrink-0">
                                <?php if (session()->get('avatar_url')): ?>
                                    <img src="<?= esc(session()->get('avatar_url')) ?>" alt="Avatar" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <?= strtoupper(substr(session()->get('full_name') ?? 'U', 0, 1)) ?>
                                <?php endif; ?>
                            </div>
                            <span class="text-sm font-semibold hidden md:inline max-w-[120px] truncate"><?= esc(session()->get('full_name')) ?></span>
                        </a>

                        <a href="<?= base_url('logout') ?>" class="hidden sm:flex p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors" title="Keluar / Logout">
                            <i data-lucide="log-out" class="w-5 h-5"></i>
                        </a>

                        <!-- Jual Button (Desktop) -->
                        <a href="<?= base_url('sell/products/create') ?>" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-sm font-bold rounded-xl shadow-md shadow-emerald-600/20 hover:shadow-lg transition-all ml-1">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            <span>Jual Barang</span>
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('login') ?>" class="px-3 sm:px-4 py-2 text-sm font-semibold text-gray-700 hover:text-emerald-600 hover:bg-gray-100 rounded-xl transition-colors">
                            Masuk
                        </a>
                        <a href="<?= base_url('register') ?>" class="px-3.5 sm:px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-sm hover:shadow-md transition-all">
                            Daftar
                        </a>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- Mobile Search Bar (Visible on Mobile Screens) -->
            <div class="pb-3 md:hidden">
                <form action="<?= base_url('products') ?>" method="GET" class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input 
                        type="text" 
                        name="q" 
                        value="<?= esc(request()->getGet('q') ?? '') ?>"
                        placeholder="Cari barang bekas di Bekasin..." 
                        class="w-full pl-9 pr-3 py-2 text-xs bg-gray-100 rounded-full border border-gray-200 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                    >
                </form>
            </div>
        </div>
    </header>

    <!-- Flash Messages Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="flex items-center gap-3 p-3.5 mb-3 text-sm text-emerald-800 bg-emerald-50 border border-emerald-200/80 rounded-2xl shadow-xs">
                <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0 text-emerald-600"></i>
                <span class="font-medium"><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="flex items-center gap-3 p-3.5 mb-3 text-sm text-red-800 bg-red-50 border border-red-200/80 rounded-2xl shadow-xs">
                <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 text-red-600"></i>
                <span class="font-medium"><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 sm:pb-12">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Full Responsive Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto hidden sm:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white font-black text-sm">
                            B
                        </div>
                        <span class="text-xl font-black text-emerald-600">bekasin</span>
                    </div>
                    <p class="text-xs text-gray-500 leading-relaxed mb-4">
                        Marketplace barang bekas terpercaya. Jual beli barang preloved mudah, aman, dan langsung antar pengguna di seluruh Indonesia.
                    </p>
                    <div class="flex items-center gap-2 text-xs text-emerald-700 font-semibold bg-emerald-50 py-1.5 px-3 rounded-lg w-fit">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                        <span>100% Transaksi Langsung & Aman</span>
                    </div>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-3">Jelajahi</h4>
                    <ul class="space-y-2 text-xs text-gray-600">
                        <li><a href="<?= base_url('products') ?>" class="hover:text-emerald-600 transition-colors">Semua Barang Bekas</a></li>
                        <li><a href="<?= base_url('categories') ?>" class="hover:text-emerald-600 transition-colors">Kategori Produk</a></li>
                        <li><a href="<?= base_url('products?sort=popular') ?>" class="hover:text-emerald-600 transition-colors">Produk Populer</a></li>
                        <li><a href="<?= base_url('products?sort=latest') ?>" class="hover:text-emerald-600 transition-colors">Barang Baru Masuk</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-3">Pusat Bantuan</h4>
                    <ul class="space-y-2 text-xs text-gray-600">
                        <li><a href="<?= base_url('sell/products/create') ?>" class="hover:text-emerald-600 transition-colors">Panduan Menjual Barang</a></li>
                        <li><a href="<?= base_url('register') ?>" class="hover:text-emerald-600 transition-colors">Daftar Akun Baru</a></li>
                        <li><a href="<?= base_url('login') ?>" class="hover:text-emerald-600 transition-colors">Masuk ke Akun</a></li>
                        <li><a href="<?= base_url('messages') ?>" class="hover:text-emerald-600 transition-colors">Pusat Pesan & Chat</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-3">Mulai Jual</h4>
                    <p class="text-xs text-gray-500 mb-3">Punya barang tidak terpakai di rumah? Ubah jadi uang tunai sekarang juga!</p>
                    <a href="<?= base_url('sell/products/create') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Pasang Iklan Gratis</span>
                    </a>
                </div>
            </div>
            <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-400">
                <div>&copy; <?= date('Y') ?> Bekasin Indonesia. Hak Cipta Dilindungi.</div>
                <div class="flex gap-4">
                    <span class="hover:text-gray-600 cursor-pointer">Syarat & Ketentuan</span>
                    <span>•</span>
                    <span class="hover:text-gray-600 cursor-pointer">Kebijakan Privasi</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Navigation Bar (Visible ONLY on Mobile < 640px) -->
    <nav class="sm:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-gray-200/80 px-2 pt-2 pb-3 flex items-center justify-around shadow-[0_-4px_20px_rgba(0,0,0,0.06)]">
        <a href="<?= base_url('/') ?>" class="flex flex-col items-center gap-1 text-gray-500 hover:text-emerald-600 text-[11px] font-medium transition-colors">
            <i data-lucide="home" class="w-5 h-5"></i>
            <span>Beranda</span>
        </a>
        <a href="<?= base_url('products') ?>" class="flex flex-col items-center gap-1 text-gray-500 hover:text-emerald-600 text-[11px] font-medium transition-colors">
            <i data-lucide="grid-2x2" class="w-5 h-5"></i>
            <span>Katalog</span>
        </a>

        <!-- Center Floating Action (Jual / Admin) -->
        <?php if (session()->get('role') === 'admin' || strtolower((string)session()->get('email')) === 'admin@bekasin.com'): ?>
            <a href="<?= base_url('admin') ?>" class="flex flex-col items-center gap-0.5 text-purple-600 text-[11px] font-bold -mt-5 group">
                <div class="w-12 h-12 rounded-full bg-purple-600 text-white flex items-center justify-center shadow-lg shadow-purple-600/30 group-hover:scale-105 transition-transform ring-4 ring-white">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
                <span>Admin</span>
            </a>
        <?php else: ?>
            <a href="<?= base_url('sell/products/create') ?>" class="flex flex-col items-center gap-0.5 text-emerald-600 text-[11px] font-bold -mt-5 group">
                <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center shadow-lg shadow-emerald-600/30 group-hover:scale-105 transition-transform ring-4 ring-white">
                    <i data-lucide="plus" class="w-6 h-6"></i>
                </div>
                <span>Jual</span>
            </a>
        <?php endif; ?>

        <a href="<?= base_url('messages') ?>" class="flex flex-col items-center gap-1 text-gray-500 hover:text-emerald-600 text-[11px] font-medium transition-colors">
            <i data-lucide="message-square" class="w-5 h-5"></i>
            <span>Chat</span>
        </a>
        <a href="<?= base_url(session()->get('is_logged_in') ? 'profile' : 'login') ?>" class="flex flex-col items-center gap-1 text-gray-500 hover:text-emerald-600 text-[11px] font-medium transition-colors">
            <i data-lucide="user" class="w-5 h-5"></i>
            <span><?= session()->get('is_logged_in') ? 'Saya' : 'Masuk' ?></span>
        </a>
    </nav>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) lucide.createIcons();

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
                                        <a href="${item.url}" class="flex items-center gap-3 p-3 hover:bg-emerald-50/60 transition-colors">
                                            <img src="${item.image || '<?= base_url('assets/images/placeholder.png') ?>'}" class="w-10 h-10 rounded-lg object-cover bg-gray-100 shrink-0">
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm font-semibold text-gray-800 truncate">${item.title}</div>
                                                <div class="text-xs text-emerald-600 font-bold">${item.price_formatted}</div>
                                            </div>
                                        </a>
                                    `).join('');
                                    searchDropdown.classList.remove('hidden');
                                } else {
                                    searchResults.innerHTML = `<div class="p-4 text-center text-xs text-gray-400">Tidak ada produk ditemukan untuk "${query}"</div>`;
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
