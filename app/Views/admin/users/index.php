<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Manajemen Pengguna</h1>
            <p class="text-xs text-slate-400 mt-1">Daftar akun member, penjual, dan administrator sistem.</p>
        </div>

        <div class="flex items-center gap-3">
            <button 
                type="button" 
                onclick="openCreateUser()"
                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center gap-2 shrink-0"
            >
                <i data-lucide="user-plus" class="w-4 h-4"></i> Tambah Pengguna
            </button>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-slate-900 p-4 rounded-2xl border border-slate-800 flex flex-col md:flex-row gap-3 items-center justify-between">
        <form action="<?= base_url('admin/users') ?>" method="GET" class="w-full flex flex-col sm:flex-row gap-3 items-center">
            <div class="relative w-full sm:w-72">
                <i data-lucide="search" class="w-4 h-4 text-slate-500 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                <input 
                    type="text" 
                    name="q" 
                    value="<?= esc($search ?? '') ?>" 
                    placeholder="Cari nama, email, username..." 
                    class="w-full pl-9 pr-4 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white placeholder:text-slate-500 focus:outline-none focus:border-emerald-500"
                >
            </div>

            <select name="role" onchange="this.form.submit()" class="w-full sm:w-auto px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-slate-300 focus:outline-none">
                <option value="all" <?= ($roleFilter ?? 'all') === 'all' ? 'selected' : '' ?>>Semua Role</option>
                <option value="admin" <?= ($roleFilter ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="member" <?= ($roleFilter ?? '') === 'member' ? 'selected' : '' ?>>Member</option>
            </select>

            <select name="status" onchange="this.form.submit()" class="w-full sm:w-auto px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-slate-300 focus:outline-none">
                <option value="all" <?= ($statusFilter ?? 'all') === 'all' ? 'selected' : '' ?>>Semua Status</option>
                <option value="active" <?= ($statusFilter ?? '') === 'active' ? 'selected' : '' ?>>Aktif</option>
                <option value="suspended" <?= ($statusFilter ?? '') === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                <option value="banned" <?= ($statusFilter ?? '') === 'banned' ? 'selected' : '' ?>>Banned</option>
            </select>

            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-colors">
                Filter
            </button>

            <?php if (!empty($search) || ($roleFilter ?? 'all') !== 'all' || ($statusFilter ?? 'all') !== 'all'): ?>
                <a href="<?= base_url('admin/users') ?>" class="text-xs text-rose-400 hover:underline">Reset</a>
            <?php endif; ?>
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
                                        <div class="w-8 h-8 rounded-full bg-slate-800 text-slate-300 flex items-center justify-center font-bold text-xs shrink-0 border border-slate-700">
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
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Edit Button -->
                                        <button 
                                            type="button" 
                                            onclick="openEditUser(<?= htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8') ?>)"
                                            class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition-colors flex items-center gap-1"
                                            title="Edit Pengguna"
                                        >
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit
                                        </button>

                                        <!-- Suspend / Aktifkan Toggle -->
                                        <?php if (strtolower($u['email']) !== 'admin@bekasin.com' && (int)$u['id'] !== (int)session()->get('user_id')): ?>
                                            <form action="<?= base_url('admin/users/' . $u['id'] . '/status') ?>" method="POST" class="inline">
                                                <?= csrf_field() ?>
                                                <?php if ($u['status'] === 'active'): ?>
                                                    <input type="hidden" name="status" value="suspended">
                                                    <button type="submit" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-amber-500/20 text-amber-400 hover:bg-amber-500/30 transition-colors" title="Suspend akun">
                                                        Suspend
                                                    </button>
                                                <?php else: ?>
                                                    <input type="hidden" name="status" value="active">
                                                    <button type="submit" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-emerald-500/20 text-emerald-400 hover:bg-emerald-500/30 transition-colors" title="Aktifkan akun">
                                                        Aktifkan
                                                    </button>
                                                <?php endif; ?>
                                            </form>

                                            <!-- Hapus Button -->
                                            <form action="<?= base_url('admin/users/' . $u['id'] . '/delete') ?>" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna <?= esc(addslashes($u['username'])) ?> secara permanen?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-rose-500/20 text-rose-400 hover:bg-rose-500/30 transition-colors flex items-center gap-1" title="Hapus pengguna">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
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

<!-- Modal Tambah Pengguna -->
<div id="modal-create-user" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs hidden">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                <i data-lucide="user-plus" class="w-4 h-4 text-emerald-400"></i> Tambah Pengguna Baru
            </h3>
            <button type="button" onclick="closeCreateUser()" class="text-slate-400 hover:text-white p-1">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="<?= base_url('admin/users/store') ?>" method="POST" class="space-y-4 mt-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Lengkap <span class="text-rose-400">*</span></label>
                <input type="text" name="full_name" required placeholder="Contoh: Ahmad Rizki" class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-emerald-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Username <span class="text-rose-400">*</span></label>
                    <input type="text" name="username" required placeholder="ahmadrizki" class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Email <span class="text-rose-400">*</span></label>
                    <input type="email" name="email" required placeholder="ahmad@example.com" class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nomor Telepon/WA</label>
                    <input type="text" name="phone" placeholder="0812xxxxxxxx" class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Password <span class="text-rose-400">*</span></label>
                    <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter" class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Role Akun</label>
                    <select name="role" class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                        <option value="member">Member (Pengguna Biasa)</option>
                        <option value="admin">Admin (Administrator)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Status Akun</label>
                    <select name="status" class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                        <option value="active">Aktif</option>
                        <option value="suspended">Suspended</option>
                        <option value="banned">Banned</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Kota / Kabupaten</label>
                    <input type="text" name="city" placeholder="Jakarta Selatan" class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Provinsi</label>
                    <input type="text" name="province" placeholder="DKI Jakarta" class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-800">
                <button type="button" onclick="closeCreateUser()" class="px-4 py-2 text-xs font-bold rounded-xl text-slate-400 hover:bg-slate-800 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-bold rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white transition-colors shadow-xs">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Pengguna -->
<div id="modal-edit-user" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs hidden">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                <i data-lucide="edit-3" class="w-4 h-4 text-blue-400"></i> Edit Data Pengguna
            </h3>
            <button type="button" onclick="closeEditUser()" class="text-slate-400 hover:text-white p-1">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="form-edit-user" method="POST" class="space-y-4 mt-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Lengkap <span class="text-rose-400">*</span></label>
                <input type="text" id="edit-user-fullname" name="full_name" required class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Username <span class="text-rose-400">*</span></label>
                    <input type="text" id="edit-user-username" name="username" required class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Email <span class="text-rose-400">*</span></label>
                    <input type="email" id="edit-user-email" name="email" required class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nomor Telepon/WA</label>
                    <input type="text" id="edit-user-phone" name="phone" class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Password Baru (Opsional)</label>
                    <input type="password" id="edit-user-password" name="password" placeholder="Kosongkan jika tidak diganti" class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Role Akun</label>
                    <select id="edit-user-role" name="role" class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500">
                        <option value="member">Member</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Status Akun</label>
                    <select id="edit-user-status" name="status" class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500">
                        <option value="active">Aktif</option>
                        <option value="suspended">Suspended</option>
                        <option value="banned">Banned</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Kota / Kabupaten</label>
                    <input type="text" id="edit-user-city" name="city" class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Provinsi</label>
                    <input type="text" id="edit-user-province" name="province" class="w-full px-3 py-2 text-xs bg-slate-950 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-800">
                <button type="button" onclick="closeEditUser()" class="px-4 py-2 text-xs font-bold rounded-xl text-slate-400 hover:bg-slate-800 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-bold rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition-colors shadow-xs">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateUser() {
    const modal = document.getElementById('modal-create-user');
    modal.classList.remove('hidden');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closeCreateUser() {
    document.getElementById('modal-create-user').classList.add('hidden');
}

function openEditUser(user) {
    const modal = document.getElementById('modal-edit-user');
    const form = document.getElementById('form-edit-user');
    
    form.action = '<?= base_url('admin/users') ?>/' + user.id + '/update';
    document.getElementById('edit-user-fullname').value = user.full_name || '';
    document.getElementById('edit-user-username').value = user.username || '';
    document.getElementById('edit-user-email').value = user.email || '';
    document.getElementById('edit-user-phone').value = user.phone || '';
    document.getElementById('edit-user-password').value = '';
    document.getElementById('edit-user-role').value = user.role || 'member';
    document.getElementById('edit-user-status').value = user.status || 'active';
    document.getElementById('edit-user-city').value = user.city || '';
    document.getElementById('edit-user-province').value = user.province || '';

    // Jika admin@bekasin.com, kunci role dan status
    const isSuperAdmin = (user.email && user.email.toLowerCase() === 'admin@bekasin.com');
    document.getElementById('edit-user-role').disabled = isSuperAdmin;
    document.getElementById('edit-user-status').disabled = isSuperAdmin;

    modal.classList.remove('hidden');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closeEditUser() {
    document.getElementById('modal-edit-user').classList.add('hidden');
}
</script>
<?= $this->endSection() ?>
