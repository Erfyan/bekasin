<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="bg-slate-50 py-8 min-h-[80vh]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs & Header -->
        <nav class="flex items-center gap-2 text-sm text-slate-500 mb-4 font-medium">
            <a href="<?= base_url('/') ?>" class="hover:text-emerald-600 transition-colors">Beranda</a>
            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
            <span class="text-slate-800 font-semibold">Notifikasi</span>
        </nav>

        <div class="flex items-center justify-between gap-4 mb-6">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span class="p-2 rounded-2xl bg-emerald-50 text-emerald-600 inline-flex items-center justify-center">
                    <i data-lucide="bell" class="w-6 h-6"></i>
                </span>
                Notifikasi Saya
            </h1>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
            <?php if (empty($notifications)): ?>
                <div class="p-16 text-center max-w-md mx-auto">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="bell-off" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Tidak Ada Notifikasi</h3>
                    <p class="text-sm text-slate-500 mt-1">Anda akan menerima pemberitahuan seputar tawaran masuk, pesan, atau status transaksi di sini.</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-slate-100">
                    <?php foreach ($notifications as $n): ?>
                        <div class="p-5 flex items-start gap-4 hover:bg-slate-50 transition-colors <?= !$n['is_read'] ? 'bg-emerald-50/30' : '' ?>">
                            <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                <i data-lucide="bell-ring" class="w-5 h-5"></i>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <h3 class="text-sm font-bold text-slate-900"><?= esc($n['title']) ?></h3>
                                    <span class="text-xs text-slate-400 whitespace-nowrap">
                                        <?= date('d M H:i', strtotime($n['created_at'])) ?>
                                    </span>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed"><?= esc($n['message']) ?></p>

                                <?php if ($n['reference_type'] === 'offer'): ?>
                                    <a href="<?= base_url('offers') ?>" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:text-emerald-700 mt-2">
                                        Buka Tawaran →
                                    </a>
                                <?php elseif ($n['reference_type'] === 'transaction'): ?>
                                    <a href="<?= base_url('transactions/' . $n['reference_id']) ?>" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:text-emerald-700 mt-2">
                                        Lihat Transaksi →
                                    </a>
                                <?php elseif ($n['reference_type'] === 'conversation'): ?>
                                    <a href="<?= base_url('messages/' . $n['reference_id']) ?>" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:text-emerald-700 mt-2">
                                        Buka Obrolan Chat →
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
