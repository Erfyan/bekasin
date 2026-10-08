<?php

namespace App\Models;

use CodeIgniter\Model;

class OfferModel extends Model
{
    protected $table            = 'offers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_id',
        'buyer_id',
        'seller_id',
        'offered_price',
        'counter_price',
        'status',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getOffersForUser(int $userId, string $type = 'received')
    {
        $builder = $this->select('offers.*, products.title as product_title, products.slug as product_slug, products.price as original_price, products.status as product_status, buyer.full_name as buyer_name, buyer.avatar_url as buyer_avatar, seller.full_name as seller_name, (SELECT image_path FROM product_images WHERE product_images.product_id = products.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as product_image')
            ->join('products', 'products.id = offers.product_id')
            ->join('users as buyer', 'buyer.id = offers.buyer_id')
            ->join('users as seller', 'seller.id = offers.seller_id');

        if ($type === 'sent') {
            $builder->where('offers.buyer_id', $userId);
        } else {
            $builder->where('offers.seller_id', $userId);
        }

        return $builder->orderBy('offers.updated_at', 'DESC')->findAll();
    }
}
