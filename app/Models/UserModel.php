<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'full_name',
        'username',
        'email',
        'phone',
        'password_hash',
        'role',
        'status',
        'avatar_url',
        'bio',
        'province',
        'city',
        'address',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'full_name' => 'required|min_length[3]|max_length[150]',
        'username'  => 'required|alpha_numeric_space|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]',
        'email'     => 'required|valid_email|max_length[150]|is_unique[users.email,id,{id}]',
        'phone'     => 'required|min_length[8]|max_length[25]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Email ini sudah terdaftar di Bekasin-Aja.',
            'valid_email' => 'Format email tidak valid.',
        ],
        'username' => [
            'is_unique' => 'Username ini sudah digunakan.',
        ],
    ];

    /**
     * Find by username or email
     */
    public function findByCredentials(string $identifier)
    {
        return $this->groupStart()
            ->where('email', $identifier)
            ->orWhere('username', $identifier)
            ->groupEnd()
            ->first();
    }
}
