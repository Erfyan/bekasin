<?php

namespace App\Controllers;

use App\Models\OfferModel;
use App\Models\ProductModel;
use App\Services\OfferService;

class Offer extends BaseController
{
    protected OfferModel $offerModel;
    protected ProductModel $productModel;
    protected OfferService $offerService;

    public function __construct()
    {
        $this->offerModel = new OfferModel();
        $this->productModel = new ProductModel();
        $this->offerService = new OfferService();
    }

    /**
     * Halaman Daftar Negosiasi Masuk & Keluar
     */
    public function index(): string
    {
        $userId = (int) session()->get('user_id');
        $tab = $this->request->getGet('tab') ?? 'received'; // received or sent

        $offers = $this->offerModel->getOffersForUser($userId, $tab);

        $data = [
            'title'   => 'Kelola Negosiasi & Penawaran — Bekasin-Aja',
            'offers'  => $offers,
            'tab'     => $tab,
        ];

        return view('offers/index', $data);
    }

    /**
     * Buat Penawaran Harga Baru (Buyer ke Seller)
     */
    public function store(int $productId)
    {
        $userId = (int) session()->get('user_id');
        $offeredPrice = (float) $this->request->getPost('offered_price');
        $notes = $this->request->getPost('notes');

        $result = $this->offerService->makeOffer($productId, $userId, $offeredPrice, $notes);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        session()->setFlashdata('success', 'Penawaran harga Anda berhasil dikirim ke penjual! Tunggu respon atau hubungi via chat.');
        return redirect()->to(base_url('offers?tab=sent'));
    }

    /**
     * Terima Penawaran (Seller menerima tawaran harga)
     */
    public function accept(int $offerId)
    {
        $sellerId = (int) session()->get('user_id');
        $result = $this->offerService->acceptOffer($offerId, $sellerId);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        session()->setFlashdata('success', 'Penawaran berhasil diterima! Kesepakatan transaksi telah dibuat.');
        return redirect()->to(base_url('transactions/' . $result['transaction_id']));
    }

    /**
     * Tolak Penawaran (Seller menolak tawaran)
     */
    public function reject(int $offerId)
    {
        $sellerId = (int) session()->get('user_id');
        $result = $this->offerService->rejectOffer($offerId, $sellerId);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        session()->setFlashdata('success', 'Penawaran telah ditolak.');
        return redirect()->to(base_url('offers?tab=received'));
    }

    /**
     * Tawaran Balik / Counter Offer
     */
    public function counter(int $offerId)
    {
        $userId = (int) session()->get('user_id');
        $counterPrice = (float) $this->request->getPost('counter_price');

        $offer = $this->offerModel->find($offerId);
        if (!$offer || $offer['seller_id'] != $userId) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $this->offerModel->update($offerId, [
            'counter_price' => $counterPrice,
            'status'        => 'countered',
        ]);

        session()->setFlashdata('success', 'Tawaran balik Rp ' . number_format($counterPrice, 0, ',', '.') . ' telah dikirim ke pembeli.');
        return redirect()->to(base_url('offers?tab=received'));
    }
}
