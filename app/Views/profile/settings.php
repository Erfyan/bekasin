<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="bg-slate-50 py-8 min-h-[80vh]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Header -->
        <nav class="flex items-center gap-2 text-sm text-slate-500 mb-4 font-medium">
            <a href="<?= base_url('/') ?>" class="hover:text-emerald-600 transition-colors">Beranda</a>
            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
            <a href="<?= base_url('profile') ?>" class="hover:text-emerald-600 transition-colors">Profil</a>
            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
            <span class="text-slate-800 font-semibold">Pengaturan Akun</span>
        </nav>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-8 flex items-center gap-2.5">
            <span class="p-2 rounded-2xl bg-emerald-50 text-emerald-600 inline-flex items-center justify-center">
                <i data-lucide="settings" class="w-6 h-6"></i>
            </span>
            Pengaturan Akun & Profil
        </h1>

        <div class="space-y-8">
            <!-- Form 1: Biodata & Profil Umum -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
                <h2 class="text-lg font-bold text-slate-900 mb-1 flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-emerald-600"></i> Informasi Pribadi & Kontak
                </h2>
                <p class="text-xs text-slate-500 mb-6">Perbarui nama, kontak telepon, dan lokasi domisili Anda untuk memudahkan transaksi.</p>

                <form action="<?= base_url('profile/update') ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
                    <?= csrf_field() ?>

                    <!-- Avatar Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Foto Profil (Avatar)</label>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-700 font-extrabold text-xl flex items-center justify-center overflow-hidden border border-emerald-200 shrink-0">
                                <?php if (!empty($user['avatar_url'])): ?>
                                    <img src="<?= esc($user['avatar_url']) ?>" alt="Avatar" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <?= strtoupper(substr($user['full_name'] ?? 'U', 0, 1)) ?>
                                <?php endif; ?>
                            </div>
                            <input type="file" name="avatar" accept="image/*" class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="full_name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" id="full_name" name="full_name" required value="<?= old('full_name', $user['full_name']) ?>" class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1">No. WhatsApp / HP <span class="text-rose-500">*</span></label>
                            <input type="text" id="phone" name="phone" required value="<?= old('phone', $user['phone']) ?>" class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        </div>

                        <div>
                            <label for="province" class="block text-sm font-semibold text-slate-700 mb-1">Provinsi <span class="text-rose-500">*</span></label>
                            <input type="text" id="province" name="province" required value="<?= old('province', $user['province'] ?? 'DKI Jakarta') ?>" class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-semibold text-slate-700 mb-1">Kota / Kabupaten <span class="text-rose-500">*</span></label>
                            <input type="text" id="city" name="city" required value="<?= old('city', $user['city'] ?? 'Jakarta') ?>" class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="bio" class="block text-sm font-semibold text-slate-700 mb-1">Bio / Deskripsi Singkat Penjual</label>
                            <textarea id="bio" name="bio" rows="3" placeholder="Tuliskan pengalaman Anda dalam jual-beli barang bekas..." class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"><?= old('bio', $user['bio']) ?></textarea>
                        </div>
                    </div>

                    <div class="pt-3 text-right">
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all">
                            Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>

            <!-- Form 2: Ganti Password -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
                <h2 class="text-lg font-bold text-slate-900 mb-1 flex items-center gap-2">
                    <i data-lucide="lock" class="w-5 h-5 text-emerald-600"></i> Keamanan & Password
                </h2>
                <p class="text-xs text-slate-500 mb-6">Ubah kata sandi secara berkala untuk menjaga keamanan akun Anda.</p>

                <form action="<?= base_url('profile/password') ?>" method="POST" class="space-y-4 max-w-md">
                    <?= csrf_field() ?>

                    <div>
                        <label for="current_password" class="block text-sm font-semibold text-slate-700 mb-1">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password" required class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="new_password" class="block text-sm font-semibold text-slate-700 mb-1">Password Baru</label>
                        <input type="password" id="new_password" name="new_password" required minlength="6" class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-semibold text-slate-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="6" class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition-all">
                            Ganti Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
