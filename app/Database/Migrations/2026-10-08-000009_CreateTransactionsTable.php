<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'auto_increment' => true,
            ],
            'transaction_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'buyer_id' => [
                'type' => 'BIGINT',
            ],
            'seller_id' => [
                'type' => 'BIGINT',
            ],
            'product_id' => [
                'type' => 'BIGINT',
            ],
            'offer_id' => [
                'type' => 'BIGINT',
                'null' => true,
            ],
            'agreed_price' => [
                'type'       => 'NUMERIC',
                'constraint' => '14,2',
            ],
            'delivery_method' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'default'    => 'meetup',
            ],
            'shipping_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'meetup_location' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'default'    => 'pending',
            ],
            'payment_method' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'default'    => 'cod',
            ],
            'payment_status' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'default'    => 'unpaid',
            ],
            'payment_proof_url' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'buyer_confirmation' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'seller_confirmation' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
            ],
            'completed_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('buyer_id', 'users', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('seller_id', 'users', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('offer_id', 'offers', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('transactions', true);

        $this->db->query("CREATE INDEX IF NOT EXISTS idx_transactions_code ON transactions(transaction_code)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_transactions_buyer_id ON transactions(buyer_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_transactions_seller_id ON transactions(seller_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_transactions_product_id ON transactions(product_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_transactions_status ON transactions(status)");
    }

    public function down()
    {
        $this->forge->dropTable('transactions', true);
    }
}
