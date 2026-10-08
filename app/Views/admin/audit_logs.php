<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-extrabold text-white tracking-tight">Audit Log Aktivitas</h1>
        <p class="text-xs text-slate-400 mt-1">Catatan jejak rekam tindakan administrator untuk integritas sistem.</p>
    </div>

    <!-- Table Logs -->
    <div class="bg-slate-900 rounded-3xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/60 text-slate-400 border-b border-slate-800 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="p-4">Waktu</th>
                        <th class="p-4">Administrator</th>
                        <th class="p-4">Tindakan (Action)</th>
                        <th class="p-4">Target</th>
                        <th class="p-4">Metadata</th>
                        <th class="p-4">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-slate-300">
                    <?php if (empty($logs)): ?>
                        <tr><td colspan="6" class="p-6 text-center text-slate-500">Belum ada catatan log aktivitas.</td></tr>
                    <?php else: ?>
                        <?php foreach ($logs as $l): ?>
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="p-4 text-slate-400 font-mono text-[11px] whitespace-nowrap">
                                    <?= date('d M Y H:i:s', strtotime($l['created_at'])) ?>
                                </td>
                                <td class="p-4">
                                    <strong class="text-white block"><?= esc($l['admin_name']) ?></strong>
                                    <span class="text-slate-500 text-[10px]">@<?= esc($l['admin_username']) ?></span>
                                </td>
                                <td class="p-4">
                                    <span class="font-mono font-bold text-amber-400"><?= esc($l['action']) ?></span>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-slate-800 text-slate-300">
                                        <?= esc($l['target_type']) ?> #<?= $l['target_id'] ?>
                                    </span>
                                </td>
                                <td class="p-4 font-mono text-[10px] text-slate-400 max-w-xs truncate">
                                    <?= esc($l['metadata'] ?? '-') ?>
                                </td>
                                <td class="p-4 font-mono text-[11px] text-slate-500">
                                    <?= esc($l['ip_address']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
