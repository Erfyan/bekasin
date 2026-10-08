<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransactionModel;

class Transactions extends BaseController
{
    protected TransactionModel $transactionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
    }

    public function index(): string
    {
        $search = $this->request->getGet('q');
        $transactions = [];
        $pager = null;

        try {
            $builder = $this->transactionModel->select('transactions.*, products.title as product_title, buyer.full_name as buyer_name, seller.full_name as seller_name')
                ->join('products', 'products.id = transactions.product_id')
                ->join('users as buyer', 'buyer.id = transactions.buyer_id')
                ->join('users as seller', 'seller.id = transactions.seller_id');

            if (!empty($search)) {
                $builder->like('transactions.transaction_code', $search);
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
        ];

        return view('admin/transactions/index', $data);
    }
}
