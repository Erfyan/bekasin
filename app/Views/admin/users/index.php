<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Manajemen Pengguna</h1>
            <p class="text-xs text-slate-400 mt-1">Daftar akun member, penjual, dan administrator sistem.</p>
        </div>

        <form action="<?= base_url('admin/users') ?>" method="GET" class="w-full sm:w-64">
            <input 
                type="text" 
                name="q" 
                value="<?= esc($search ?? '') ?>" 
                placeholder="Cari nama, email, username..." 
                class="w-full px-4 py-2 text-xs bg-slate-900 border border-slate-800 rounded-xl text-white placeholder:text-slate-500 focus:outline-none focus:border-emerald-500"
            >
        </form>
    </div>

    <!-- Table Users -->
    <div class="bg-slate-900 rounded-3xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/60 text-slate-400 border-b border-slate-800 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="p-4">Pengguna</th>
                        <th class="p-4">Kontak</th>
                        <th class="p-4">Domisili</th>
                        <th class="p-4">Role</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-slate-300">
                    <?php if (empty($users)): ?>
                        <tr><td colspan="6" class="p-6 text-center text-slate-500">Tidak ada data pengguna ditemukan.</td></tr>
                    <?php else: ?>
                        <?php foreach ($users as $u): ?>
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-800 text-slate-300 flex items-center justify-center font-bold text-xs shrink-0">
                                            <?= strtoupper(substr($u['full_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <strong class="text-white block"><?= esc($u['full_name']) ?></strong>
                                            <span class="text-slate-400 text-[11px]">@<?= esc($u['username']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span><?= esc($u['email']) ?></span>
                                    <span class="block text-slate-400 text-[11px]"><?= esc($u['phone']) ?></span>
                                </td>
                                <td class="p-4">
                                    <span><?= esc($u['city'] ?? '-') ?>, <?= esc($u['province'] ?? '-') ?></span>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase <?= $u['role'] === 'admin' ? 'bg-purple-500/20 text-purple-400 border border-purple-500/30' : 'bg-slate-800 text-slate-300' ?>">
                                        <?= esc($u['role']) ?>
                                    </span>
                                </td>
                                <td class="p-4">
                                    <?php if ($u['status'] === 'active'): ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Aktif</span>
                                    <?php elseif ($u['status'] === 'suspended'): ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">Suspended</span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30">Banned</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-right">
                                    <?php if ($u['role'] !== 'admin'): ?>
                                        <form action="<?= base_url('admin/users/' . $u['id'] . '/status') ?>" method="POST" class="inline">
                                            <?= csrf_field() ?>
                                            <?php if ($u['status'] === 'active'): ?>
                                                <input type="hidden" name="status" value="suspended">
                                                <button type="submit" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-amber-500/20 text-amber-400 hover:bg-amber-500/30 transition-colors">
                                                    Suspend
                                                </button>
                                            <?php else: ?>
                                                <input type="hidden" name="status" value="active">
                                                <button type="submit" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-emerald-500/20 text-emerald-400 hover:bg-emerald-500/30 transition-colors">
                                                    Aktifkan
                                                </button>
                                            <?php endif; ?>
                                        </form>
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
