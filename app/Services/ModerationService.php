<?php

namespace App\Services;

use App\Models\AuditLogModel;
use App\Models\ReportModel;

class ModerationService
{
    protected AuditLogModel $auditLogModel;
    protected ReportModel $reportModel;

    public function __construct()
    {
        $this->auditLogModel = new AuditLogModel();
        $this->reportModel = new ReportModel();
    }

    /**
     * Log administrative action
     */
    public function logAdminAction(int $adminId, string $action, string $targetType, ?int $targetId = null, ?array $metadata = null): void
    {
        $request = service('request');
        $this->auditLogModel->insert([
            'admin_id'    => $adminId,
            'action'      => $action,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'metadata'    => $metadata ? json_encode($metadata) : null,
            'ip_address'  => $request->getIPAddress(),
            'user_agent'  => substr($request->getUserAgent()->getAgentString(), 0, 255),
        ]);
    }

    /**
     * Report content/user with anti-spam check
     */
    public function submitReport(int $reporterId, string $targetType, int $targetId, string $reason, string $description): array
    {
        // Check if report already filed by same user
        $existing = $this->reportModel->where([
            'reporter_id' => $reporterId,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'status'      => 'open',
        ])->first();

        if ($existing) {
            return ['success' => false, 'message' => 'Anda sudah pernah melaporkan item ini. Laporan Anda sedang dalam antrean peninjauan.'];
        }

        $this->reportModel->insert([
            'reporter_id' => $reporterId,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'reason'      => $reason,
            'description' => trim($description),
            'status'      => 'open',
        ]);

        return ['success' => true];
    }
}
