<?php

namespace App\Models;

use CodeIgniter\Model;

class WishlistModel extends Model
{
    protected $table            = 'wishlists';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'product_id',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function getUserWishlist(int $userId)
    {
        return $this->select('wishlists.*, products.title, products.slug, products.price, products.condition, products.city, products.status as product_status, (SELECT image_path FROM product_images WHERE product_images.product_id = products.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as primary_image, users.full_name as seller_name')
            ->join('products', 'products.id = wishlists.product_id')
            ->join('users', 'users.id = products.user_id')
            ->where('wishlists.user_id', $userId)
            ->orderBy('wishlists.created_at', 'DESC')
            ->findAll();
    }
}
