<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table            = 'messages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'conversation_id',
        'sender_id',
        'message_text',
        'is_read',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function getConversationMessages(int $conversationId)
    {
        return $this->select('messages.*, users.full_name as sender_name, users.avatar_url as sender_avatar')
            ->join('users', 'users.id = messages.sender_id')
            ->where('messages.conversation_id', $conversationId)
            ->orderBy('messages.created_at', 'ASC')
            ->findAll();
    }
}
