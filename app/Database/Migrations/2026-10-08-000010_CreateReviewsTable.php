<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'auto_increment' => true,
            ],
            'transaction_id' => [
                'type'   => 'BIGINT',
                'unique' => true,
            ],
            'seller_id' => [
                'type' => 'BIGINT',
            ],
            'buyer_id' => [
                'type' => 'BIGINT',
            ],
            'product_id' => [
                'type' => 'BIGINT',
            ],
            'rating' => [
                'type' => 'SMALLINT',
            ],
            'comment' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('transaction_id', 'transactions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('seller_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('buyer_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('reviews', true);

        $this->db->query("ALTER TABLE reviews ADD CONSTRAINT chk_reviews_rating CHECK (rating >= 1 AND rating <= 5)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_reviews_seller_id ON reviews(seller_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_reviews_buyer_id ON reviews(buyer_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_reviews_product_id ON reviews(product_id)");
    }

    public function down()
    {
        $this->forge->dropTable('reviews', true);
    }
}
