<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Services\ModerationService;

class Users extends BaseController
{
    protected UserModel $userModel;
    protected ModerationService $moderationService;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->moderationService = new ModerationService();
    }

    public function index(): string
    {
        $search = $this->request->getGet('q');
        $roleFilter = $this->request->getGet('role');
        $statusFilter = $this->request->getGet('status');
        $users = [];
        $pager = null;

        try {
            $builder = $this->userModel;

            if (!empty($search)) {
                $builder = $builder->groupStart()
                    ->like('full_name', $search)
                    ->orLike('email', $search)
                    ->orLike('username', $search)
                    ->orLike('phone', $search)
                    ->groupEnd();
            }

            if (!empty($roleFilter) && $roleFilter !== 'all') {
                $builder = $builder->where('role', $roleFilter);
            }

            if (!empty($statusFilter) && $statusFilter !== 'all') {
                $builder = $builder->where('status', $statusFilter);
            }

            $users = $builder->orderBy('created_at', 'DESC')->paginate(20);
            $pager = $this->userModel->pager;
        } catch (\Throwable $e) {
            log_message('error', 'Admin users index error: ' . $e->getMessage());
        }

        $data = [
            'title'        => 'Kelola Pengguna — Admin Panel Bekasin-Aja',
            'users'        => $users,
            'pager'        => $pager,
            'search'       => $search,
            'roleFilter'   => $roleFilter ?? 'all',
            'statusFilter' => $statusFilter ?? 'all',
        ];

        return view('admin/users/index', $data);
    }

    public function store()
    {
        $adminId = (int) session()->get('user_id');

        $fullName = trim((string) $this->request->getPost('full_name'));
        $username = strtolower(trim((string) $this->request->getPost('username')));
        $email    = strtolower(trim((string) $this->request->getPost('email')));
        $phone    = trim((string) $this->request->getPost('phone'));
        $password = (string) $this->request->getPost('password');
        $role     = $this->request->getPost('role') === 'admin' ? 'admin' : 'member';
        $status   = in_array($this->request->getPost('status'), ['active', 'suspended', 'banned'], true) ? $this->request->getPost('status') : 'active';
        $city     = trim((string) $this->request->getPost('city'));
        $province = trim((string) $this->request->getPost('province'));

        if (empty($fullName) || empty($username) || empty($email) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Nama, username, email, dan password wajib diisi.');
        }

        // Cek keunikan email & username
        $existingEmail = $this->userModel->where('LOWER(email)', $email)->first();
        if ($existingEmail) {
            return redirect()->back()->withInput()->with('error', 'Email sudah digunakan oleh pengguna lain.');
        }

        $existingUsername = $this->userModel->where('LOWER(username)', $username)->first();
        if ($existingUsername) {
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan oleh pengguna lain.');
        }

        $passwordHash = password_hash($password, defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT);

        try {
            $insertId = $this->userModel->insert([
                'full_name'     => $fullName,
                'username'      => $username,
                'email'         => $email,
                'phone'         => $phone,
                'password_hash' => $passwordHash,
                'role'          => ($email === 'admin@bekasin.com') ? 'admin' : $role,
                'status'        => $status,
                'city'          => $city ?: null,
                'province'      => $province ?: null,
            ]);

            $this->moderationService->logAdminAction($adminId, 'create_user', 'user', $insertId, [
                'username' => $username,
                'role'     => $role,
                'status'   => $status,
            ]);

            session()->setFlashdata('success', "Pengguna baru '{$username}' berhasil dibuat.");
        } catch (\Throwable $e) {
            log_message('error', 'Admin create user error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal membuat pengguna: ' . $e->getMessage());
        }

        return redirect()->to(base_url('admin/users'));
    }

    public function update(int $id)
    {
        $adminId = (int) session()->get('user_id');
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        $fullName = trim((string) $this->request->getPost('full_name'));
        $username = strtolower(trim((string) $this->request->getPost('username')));
        $email    = strtolower(trim((string) $this->request->getPost('email')));
        $phone    = trim((string) $this->request->getPost('phone'));
        $password = (string) $this->request->getPost('password');
        $role     = $this->request->getPost('role') === 'admin' ? 'admin' : 'member';
        $status   = in_array($this->request->getPost('status'), ['active', 'suspended', 'banned'], true) ? $this->request->getPost('status') : 'active';
        $city     = trim((string) $this->request->getPost('city'));
        $province = trim((string) $this->request->getPost('province'));

        // Proteksi untuk admin@bekasin.com
        if (strtolower($user['email']) === 'admin@bekasin.com') {
            $role = 'admin';
            $status = 'active';
        }

        // Proteksi agar admin yang sedang login tidak mendemote dirinya sendiri
        if ($adminId === $id) {
            $role = 'admin';
            $status = 'active';
        }

        // Cek duplikasi email pada user lain
        $existingEmail = $this->userModel->where('LOWER(email)', $email)->where('id !=', $id)->first();
        if ($existingEmail) {
            return redirect()->back()->with('error', 'Email sudah digunakan pengguna lain.');
        }

        $existingUsername = $this->userModel->where('LOWER(username)', $username)->where('id !=', $id)->first();
        if ($existingUsername) {
            return redirect()->back()->with('error', 'Username sudah digunakan pengguna lain.');
        }

        $updateData = [
            'full_name' => $fullName,
            'username'  => $username,
            'email'     => $email,
            'phone'     => $phone,
            'role'      => $role,
            'status'    => $status,
            'city'      => $city ?: null,
            'province'  => $province ?: null,
        ];

        // Jika ada password baru yang diisi
        if (!empty($password)) {
            $updateData['password_hash'] = password_hash($password, defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT);
        }

        try {
            $this->userModel->update($id, $updateData);

            $this->moderationService->logAdminAction($adminId, 'update_user', 'user', $id, [
                'target_username' => $username,
                'new_role'        => $role,
                'new_status'      => $status,
            ]);

            session()->setFlashdata('success', "Data pengguna '{$username}' berhasil diperbarui.");
        } catch (\Throwable $e) {
            log_message('error', 'Admin update user error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal memperbarui pengguna: ' . $e->getMessage());
        }

        return redirect()->to(base_url('admin/users'));
    }

    public function changeStatus(int $id)
    {
        $adminId = (int) session()->get('user_id');
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        if (strtolower($user['email']) === 'admin@bekasin.com' || $adminId === $id) {
            return redirect()->back()->with('error', 'Tidak dapat mengubah status akun Administrator utama.');
        }

        $status = $this->request->getPost('status');
        if (!in_array($status, ['active', 'suspended', 'banned'], true)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        try {
            $this->userModel->update($id, ['status' => $status]);

            $this->moderationService->logAdminAction(
                $adminId,
                'change_user_status',
                'user',
                $id,
                ['new_status' => $status, 'target_user' => $user['username']]
            );

            session()->setFlashdata('success', 'Status pengguna ' . esc($user['username']) . ' diubah menjadi ' . ucfirst($status));
        } catch (\Throwable $e) {
            log_message('error', 'Admin changeStatus user error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal mengubah status pengguna: ' . $e->getMessage());
        }

        return redirect()->to(base_url('admin/users'));
    }

    public function delete(int $id)
    {
        $adminId = (int) session()->get('user_id');
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        if (strtolower($user['email']) === 'admin@bekasin.com') {
            return redirect()->back()->with('error', 'Akun Administrator utama tidak boleh dihapus.');
        }

        if ($adminId === $id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
        }

        try {
            $this->userModel->delete($id);

            $this->moderationService->logAdminAction($adminId, 'delete_user', 'user', $id, [
                'deleted_username' => $user['username'],
                'deleted_email'    => $user['email'],
            ]);

            session()->setFlashdata('success', "Pengguna '{$user['username']}' berhasil dihapus secara permanen.");
        } catch (\Throwable $e) {
            log_message('error', 'Admin delete user error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }

        return redirect()->to(base_url('admin/users'));
    }
}
