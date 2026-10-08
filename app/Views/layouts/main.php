<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Bekasin-Aja — Marketplace Barang Bekas C2C') ?></title>
    <meta name="description" content="<?= esc($metaDescription ?? 'Jual beli barang bekas berkualitas, aman, dan mudah dari orang di sekitarmu. Barang Lama, Manfaat Baru di Bekasin-Aja.') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <script defer src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="flex flex-col min-h-full font-sans antialiased text-slate-800 selection:bg-emerald-500 selection:text-white">

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 text-white text-xs py-2 px-4 text-center font-medium shadow-sm">
        <span>✨ Bekasin-Aja: Jual beli barang bekas terpercaya, cepat, dan tanpa ribet!</span>
    </div>

    <!-- Header & Navbar Desktop/Tablet -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200/80 transition-shadow duration-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-18 gap-4">
                
                <!-- Logo Brand -->
                <a href="<?= base_url('/') ?>" class="flex items-center gap-2 group shrink-0">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white shadow-md shadow-emerald-500/20 group-hover:scale-105 transition-transform duration-200">
                        <i data-lucide="package-search" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-600 to-teal-700 bg-clip-text text-transparent">Bekasin-Aja</span>
                        <span class="hidden sm:block text-[10px] text-slate-400 font-semibold tracking-wider uppercase -mt-1">C2C Marketplace</span>
                    </div>
                </a>

                <!-- Search Bar with Live Debounced Dropdown -->
                <div class="flex-1 max-w-xl relative hidden md:block">
                    <form action="<?= base_url('products') ?>" method="GET" class="relative">
                        <div class="relative flex items-center">
                            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-4.5 pointer-events-none"></i>
                            <input 
                                type="text" 
                                id="header-search-input"
                                name="q" 
                                value="<?= esc(request()->getGet('q') ?? '') ?>"
                                placeholder="Cari barang bekas, iPhone, laptop, jaket, kamera..." 
                                class="w-full pl-11 pr-4 py-2.5 text-sm bg-slate-100 border-none rounded-full focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder:text-slate-400 font-medium"
                                autocomplete="off"
                            >
                        </div>
                    </form>
                    <!-- Live Search Popup Dropdown -->
                    <div id="live-search-dropdown" class="hidden absolute top-full mt-2 w-full bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50">
                        <div id="live-search-results" class="max-h-80 overflow-y-auto divide-y divide-slate-100 p-2"></div>
                    </div>
                </div>

                <!-- Navigation Action Buttons -->
                <nav class="flex items-center gap-2 sm:gap-3">
                    <a href="<?= base_url('products') ?>" class="hidden lg:flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold text-slate-600 hover:text-emerald-600 rounded-lg transition-colors">
                        <i data-lucide="compass" class="w-4 h-4"></i>
                        <span>Jelajahi</span>
                    </a>

                    <?php if (session()->get('is_logged_in')): ?>
                        <!-- Wishlist Button -->
                        <a href="<?= base_url('wishlist') ?>" class="relative p-2.5 text-slate-600 hover:text-emerald-600 hover:bg-slate-100 rounded-full transition-colors" title="Favorit Saya">
                            <i data-lucide="heart" class="w-5 h-5"></i>
                        </a>

                        <!-- Chat Messages -->
                        <a href="<?= base_url('messages') ?>" class="relative p-2.5 text-slate-600 hover:text-emerald-600 hover:bg-slate-100 rounded-full transition-colors" title="Pesan Chat">
                            <i data-lucide="message-square" class="w-5 h-5"></i>
                        </a>

                        <!-- Notifications -->
                        <a href="<?= base_url('notifications') ?>" class="relative p-2.5 text-slate-600 hover:text-emerald-600 hover:bg-slate-100 rounded-full transition-colors" title="Notifikasi">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                        </a>

                        <!-- User Profile Dropdown / Avatar -->
                        <div class="relative group ml-1">
                            <a href="<?= base_url('profile') ?>" class="flex items-center gap-2 p-1.5 rounded-full hover:bg-slate-100 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm overflow-hidden border border-emerald-200">
                                    <?php if (session()->get('avatar_url')): ?>
                                        <img src="<?= esc(session()->get('avatar_url')) ?>" alt="Avatar" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <?= strtoupper(substr(session()->get('full_name') ?? 'U', 0, 1)) ?>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </div>

                        <!-- CTA Jual Barang -->
                        <a href="<?= base_url('sell/products/create') ?>" class="hidden sm:inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-md shadow-emerald-600/20 hover:shadow-lg hover:shadow-emerald-600/30 active:scale-98 transition-all">
                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                            <span>Jual Barang</span>
                        </a>

                    <?php else: ?>
                        <a href="<?= base_url('login') ?>" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-emerald-600 transition-colors">
                            Masuk
                        </a>
                        <a href="<?= base_url('register') ?>" class="px-4 py-2 text-sm font-semibold bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl transition-colors">
                            Daftar
                        </a>
                        <a href="<?= base_url('login') ?>" class="hidden sm:inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-md shadow-emerald-600/20 transition-all">
                            <i data-lucide="tag" class="w-4 h-4"></i>
                            <span>Jual Barang</span>
                        </a>
                    <?php endif; ?>

                </nav>
            </div>

            <!-- Mobile Search Bar (Visible on small screens) -->
            <div class="pb-3 md:hidden">
                <form action="<?= base_url('products') ?>" method="GET" class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input 
                        type="text" 
                        name="q" 
                        value="<?= esc(request()->getGet('q') ?? '') ?>"
                        placeholder="Cari barang bekas di sekitarmu..." 
                        class="w-full pl-10 pr-4 py-2 text-sm bg-slate-100 border-none rounded-full focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                    >
                </form>
            </div>
        </div>
    </header>

    <!-- Flash Messages (Toast Alerts) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="flex items-center gap-3 p-4 mb-4 text-sm text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-2xl shadow-xs">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                <div class="font-medium"><?= session()->getFlashdata('success') ?></div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="flex items-center gap-3 p-4 mb-4 text-sm text-rose-800 bg-rose-50 border border-rose-200 rounded-2xl shadow-xs">
                <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600 shrink-0"></i>
                <div class="font-medium"><?= session()->getFlashdata('error') ?></div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Dynamic Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Modern Marketplace Footer -->
    <footer class="bg-white border-t border-slate-200 mt-16 pt-12 pb-24 md:pb-12 text-slate-600 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-10 border-b border-slate-100">
                
                <!-- Brand Info -->
                <div class="space-y-3 md:col-span-1">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white">
                            <i data-lucide="package-search" class="w-4 h-4"></i>
                        </div>
                        <span class="text-lg font-bold text-slate-900">Bekasin-Aja</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Platform marketplace C2C barang bekas terpercaya. Jual cepat barang yang tidak terpakai, temukan barang impian dengan harga terbaik.
                    </p>
                    <p class="text-xs font-semibold text-emerald-700">“Barang Lama, Manfaat Baru”</p>
                </div>

                <!-- Nav Links -->
                <div>
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Jelajahi</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="<?= base_url('products') ?>" class="hover:text-emerald-600 transition-colors">Semua Barang</a></li>
                        <li><a href="<?= base_url('categories') ?>" class="hover:text-emerald-600 transition-colors">Kategori Pilihan</a></li>
                        <li><a href="<?= base_url('products?condition=like_new') ?>" class="hover:text-emerald-600 transition-colors">Kondisi Seperti Baru</a></li>
                    </ul>
                </div>

                <!-- Jual & Transaksi -->
                <div>
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Panduan & Bantuan</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="<?= base_url('sell/products/create') ?>" class="hover:text-emerald-600 transition-colors">Cara Jual Barang</a></li>
                        <li><a href="<?= base_url('login') ?>" class="hover:text-emerald-600 transition-colors">Tips Transaksi Aman (COD)</a></li>
                        <li><a href="<?= base_url('register') ?>" class="hover:text-emerald-600 transition-colors">Daftar Akun Gratis</a></li>
                    </ul>
                </div>

                <!-- Security & Transparency -->
                <div>
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Keamanan & Privasi</h4>
                    <p class="text-xs text-slate-500 leading-relaxed mb-3">
                        Setiap transaksi dilindungi sistem konfirmasi dua arah dan ulasan transparan dari sesama pembeli dan penjual.
                    </p>
                    <div class="flex items-center gap-2 text-xs text-emerald-700 font-semibold">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                        <span>100% C2C Komunitas Indonesia</span>
                    </div>
                </div>

            </div>

            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                <p>&copy; <?= date('Y') ?> Bekasin-Aja.com. Hak cipta dilindungi undang-undang.</p>
                <div class="flex items-center gap-4">
                    <span>CodeIgniter 4 MVC</span>
                    <span>•</span>
                    <span>Tailwind CSS</span>
                    <span>•</span>
                    <span>Supabase PostgreSQL</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Navigation Bar (Sticky for smooth mobile experience) -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur border-t border-slate-200 px-4 py-2 flex items-center justify-around shadow-lg">
        <a href="<?= base_url('/') ?>" class="flex flex-col items-center gap-1 text-slate-600 hover:text-emerald-600 text-[10px] font-medium">
            <i data-lucide="home" class="w-5 h-5"></i>
            <span>Beranda</span>
        </a>
        <a href="<?= base_url('products') ?>" class="flex flex-col items-center gap-1 text-slate-600 hover:text-emerald-600 text-[10px] font-medium">
            <i data-lucide="grid" class="w-5 h-5"></i>
            <span>Katalog</span>
        </a>
        <a href="<?= base_url('sell/products/create') ?>" class="flex flex-col items-center gap-1 text-emerald-600 font-bold text-[10px] -mt-5">
            <div class="w-12 h-12 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-lg shadow-emerald-600/40">
                <i data-lucide="plus" class="w-6 h-6"></i>
            </div>
            <span>Jual</span>
        </a>
        <a href="<?= base_url('messages') ?>" class="flex flex-col items-center gap-1 text-slate-600 hover:text-emerald-600 text-[10px] font-medium">
            <i data-lucide="message-square" class="w-5 h-5"></i>
            <span>Chat</span>
        </a>
        <a href="<?= base_url(session()->get('is_logged_in') ? 'profile' : 'login') ?>" class="flex flex-col items-center gap-1 text-slate-600 hover:text-emerald-600 text-[10px] font-medium">
            <i data-lucide="user" class="w-5 h-5"></i>
            <span><?= session()->get('is_logged_in') ? 'Saya' : 'Masuk' ?></span>
        </a>
    </div>

    <!-- Live Search & Lucide Initialization Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                lucide.createIcons();
            }

            // Live search debounce handler
            const searchInput = document.getElementById('header-search-input');
            const searchDropdown = document.getElementById('live-search-dropdown');
            const searchResults = document.getElementById('live-search-results');
            let debounceTimer;

            if (searchInput && searchDropdown && searchResults) {
                searchInput.addEventListener('input', (e) => {
                    clearTimeout(debounceTimer);
                    const query = e.target.value.trim();

                    if (query.length < 2) {
                        searchDropdown.classList.add('hidden');
                        return;
                    }

                    debounceTimer = setTimeout(() => {
                        fetch(`<?= base_url('api/search/live') ?>?q=${encodeURIComponent(query)}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data.status === 'success' && data.results.length > 0) {
                                    searchResults.innerHTML = data.results.map(item => `
                                        <a href="${item.url}" class="flex items-center gap-3 p-2.5 hover:bg-slate-50 rounded-xl transition-colors">
                                            <img src="${item.image || '<?= base_url('assets/images/placeholder.png') ?>'}" class="w-10 h-10 rounded-lg object-cover bg-slate-100 shrink-0">
                                            <div class="flex-1 min-w-0">
                                                <div class="text-xs font-bold text-slate-800 truncate">${item.title}</div>
                                                <div class="text-[11px] text-emerald-600 font-semibold">${item.price_formatted}</div>
                                            </div>
                                            <span class="text-[10px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">${item.city}</span>
                                        </a>
                                    `).join('');
                                    searchDropdown.classList.remove('hidden');
                                } else {
                                    searchResults.innerHTML = `<div class="p-4 text-center text-xs text-slate-400">Tidak ada barang ditemukan untuk "${query}"</div>`;
                                    searchDropdown.classList.remove('hidden');
                                }
                            })
                            .catch(() => {
                                searchDropdown.classList.add('hidden');
                            });
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
