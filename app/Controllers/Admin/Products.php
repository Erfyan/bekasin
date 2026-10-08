<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Services\ModerationService;

class Products extends BaseController
{
    protected ProductModel $productModel;
    protected ModerationService $moderationService;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->moderationService = new ModerationService();
    }

    public function index(): string
    {
        $search = $this->request->getGet('q');
        $status = $this->request->getGet('status');
        $products = [];
        $pager = null;

        try {
            $builder = $this->productModel->select('products.*, users.full_name as seller_name, categories.name as category_name')
                ->join('users', 'users.id = products.user_id')
                ->join('categories', 'categories.id = products.category_id');

            if (!empty($search)) {
                $builder->like('products.title', $search);
            }

            if (!empty($status) && $status !== 'all') {
                $builder->where('products.status', $status);
            }

            $products = $builder->orderBy('products.created_at', 'DESC')->paginate(20);
            $pager = $this->productModel->pager;
        } catch (\Throwable $e) {
            log_message('error', 'Admin products index error: ' . $e->getMessage());
        }

        $data = [
            'title'    => 'Moderasi Iklan Barang — Admin Panel',
            'products' => $products,
            'pager'    => $pager,
            'search'   => $search,
            'status'   => $status ?? 'all',
        ];

        return view('admin/products/index', $data);
    }

    public function moderate(int $id)
    {
        $adminId = (int) session()->get('user_id');
        $product = $this->productModel->find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan.');
        }

        $action = $this->request->getPost('action'); // active, archived, deleted
        if ($action === 'deleted') {
            $this->productModel->delete($id);
            $this->moderationService->logAdminAction($adminId, 'delete_product', 'product', $id, ['title' => $product['title']]);
            session()->setFlashdata('success', 'Barang berhasil dihapus secara permanen.');
        } else {
            $this->productModel->update($id, ['status' => $action]);
            $this->moderationService->logAdminAction($adminId, 'moderate_product_status', 'product', $id, ['status' => $action, 'title' => $product['title']]);
            session()->setFlashdata('success', 'Status barang diubah menjadi ' . ucfirst($action));
        }

        return redirect()->to(base_url('admin/products'));
    }
}
