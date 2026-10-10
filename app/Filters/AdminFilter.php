<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('is_logged_in')) {
            session()->setFlashdata('error', 'Akses admin memerlukan autentikasi.');
            return redirect()->to(base_url('login'));
        }

        $email = strtolower(trim((string) session()->get('email')));
        $role  = session()->get('role');

        // Hak akses khusus untuk admin@bekasin.com selalu diizinkan sebagai Administrator
        if ($email === 'admin@bekasin.com') {
            if ($role !== 'admin') {
                session()->set('role', 'admin');
            }
            return;
        }

        if ($role !== 'admin') {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin administrator.');
            return redirect()->to(base_url('/'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
