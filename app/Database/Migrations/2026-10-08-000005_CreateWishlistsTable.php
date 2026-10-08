<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWishlistsTable extends Migration
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
            'product_id' => [
                'type' => 'BIGINT',
            ],
            'created_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('wishlists', true);

        $this->db->query("CREATE UNIQUE INDEX IF NOT EXISTS idx_wishlist_user_product ON wishlists(user_id, product_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_wishlists_user_id ON wishlists(user_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_wishlists_product_id ON wishlists(product_id)");
    }

    public function down()
    {
        $this->forge->dropTable('wishlists', true);
    }
}
