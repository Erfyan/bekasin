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

        if (session()->get('role') !== 'admin') {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin administrator.');
            return redirect()->to(base_url('/'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
