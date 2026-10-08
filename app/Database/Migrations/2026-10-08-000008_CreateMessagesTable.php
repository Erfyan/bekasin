<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'auto_increment' => true,
            ],
            'conversation_id' => [
                'type' => 'BIGINT',
            ],
            'sender_id' => [
                'type' => 'BIGINT',
            ],
            'message_text' => [
                'type' => 'TEXT',
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
        $this->forge->addForeignKey('conversation_id', 'conversations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('sender_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('messages', true);

        $this->db->query("CREATE INDEX IF NOT EXISTS idx_messages_conversation_id ON messages(conversation_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_messages_sender_id ON messages(sender_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_messages_is_read ON messages(is_read)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_messages_created_at ON messages(created_at ASC)");
    }

    public function down()
    {
        $this->forge->dropTable('messages', true);
    }
}
