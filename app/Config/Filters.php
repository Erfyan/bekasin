<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;
use App\Filters\AuthFilter;
use App\Filters\AdminFilter;
use App\Filters\GuestFilter;
use App\Filters\SecurityHeaders;

class Filters extends BaseFilters
{
    /**
     * Konfigurasi alias filter untuk mempermudah pemanggilan pada rute
     *
     * @var array<string, class-string|list<class-string>>
     */
    public array $aliases = [
        'csrf'             => CSRF::class,
        'toolbar'          => DebugToolbar::class,
        'honeypot'         => Honeypot::class,
        'invalidchars'     => InvalidChars::class,
        'secureheaders'    => SecureHeaders::class,
        'cors'             => Cors::class,
        'forcehttps'       => ForceHTTPS::class,
        'pagecache'        => PageCache::class,
        'performance'      => PerformanceMetrics::class,
        'auth'             => AuthFilter::class,
        'admin'            => AdminFilter::class,
        'guest'            => GuestFilter::class,
        'security_headers' => SecurityHeaders::class,
    ];

    /**
     * Filter wajib yang dieksekusi sebelum & sesudah filter lainnya
     */
    public array $required = [
        'before' => [
            'forcehttps',
            'pagecache',
        ],
        'after' => [
            'pagecache',
            'performance',
            'toolbar',
        ],
    ];

    /**
     * Filter global yang otomatis berjalan di setiap request
     */
    public array $globals = [
        'before' => [
            // 'csrf', // CSRF aktif otomatis pada form yang relevan
        ],
        'after' => [
            'security_headers', // Menambahkan header keamanan XSS, Frame, dan MIME
        ],
    ];

    public array $methods = [];

    public array $filters = [];
}
