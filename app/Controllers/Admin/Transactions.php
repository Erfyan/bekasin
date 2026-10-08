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
        $builder = $this->transactionModel->select('transactions.*, products.title as product_title, buyer.full_name as buyer_name, seller.full_name as seller_name')
            ->join('products', 'products.id = transactions.product_id')
            ->join('users as buyer', 'buyer.id = transactions.buyer_id')
            ->join('users as seller', 'seller.id = transactions.seller_id');

        if (!empty($search)) {
            $builder->like('transactions.transaction_code', $search);
        }

        $transactions = $builder->orderBy('transactions.created_at', 'DESC')->paginate(20);

        $data = [
            'title'        => 'Semua Transaksi — Admin Panel',
            'transactions' => $transactions,
            'pager'        => $this->transactionModel->pager,
            'search'       => $search,
        ];

        return view('admin/transactions/index', $data);
    }
}
