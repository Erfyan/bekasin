<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConversationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'auto_increment' => true,
            ],
            'product_id' => [
                'type' => 'BIGINT',
                'null' => true,
            ],
            'buyer_id' => [
                'type' => 'BIGINT',
            ],
            'seller_id' => [
                'type' => 'BIGINT',
            ],
            'last_message_at' => [
                'type' => 'TIMESTAMPTZ',
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
        $this->forge->addForeignKey('product_id', 'products', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('buyer_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('seller_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('conversations', true);

        $this->db->query("CREATE INDEX IF NOT EXISTS idx_conversations_buyer ON conversations(buyer_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_conversations_seller ON conversations(seller_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_conversations_last_msg ON conversations(last_message_at DESC)");
    }

    public function down()
    {
        $this->forge->dropTable('conversations', true);
    }
}
