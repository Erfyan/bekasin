<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-extrabold text-white tracking-tight">Laporan Aduan Pelanggaran</h1>
        <p class="text-xs text-slate-400 mt-1">Daftar laporan kecurangan, penipuan, atau produk terlarang dari pengguna.</p>
    </div>

    <!-- Table Reports -->
    <div class="bg-slate-900 rounded-3xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/60 text-slate-400 border-b border-slate-800 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="p-4">Pelapor</th>
                        <th class="p-4">Target Aduan</th>
                        <th class="p-4">Alasan</th>
                        <th class="p-4">Deskripsi</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-slate-300">
                    <?php if (empty($reports)): ?>
                        <tr><td colspan="6" class="p-6 text-center text-slate-500">Belum ada aduan pelanggaran.</td></tr>
                    <?php else: ?>
                        <?php foreach ($reports as $r): ?>
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="p-4">
                                    <strong class="text-white block"><?= esc($r['reporter_name']) ?></strong>
                                    <span class="text-slate-500 text-[10px]"><?= date('d M Y H:i', strtotime($r['created_at'])) ?></span>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase <?= $r['target_type'] === 'product' ? 'bg-amber-500/20 text-amber-400' : 'bg-blue-500/20 text-blue-400' ?>">
                                        <?= esc($r['target_type']) ?> #<?= $r['target_id'] ?>
                                    </span>
                                </td>
                                <td class="p-4 font-semibold text-rose-400"><?= esc($r['reason']) ?></td>
                                <td class="p-4 max-w-xs truncate text-slate-400"><?= esc($r['description']) ?></td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $r['status'] === 'resolved' ? 'bg-emerald-500/20 text-emerald-400' : ($r['status'] === 'dismissed' ? 'bg-slate-800 text-slate-400' : 'bg-amber-500/20 text-amber-400') ?>">
                                        <?= esc($r['status']) ?>
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <?php if ($r['status'] === 'pending'): ?>
                                        <div class="flex items-center justify-end gap-1.5">
                                            <form action="<?= base_url('admin/reports/' . $r['id'] . '/resolve') ?>" method="POST" class="inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="status" value="resolved">
                                                <input type="hidden" name="admin_notes" value="Telah ditindaklanjuti admin">
                                                <button type="submit" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-emerald-500/20 text-emerald-400 hover:bg-emerald-500/30">
                                                    Selesaikan
                                                </button>
                                            </form>
                                            <form action="<?= base_url('admin/reports/' . $r['id'] . '/resolve') ?>" method="POST" class="inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="status" value="dismissed">
                                                <input type="hidden" name="admin_notes" value="Laporan diabaikan / tidak valid">
                                                <button type="submit" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-slate-800 text-slate-400 hover:bg-slate-700">
                                                    Abaikan
                                                </button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-[11px] text-slate-500 italic"><?= esc($r['admin_notes'] ?? 'Selesai') ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($pager): ?>
            <div class="p-4 border-t border-slate-800">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
