<?php

namespace App\Services;

use App\Models\UserModel;

class AuthService
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Register a new user
     */
    public function register(array $data): array
    {
        $passwordHash = password_hash($data['password'], defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT);

        $userData = [
            'full_name'     => trim($data['full_name']),
            'username'      => strtolower(trim($data['username'])),
            'email'         => strtolower(trim($data['email'])),
            'phone'         => trim($data['phone']),
            'password_hash' => $passwordHash,
            'role'          => 'member',
            'status'        => 'active',
            'province'      => $data['province'] ?? null,
            'city'          => $data['city'] ?? null,
            'address'       => $data['address'] ?? null,
        ];

        $userId = $this->userModel->insert($userData, true);

        if (!$userId) {
            return [
                'success' => false,
                'errors'  => $this->userModel->errors(),
            ];
        }

        $user = $this->userModel->find($userId);
        return [
            'success' => true,
            'user'    => $user,
        ];
    }

    /**
     * Attempt login
     */
    public function attemptLogin(string $identifier, string $password): array
    {
        $user = $this->userModel->findByCredentials($identifier);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Email/Username atau password yang Anda masukkan salah.',
            ];
        }

        if ($user['status'] === 'suspended' || $user['status'] === 'banned') {
            return [
                'success' => false,
                'message' => 'Akun Anda sedang dinonaktifkan oleh administrator.',
            ];
        }

        if (!password_verify($password, $user['password_hash'])) {
            return [
                'success' => false,
                'message' => 'Email/Username atau password yang Anda masukkan salah.',
            ];
        }

        // Set secure session
        session()->regenerate();
        session()->set([
            'is_logged_in' => true,
            'user_id'      => $user['id'],
            'full_name'    => $user['full_name'],
            'username'     => $user['username'],
            'email'        => $user['email'],
            'role'         => $user['role'],
            'avatar_url'   => $user['avatar_url'],
        ]);

        return [
            'success' => true,
            'user'    => $user,
        ];
    }

    /**
     * Logout
     */
    public function logout(): void
    {
        session()->destroy();
    }
}
