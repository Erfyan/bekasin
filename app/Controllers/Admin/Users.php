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
        $builder = $this->userModel;

        if (!empty($search)) {
            $builder = $builder->groupStart()
                ->like('full_name', $search)
                ->orLike('email', $search)
                ->orLike('username', $search)
                ->groupEnd();
        }

        $users = $builder->orderBy('created_at', 'DESC')->paginate(20);

        $data = [
            'title' => 'Kelola Pengguna — Admin Panel Bekasin-Aja',
            'users' => $users,
            'pager' => $this->userModel->pager,
            'search' => $search,
        ];

        return view('admin/users/index', $data);
    }

    public function changeStatus(int $id)
    {
        $adminId = (int) session()->get('user_id');
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        $status = $this->request->getPost('status');
        if (!in_array($status, ['active', 'suspended', 'banned'], true)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $this->userModel->update($id, ['status' => $status]);

        $this->moderationService->logAdminAction(
            $adminId,
            'change_user_status',
            'user',
            $id,
            ['new_status' => $status, 'target_user' => $user['username']]
        );

        session()->setFlashdata('success', 'Status pengguna ' . esc($user['username']) . ' diubah menjadi ' . ucfirst($status));
        return redirect()->to(base_url('admin/users'));
    }
}
