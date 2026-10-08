<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\ReviewModel;
use App\Services\ProductService;

class Product extends BaseController
{
    protected ProductModel $productModel;
    protected CategoryModel $categoryModel;
    protected ProductService $productService;
    protected ReviewModel $reviewModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->productService = new ProductService();
        $this->reviewModel = new ReviewModel();
    }

    /**
     * Katalog Produk dengan Multi-Filter & Server-side Pagination
     */
    public function index(): string
    {
        $filters = [
            'keyword'         => $this->request->getGet('keyword') ?? $this->request->getGet('q'),
            'category'        => $this->request->getGet('category'),
            'condition'       => $this->request->getGet('condition'),
            'min_price'       => $this->request->getGet('min_price'),
            'max_price'       => $this->request->getGet('max_price'),
            'city'            => $this->request->getGet('city'),
            'delivery_method' => $this->request->getGet('delivery_method'),
            'sort'            => $this->request->getGet('sort') ?? 'latest',
        ];

        $result = $this->productModel->getFilteredProducts($filters, 12);
        $categories = $this->categoryModel->where('is_active', true)->findAll();

        $data = [
            'title'           => 'Katalog Barang Bekas — Bekasin-Aja',
            'metaDescription' => 'Cari dan temukan ribuan barang bekas murah dan berkualitas di sekitarmu.',
            'products'        => $result['products'],
            'pager'           => $result['pager'],
            'categories'      => $categories,
            'filters'         => $filters,
        ];

        return view('products/index', $data);
    }

    /**
     * Katalog Produk berdasarkan Kategori
     */
    public function category(string $slug): string
    {
        $category = $this->categoryModel->where('slug', $slug)->first();
        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kategori tidak ditemukan.');
        }

        $filters = [
            'category' => $slug,
            'sort'     => $this->request->getGet('sort') ?? 'latest',
        ];

        $result = $this->productModel->getFilteredProducts($filters, 12);
        $categories = $this->categoryModel->where('is_active', true)->findAll();

        $data = [
            'title'           => 'Barang Bekas Kategori ' . esc($category['name']) . ' — Bekasin-Aja',
            'metaDescription' => esc($category['description'] ?? 'Koleksi barang bekas kategori ' . $category['name']),
            'currentCategory' => $category,
            'products'        => $result['products'],
            'pager'           => $result['pager'],
            'categories'      => $categories,
            'filters'         => $filters,
        ];

        return view('products/index', $data);
    }

    /**
     * Detail Produk Lengkap
     */
    public function show(string $slug): string
    {
        $product = $this->productModel->getProductDetail($slug);
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Barang yang Anda cari tidak ditemukan atau sudah dihapus.');
        }

        // Catat view count tanpa spam
        $this->productService->recordView((int) $product['id']);

        // Ambil ulasan seller & hitung rating rata-rata
        $sellerReviews = $this->reviewModel->getSellerReviews((int) $product['user_id']);
        $avgRating = 0;
        if (!empty($sellerReviews)) {
            $totalRating = array_sum(array_column($sellerReviews, 'rating'));
            $avgRating = round($totalRating / count($sellerReviews), 1);
        }

        // Ambil barang terkait dari kategori yang sama
        $relatedProducts = $this->productModel->select('products.*, users.full_name as seller_name, users.city as seller_city, (SELECT image_path FROM product_images WHERE product_images.product_id = products.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image')
            ->join('users', 'users.id = products.user_id')
            ->where('products.category_id', $product['category_id'])
            ->where('products.id !=', $product['id'])
            ->where('products.status', 'active')
            ->findAll(4);

        $data = [
            'title'           => esc($product['title']) . ' — Bekasin-Aja',
            'metaDescription' => esc(substr(strip_tags($product['description']), 0, 150)),
            'product'         => $product,
            'avgRating'       => $avgRating,
            'reviewsCount'    => count($sellerReviews),
            'relatedProducts' => $relatedProducts,
        ];

        return view('products/show', $data);
    }

    /**
     * Halaman Dashboard Barang Milik Seller
     */
    public function myProducts(): string
    {
        $userId = (int) session()->get('user_id');
        $status = $this->request->getGet('status') ?? 'all';

        $builder = $this->productModel->select('products.*, categories.name as category_name, (SELECT image_path FROM product_images WHERE product_images.product_id = products.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image, (SELECT COUNT(id) FROM offers WHERE offers.product_id = products.id AND offers.status = \'pending\') as pending_offers_count')
            ->join('categories', 'categories.id = products.category_id')
            ->where('products.user_id', $userId);

        if ($status !== 'all') {
            $builder->where('products.status', $status);
        }

        $products = $builder->orderBy('products.created_at', 'DESC')->findAll();

        $data = [
            'title'         => 'Kelola Barang Jualan Saya — Bekasin-Aja',
            'products'      => $products,
            'currentStatus' => $status,
        ];

        return view('products/my_products', $data);
    }

    /**
     * Form Tambah Barang Bekas Baru
     */
    public function create(): string
    {
        $categories = $this->categoryModel->where('is_active', true)->findAll();

        return view('products/create', [
            'title'      => 'Jual Barang Bekas — Pasang Iklan Cepat',
            'categories' => $categories,
        ]);
    }

    /**
     * Simpan Listing Barang Baru
     */
    public function store()
    {
        $rules = [
            'title'           => 'required|min_length[5]|max_length[200]',
            'category_id'     => 'required|is_not_unique[categories.id]',
            'price'           => 'required|numeric|greater_than_equal_to[0]',
            'condition'       => 'required|in_list[like_new,very_good,good,fair,needs_repair]',
            'delivery_method' => 'required|in_list[meetup,shipping,both]',
            'city'            => 'required',
            'province'        => 'required',
            'description'     => 'required|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $userId = (int) session()->get('user_id');
        $images = $this->request->getFileMultiple('images');

        $result = $this->productService->createProduct($this->request->getPost(), $images ?? [], $userId);

        if (!$result['success']) {
            return redirect()->back()->withInput()->with('error', $result['message']);
        }

        session()->setFlashdata('success', 'Barang Anda berhasil dipasang dan sudah aktif di marketplace!');
        return redirect()->to(base_url('products/' . $result['slug']));
    }

    /**
     * Form Edit Produk (Dengan Pengecekan Kepemilikan / Anti IDOR)
     */
    public function edit(int $id): string
    {
        $userId = (int) session()->get('user_id');
        $product = $this->productModel->find($id);

        if (!$product || ($product['user_id'] != $userId && session()->get('role') !== 'admin')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Barang tidak ditemukan atau Anda tidak memiliki hak akses.');
        }

        $categories = $this->categoryModel->where('is_active', true)->findAll();

        return view('products/edit', [
            'title'      => 'Edit Barang — ' . esc($product['title']),
            'product'    => $product,
            'categories' => $categories,
        ]);
    }

    /**
     * Update Produk (Dengan Pengecekan Kepemilikan)
     */
    public function update(int $id)
    {
        $userId = (int) session()->get('user_id');
        $product = $this->productModel->find($id);

        if (!$product || ($product['user_id'] != $userId && session()->get('role') !== 'admin')) {
            return redirect()->to(base_url('sell/products'))->with('error', 'Akses ditolak.');
        }

        $rules = [
            'title'           => 'required|min_length[5]|max_length[200]',
            'category_id'     => 'required|is_not_unique[categories.id]',
            'price'           => 'required|numeric|greater_than_equal_to[0]',
            'condition'       => 'required|in_list[like_new,very_good,good,fair,needs_repair]',
            'delivery_method' => 'required|in_list[meetup,shipping,both]',
            'city'            => 'required',
            'province'        => 'required',
            'description'     => 'required|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $updateData = [
            'category_id'        => (int) $this->request->getPost('category_id'),
            'title'              => trim($this->request->getPost('title')),
            'price'              => (float) $this->request->getPost('price'),
            'condition'          => $this->request->getPost('condition'),
            'delivery_method'    => $this->request->getPost('delivery_method'),
            'city'               => trim($this->request->getPost('city')),
            'province'           => trim($this->request->getPost('province')),
            'description'        => trim($this->request->getPost('description')),
            'has_scratches'      => !empty($this->request->getPost('has_scratches')),
            'has_damages'        => !empty($this->request->getPost('has_damages')),
            'is_functional'      => !empty($this->request->getPost('is_functional')),
            'was_repaired'       => !empty($this->request->getPost('was_repaired')),
            'completeness_notes' => $this->request->getPost('completeness_notes') ?? null,
            'meetup_location'    => $this->request->getPost('meetup_location') ?? null,
        ];

        $this->productModel->update($id, $updateData);

        session()->setFlashdata('success', 'Informasi barang berhasil diperbarui.');
        return redirect()->to(base_url('products/' . $product['slug']));
    }

    /**
     * Ganti Status Produk (Draft, Active, Sold, Archived)
     */
    public function changeStatus(int $id)
    {
        $userId = (int) session()->get('user_id');
        $product = $this->productModel->find($id);

        if (!$product || ($product['user_id'] != $userId && session()->get('role') !== 'admin')) {
            return redirect()->to(base_url('sell/products'))->with('error', 'Akses ditolak.');
        }

        $newStatus = $this->request->getPost('status');
        if (!in_array($newStatus, ['active', 'sold', 'archived', 'draft'], true)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $this->productModel->update($id, ['status' => $newStatus]);
        session()->setFlashdata('success', 'Status barang berhasil diubah menjadi: ' . ucfirst($newStatus));
        return redirect()->to(base_url('sell/products'));
    }

    /**
     * Hapus Produk
     */
    public function delete(int $id)
    {
        $userId = (int) session()->get('user_id');
        $product = $this->productModel->find($id);

        if (!$product || ($product['user_id'] != $userId && session()->get('role') !== 'admin')) {
            return redirect()->to(base_url('sell/products'))->with('error', 'Akses ditolak.');
        }

        $this->productModel->delete($id);
        session()->setFlashdata('success', 'Barang berhasil dihapus.');
        return redirect()->to(base_url('sell/products'));
    }
}
