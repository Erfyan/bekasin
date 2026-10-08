<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'BIGINT',
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'reference_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'reference_id' => [
                'type' => 'BIGINT',
                'null' => true,
            ],
            'is_read' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('notifications', true);

        $this->db->query("CREATE INDEX IF NOT EXISTS idx_notifications_user_id ON notifications(user_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_notifications_is_read ON notifications(is_read)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_notifications_created_at ON notifications(created_at DESC)");
    }

    public function down()
    {
        $this->forge->dropTable('notifications', true);
    }
}
