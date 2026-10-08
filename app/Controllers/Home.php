<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;

class Home extends BaseController
{
    protected CategoryModel $categoryModel;
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->productModel = new ProductModel();
    }

    /**
     * Halaman Utama / Landing Page Bekasin-Aja
     */
    public function index(): string
    {
        $categories = [];
        $latestProducts = [];
        $popularProducts = [];

        try {
            // Ambil kategori aktif
            $categories = $this->categoryModel->where('is_active', true)->findAll(8);

            // Ambil produk terbaru yang aktif
            $latestProducts = $this->productModel->select('products.*, users.full_name as seller_name, users.city as seller_city, (SELECT image_path FROM product_images WHERE product_images.product_id = products.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image')
                ->join('users', 'users.id = products.user_id')
                ->where('products.status', 'active')
                ->orderBy('products.created_at', 'DESC')
                ->findAll(8);

            // Ambil produk terpopuler berdasarkan jumlah views
            $popularProducts = $this->productModel->select('products.*, users.full_name as seller_name, users.city as seller_city, (SELECT image_path FROM product_images WHERE product_images.product_id = products.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image')
                ->join('users', 'users.id = products.user_id')
                ->where('products.status', 'active')
                ->orderBy('products.views_count', 'DESC')
                ->findAll(8);
        } catch (\Throwable $e) {
            log_message('error', 'Database connection error: ' . $e->getMessage());

            // Fallback demo categories if cloud database is not connected
            $categories = [
                ['name' => 'Elektronik & Gadget', 'slug' => 'elektronik-gadget', 'icon' => 'smartphone'],
                ['name' => 'Kamera & Fotografi', 'slug' => 'kamera-fotografi', 'icon' => 'camera'],
                ['name' => 'Fashion & Pakaian', 'slug' => 'fashion-pakaian', 'icon' => 'shirt'],
                ['name' => 'Hobi & Koleksi', 'slug' => 'hobi-koleksi', 'icon' => 'gamepad-2'],
                ['name' => 'Buku & Majalah', 'slug' => 'buku-majalah', 'icon' => 'book-open'],
                ['name' => 'Otomotif & Aksesoris', 'slug' => 'otomotif-aksesoris', 'icon' => 'car'],
                ['name' => 'Peralatan Rumah', 'slug' => 'peralatan-rumah-tangga', 'icon' => 'home'],
            ];

            // Fallback demo products
            $latestProducts = [
                [
                    'id' => 1,
                    'title' => 'iPhone 13 Pro 128GB Sierra Blue Fullset Mulus',
                    'slug' => 'iphone-13-pro-128gb-sierra-blue-fullset-mulus-98',
                    'price' => 9800000,
                    'condition' => 'very_good',
                    'city' => 'Jakarta Selatan',
                    'primary_image' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800',
                ],
                [
                    'id' => 2,
                    'title' => 'Sony Alpha A6400 Kit 16-50mm Shutter Count Rendah',
                    'slug' => 'sony-alpha-a6400-kit-16-50mm-shutter-count-rendah',
                    'price' => 10500000,
                    'condition' => 'like_new',
                    'city' => 'Jakarta Selatan',
                    'primary_image' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800',
                ],
                [
                    'id' => 3,
                    'title' => 'MacBook Air M1 2020 8/256GB Space Grey Baterai Normal',
                    'slug' => 'macbook-air-m1-2020-8-256gb-space-grey-baterai-normal',
                    'price' => 8750000,
                    'condition' => 'good',
                    'city' => 'Sleman',
                    'primary_image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800',
                ],
                [
                    'id' => 4,
                    'title' => 'Jaket Kulit Asli Vintage Schott Perfecto Size M',
                    'slug' => 'jaket-kulit-asli-vintage-schott-perfecto-size-m',
                    'price' => 1250000,
                    'condition' => 'very_good',
                    'city' => 'Bandung',
                    'primary_image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800',
                ],
            ];
            $popularProducts = $latestProducts;
        }

        $data = [
            'title'           => 'Bekasin — Marketplace Barang Bekas',
            'metaDescription' => 'Jual beli barang bekas berkualitas, aman, dan mudah langsung antar pengguna.',
            'categories'      => $categories,
            'latestProducts'  => $latestProducts,
            'popularProducts' => $popularProducts,
        ];

        return view('home/index', $data);
    }

    /**
     * Halaman Semua Kategori
     */
    public function categories(): string
    {
        $categories = [];
        try {
            $categories = $this->categoryModel->getActiveCategoriesWithCount();
        } catch (\Throwable $e) {
            log_message('error', 'Database error in categories page: ' . $e->getMessage());
            $categories = $this->categoryModel->where('is_active', true)->findAll();
        }

        $data = [
            'title'           => 'Semua Kategori Barang Bekas — Bekasin-Aja',
            'metaDescription' => 'Temukan berbagai kategori barang bekas mulai dari elektronik, gadget, pakaian, hingga perabotan rumah tangga.',
            'categories'      => $categories,
        ];

        return view('home/categories', $data);
    }
}
