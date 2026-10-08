<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTable extends Migration
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
            'category_id' => [
                'type' => 'BIGINT',
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'unique'     => true,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'price' => [
                'type'       => 'NUMERIC',
                'constraint' => '14,2',
                'default'    => 0.00,
            ],
            'condition' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'default'    => 'good',
            ],
            'delivery_method' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'default'    => 'both',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'default'    => 'active',
            ],
            'has_scratches' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'has_damages' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'is_functional' => [
                'type'    => 'BOOLEAN',
                'default' => true,
            ],
            'was_repaired' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'completeness_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'province' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'meetup_location' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'views_count' => [
                'type'    => 'INTEGER',
                'default' => 0,
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
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('products', true);

        // PostgreSQL Indexes
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_products_slug ON products(slug)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_products_user_id ON products(user_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_products_category_id ON products(category_id)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_products_status ON products(status)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_products_price ON products(price)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_products_city ON products(city)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_products_province ON products(province)");
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_products_created_at ON products(created_at DESC)");
    }

    public function down()
    {
        $this->forge->dropTable('products', true);
    }
}
