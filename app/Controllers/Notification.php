<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use CodeIgniter\HTTP\ResponseInterface;

class Notification extends BaseController
{
    protected NotificationModel $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Halaman Daftar Notifikasi Pengguna
     */
    public function index(): string
    {
        $userId = (int) session()->get('user_id');

        $notifications = $this->notificationModel->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll(50);

        // Mark all as read when opening page
        $this->notificationModel->where('user_id', $userId)
            ->where('is_read', false)
            ->set(['is_read' => true])
            ->update();

        $data = [
            'title'         => 'Notifikasi Saya — Bekasin-Aja',
            'notifications' => $notifications,
        ];

        return view('notifications/index', $data);
    }

    /**
     * API Tandai Semua Dibaca
     */
    public function markRead(): ResponseInterface
    {
        $userId = (int) session()->get('user_id');
        $this->notificationModel->where('user_id', $userId)->set(['is_read' => true])->update();

        return $this->response->setJSON(['success' => true]);
    }

    /**
     * API Ambil Jumlah Belum Dibaca (untuk Badge Navbar)
     */
    public function unreadCount(): ResponseInterface
    {
        $userId = (int) session()->get('user_id');
        $count = $this->notificationModel->getUnreadCount($userId);

        return $this->response->setJSON(['unread_count' => $count]);
    }
}
