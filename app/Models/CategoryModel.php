<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'slug',
        'icon',
        'image_url',
        'description',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get active categories with products count
     */
    public function getActiveCategoriesWithCount()
    {
        return $this->select('categories.*, COUNT(products.id) as products_count')
            ->join('products', 'products.category_id = categories.id AND products.status = \'active\'', 'left')
            ->where('categories.is_active', true)
            ->groupBy('categories.id')
            ->orderBy('categories.name', 'ASC')
            ->findAll();
    }
}
