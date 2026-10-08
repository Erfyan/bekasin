<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<div class="mb-6 text-center">
    <h2 class="text-xl font-extrabold text-slate-900">Buat Akun Baru</h2>
    <p class="text-xs text-slate-500 mt-1">Gabung komunitas jual beli barang bekas terpercaya</p>
</div>

<form action="<?= base_url('register') ?>" method="POST" class="space-y-3.5">
    <?= csrf_field() ?>

    <div>
        <label for="full_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
        <input 
            type="text" 
            id="full_name" 
            name="full_name" 
            value="<?= old('full_name') ?>" 
            required 
            placeholder="Contoh: Budi Pratama"
            class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all"
        >
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label for="username" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Username</label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                value="<?= old('username') ?>" 
                required 
                placeholder="budipratama"
                class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all"
            >
        </div>
        <div>
            <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">No. WhatsApp/HP</label>
            <input 
                type="tel" 
                id="phone" 
                name="phone" 
                value="<?= old('phone') ?>" 
                required 
                placeholder="081234567890"
                class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all"
            >
        </div>
    </div>

    <div>
        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Email</label>
        <input 
            type="email" 
            id="email" 
            name="email" 
            value="<?= old('email') ?>" 
            required 
            placeholder="nama@email.com"
            class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all"
        >
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label for="city" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kota/Kabupaten</label>
            <input 
                type="text" 
                id="city" 
                name="city" 
                value="<?= old('city') ?>" 
                required 
                placeholder="Jakarta Selatan"
                class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all"
            >
        </div>
        <div>
            <label for="province" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Provinsi</label>
            <input 
                type="text" 
                id="province" 
                name="province" 
                value="<?= old('province') ?>" 
                placeholder="DKI Jakarta"
                class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all"
            >
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                required 
                placeholder="Min. 8 karakter"
                class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all"
            >
        </div>
        <div>
            <label for="password_confirm" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Ulangi Password</label>
            <input 
                type="password" 
                id="password_confirm" 
                name="password_confirm" 
                required 
                placeholder="Konfirmasi password"
                class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all"
            >
        </div>
    </div>

    <button 
        type="submit" 
        class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl shadow-md shadow-emerald-600/20 active:scale-98 transition-all flex items-center justify-center gap-2 mt-4"
    >
        <i data-lucide="user-plus" class="w-4 h-4"></i>
        <span>Daftar Sekarang</span>
    </button>
</form>

<div class="mt-6 pt-6 border-t border-slate-100 text-center text-xs text-slate-500">
    Sudah punya akun? 
    <a href="<?= base_url('login') ?>" class="font-bold text-emerald-600 hover:text-emerald-700">Masuk di sini</a>
</div>

<?= $this->endSection() ?>
