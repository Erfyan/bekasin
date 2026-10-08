<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'auto_increment' => true,
            ],
            'admin_id' => [
                'type' => 'BIGINT',
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'target_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'target_id' => [
                'type' => 'BIGINT',
                'null' => true,
            ],
            'metadata' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
                'null'       => true,
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('admin_id', 'users', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('audit_logs', true);

        $this->db->query("CREATE INDEX IF NOT EXISTS idx_audit_logs_admin_id ON audit_logs(admin_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_audit_logs_action ON audit_logs(action)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_audit_logs_created_at ON audit_logs(created_at DESC)");
    }

    public function down()
    {
        $this->forge->dropTable('audit_logs', true);
    }
}
