<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table            = 'audit_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'admin_id',
        'action',
        'target_type',
        'target_id',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function getLogsWithAdmin(int $limit = 50)
    {
        return $this->select('audit_logs.*, users.full_name as admin_name, users.username as admin_username')
            ->join('users', 'users.id = audit_logs.admin_id')
            ->orderBy('audit_logs.created_at', 'DESC')
            ->findAll($limit);
    }
}
