<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\ReviewModel;
use App\Models\ProductModel;
use App\Services\TransactionService;
use App\Services\StorageService;

class Transaction extends BaseController
{
    protected TransactionModel $transactionModel;
    protected ReviewModel $reviewModel;
    protected ProductModel $productModel;
    protected TransactionService $transactionService;
    protected StorageService $storageService;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->reviewModel = new ReviewModel();
        $this->productModel = new ProductModel();
        $this->transactionService = new TransactionService();
        $this->storageService = new StorageService();
    }

    /**
     * Halaman Daftar Transaksi & Kesepakatan
     */
    public function index(): string
    {
        $userId = (int) session()->get('user_id');

        $transactions = $this->transactionModel->select('transactions.*, products.title as product_title, products.slug as product_slug, (SELECT image_path FROM product_images WHERE product_images.product_id = products.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as product_image, buyer.full_name as buyer_name, seller.full_name as seller_name')
            ->join('products', 'products.id = transactions.product_id')
            ->join('users as buyer', 'buyer.id = transactions.buyer_id')
            ->join('users as seller', 'seller.id = transactions.seller_id')
            ->groupStart()
                ->where('transactions.buyer_id', $userId)
                ->orWhere('transactions.seller_id', $userId)
            ->groupEnd()
            ->orderBy('transactions.created_at', 'DESC')
            ->findAll();

        $data = [
            'title'        => 'Daftar Transaksi Saya — Bekasin-Aja',
            'transactions' => $transactions,
        ];

        return view('transactions/index', $data);
    }

    /**
     * Detail Kesepakatan & Alur Transaksi
     */
    public function show(int $id): string
    {
        $userId = (int) session()->get('user_id');
        $trx = $this->transactionModel->getTransactionDetail($id);

        if (!$trx || ($trx['buyer_id'] != $userId && $trx['seller_id'] != $userId && session()->get('role') !== 'admin')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Transaksi tidak ditemukan.');
        }

        $review = $this->reviewModel->where('transaction_id', $id)->first();

        $data = [
            'title'       => 'Detail Transaksi #' . esc($trx['transaction_code']) . ' — Bekasin-Aja',
            'transaction' => $trx,
            'isBuyer'     => ($trx['buyer_id'] == $userId),
            'review'      => $review,
        ];

        return view('transactions/show', $data);
    }

    /**
     * Unggah Bukti Pembayaran / Transfer
     */
    public function uploadProof(int $id)
    {
        $userId = (int) session()->get('user_id');
        $trx = $this->transactionModel->find($id);

        if (!$trx || $trx['buyer_id'] != $userId) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $proofFile = $this->request->getFile('payment_proof');
        if (!$proofFile || !$proofFile->isValid() || $proofFile->hasMoved()) {
            return redirect()->back()->with('error', 'File bukti pembayaran tidak valid.');
        }

        $proofUrl = $this->storageService->upload($proofFile, 'payment-proofs', "transactions/{$id}");

        $this->transactionModel->update($id, [
            'payment_proof_url' => $proofUrl,
            'payment_status'    => 'paid',
        ]);

        session()->setFlashdata('success', 'Bukti pembayaran berhasil diunggah.');
        return redirect()->to(base_url('transactions/' . $id));
    }

    /**
     * Selesaikan Transaksi (Barang diterima / Uang diserahkan)
     */
    public function complete(int $id)
    {
        $userId = (int) session()->get('user_id');
        $result = $this->transactionService->completeTransaction($id, $userId);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        session()->setFlashdata('success', 'Transaksi berhasil diselesaikan! Terima kasih telah bertransaksi aman di Bekasin-Aja.');
        return redirect()->to(base_url('transactions/' . $id));
    }

    /**
     * Batalkan Transaksi
     */
    public function cancel(int $id)
    {
        $userId = (int) session()->get('user_id');
        $trx = $this->transactionModel->find($id);

        if (!$trx || ($trx['buyer_id'] != $userId && $trx['seller_id'] != $userId)) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        if ($trx['status'] === 'completed') {
            return redirect()->back()->with('error', 'Transaksi yang sudah selesai tidak dapat dibatalkan.');
        }

        $this->transactionModel->update($id, ['status' => 'cancelled']);
        // Kembalikan produk ke status active
        $this->productModel->update($trx['product_id'], ['status' => 'active']);

        session()->setFlashdata('success', 'Transaksi telah dibatalkan dan barang kembali aktif di marketplace.');
        return redirect()->to(base_url('transactions/' . $id));
    }
}
