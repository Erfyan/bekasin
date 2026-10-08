<?php

namespace App\Models;

use CodeIgniter\Model;

class ConversationModel extends Model
{
    protected $table            = 'conversations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_id',
        'buyer_id',
        'seller_id',
        'last_message_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getUserConversations(int $userId)
    {
        return $this->select('conversations.*, products.title as product_title, products.price as product_price, (SELECT image_path FROM product_images WHERE product_images.product_id = products.id ORDER BY is_primary DESC, sort_order ASC LIMIT 1) as product_image, buyer.full_name as buyer_name, buyer.avatar_url as buyer_avatar, seller.full_name as seller_name, seller.avatar_url as seller_avatar, (SELECT message_text FROM messages WHERE messages.conversation_id = conversations.id ORDER BY created_at DESC LIMIT 1) as last_message_text, (SELECT COUNT(id) FROM messages WHERE messages.conversation_id = conversations.id AND messages.sender_id != ' . $userId . ' AND messages.is_read = FALSE) as unread_count')
            ->join('products', 'products.id = conversations.product_id', 'left')
            ->join('users as buyer', 'buyer.id = conversations.buyer_id')
            ->join('users as seller', 'seller.id = conversations.seller_id')
            ->groupStart()
                ->where('conversations.buyer_id', $userId)
                ->orWhere('conversations.seller_id', $userId)
            ->groupEnd()
            ->orderBy('conversations.last_message_at', 'DESC')
            ->findAll();
    }
}
