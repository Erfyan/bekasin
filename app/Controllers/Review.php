<?php

namespace App\Controllers;

use App\Services\TransactionService;

class Review extends BaseController
{
    protected TransactionService $transactionService;

    public function __construct()
    {
        $this->transactionService = new TransactionService();
    }

    /**
     * Simpan Ulasan & Rating Bintang untuk Seller
     */
    public function store(int $transactionId)
    {
        $userId = (int) session()->get('user_id');
        $rating = (int) $this->request->getPost('rating');
        $comment = $this->request->getPost('comment') ?? '';

        $result = $this->transactionService->submitReview($transactionId, $userId, $rating, $comment);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        session()->setFlashdata('success', 'Ulasan dan bintang berhasil dikirim! Terima kasih atas feedback Anda.');
        return redirect()->to(base_url('transactions/' . $transactionId));
    }
}
