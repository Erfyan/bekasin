<?php

namespace App\Controllers;

use App\Models\WishlistModel;
use App\Models\ProductModel;
use CodeIgniter\HTTP\ResponseInterface;

class Wishlist extends BaseController
{
    protected WishlistModel $wishlistModel;
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->wishlistModel = new WishlistModel();
        $this->productModel = new ProductModel();
    }

    /**
     * Halaman Daftar Keinginan / Wishlist Pengguna
     */
    public function index(): string
    {
        $userId = (int) session()->get('user_id');
        $items = $this->wishlistModel->getUserWishlist($userId);

        $data = [
            'title' => 'Barang Favorit Saya — Bekasin-Aja',
            'items' => $items,
        ];

        return view('wishlist/index', $data);
    }

    /**
     * API Toggle Simpan / Hapus dari Wishlist (AJAX or Form Post)
     */
    public function toggle(int $productId): ResponseInterface
    {
        $userId = (int) session()->get('user_id');
        
        $product = $this->productModel->find($productId);
        if (!$product) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Produk tidak ditemukan.',
            ])->setStatusCode(404);
        }

        $existing = $this->wishlistModel->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $this->wishlistModel->delete($existing['id']);
            return $this->response->setJSON([
                'success' => true,
                'action'  => 'removed',
                'message' => 'Barang dihapus dari wishlist.',
            ]);
        }

        $this->wishlistModel->insert([
            'user_id'    => $userId,
            'product_id' => $productId,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'action'  => 'added',
            'message' => 'Barang ditambahkan ke wishlist!',
        ]);
    }
}
