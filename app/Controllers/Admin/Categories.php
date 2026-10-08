<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Services\ModerationService;

class Categories extends BaseController
{
    protected CategoryModel $categoryModel;
    protected ModerationService $moderationService;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->moderationService = new ModerationService();
    }

    public function index(): string
    {
        $categories = $this->categoryModel->orderBy('sort_order', 'ASC')->findAll();

        $data = [
            'title'      => 'Kelola Kategori — Admin Panel',
            'categories' => $categories,
        ];

        return view('admin/categories/index', $data);
    }

    public function store()
    {
        $adminId = (int) session()->get('user_id');
        $name = trim($this->request->getPost('name'));
        $slug = url_title($name, '-', true);

        $this->categoryModel->insert([
            'name'        => $name,
            'slug'        => $slug,
            'icon'        => trim($this->request->getPost('icon') ?? 'tag'),
            'description' => trim($this->request->getPost('description') ?? ''),
            'sort_order'  => (int) $this->request->getPost('sort_order'),
            'is_active'   => true,
        ]);

        $this->moderationService->logAdminAction($adminId, 'create_category', 'category', null, ['name' => $name]);

        session()->setFlashdata('success', 'Kategori baru berhasil ditambahkan.');
        return redirect()->to(base_url('admin/categories'));
    }

    public function update(int $id)
    {
        $adminId = (int) session()->get('user_id');
        $name = trim($this->request->getPost('name'));

        $this->categoryModel->update($id, [
            'name'        => $name,
            'icon'        => trim($this->request->getPost('icon') ?? 'tag'),
            'description' => trim($this->request->getPost('description') ?? ''),
            'sort_order'  => (int) $this->request->getPost('sort_order'),
            'is_active'   => !empty($this->request->getPost('is_active')),
        ]);

        $this->moderationService->logAdminAction($adminId, 'update_category', 'category', $id, ['name' => $name]);

        session()->setFlashdata('success', 'Kategori berhasil diperbarui.');
        return redirect()->to(base_url('admin/categories'));
    }
}
