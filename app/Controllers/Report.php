<?php

namespace App\Controllers;

use App\Models\ReportModel;

class Report extends BaseController
{
    protected ReportModel $reportModel;

    public function __construct()
    {
        $this->reportModel = new ReportModel();
    }

    /**
     * Kirim Laporan Pelanggaran / Penipuan (User atau Produk)
     */
    public function store()
    {
        $userId = (int) session()->get('user_id');

        $rules = [
            'target_type' => 'required|in_list[product,user]',
            'target_id'   => 'required|numeric',
            'reason'      => 'required',
            'description' => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Silakan lengkapi form aduan pelanggaran.');
        }

        $this->reportModel->insert([
            'reporter_id' => $userId,
            'target_type' => $this->request->getPost('target_type'),
            'target_id'   => (int) $this->request->getPost('target_id'),
            'reason'      => trim($this->request->getPost('reason')),
            'description' => trim($this->request->getPost('description')),
            'status'      => 'pending',
        ]);

        session()->setFlashdata('success', 'Laporan Anda telah diterima tim moderasi Bekasin-Aja untuk ditindaklanjuti.');
        return redirect()->back();
    }
}
