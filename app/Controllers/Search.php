<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Search extends BaseController
{
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    /**
     * Halaman Utama Search
     */
    public function index()
    {
        return redirect()->to(base_url('products') . '?' . http_build_query($this->request->getGet()));
    }

    /**
     * Endpoint Asynchronous Live Search (Debounced via Fetch API)
     */
    public function live()
    {
        $keyword = trim($this->request->getGet('q') ?? '');

        if (strlen($keyword) < 2) {
            return $this->response->setJSON([
                'status'  => 'success',
                'results' => [],
            ]);
        }

        $products = $this->productModel->select('products.id, products.title, products.slug, products.price, products.city, (SELECT image_path FROM product_images WHERE product_images.product_id = products.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image')
            ->where('products.status', 'active')
            ->groupStart()
                ->like('products.title', $keyword, 'both', null, true)
                ->orLike('products.description', $keyword, 'both', null, true)
                ->orLike('products.city', $keyword, 'both', null, true)
            ->groupEnd()
            ->orderBy('products.views_count', 'DESC')
            ->findAll(6);

        $results = [];
        foreach ($products as $p) {
            $results[] = [
                'id'              => (int) $p['id'],
                'title'           => esc($p['title']),
                'price_formatted' => 'Rp ' . number_format((float) $p['price'], 0, ',', '.'),
                'city'            => esc($p['city']),
                'image'           => $p['primary_image'],
                'url'             => base_url('products/' . $p['slug']),
            ];
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'results' => $results,
        ]);
    }
}
