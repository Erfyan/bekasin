<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;

class Settings extends BaseController
{
    protected AuditLogModel $auditLogModel;

    public function __construct()
    {
        $this->auditLogModel = new AuditLogModel();
    }

    public function auditLogs(): string
    {
        $logs = $this->auditLogModel->getLogsWithAdmin(100);

        $data = [
            'title' => 'Audit Log Aktivitas Admin — Bekasin-Aja',
            'logs'  => $logs,
        ];

        return view('admin/audit_logs', $data);
    }
}
