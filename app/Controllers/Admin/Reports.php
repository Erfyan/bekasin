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
            $reports = $this->reportModel->select('reports.*, users.full_name as reporter_name, users.username as reporter_username')
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

        try {
            $this->reportModel->update($id, [
                'status'      => $status,
                'admin_notes' => $adminNotes,
            ]);

            $this->moderationService->logAdminAction($adminId, 'resolve_report', 'report', $id, ['status' => $status]);
            session()->setFlashdata('success', 'Status laporan aduan berhasil diperbarui.');
        } catch (\Throwable $e) {
            log_message('error', 'Admin resolve report error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal memperbarui laporan: ' . $e->getMessage());
        }

        return redirect()->to(base_url('admin/reports'));
    }

    public function delete(int $id)
    {
        $adminId = (int) session()->get('user_id');
        $report = $this->reportModel->find($id);

        if (!$report) {
            return redirect()->back()->with('error', 'Laporan tidak ditemukan.');
        }

        try {
            $this->reportModel->delete($id);
            $this->moderationService->logAdminAction($adminId, 'delete_report', 'report', $id, ['reason' => $report['reason']]);
            session()->setFlashdata('success', 'Laporan aduan berhasil dihapus.');
        } catch (\Throwable $e) {
            log_message('error', 'Admin delete report error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }

        return redirect()->to(base_url('admin/reports'));
    }
}
