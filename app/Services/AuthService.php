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
        $email = strtolower(trim($data['email']));
        $isAdmin = ($email === 'admin@bekasin.com');

        $userData = [
            'full_name'     => trim($data['full_name']),
            'username'      => strtolower(trim($data['username'])),
            'email'         => $email,
            'phone'         => trim($data['phone']),
            'password_hash' => $passwordHash,
            'role'          => $isAdmin ? 'admin' : 'member',
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

        $cleanEmail = strtolower(trim((string) $user['email']));
        $isAdminEmail = ($cleanEmail === 'admin@bekasin.com');

        // Jika email adalah admin@bekasin.com, pastikan role admin & status active di DB
        if ($isAdminEmail) {
            $syncData = [];
            if ($user['role'] !== 'admin') {
                $syncData['role'] = 'admin';
                $user['role'] = 'admin';
            }
            if ($user['status'] !== 'active') {
                $syncData['status'] = 'active';
                $user['status'] = 'active';
            }
            if (!empty($syncData)) {
                try {
                    $this->userModel->update($user['id'], $syncData);
                } catch (\Throwable $e) {
                    log_message('error', 'Auto-upgrade admin@bekasin.com role error: ' . $e->getMessage());
                }
            }
        } else {
            if ($user['status'] === 'suspended' || $user['status'] === 'banned') {
                return [
                    'success' => false,
                    'message' => 'Akun Anda sedang dinonaktifkan oleh administrator.',
                ];
            }
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
            'role'         => $isAdminEmail ? 'admin' : $user['role'],
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
