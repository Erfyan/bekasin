<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOffersTable extends Migration
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
            ],
            'buyer_id' => [
                'type' => 'BIGINT',
            ],
            'seller_id' => [
                'type' => 'BIGINT',
            ],
            'offered_price' => [
                'type'       => 'NUMERIC',
                'constraint' => '14,2',
            ],
            'counter_price' => [
                'type'       => 'NUMERIC',
                'constraint' => '14,2',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'default'    => 'pending',
            ],
            'notes' => [
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
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('buyer_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('seller_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('offers', true);

        $this->db->query("CREATE INDEX IF NOT EXISTS idx_offers_product_id ON offers(product_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_offers_buyer_id ON offers(buyer_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_offers_seller_id ON offers(seller_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_offers_status ON offers(status)");
    }

    public function down()
    {
        $this->forge->dropTable('offers', true);
    }
}
