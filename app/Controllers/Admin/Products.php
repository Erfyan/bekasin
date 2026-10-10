<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\TransactionModel;
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
            $builder = $this->productModel->select('products.*, users.full_name as seller_name, users.username as seller_username, categories.name as category_name, (SELECT image_path FROM product_images WHERE product_images.product_id = products.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image')
                ->join('users', 'users.id = products.user_id')
                ->join('categories', 'categories.id = products.category_id');

            if (!empty($search)) {
                $builder->groupStart()
                    ->like('products.title', $search)
                    ->orLike('users.full_name', $search)
                    ->orLike('categories.name', $search)
                    ->groupEnd();
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

        $action = $this->request->getPost('action'); // active, archived, deleted, sold, draft
        if ($action === 'deleted') {
            return $this->delete($id);
        }

        $allowedStatuses = ['active', 'archived', 'sold', 'draft', 'reserved'];
        if (!in_array($action, $allowedStatuses, true)) {
            return redirect()->back()->with('error', 'Status moderasi tidak valid.');
        }

        try {
            $this->productModel->update($id, ['status' => $action]);
            $this->moderationService->logAdminAction($adminId, 'moderate_product_status', 'product', $id, [
                'new_status' => $action,
                'title'      => $product['title'],
            ]);
            session()->setFlashdata('success', 'Status barang "' . esc($product['title']) . '" diubah menjadi ' . ucfirst($action));
        } catch (\Throwable $e) {
            log_message('error', 'Admin moderate product error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal memoderasi barang: ' . $e->getMessage());
        }

        return redirect()->to(base_url('admin/products'));
    }

    public function delete(int $id)
    {
        $adminId = (int) session()->get('user_id');
        $product = $this->productModel->find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan.');
        }

        // Cek apakah barang memiliki riwayat transaksi
        try {
            $trxModel = new TransactionModel();
            $trxCount = $trxModel->where('product_id', $id)->countAllResults();

            if ($trxCount > 0) {
                // Jangan hard delete jika ada transaksi terikat (karena foreign key RESTRICT), ubah status ke archived
                $this->productModel->update($id, ['status' => 'archived']);
                $this->moderationService->logAdminAction($adminId, 'archive_product_due_to_transactions', 'product', $id, [
                    'title' => $product['title'],
                ]);
                session()->setFlashdata('error', "Barang memiliki {$trxCount} riwayat transaksi aktif. Barang dialihkan ke status 'Diarsipkan' demi integritas data.");
                return redirect()->to(base_url('admin/products'));
            }

            $this->productModel->delete($id);
            $this->moderationService->logAdminAction($adminId, 'delete_product', 'product', $id, [
                'title' => $product['title'],
            ]);
            session()->setFlashdata('success', 'Barang "' . esc($product['title']) . '" berhasil dihapus secara permanen.');
        } catch (\Throwable $e) {
            log_message('error', 'Admin delete product error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }

        return redirect()->to(base_url('admin/products'));
    }
}
