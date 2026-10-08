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
        $categories = $this->categoryModel->getActiveCategoriesWithCount();

        $data = [
            'title'           => 'Semua Kategori Barang Bekas — Bekasin-Aja',
            'metaDescription' => 'Temukan berbagai kategori barang bekas mulai dari elektronik, gadget, pakaian, hingga perabotan rumah tangga.',
            'categories'      => $categories,
        ];

        return view('home/categories', $data);
    }
}
