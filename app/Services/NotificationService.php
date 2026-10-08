<?php

namespace App\Services;

use App\Models\NotificationModel;

class NotificationService
{
    protected NotificationModel $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    public function notify(int $userId, string $type, string $title, string $message, ?string $refType = null, ?int $refId = null): int
    {
        return (int) $this->notificationModel->insert([
            'user_id'        => $userId,
            'type'           => $type,
            'title'          => $title,
            'message'        => $message,
            'reference_type' => $refType,
            'reference_id'   => $refId,
            'is_read'        => false,
        ], true);
    }
}
