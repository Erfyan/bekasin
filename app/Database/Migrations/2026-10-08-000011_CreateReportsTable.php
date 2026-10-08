<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'auto_increment' => true,
            ],
            'reporter_id' => [
                'type' => 'BIGINT',
            ],
            'target_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'target_id' => [
                'type' => 'BIGINT',
            ],
            'reason' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'open',
            ],
            'admin_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('reporter_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('reports', true);

        $this->db->query("CREATE INDEX IF NOT EXISTS idx_reports_target ON reports(target_type, target_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_reports_status ON reports(status)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_reports_reporter_id ON reports(reporter_id)");
    }

    public function down()
    {
        $this->forge->dropTable('reports', true);
    }
}
