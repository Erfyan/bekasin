<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductImagesTable extends Migration
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
            'image_path' => [
                'type' => 'TEXT',
            ],
            'is_primary' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'sort_order' => [
                'type'    => 'SMALLINT',
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('product_images', true);

        $this->db->query("CREATE INDEX IF NOT EXISTS idx_product_images_product_id ON product_images(product_id)");
    }

    public function down()
    {
        $this->forge->dropTable('product_images', true);
    }
}
