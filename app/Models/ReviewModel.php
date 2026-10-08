<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table            = 'reviews';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'transaction_id',
        'seller_id',
        'buyer_id',
        'product_id',
        'rating',
        'comment',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function getSellerReviews(int $sellerId)
    {
        return $this->select('reviews.*, buyer.full_name as buyer_name, buyer.avatar_url as buyer_avatar, products.title as product_title')
            ->join('users as buyer', 'buyer.id = reviews.buyer_id')
            ->join('products', 'products.id = reviews.product_id')
            ->where('reviews.seller_id', $sellerId)
            ->orderBy('reviews.created_at', 'DESC')
            ->findAll();
    }
}
