<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'price',
        'condition',
        'delivery_method',
        'status',
        'has_scratches',
        'has_damages',
        'is_functional',
        'was_repaired',
        'completeness_notes',
        'province',
        'city',
        'meetup_location',
        'views_count',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get single product with full seller, category, and images details
     */
    public function getProductDetail(string $slug)
    {
        $product = $this->select('products.*, users.full_name as seller_name, users.username as seller_username, users.avatar_url as seller_avatar, users.city as seller_city, users.created_at as seller_joined_at, categories.name as category_name, categories.slug as category_slug')
            ->join('users', 'users.id = products.user_id')
            ->join('categories', 'categories.id = products.category_id')
            ->where('products.slug', $slug)
            ->first();

        if ($product) {
            $imageModel = new ProductImageModel();
            $product['images'] = $imageModel->where('product_id', $product['id'])
                ->orderBy('is_primary', 'DESC')
                ->orderBy('sort_order', 'ASC')
                ->findAll();

            $product['primary_image'] = !empty($product['images']) ? $product['images'][0]['image_path'] : null;
        }

        return $product;
    }

    /**
     * Filter and paginate products with search parameters
     */
    public function getFilteredProducts(array $filters = [], int $perPage = 12)
    {
        $builder = $this->select('products.*, users.full_name as seller_name, users.city as seller_city, categories.name as category_name, categories.slug as category_slug, (SELECT image_path FROM product_images WHERE product_images.product_id = products.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image')
            ->join('users', 'users.id = products.user_id')
            ->join('categories', 'categories.id = products.category_id');

        if (!empty($filters['status'])) {
            $builder->where('products.status', $filters['status']);
        } else {
            $builder->where('products.status', 'active');
        }

        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $builder->groupStart()
                ->like('products.title', $keyword, 'both', null, true)
                ->orLike('products.description', $keyword, 'both', null, true)
                ->orLike('products.city', $keyword, 'both', null, true)
                ->groupEnd();
        }

        if (!empty($filters['category'])) {
            $builder->where('categories.slug', $filters['category']);
        }

        if (!empty($filters['condition'])) {
            $builder->where('products.condition', $filters['condition']);
        }

        if (!empty($filters['min_price'])) {
            $builder->where('products.price >=', (float) $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $builder->where('products.price <=', (float) $filters['max_price']);
        }

        if (!empty($filters['city'])) {
            $builder->like('products.city', $filters['city'], 'both', null, true);
        }

        if (!empty($filters['delivery_method']) && $filters['delivery_method'] !== 'all') {
            $builder->groupStart()
                ->where('products.delivery_method', $filters['delivery_method'])
                ->orWhere('products.delivery_method', 'both')
                ->groupEnd();
        }

        // Sorting
        $sort = $filters['sort'] ?? 'latest';
        switch ($sort) {
            case 'price_low':
                $builder->orderBy('products.price', 'ASC');
                break;
            case 'price_high':
                $builder->orderBy('products.price', 'DESC');
                break;
            case 'popular':
                $builder->orderBy('products.views_count', 'DESC');
                break;
            case 'latest':
            default:
                $builder->orderBy('products.created_at', 'DESC');
                break;
        }

        return [
            'products' => $builder->paginate($perPage),
            'pager'    => $this->pager,
        ];
    }
}
