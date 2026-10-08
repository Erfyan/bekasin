<?php

namespace App\Controllers;

use App\Services\AuthService;

class Auth extends BaseController
{
    protected AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    /**
     * Tampilan form Login
     */
    public function login(): string
    {
        return view('auth/login', [
            'title' => 'Masuk ke Akun Anda — Bekasin-Aja',
        ]);
    }

    /**
     * Proses autentikasi Login
     */
    public function attemptLogin()
    {
        $rules = [
            'identifier' => 'required',
            'password'   => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Silakan isi email/username dan password.');
        }

        try {
            $identifier = $this->request->getPost('identifier');
            $password = $this->request->getPost('password');

            $result = $this->authService->attemptLogin($identifier, $password);

            if (!$result['success']) {
                return redirect()->back()->withInput()->with('error', $result['message']);
            }

            session()->setFlashdata('success', 'Selamat datang kembali, ' . esc($result['user']['full_name']) . '!');

            // Redirect admin ke admin panel, user ke dashboard/beranda
            if ($result['user']['role'] === 'admin') {
                return redirect()->to(base_url('admin'));
            }

            return redirect()->to(base_url('/'));
        } catch (\Exception $e) {
            log_message('error', 'Login error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat login. Silakan coba lagi nanti.');
        }
    }

    /**
     * Tampilan form Register
     */
    public function register(): string
    {
        return view('auth/register', [
            'title' => 'Daftar Akun Baru — Bekasin-Aja',
        ]);
    }

    /**
     * Proses pendaftaran akun baru
     */
    public function attemptRegister()
    {
        $rules = [
            'full_name'        => 'required|min_length[3]|max_length[150]',
            'username'         => 'required|alpha_numeric_space|min_length[3]|max_length[50]|is_unique[users.username]',
            'email'            => 'required|valid_email|max_length[150]|is_unique[users.email]',
            'phone'            => 'required|min_length[8]|max_length[25]',
            'city'             => 'required',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        $messages = [
            'email' => [
                'is_unique'   => 'Email ini sudah terdaftar. Silakan gunakan email lain atau masuk.',
                'valid_email' => 'Format alamat email tidak valid.',
            ],
            'username' => [
                'is_unique' => 'Username ini sudah digunakan.',
            ],
            'password_confirm' => [
                'matches' => 'Konfirmasi password tidak cocok dengan password.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        try {
            $result = $this->authService->register($this->request->getPost());

            if (!$result['success']) {
                return redirect()->back()->withInput()->with('error', 'Pendaftaran gagal. Silakan periksa kembali data Anda.');
            }

            session()->setFlashdata('success', 'Akun Anda berhasil didaftarkan! Silakan masuk.');
            return redirect()->to(base_url('login'));
        } catch (\Exception $e) {
            log_message('error', 'Register error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat pendaftaran. Silakan coba lagi nanti.');
        }
    }

    /**
     * Proses Logout
     */
    public function logout()
    {
        $this->authService->logout();
        session()->setFlashdata('success', 'Anda telah berhasil keluar dari akun.');
        return redirect()->to(base_url('login'));
    }
}
