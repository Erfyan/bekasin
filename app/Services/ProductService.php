<?php

namespace App\Services;

use App\Models\ProductModel;
use App\Models\ProductImageModel;
use Config\Database;

class ProductService
{
    protected ProductModel $productModel;
    protected ProductImageModel $productImageModel;
    protected StorageService $storageService;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->productImageModel = new ProductImageModel();
        $this->storageService = new StorageService();
    }

    /**
     * Create slug from title
     */
    public function generateSlug(string $title): string
    {
        $baseSlug = url_title($title, '-', true);
        $slug = $baseSlug;
        $counter = 1;

        while ($this->productModel->where('slug', $slug)->countAllResults() > 0) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Store new product with transactional safety and uploaded images
     */
    public function createProduct(array $data, array $uploadedFiles, int $userId): array
    {
        $db = Database::connect();
        $db->transBegin();

        try {
            $slug = $this->generateSlug($data['title']);

            $productData = [
                'user_id'            => $userId,
                'category_id'        => (int) $data['category_id'],
                'title'              => trim($data['title']),
                'slug'               => $slug,
                'description'        => trim($data['description']),
                'price'              => (float) $data['price'],
                'condition'          => $data['condition'],
                'delivery_method'    => $data['delivery_method'],
                'status'             => 'active',
                'has_scratches'      => !empty($data['has_scratches']),
                'has_damages'        => !empty($data['has_damages']),
                'is_functional'      => !empty($data['is_functional']),
                'was_repaired'       => !empty($data['was_repaired']),
                'completeness_notes' => $data['completeness_notes'] ?? null,
                'province'           => trim($data['province']),
                'city'               => trim($data['city']),
                'meetup_location'    => $data['meetup_location'] ?? null,
                'views_count'        => 0,
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ];

            $productId = $this->productModel->insert($productData, true);

            if (!$productId) {
                $db->transRollback();
                return ['success' => false, 'message' => 'Gagal menyimpan data produk.'];
            }

            // Upload images
            $sortOrder = 0;
            foreach ($uploadedFiles as $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $imageUrl = $this->storageService->upload($file, 'product-images', "products/{$productId}");
                    $this->productImageModel->insert([
                        'product_id' => $productId,
                        'image_path' => $imageUrl,
                        'is_primary' => ($sortOrder === 0),
                        'sort_order' => $sortOrder,
                    ]);
                    $sortOrder++;
                }
            }

            $db->transCommit();
            return ['success' => true, 'slug' => $slug, 'product_id' => $productId];
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Error creating product: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Increment views count intelligently without artificial spam
     */
    public function recordView(int $productId): void
    {
        $viewedKey = 'viewed_product_' . $productId;
        if (!session()->get($viewedKey)) {
            $this->productModel->where('id', $productId)->set('views_count', 'views_count + 1', false)->update();
            session()->set($viewedKey, time());
        }
    }
}
