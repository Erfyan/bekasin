<?php

namespace App\Controllers;

use App\Models\ConversationModel;
use App\Models\MessageModel;
use App\Models\ProductModel;
use App\Services\NotificationService;
use CodeIgniter\HTTP\ResponseInterface;

class Message extends BaseController
{
    protected ConversationModel $conversationModel;
    protected MessageModel $messageModel;
    protected ProductModel $productModel;
    protected NotificationService $notificationService;

    public function __construct()
    {
        $this->conversationModel = new ConversationModel();
        $this->messageModel = new MessageModel();
        $this->productModel = new ProductModel();
        $this->notificationService = new NotificationService();
    }

    /**
     * Kotak Masuk Pesan Chat
     */
    public function index(): string
    {
        $userId = (int) session()->get('user_id');
        $conversations = $this->conversationModel->getUserConversations($userId);

        $data = [
            'title'         => 'Pesan Chat — Bekasin-Aja',
            'conversations' => $conversations,
        ];

        return view('messages/index', $data);
    }

    /**
     * Buka Percakapan Chat Tertentu
     */
    public function show(int $conversationId): string
    {
        $userId = (int) session()->get('user_id');
        $conversation = $this->conversationModel->find($conversationId);

        if (!$conversation || ($conversation['buyer_id'] != $userId && $conversation['seller_id'] != $userId)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Percakapan tidak ditemukan.');
        }

        // Mark unread messages as read
        $this->messageModel->where('conversation_id', $conversationId)
            ->where('sender_id !=', $userId)
            ->set(['is_read' => true])
            ->update();

        $messages = $this->messageModel->getConversationMessages($conversationId);
        $product = $conversation['product_id'] ? $this->productModel->find($conversation['product_id']) : null;
        $conversations = $this->conversationModel->getUserConversations($userId);

        // Cari lawan bicara
        $partnerId = ($conversation['buyer_id'] == $userId) ? $conversation['seller_id'] : $conversation['buyer_id'];
        $userModel = new \App\Models\UserModel();
        $partner = $userModel->find($partnerId);

        $data = [
            'title'          => 'Chat dengan ' . esc($partner['full_name'] ?? 'Pengguna') . ' — Bekasin-Aja',
            'conversation'   => $conversation,
            'messages'       => $messages,
            'product'        => $product,
            'partner'        => $partner,
            'conversations'  => $conversations,
            'activeConvId'   => $conversationId,
        ];

        return view('messages/show', $data);
    }

    /**
     * Mulai / Buka Chat untuk Produk Tertentu
     */
    public function startForProduct(int $productId)
    {
        $userId = (int) session()->get('user_id');
        $product = $this->productModel->find($productId);

        if (!$product) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan.');
        }

        if ($product['user_id'] == $userId) {
            return redirect()->back()->with('error', 'Ini adalah barang jualan Anda sendiri.');
        }

        // Cari apakah sudah ada percakapan untuk produk ini antara buyer dan seller
        $existing = $this->conversationModel->where('product_id', $productId)
            ->where('buyer_id', $userId)
            ->where('seller_id', $product['user_id'])
            ->first();

        if ($existing) {
            return redirect()->to(base_url('messages/' . $existing['id']));
        }

        // Buat percakapan baru
        $convId = $this->conversationModel->insert([
            'product_id'      => $productId,
            'buyer_id'        => $userId,
            'seller_id'       => $product['user_id'],
            'last_message_at' => date('Y-m-d H:i:s'),
        ], true);

        // Kirim pesan pembuka otomatis opsional
        $initialMessage = $this->request->getPost('message');
        if (!empty($initialMessage)) {
            $this->messageModel->insert([
                'conversation_id' => $convId,
                'sender_id'       => $userId,
                'message_text'    => trim($initialMessage),
                'is_read'         => false,
            ]);
        }

        return redirect()->to(base_url('messages/' . $convId));
    }

    /**
     * Kirim Pesan dalam Percakapan
     */
    public function send(int $conversationId)
    {
        $userId = (int) session()->get('user_id');
        $conversation = $this->conversationModel->find($conversationId);

        if (!$conversation || ($conversation['buyer_id'] != $userId && $conversation['seller_id'] != $userId)) {
            return redirect()->to(base_url('messages'))->with('error', 'Akses ditolak.');
        }

        $messageText = trim($this->request->getPost('message_text') ?? '');
        if (empty($messageText)) {
            return redirect()->back()->with('error', 'Pesan tidak boleh kosong.');
        }

        $this->messageModel->insert([
            'conversation_id' => $conversationId,
            'sender_id'       => $userId,
            'message_text'    => $messageText,
            'is_read'         => false,
        ]);

        $this->conversationModel->update($conversationId, [
            'last_message_at' => date('Y-m-d H:i:s'),
        ]);

        // Notifikasi ke lawan bicara
        $receiverId = ($conversation['buyer_id'] == $userId) ? $conversation['seller_id'] : $conversation['buyer_id'];
        $this->notificationService->notify(
            $receiverId,
            'new_message',
            'Pesan Baru Masuk',
            substr($messageText, 0, 80),
            'conversation',
            $conversationId
        );

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => 'Pesan terkirim.']);
        }

        return redirect()->to(base_url('messages/' . $conversationId));
    }

    /**
     * AJAX Long-Polling / Polling untuk Realtime Chat updates
     */
    public function poll(int $conversationId): ResponseInterface
    {
        $userId = (int) session()->get('user_id');
        $afterId = (int) ($this->request->getGet('after_id') ?? 0);

        $messages = $this->messageModel->select('messages.*, users.full_name as sender_name')
            ->join('users', 'users.id = messages.sender_id')
            ->where('messages.conversation_id', $conversationId)
            ->where('messages.id >', $afterId)
            ->orderBy('messages.created_at', 'ASC')
            ->findAll();

        if (!empty($messages)) {
            $this->messageModel->where('conversation_id', $conversationId)
                ->where('sender_id !=', $userId)
                ->set(['is_read' => true])
                ->update();
        }

        return $this->response->setJSON([
            'success'  => true,
            'messages' => $messages,
        ]);
    }
}
