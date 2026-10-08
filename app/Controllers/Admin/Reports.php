<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReportModel;
use App\Services\ModerationService;

class Reports extends BaseController
{
    protected ReportModel $reportModel;
    protected ModerationService $moderationService;

    public function __construct()
    {
        $this->reportModel = new ReportModel();
        $this->moderationService = new ModerationService();
    }

    public function index(): string
    {
        $reports = [];
        $pager = null;

        try {
            $reports = $this->reportModel->select('reports.*, users.full_name as reporter_name')
                ->join('users', 'users.id = reports.reporter_id')
                ->orderBy('reports.created_at', 'DESC')
                ->paginate(20);
            $pager = $this->reportModel->pager;
        } catch (\Throwable $e) {
            log_message('error', 'Admin reports error: ' . $e->getMessage());
        }

        $data = [
            'title'   => 'Laporan Pelanggaran — Admin Panel',
            'reports' => $reports,
            'pager'   => $pager,
        ];

        return view('admin/reports/index', $data);
    }

    public function resolve(int $id)
    {
        $adminId = (int) session()->get('user_id');
        $status = $this->request->getPost('status'); // resolved, dismissed
        $adminNotes = $this->request->getPost('admin_notes');

        $this->reportModel->update($id, [
            'status'      => $status,
            'admin_notes' => $adminNotes,
        ]);

        $this->moderationService->logAdminAction($adminId, 'resolve_report', 'report', $id, ['status' => $status]);

        session()->setFlashdata('success', 'Laporan aduan telah diselesaikan.');
        return redirect()->to(base_url('admin/reports'));
    }
}
