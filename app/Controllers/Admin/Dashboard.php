<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ProductModel;
use App\Models\TransactionModel;
use App\Models\ReportModel;

class Dashboard extends BaseController
{
    public function index(): string
    {
        $totalUsers = 0;
        $totalProducts = 0;
        $activeProducts = 0;
        $totalTransactions = 0;
        $pendingReports = 0;
        $recentTransactions = [];
        $recentUsers = [];

        try {
            $userModel = new UserModel();
            $productModel = new ProductModel();
            $transactionModel = new TransactionModel();
            $reportModel = new ReportModel();

            $totalUsers = $userModel->countAllResults();
            $totalProducts = $productModel->countAllResults();
            $activeProducts = $productModel->where('status', 'active')->countAllResults();
            $totalTransactions = $transactionModel->countAllResults();
            $pendingReports = $reportModel->where('status', 'pending')->countAllResults();

            $recentTransactions = $transactionModel->select('transactions.*, products.title as product_title, buyer.full_name as buyer_name, seller.full_name as seller_name')
                ->join('products', 'products.id = transactions.product_id')
                ->join('users as buyer', 'buyer.id = transactions.buyer_id')
                ->join('users as seller', 'seller.id = transactions.seller_id')
                ->orderBy('transactions.created_at', 'DESC')
                ->findAll(6);

            $recentUsers = $userModel->orderBy('created_at', 'DESC')->findAll(6);
        } catch (\Throwable $e) {
            log_message('error', 'Admin dashboard database error: ' . $e->getMessage());
        }

        $data = [
            'title'              => 'Admin Panel Dashboard — Bekasin-Aja',
            'totalUsers'         => $totalUsers,
            'totalProducts'      => $totalProducts,
            'activeProducts'     => $activeProducts,
            'totalTransactions'  => $totalTransactions,
            'pendingReports'     => $pendingReports,
            'recentTransactions' => $recentTransactions,
            'recentUsers'        => $recentUsers,
        ];

        return view('admin/dashboard', $data);
    }
}
