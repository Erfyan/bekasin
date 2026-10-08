<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="bg-slate-50 py-8 min-h-[80vh]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <nav class="flex items-center gap-2 text-sm text-slate-500 mb-4 font-medium">
            <a href="<?= base_url('/') ?>" class="hover:text-emerald-600 transition-colors">Beranda</a>
            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
            <span class="text-slate-800 font-semibold">Pesan & Obrolan</span>
        </nav>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-6 flex items-center gap-2.5">
            <span class="p-2 rounded-2xl bg-emerald-50 text-emerald-600 inline-flex items-center justify-center">
                <i data-lucide="message-square" class="w-6 h-6"></i>
            </span>
            Kotak Masuk Pesan
        </h1>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
            <?php if (empty($conversations)): ?>
                <div class="p-16 text-center max-w-md mx-auto">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="message-circle" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Belum Ada Pesan</h3>
                    <p class="text-sm text-slate-500 mt-1 mb-6">Mulai percakapan dengan penjual atau pembeli di halaman detail barang bekas.</p>
                    <a href="<?= base_url('products') ?>" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 text-white font-semibold text-sm rounded-xl hover:bg-emerald-700 transition-all shadow-md shadow-emerald-600/20">
                        <i data-lucide="compass" class="w-4 h-4"></i> Cari Barang Bekas
                    </a>
                </div>
            <?php else: ?>
                <div class="divide-y divide-slate-100">
                    <?php 
                    $userId = (int) session()->get('user_id');
                    foreach ($conversations as $c): 
                        $isBuyer = ($c['buyer_id'] == $userId);
                        $partnerName = $isBuyer ? $c['seller_name'] : $c['buyer_name'];
                        $partnerAvatar = $isBuyer ? $c['seller_avatar'] : $c['buyer_avatar'];
                    ?>
                        <a href="<?= base_url('messages/' . $c['id']) ?>" class="flex items-center justify-between p-5 hover:bg-slate-50 transition-colors group">
                            <div class="flex items-center gap-4 min-w-0">
                                <!-- Avatar -->
                                <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-base shrink-0 overflow-hidden border border-emerald-200 shadow-xs">
                                    <?php if ($partnerAvatar): ?>
                                        <img src="<?= esc($partnerAvatar) ?>" alt="Avatar" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <?= strtoupper(substr($partnerName ?? 'U', 0, 1)) ?>
                                    <?php endif; ?>
                                </div>

                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-slate-900 group-hover:text-emerald-600 transition-colors truncate">
                                            <?= esc($partnerName) ?>
                                        </h3>
                                        <?php if ($c['unread_count'] > 0): ?>
                                            <span class="px-2 py-0.5 rounded-full bg-emerald-600 text-white text-[10px] font-extrabold shadow-xs">
                                                <?= $c['unread_count'] ?> Baru
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($c['product_title'])): ?>
                                        <span class="text-xs font-semibold text-emerald-700 block truncate">
                                            🏷️ <?= esc($c['product_title']) ?>
                                        </span>
                                    <?php endif; ?>

                                    <p class="text-xs text-slate-500 truncate mt-0.5">
                                        <?= esc($c['last_message_text'] ?? 'Mulai percakapan...') ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Meta time -->
                            <div class="text-right shrink-0 pl-4">
                                <span class="text-xs text-slate-400">
                                    <?= date('d M H:i', strtotime($c['last_message_at'])) ?>
                                </span>
                                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:text-emerald-600 ml-auto mt-1 transition-colors"></i>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
