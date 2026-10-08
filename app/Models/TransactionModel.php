<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'transaction_code',
        'buyer_id',
        'seller_id',
        'product_id',
        'offer_id',
        'agreed_price',
        'delivery_method',
        'shipping_address',
        'meetup_location',
        'status',
        'payment_method',
        'payment_status',
        'payment_proof_url',
        'buyer_confirmation',
        'seller_confirmation',
        'completed_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getTransactionDetail(int $id)
    {
        return $this->select('transactions.*, products.title as product_title, products.slug as product_slug, (SELECT image_path FROM product_images WHERE product_images.product_id = products.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as product_image, buyer.full_name as buyer_name, buyer.phone as buyer_phone, buyer.city as buyer_city, seller.full_name as seller_name, seller.phone as seller_phone, seller.city as seller_city')
            ->join('products', 'products.id = transactions.product_id')
            ->join('users as buyer', 'buyer.id = transactions.buyer_id')
            ->join('users as seller', 'seller.id = transactions.seller_id')
            ->where('transactions.id', $id)
            ->first();
    }
}
