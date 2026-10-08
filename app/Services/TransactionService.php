<?php

namespace App\Services;

use App\Models\TransactionModel;
use App\Models\ProductModel;
use App\Models\ReviewModel;
use Config\Database;

class TransactionService
{
    protected TransactionModel $transactionModel;
    protected ProductModel $productModel;
    protected ReviewModel $reviewModel;
    protected NotificationService $notificationService;
    protected StorageService $storageService;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->productModel = new ProductModel();
        $this->reviewModel = new ReviewModel();
        $this->notificationService = new NotificationService();
        $this->storageService = new StorageService();
    }

    /**
     * Complete a transaction & permanently mark product as sold
     */
    public function completeTransaction(int $transactionId, int $userId): array
    {
        $trx = $this->transactionModel->find($transactionId);
        if (!$trx || ($trx['buyer_id'] != $userId && $trx['seller_id'] != $userId)) {
            return ['success' => false, 'message' => 'Transaksi tidak ditemukan atau akses ditolak.'];
        }

        if ($trx['status'] === 'completed') {
            return ['success' => false, 'message' => 'Transaksi ini sudah selesai.'];
        }

        $db = Database::connect();
        $db->transBegin();

        try {
            $isBuyer = ($trx['buyer_id'] == $userId);
            $updateData = [];

            if ($isBuyer) {
                $updateData['buyer_confirmation'] = true;
            } else {
                $updateData['seller_confirmation'] = true;
            }

            // If both confirmed or single confirmation threshold met
            $updateData['status'] = 'completed';
            $updateData['completed_at'] = date('Y-m-d H:i:s');
            $updateData['payment_status'] = 'paid';

            $this->transactionModel->update($transactionId, $updateData);

            // Mark product as SOLD
            $this->productModel->update($trx['product_id'], ['status' => 'sold']);

            // Notify counterpart
            $targetUserId = $isBuyer ? $trx['seller_id'] : $trx['buyer_id'];
            $this->notificationService->notify(
                $targetUserId,
                'transaction_completed',
                'Transaksi Selesai!',
                "Transaksi #{$trx['transaction_code']} telah diselesaikan.",
                'transaction',
                $transactionId
            );

            $db->transCommit();
            return ['success' => true];
        } catch (\Throwable $e) {
            $db->transRollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Submit a review for a completed transaction
     */
    public function submitReview(int $transactionId, int $buyerId, int $rating, string $comment): array
    {
        $trx = $this->transactionModel->find($transactionId);

        if (!$trx || $trx['buyer_id'] != $buyerId) {
            return ['success' => false, 'message' => 'Anda tidak memiliki hak memberikan ulasan untuk transaksi ini.'];
        }

        if ($trx['status'] !== 'completed') {
            return ['success' => false, 'message' => 'Ulasan hanya dapat diberikan setelah transaksi selesai.'];
        }

        // Prevent duplicate review
        if ($this->reviewModel->where('transaction_id', $transactionId)->countAllResults() > 0) {
            return ['success' => false, 'message' => 'Anda sudah memberikan ulasan untuk transaksi ini.'];
        }

        $this->reviewModel->insert([
            'transaction_id' => $transactionId,
            'seller_id'      => $trx['seller_id'],
            'buyer_id'       => $buyerId,
            'product_id'     => $trx['product_id'],
            'rating'         => max(1, min(5, $rating)),
            'comment'        => trim($comment),
        ]);

        // Notify seller
        $this->notificationService->notify(
            $trx['seller_id'],
            'new_review',
            'Ulasan Baru Diterima!',
            "Pembeli memberikan rating {$rating} bintang untuk transaksi #{$trx['transaction_code']}.",
            'transaction',
            $transactionId
        );

        return ['success' => true];
    }
}
