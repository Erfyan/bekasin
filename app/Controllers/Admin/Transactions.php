<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Services\ModerationService;

class Transactions extends BaseController
{
    protected TransactionModel $transactionModel;
    protected ModerationService $moderationService;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->moderationService = new ModerationService();
    }

    public function index(): string
    {
        $search = $this->request->getGet('q');
        $status = $this->request->getGet('status');
        $transactions = [];
        $pager = null;

        try {
            $builder = $this->transactionModel->select('transactions.*, products.title as product_title, buyer.full_name as buyer_name, seller.full_name as seller_name')
                ->join('products', 'products.id = transactions.product_id')
                ->join('users as buyer', 'buyer.id = transactions.buyer_id')
                ->join('users as seller', 'seller.id = transactions.seller_id');

            if (!empty($search)) {
                $builder->groupStart()
                    ->like('transactions.transaction_code', $search)
                    ->orLike('products.title', $search)
                    ->orLike('buyer.full_name', $search)
                    ->orLike('seller.full_name', $search)
                    ->groupEnd();
            }

            if (!empty($status) && $status !== 'all') {
                $builder->where('transactions.status', $status);
            }

            $transactions = $builder->orderBy('transactions.created_at', 'DESC')->paginate(20);
            $pager = $this->transactionModel->pager;
        } catch (\Throwable $e) {
            log_message('error', 'Admin transactions error: ' . $e->getMessage());
        }

        $data = [
            'title'        => 'Semua Transaksi — Admin Panel',
            'transactions' => $transactions,
            'pager'        => $pager,
            'search'       => $search,
            'status'       => $status ?? 'all',
        ];

        return view('admin/transactions/index', $data);
    }

    public function changeStatus(int $id)
    {
        $adminId = (int) session()->get('user_id');
        $transaction = $this->transactionModel->find($id);

        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }

        $status = $this->request->getPost('status');
        $allowedStatuses = ['deal_agreed', 'waiting_payment', 'paid_confirmed', 'processing', 'completed', 'cancelled'];

        if (!in_array($status, $allowedStatuses, true)) {
            return redirect()->back()->with('error', 'Status transaksi tidak valid.');
        }

        $updateData = ['status' => $status];
        if ($status === 'completed' && empty($transaction['completed_at'])) {
            $updateData['completed_at'] = date('Y-m-d H:i:s');
        }

        try {
            $this->transactionModel->update($id, $updateData);

            $this->moderationService->logAdminAction($adminId, 'change_transaction_status', 'transaction', $id, [
                'transaction_code' => $transaction['transaction_code'],
                'new_status'       => $status,
            ]);

            session()->setFlashdata('success', "Status transaksi #{$transaction['transaction_code']} berhasil diubah menjadi " . ucfirst(str_replace('_', ' ', $status)));
        } catch (\Throwable $e) {
            log_message('error', 'Admin change transaction status error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal mengubah status transaksi: ' . $e->getMessage());
        }

        return redirect()->to(base_url('admin/transactions'));
    }
}
