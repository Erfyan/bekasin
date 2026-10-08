<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ==========================================
// 1. RUTE PUBLIK (Dapat diakses siapa saja)
// ==========================================
$routes->get('/', 'Home::index');
$routes->get('/products', 'Product::index');
$routes->get('/products/(:segment)', 'Product::show/$1');
$routes->get('/categories', 'Home::categories');
$routes->get('/categories/(:segment)', 'Product::category/$1');
$routes->get('/search', 'Search::index');
$routes->get('/api/search/live', 'Search::live');
$routes->get('/sellers/(:segment)', 'Profile::seller/$1');

// ==========================================
// 2. RUTE AUTENTIKASI (Hanya untuk tamu/guest)
// ==========================================
$routes->group('', ['filter' => 'guest'], static function ($routes) {
    $routes->get('/login', 'Auth::login');
    $routes->post('/login', 'Auth::attemptLogin');
    $routes->get('/register', 'Auth::register');
    $routes->post('/register', 'Auth::attemptRegister');
});

$routes->post('/logout', 'Auth::logout', ['filter' => 'auth']);

// ==========================================
// 3. RUTE MEMBER (Wajib Login - Buyer & Seller)
// ==========================================
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Profil & Dashboard Pengguna
    $routes->get('/profile', 'Profile::index');
    $routes->get('/profile/settings', 'Profile::settings');
    $routes->post('/profile/update', 'Profile::update');
    $routes->post('/profile/password', 'Profile::updatePassword');

    // Kelola Jual Barang (Seller)
    $routes->get('/sell/products', 'Product::myProducts');
    $routes->get('/sell/products/create', 'Product::create');
    $routes->post('/sell/products', 'Product::store');
    $routes->get('/sell/products/(:num)/edit', 'Product::edit/$1');
    $routes->post('/sell/products/(:num)/update', 'Product::update/$1');
    $routes->post('/sell/products/(:num)/status', 'Product::changeStatus/$1');
    $routes->post('/sell/products/(:num)/delete', 'Product::delete/$1');

    // Wishlist / Favorit
    $routes->get('/wishlist', 'Wishlist::index');
    $routes->post('/api/wishlist/toggle/(:num)', 'Wishlist::toggle/$1');

    // Nego & Penawaran (Offers)
    $routes->get('/offers', 'Offer::index');
    $routes->post('/products/(:num)/offer', 'Offer::store/$1');
    $routes->post('/offers/(:num)/accept', 'Offer::accept/$1');
    $routes->post('/offers/(:num)/reject', 'Offer::reject/$1');
    $routes->post('/offers/(:num)/counter', 'Offer::counter/$1');

    // Percakapan & Pesan Pribadi (Chat)
    $routes->get('/messages', 'Message::index');
    $routes->get('/messages/(:num)', 'Message::show/$1');
    $routes->post('/messages/start/(:num)', 'Message::startForProduct/$1');
    $routes->post('/messages/(:num)/send', 'Message::send/$1');
    $routes->get('/api/messages/(:num)/poll', 'Message::poll/$1');

    // Transaksi & Kesepakatan
    $routes->get('/transactions', 'Transaction::index');
    $routes->get('/transactions/(:num)', 'Transaction::show/$1');
    $routes->post('/transactions/(:num)/confirm', 'Transaction::confirm/$1');
    $routes->post('/transactions/(:num)/payment-proof', 'Transaction::uploadProof/$1');
    $routes->post('/transactions/(:num)/complete', 'Transaction::complete/$1');
    $routes->post('/transactions/(:num)/cancel', 'Transaction::cancel/$1');
    $routes->post('/transactions/(:num)/review', 'Review::store/$1');

    // Laporan & Aduan (Report)
    $routes->post('/reports', 'Report::store');

    // Notifikasi Internal
    $routes->get('/notifications', 'Notification::index');
    $routes->post('/api/notifications/mark-read', 'Notification::markRead');
    $routes->get('/api/notifications/unread-count', 'Notification::unreadCount');
});

// ==========================================
// 4. RUTE ADMINISTRATOR (Wajib Role Admin)
// ==========================================
$routes->group('admin', ['filter' => 'admin'], static function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('users', 'Admin\Users::index');
    $routes->post('users/(:num)/status', 'Admin\Users::changeStatus/$1');
    $routes->get('products', 'Admin\Products::index');
    $routes->post('products/(:num)/moderate', 'Admin\Products::moderate/$1');
    $routes->get('categories', 'Admin\Categories::index');
    $routes->post('categories/store', 'Admin\Categories::store');
    $routes->post('categories/(:num)/update', 'Admin\Categories::update/$1');
    $routes->get('reports', 'Admin\Reports::index');
    $routes->post('reports/(:num)/resolve', 'Admin\Reports::resolve/$1');
    $routes->get('transactions', 'Admin\Transactions::index');
    $routes->get('audit-logs', 'Admin\Settings::auditLogs');
});
