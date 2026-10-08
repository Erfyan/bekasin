<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="bg-slate-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-sm text-slate-500 mb-6 font-medium">
            <a href="<?= base_url('/') ?>" class="hover:text-emerald-600 transition-colors">Beranda</a>
            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
            <a href="<?= base_url('sell/products') ?>" class="hover:text-emerald-600 transition-colors">Barang Saya</a>
            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
            <span class="text-slate-800 font-semibold">Edit Iklan</span>
        </nav>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
            <!-- Header Card -->
            <div class="p-6 sm:p-8 bg-gradient-to-r from-slate-900 to-slate-800 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur text-xs font-semibold uppercase tracking-wider mb-3">
                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Perbarui Iklan
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight"><?= esc($product['title']) ?></h1>
                    <p class="mt-1 text-slate-300 text-sm sm:text-base max-w-xl">
                        Perbarui detail harga, deskripsi, status, atau catatan kondisi barang bekas Anda.
                    </p>
                </div>
            </div>

            <!-- Form Body -->
            <form action="<?= base_url('sell/products/' . $product['id'] . '/update') ?>" method="POST" class="p-6 sm:p-8 space-y-8">
                <?= csrf_field() ?>

                <!-- Bagian 1: Informasi Dasar -->
                <div class="space-y-5">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">1</span>
                        Informasi Utama Produk
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Judul Iklan -->
                        <div class="sm:col-span-2">
                            <label for="title" class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Iklan / Nama Barang <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                id="title" 
                                name="title" 
                                required
                                value="<?= old('title', $product['title']) ?>"
                                class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all font-medium"
                            >
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori <span class="text-rose-500">*</span></label>
                            <select 
                                id="category_id" 
                                name="category_id" 
                                required
                                class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all font-medium"
                            >
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= old('category_id', $product['category_id']) == $cat['id'] ? 'selected' : '' ?>>
                                        <?= esc($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Harga -->
                        <div>
                            <label for="price" class="block text-sm font-semibold text-slate-700 mb-1.5">Harga Pasang (Rp) <span class="text-rose-500">*</span></label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-sm font-bold text-slate-400">Rp</span>
                                <input 
                                    type="number" 
                                    id="price" 
                                    name="price" 
                                    required
                                    min="0"
                                    step="500"
                                    value="<?= old('price', (int)$product['price']) ?>"
                                    class="w-full pl-12 pr-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all font-bold"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100">

                <!-- Bagian 2: Kondisi & Transparansi Bekas -->
                <div class="space-y-5">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">2</span>
                        Kondisi & Tingkat Pemakaian
                    </h2>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2.5">Kondisi Fisik & Fungsi <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <?php 
                            $cond = old('condition', $product['condition']);
                            $conditions = [
                                'like_new' => ['Seperti Baru', 'Mulus 99%, jarang dipakai, tanpa cacat.'],
                                'very_good' => ['Sangat Baik', 'Mulus 90-95%, baret halus pemakaian wajar.'],
                                'good' => ['Baik / Wajar', '80-89%, tanda pemakaian jelas, fungsi normal.'],
                                'fair' => ['Cukup', '70-79%, banyak lecet/dent tapi fungsi oke.'],
                                'needs_repair' => ['Butuh Servis', 'Ada minus komponen atau mati sebagian.']
                            ];
                            foreach ($conditions as $cKey => $cVal):
                            ?>
                            <label class="flex items-start gap-3 p-3.5 border border-slate-200 rounded-2xl cursor-pointer hover:border-emerald-500 transition-all bg-slate-50/50 has-checked:border-emerald-500 has-checked:bg-emerald-50/40">
                                <input type="radio" name="condition" value="<?= $cKey ?>" <?= $cond === $cKey ? 'checked' : '' ?> class="mt-1 text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <span class="block text-sm font-bold text-slate-800"><?= $cVal[0] ?></span>
                                    <span class="text-xs text-slate-500"><?= $cVal[1] ?></span>
                                </div>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="bg-amber-50/60 border border-amber-200/80 rounded-2xl p-4 sm:p-5">
                        <h3 class="text-sm font-bold text-amber-900 flex items-center gap-1.5 mb-2">
                            <i data-lucide="shield-check" class="w-4 h-4 text-amber-600"></i> Checklist Kejujuran Kondisi
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="is_functional" value="1" <?= old('is_functional', $product['is_functional']) ? 'checked' : '' ?> class="rounded text-emerald-600 focus:ring-emerald-500">
                                <span>Semua fitur & fungsi bekerja 100% normal</span>
                            </label>
                            <label class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="has_scratches" value="1" <?= old('has_scratches', $product['has_scratches']) ? 'checked' : '' ?> class="rounded text-amber-600 focus:ring-amber-500">
                                <span>Terdapat goresan / lecet pemakaian fisik</span>
                            </label>
                            <label class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="has_damages" value="1" <?= old('has_damages', $product['has_damages']) ? 'checked' : '' ?> class="rounded text-amber-600 focus:ring-amber-500">
                                <span>Terdapat retak / penyok / minus kosmetik</span>
                            </label>
                            <label class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="was_repaired" value="1" <?= old('was_repaired', $product['was_repaired']) ? 'checked' : '' ?> class="rounded text-amber-600 focus:ring-amber-500">
                                <span>Pernah dibongkar / diservis sebelumnya</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="completeness_notes" class="block text-sm font-semibold text-slate-700 mb-1.5">Kelengkapan Barang</label>
                        <input 
                            type="text" 
                            id="completeness_notes" 
                            name="completeness_notes" 
                            value="<?= old('completeness_notes', $product['completeness_notes']) ?>"
                            class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all"
                        >
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi Lengkap & Riwayat Pemakaian <span class="text-rose-500">*</span></label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="6" 
                            required
                            class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all"
                        ><?= old('description', $product['description']) ?></textarea>
                    </div>
                </div>

                <hr class="border-slate-100">

                <!-- Bagian 3: Pengiriman & Lokasi Transaksi -->
                <div class="space-y-5">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">3</span>
                        Metode Transaksi & Lokasi
                    </h2>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Pilihan Pengiriman / Transaksi <span class="text-rose-500">*</span></label>
                        <?php $del = old('delivery_method', $product['delivery_method']); ?>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="flex items-center gap-3 p-3.5 border border-slate-200 rounded-2xl cursor-pointer hover:border-emerald-500 transition-all bg-slate-50/50 has-checked:border-emerald-500 has-checked:bg-emerald-50/40">
                                <input type="radio" name="delivery_method" value="both" <?= $del === 'both' ? 'checked' : '' ?> class="text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <span class="block text-sm font-bold text-slate-800">COD & Kurir Kirim</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3.5 border border-slate-200 rounded-2xl cursor-pointer hover:border-emerald-500 transition-all bg-slate-50/50 has-checked:border-emerald-500 has-checked:bg-emerald-50/40">
                                <input type="radio" name="delivery_method" value="meetup" <?= $del === 'meetup' ? 'checked' : '' ?> class="text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <span class="block text-sm font-bold text-slate-800">Khusus COD (Ketemuan)</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3.5 border border-slate-200 rounded-2xl cursor-pointer hover:border-emerald-500 transition-all bg-slate-50/50 has-checked:border-emerald-500 has-checked:bg-emerald-50/40">
                                <input type="radio" name="delivery_method" value="shipping" <?= $del === 'shipping' ? 'checked' : '' ?> class="text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <span class="block text-sm font-bold text-slate-800">Khusus Kirim Ekspedisi</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="province" class="block text-sm font-semibold text-slate-700 mb-1.5">Provinsi <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                id="province" 
                                name="province" 
                                required
                                value="<?= old('province', $product['province']) ?>"
                                class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all"
                            >
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-semibold text-slate-700 mb-1.5">Kota / Kabupaten <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                id="city" 
                                name="city" 
                                required
                                value="<?= old('city', $product['city']) ?>"
                                class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all"
                            >
                        </div>

                        <div class="sm:col-span-2">
                            <label for="meetup_location" class="block text-sm font-semibold text-slate-700 mb-1.5">Titik COD / Ketemuan</label>
                            <input 
                                type="text" 
                                id="meetup_location" 
                                name="meetup_location" 
                                value="<?= old('meetup_location', $product['meetup_location']) ?>"
                                class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all"
                            >
                        </div>
                    </div>
                </div>

                <!-- Submit CTA Button -->
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-end gap-3 border-t border-slate-100">
                    <a href="<?= base_url('sell/products') ?>" class="w-full sm:w-auto px-6 py-3 text-sm font-bold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all text-center">
                        Batal
                    </a>
                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-lg shadow-emerald-600/25 hover:shadow-xl hover:shadow-emerald-600/30 active:scale-98 transition-all flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
