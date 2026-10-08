<?php

namespace App\Services;

use App\Models\OfferModel;
use App\Models\ProductModel;
use App\Models\TransactionModel;
use Config\Database;

class OfferService
{
    protected OfferModel $offerModel;
    protected ProductModel $productModel;
    protected TransactionModel $transactionModel;
    protected NotificationService $notificationService;

    public function __construct()
    {
        $this->offerModel = new OfferModel();
        $this->productModel = new ProductModel();
        $this->transactionModel = new TransactionModel();
        $this->notificationService = new NotificationService();
    }

    /**
     * Submit an offer
     */
    public function makeOffer(int $productId, int $buyerId, float $offeredPrice, ?string $notes = null): array
    {
        $product = $this->productModel->find($productId);
        if (!$product || $product['status'] !== 'active') {
            return ['success' => false, 'message' => 'Produk tidak tersedia untuk ditawar.'];
        }

        if ($product['user_id'] == $buyerId) {
            return ['success' => false, 'message' => 'Anda tidak dapat menawar barang Anda sendiri.'];
        }

        if ($offeredPrice <= 0) {
            return ['success' => false, 'message' => 'Harga penawaran harus lebih dari Rp 0.'];
        }

        $offerId = $this->offerModel->insert([
            'product_id'    => $productId,
            'buyer_id'      => $buyerId,
            'seller_id'     => $product['user_id'],
            'offered_price' => $offeredPrice,
            'status'        => 'pending',
            'notes'         => $notes,
        ], true);

        // Notify seller
        $this->notificationService->notify(
            $product['user_id'],
            'new_offer',
            'Penawaran Baru Diterima!',
            "Calon pembeli mengajukan penawaran Rp " . number_format($offeredPrice, 0, ',', '.') . " untuk produk {$product['title']}.",
            'offer',
            $offerId
        );

        return ['success' => true, 'offer_id' => $offerId];
    }

    /**
     * Accept offer with transaction consistency (reserve product & create initial transaction)
     */
    public function acceptOffer(int $offerId, int $sellerId): array
    {
        $offer = $this->offerModel->find($offerId);
        if (!$offer || $offer['seller_id'] != $sellerId || $offer['status'] !== 'pending') {
            return ['success' => false, 'message' => 'Penawaran tidak valid atau sudah diproses.'];
        }

        $product = $this->productModel->find($offer['product_id']);
        if (!$product || $product['status'] !== 'active') {
            return ['success' => false, 'message' => 'Produk sudah tidak aktif atau dalam proses transaksi lain.'];
        }

        $db = Database::connect();
        $db->transBegin();

        try {
            // Update offer status
            $this->offerModel->update($offerId, ['status' => 'accepted']);

            // Update product status to reserved
            $this->productModel->update($offer['product_id'], ['status' => 'reserved']);

            // Create transaction record
            $transactionCode = 'TRX-' . strtoupper(bin2hex(random_bytes(4))) . '-' . date('ymd');
            $transactionId = $this->transactionModel->insert([
                'transaction_code' => $transactionCode,
                'buyer_id'         => $offer['buyer_id'],
                'seller_id'        => $sellerId,
                'product_id'       => $offer['product_id'],
                'offer_id'         => $offerId,
                'agreed_price'     => $offer['offered_price'],
                'delivery_method'  => $product['delivery_method'] === 'both' ? 'meetup' : $product['delivery_method'],
                'status'           => 'confirmed',
                'payment_method'   => 'cod',
                'payment_status'   => 'unpaid',
            ], true);

            // Notify buyer
            $this->notificationService->notify(
                $offer['buyer_id'],
                'offer_accepted',
                'Penawaran Anda Diterima!',
                "Penjual menerima tawaran Anda untuk {$product['title']} sebesar Rp " . number_format($offer['offered_price'], 0, ',', '.') . ". Silakan lanjutkan transaksi.",
                'transaction',
                $transactionId
            );

            $db->transCommit();
            return ['success' => true, 'transaction_id' => $transactionId];
        } catch (\Throwable $e) {
            $db->transRollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Reject offer
     */
    public function rejectOffer(int $offerId, int $sellerId): array
    {
        $offer = $this->offerModel->find($offerId);
        if (!$offer || $offer['seller_id'] != $sellerId) {
            return ['success' => false, 'message' => 'Akses ditolak.'];
        }

        $this->offerModel->update($offerId, ['status' => 'rejected']);
        $product = $this->productModel->find($offer['product_id']);

        $this->notificationService->notify(
            $offer['buyer_id'],
            'offer_rejected',
            'Penawaran Ditolak',
            "Penawaran Anda untuk {$product['title']} belum dapat diterima oleh penjual.",
            'offer',
            $offerId
        );

        return ['success' => true];
    }
}
