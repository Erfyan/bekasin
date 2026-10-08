<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<div class="mb-6 text-center">
    <h2 class="text-xl font-extrabold text-slate-900">Masuk ke Akun</h2>
    <p class="text-xs text-slate-500 mt-1">Gunakan email atau username terdaftar Anda</p>
</div>

<form action="<?= base_url('login') ?>" method="POST" class="space-y-4">
    <?= csrf_field() ?>

    <div>
        <label for="identifier" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email atau Username</label>
        <div class="relative">
            <i data-lucide="user" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
            <input 
                type="text" 
                id="identifier" 
                name="identifier" 
                value="<?= old('identifier') ?>" 
                required 
                placeholder="nama@email.com atau username"
                class="w-full pl-10 pr-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all"
            >
        </div>
    </div>

    <div>
        <div class="flex items-center justify-between mb-1">
            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
        </div>
        <div class="relative">
            <i data-lucide="lock" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
            <input 
                type="password" 
                id="password" 
                name="password" 
                required 
                placeholder="••••••••"
                class="w-full pl-10 pr-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all"
            >
        </div>
    </div>

    <button 
        type="submit" 
        class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl shadow-md shadow-emerald-600/20 active:scale-98 transition-all flex items-center justify-center gap-2 mt-2"
    >
        <i data-lucide="log-in" class="w-4 h-4"></i>
        <span>Masuk Sekarang</span>
    </button>
</form>

<div class="mt-6 pt-6 border-t border-slate-100 text-center text-xs text-slate-500">
    Belum punya akun? 
    <a href="<?= base_url('register') ?>" class="font-bold text-emerald-600 hover:text-emerald-700">Daftar di sini</a>
</div>

<?= $this->endSection() ?>
