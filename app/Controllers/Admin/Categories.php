<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Services\ModerationService;

class Categories extends BaseController
{
    protected CategoryModel $categoryModel;
    protected ProductModel $productModel;
    protected ModerationService $moderationService;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->productModel = new ProductModel();
        $this->moderationService = new ModerationService();
    }

    public function index(): string
    {
        $categories = [];
        try {
            $categories = $this->categoryModel->select('categories.*, COUNT(products.id) as products_count')
                ->join('products', 'products.category_id = categories.id', 'left')
                ->groupBy('categories.id')
                ->orderBy('categories.sort_order', 'ASC')
                ->findAll();
        } catch (\Throwable $e) {
            log_message('error', 'Admin categories error: ' . $e->getMessage());
            try {
                $categories = $this->categoryModel->orderBy('sort_order', 'ASC')->findAll();
            } catch (\Throwable $ex) {
                log_message('error', 'Admin categories fallback error: ' . $ex->getMessage());
            }
        }

        $data = [
            'title'      => 'Kelola Kategori — Admin Panel',
            'categories' => $categories,
        ];

        return view('admin/categories/index', $data);
    }

    public function store()
    {
        $adminId = (int) session()->get('user_id');
        $name = trim((string) $this->request->getPost('name'));

        if (empty($name)) {
            return redirect()->back()->with('error', 'Nama kategori wajib diisi.');
        }

        $slug = url_title($name, '-', true);
        if (empty($slug)) {
            $slug = 'kat-' . time();
        }

        // Cek duplikasi slug
        $existing = $this->categoryModel->where('slug', $slug)->first();
        if ($existing) {
            $slug .= '-' . substr(uniqid(), -4);
        }

        try {
            $this->categoryModel->insert([
                'name'        => $name,
                'slug'        => $slug,
                'icon'        => trim((string) $this->request->getPost('icon')) ?: 'tag',
                'description' => trim((string) $this->request->getPost('description')),
                'sort_order'  => (int) $this->request->getPost('sort_order'),
                'is_active'   => true,
            ]);

            $this->moderationService->logAdminAction($adminId, 'create_category', 'category', null, ['name' => $name, 'slug' => $slug]);
            session()->setFlashdata('success', 'Kategori baru berhasil ditambahkan.');
        } catch (\Throwable $e) {
            log_message('error', 'Store category error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menambahkan kategori: ' . $e->getMessage());
        }

        return redirect()->to(base_url('admin/categories'));
    }

    public function update(int $id)
    {
        $adminId = (int) session()->get('user_id');
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan.');
        }

        $name = trim((string) $this->request->getPost('name'));
        if (empty($name)) {
            return redirect()->back()->with('error', 'Nama kategori wajib diisi.');
        }

        $slug = url_title($name, '-', true);
        if (empty($slug)) {
            $slug = $category['slug'];
        }

        // Cek duplikasi slug dengan id lain
        $existing = $this->categoryModel->where('slug', $slug)->where('id !=', $id)->first();
        if ($existing) {
            $slug .= '-' . $id;
        }

        try {
            $this->categoryModel->update($id, [
                'name'        => $name,
                'slug'        => $slug,
                'icon'        => trim((string) $this->request->getPost('icon')) ?: 'tag',
                'description' => trim((string) $this->request->getPost('description')),
                'sort_order'  => (int) $this->request->getPost('sort_order'),
                'is_active'   => (bool) $this->request->getPost('is_active'),
            ]);

            $this->moderationService->logAdminAction($adminId, 'update_category', 'category', $id, ['name' => $name]);
            session()->setFlashdata('success', 'Kategori "' . esc($name) . '" berhasil diperbarui.');
        } catch (\Throwable $e) {
            log_message('error', 'Update category error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal memperbarui kategori: ' . $e->getMessage());
        }

        return redirect()->to(base_url('admin/categories'));
    }

    public function delete(int $id)
    {
        $adminId = (int) session()->get('user_id');
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan.');
        }

        // Cek apakah ada produk dalam kategori ini
        $productCount = $this->productModel->where('category_id', $id)->countAllResults();
        if ($productCount > 0) {
            session()->setFlashdata('error', "Kategori '{$category['name']}' tidak dapat dihapus karena masih digunakan oleh {$productCount} barang.");
            return redirect()->to(base_url('admin/categories'));
        }

        try {
            $this->categoryModel->delete($id);
            $this->moderationService->logAdminAction($adminId, 'delete_category', 'category', $id, ['name' => $category['name']]);
            session()->setFlashdata('success', "Kategori '{$category['name']}' berhasil dihapus.");
        } catch (\Throwable $e) {
            log_message('error', 'Delete category error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }

        return redirect()->to(base_url('admin/categories'));
    }
}
