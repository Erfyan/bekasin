<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProductModel;
use App\Models\ReviewModel;
use App\Models\TransactionModel;
use App\Services\StorageService;

class Profile extends BaseController
{
    protected UserModel $userModel;
    protected ProductModel $productModel;
    protected ReviewModel $reviewModel;
    protected TransactionModel $transactionModel;
    protected StorageService $storageService;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->productModel = new ProductModel();
        $this->reviewModel = new ReviewModel();
        $this->transactionModel = new TransactionModel();
        $this->storageService = new StorageService();
    }

    /**
     * Dashboard Profil Pengguna Pribadi
     */
    public function index(): string
    {
        $userId = (int) session()->get('user_id');
        $user = $this->userModel->find($userId);

        // Stats
        $activeProductsCount = $this->productModel->where('user_id', $userId)->where('status', 'active')->countAllResults();
        $soldProductsCount = $this->productModel->where('user_id', $userId)->where('status', 'sold')->countAllResults();
        $reviews = $this->reviewModel->getSellerReviews($userId);
        
        $avgRating = 0;
        if (!empty($reviews)) {
            $avgRating = round(array_sum(array_column($reviews, 'rating')) / count($reviews), 1);
        }

        $recentProducts = $this->productModel->select('products.*, (SELECT image_path FROM product_images WHERE product_images.product_id = products.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll(6);

        $data = [
            'title'               => 'Profil Saya — Bekasin-Aja',
            'user'                => $user,
            'activeProductsCount' => $activeProductsCount,
            'soldProductsCount'   => $soldProductsCount,
            'reviews'             => $reviews,
            'avgRating'           => $avgRating,
            'recentProducts'      => $recentProducts,
        ];

        return view('profile/index', $data);
    }

    /**
     * Halaman Pengaturan Akun
     */
    public function settings(): string
    {
        $userId = (int) session()->get('user_id');
        $user = $this->userModel->find($userId);

        $data = [
            'title' => 'Pengaturan Akun & Profil — Bekasin-Aja',
            'user'  => $user,
        ];

        return view('profile/settings', $data);
    }

    /**
     * Simpan Pembaruan Biodata & Lokasi
     */
    public function update()
    {
        $userId = (int) session()->get('user_id');

        $rules = [
            'full_name' => 'required|min_length[3]|max_length[150]',
            'phone'     => 'required|min_length[8]|max_length[25]',
            'city'      => 'required',
            'province'  => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $avatarUrl = session()->get('avatar_url');
        $avatarFile = $this->request->getFile('avatar');
        if ($avatarFile && $avatarFile->isValid() && !$avatarFile->hasMoved()) {
            $avatarUrl = $this->storageService->upload($avatarFile, 'avatars', "users/{$userId}");
        }

        $updateData = [
            'full_name'  => trim($this->request->getPost('full_name')),
            'phone'      => trim($this->request->getPost('phone')),
            'bio'        => trim($this->request->getPost('bio') ?? ''),
            'province'   => trim($this->request->getPost('province')),
            'city'       => trim($this->request->getPost('city')),
            'address'    => trim($this->request->getPost('address') ?? ''),
            'avatar_url' => $avatarUrl,
        ];

        $this->userModel->update($userId, $updateData);

        // Update session
        session()->set([
            'full_name'  => $updateData['full_name'],
            'avatar_url' => $updateData['avatar_url'],
            'province'   => $updateData['province'],
            'city'       => $updateData['city'],
        ]);

        session()->setFlashdata('success', 'Profil Anda berhasil diperbarui.');
        return redirect()->to(base_url('profile'));
    }

    /**
     * Ganti Password
     */
    public function updatePassword()
    {
        $userId = (int) session()->get('user_id');
        $user = $this->userModel->find($userId);

        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $currentPass = $this->request->getPost('current_password');
        if (!password_verify($currentPass, $user['password_hash'])) {
            return redirect()->back()->with('error', 'Password saat ini tidak sesuai.');
        }

        $this->userModel->update($userId, [
            'password_hash' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT),
        ]);

        session()->setFlashdata('success', 'Password berhasil diubah!');
        return redirect()->to(base_url('profile/settings'));
    }

    /**
     * Halaman Publik Profil Penjual (Seller Storefront)
     */
    public function seller(string $username): string
    {
        $seller = $this->userModel->where('username', $username)->first();
        if (!$seller) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Penjual tidak ditemukan.');
        }

        $products = $this->productModel->select('products.*, (SELECT image_path FROM product_images WHERE product_images.product_id = products.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image')
            ->where('user_id', $seller['id'])
            ->where('status', 'active')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $soldCount = $this->productModel->where('user_id', $seller['id'])->where('status', 'sold')->countAllResults();
        $reviews = $this->reviewModel->getSellerReviews((int)$seller['id']);

        $avgRating = 0;
        if (!empty($reviews)) {
            $avgRating = round(array_sum(array_column($reviews, 'rating')) / count($reviews), 1);
        }

        $data = [
            'title'     => 'Profil Toko ' . esc($seller['full_name']) . ' — Bekasin-Aja',
            'seller'    => $seller,
            'products'  => $products,
            'soldCount' => $soldCount,
            'reviews'   => $reviews,
            'avgRating' => $avgRating,
        ];

        return view('profile/seller', $data);
    }
}
