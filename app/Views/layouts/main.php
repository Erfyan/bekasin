<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Bekasin — Marketplace Barang Bekas') ?></title>
    <meta name="description" content="<?= esc($metaDescription ?? 'Jual beli barang bekas berkualitas, aman, dan mudah. Barang Lama, Manfaat Baru.') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <script defer src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen bg-gray-50 text-gray-800 flex flex-col" style="font-family: 'Inter', sans-serif;">

    <!-- Header -->
    <header class="sticky top-0 z-40 bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-14">

                <!-- Logo (text only, simple) -->
                <a href="<?= base_url('/') ?>" class="flex items-center gap-1.5">
                    <span class="text-lg font-bold text-emerald-600">bekasin</span>
                </a>

                <!-- Search -->
                <div class="flex-1 max-w-md mx-4 hidden sm:block">
                    <form action="<?= base_url('products') ?>" method="GET" class="relative">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input 
                            type="text" 
                            id="header-search-input"
                            name="q" 
                            value="<?= esc(request()->getGet('q') ?? '') ?>"
                            placeholder="Cari barang bekas..." 
                            class="w-full pl-9 pr-3 py-2 text-sm bg-gray-100 rounded-lg border border-gray-200 focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none"
                            autocomplete="off"
                        >
                    </form>
                    <!-- Live Search Dropdown -->
                    <div id="live-search-dropdown" class="hidden absolute top-full mt-1 w-full bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden z-50">
                        <div id="live-search-results" class="max-h-72 overflow-y-auto divide-y divide-gray-100"></div>
                    </div>
                </div>

                <!-- Nav Actions -->
                <nav class="flex items-center gap-1">
                    <a href="<?= base_url('products') ?>" class="hidden md:flex items-center gap-1 px-3 py-1.5 text-sm text-gray-600 hover:text-emerald-600 rounded-md">
                        Jelajahi
                    </a>

                    <?php if (session()->get('is_logged_in')): ?>
                        <?php if (session()->get('role') === 'admin'): ?>
                            <a href="<?= base_url('admin') ?>" class="px-3 py-1.5 text-xs bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg flex items-center gap-1.5 shadow-xs transition-all" title="Buka Admin Panel">
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                                <span>Admin Panel</span>
                            </a>
                        <?php endif; ?>

                        <a href="<?= base_url('wishlist') ?>" class="p-2 text-gray-500 hover:text-emerald-600 rounded-md" title="Favorit">
                            <i data-lucide="heart" class="w-5 h-5"></i>
                        </a>
                        <a href="<?= base_url('messages') ?>" class="p-2 text-gray-500 hover:text-emerald-600 rounded-md" title="Pesan">
                            <i data-lucide="message-square" class="w-5 h-5"></i>
                        </a>
                        <a href="<?= base_url('notifications') ?>" class="p-2 text-gray-500 hover:text-emerald-600 rounded-md" title="Notifikasi">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                        </a>
                        <a href="<?= base_url('profile') ?>" class="p-2 text-gray-500 hover:text-emerald-600 rounded-md" title="Profil">
                            <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-semibold">
                                <?php if (session()->get('avatar_url')): ?>
                                    <img src="<?= esc(session()->get('avatar_url')) ?>" alt="Avatar" class="w-full h-full rounded-full object-cover">
                                <?php else: ?>
                                    <?= strtoupper(substr(session()->get('full_name') ?? 'U', 0, 1)) ?>
                                <?php endif; ?>
                            </div>
                        </a>
                        <a href="<?= base_url('logout') ?>" class="p-2 text-gray-500 hover:text-red-600 rounded-md" title="Keluar / Logout">
                            <i data-lucide="log-out" class="w-5 h-5"></i>
                        </a>
                        <a href="<?= base_url('sell/products/create') ?>" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg ml-1">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Jual
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('login') ?>" class="px-3 py-1.5 text-sm text-gray-600 hover:text-emerald-600">Masuk</a>
                        <a href="<?= base_url('register') ?>" class="px-3 py-1.5 text-sm bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg">Daftar</a>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- Mobile Search -->
            <div class="pb-2 sm:hidden">
                <form action="<?= base_url('products') ?>" method="GET" class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input 
                        type="text" 
                        name="q" 
                        value="<?= esc(request()->getGet('q') ?? '') ?>"
                        placeholder="Cari barang bekas..." 
                        class="w-full pl-9 pr-3 py-2 text-sm bg-gray-100 rounded-lg border border-gray-200 focus:bg-white focus:border-emerald-500 focus:outline-none"
                    >
                </form>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 mt-3 w-full">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="flex items-center gap-2 p-3 mb-3 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg">
                <i data-lucide="check-circle-2" class="w-4 h-4 shrink-0"></i>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="flex items-center gap-2 p-3 mb-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 py-5">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                <div>
                    <span class="text-base font-bold text-emerald-600">bekasin</span>
                    <p class="text-xs text-gray-500 mt-1">Marketplace barang bekas terpercaya. Jual beli mudah, aman, langsung antar pengguna.</p>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-800 uppercase mb-2">Jelajahi</h4>
                    <ul class="space-y-1 text-xs text-gray-500">
                        <li><a href="<?= base_url('products') ?>" class="hover:text-emerald-600">Semua Barang</a></li>
                        <li><a href="<?= base_url('categories') ?>" class="hover:text-emerald-600">Kategori</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-800 uppercase mb-2">Bantuan</h4>
                    <ul class="space-y-1 text-xs text-gray-500">
                        <li><a href="<?= base_url('sell/products/create') ?>" class="hover:text-emerald-600">Cara Jual Barang</a></li>
                        <li><a href="<?= base_url('register') ?>" class="hover:text-emerald-600">Daftar Akun</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-4 border-t border-gray-100 text-xs text-gray-400 text-center">
                &copy; <?= date('Y') ?> Bekasin. Hak cipta dilindungi.
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Nav -->
    <div class="sm:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 px-2 py-1.5 flex items-center justify-around">
        <a href="<?= base_url('/') ?>" class="flex flex-col items-center gap-0.5 text-gray-500 text-[10px]">
            <i data-lucide="home" class="w-5 h-5"></i>
            <span>Beranda</span>
        </a>
        <a href="<?= base_url('products') ?>" class="flex flex-col items-center gap-0.5 text-gray-500 text-[10px]">
            <i data-lucide="grid-2x2" class="w-5 h-5"></i>
            <span>Katalog</span>
        </a>
        <?php if (session()->get('role') === 'admin'): ?>
            <a href="<?= base_url('admin') ?>" class="flex flex-col items-center gap-0.5 text-purple-600 text-[10px] font-bold">
                <div class="w-10 h-10 rounded-full bg-purple-600 text-white flex items-center justify-center -mt-4 shadow-md">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </div>
                <span>Admin</span>
            </a>
        <?php else: ?>
            <a href="<?= base_url('sell/products/create') ?>" class="flex flex-col items-center gap-0.5 text-emerald-600 text-[10px] font-semibold">
                <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center -mt-4 shadow-md">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                </div>
                <span>Jual</span>
            </a>
        <?php endif; ?>
        <a href="<?= base_url('messages') ?>" class="flex flex-col items-center gap-0.5 text-gray-500 text-[10px]">
            <i data-lucide="message-square" class="w-5 h-5"></i>
            <span>Chat</span>
        </a>
        <a href="<?= base_url(session()->get('is_logged_in') ? 'profile' : 'login') ?>" class="flex flex-col items-center gap-0.5 text-gray-500 text-[10px]">
            <i data-lucide="user" class="w-5 h-5"></i>
            <span><?= session()->get('is_logged_in') ? 'Saya' : 'Masuk' ?></span>
        </a>
    </div>

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
                                        <a href="${item.url}" class="flex items-center gap-3 p-2 hover:bg-gray-50">
                                            <img src="${item.image || '<?= base_url('assets/images/placeholder.png') ?>'}" class="w-10 h-10 rounded object-cover bg-gray-100">
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm font-medium text-gray-800 truncate">${item.title}</div>
                                                <div class="text-xs text-emerald-600 font-semibold">${item.price_formatted}</div>
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
