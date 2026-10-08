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
            <span class="text-slate-800 font-semibold">Jual Barang Bekas</span>
        </nav>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
            <!-- Header Card -->
            <div class="p-6 sm:p-8 bg-gradient-to-r from-emerald-600 to-teal-700 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur text-xs font-semibold uppercase tracking-wider mb-3">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Pasang Iklan Cepat
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Jual Barang Bekasmu Sekarang</h1>
                    <p class="mt-1 text-emerald-100 text-sm sm:text-base max-w-xl">
                        Ubah barang tak terpakai menjadi uang tunai. Isi detail sejujur-jujurnya agar cepat laku dan terpercaya!
                    </p>
                </div>
                <div class="absolute -right-8 -bottom-10 opacity-15 pointer-events-none">
                    <i data-lucide="tag" class="w-48 h-48"></i>
                </div>
            </div>

            <!-- Form Body -->
            <form action="<?= base_url('sell/products') ?>" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-8" id="form-sell-product">
                <?= csrf_field() ?>

                <!-- Bagian 1: Upload Foto Barang -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">1</span>
                                Foto Produk Asli
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">Unggah hingga 5 foto asli dari berbagai sudut (depan, belakang, kondisi/cacat jika ada).</p>
                        </div>
                        <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">Maks. 5 Foto</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3" id="image-preview-container">
                        <label for="product_images" class="col-span-2 sm:col-span-1 aspect-square rounded-2xl border-2 border-dashed border-slate-300 hover:border-emerald-500 bg-slate-50 hover:bg-emerald-50/50 flex flex-col items-center justify-center cursor-pointer transition-all group">
                            <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-400 group-hover:text-emerald-600 group-hover:scale-110 transition-all mb-2 border border-slate-200">
                                <i data-lucide="camera" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-semibold text-slate-600 group-hover:text-emerald-600">+ Tambah Foto</span>
                            <span class="text-[10px] text-slate-400">JPG, PNG (Maks 2MB)</span>
                            <input type="file" id="product_images" name="images[]" multiple accept="image/*" class="hidden" onchange="previewImages(event)">
                        </label>
                    </div>
                </div>

                <hr class="border-slate-100">

                <!-- Bagian 2: Informasi Dasar -->
                <div class="space-y-5">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">2</span>
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
                                value="<?= old('title') ?>"
                                placeholder="Contoh: iPhone 13 Pro 128GB Sierra Blue Fullset Mulus 98%"
                                class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all"
                            >
                            <p class="text-[11px] text-slate-400 mt-1">Sertakan merk, tipe, dan kondisi utama agar mudah ditemukan pembeli.</p>
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori <span class="text-rose-500">*</span></label>
                            <select 
                                id="category_id" 
                                name="category_id" 
                                required
                                class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all"
                            >
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= old('category_id') == $cat['id'] ? 'selected' : '' ?>>
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
                                    value="<?= old('price') ?>"
                                    placeholder="0"
                                    class="w-full pl-12 pr-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all font-semibold"
                                >
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Isi 0 jika ingin gratis / hibah.</p>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100">

                <!-- Bagian 3: Kondisi & Transparansi Bekas -->
                <div class="space-y-5">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">3</span>
                        Kondisi & Tingkat Pemakaian
                    </h2>

                    <!-- Radio Pill Kondisi -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2.5">Kondisi Fisik & Fungsi <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="flex items-start gap-3 p-3.5 border border-slate-200 rounded-2xl cursor-pointer hover:border-emerald-500 transition-all bg-slate-50/50 has-checked:border-emerald-500 has-checked:bg-emerald-50/40">
                                <input type="radio" name="condition" value="like_new" <?= old('condition') === 'like_new' ? 'checked' : '' ?> class="mt-1 text-emerald-600 focus:ring-emerald-500" required>
                                <div>
                                    <span class="block text-sm font-bold text-slate-800">Seperti Baru (Like New)</span>
                                    <span class="text-xs text-slate-500">Mulus 99%, jarang dipakai, tanpa cacat.</span>
                                </div>
                            </label>
                            
                            <label class="flex items-start gap-3 p-3.5 border border-slate-200 rounded-2xl cursor-pointer hover:border-emerald-500 transition-all bg-slate-50/50 has-checked:border-emerald-500 has-checked:bg-emerald-50/40">
                                <input type="radio" name="condition" value="very_good" <?= old('condition', 'very_good') === 'very_good' ? 'checked' : '' ?> class="mt-1 text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <span class="block text-sm font-bold text-slate-800">Sangat Baik (Very Good)</span>
                                    <span class="text-xs text-slate-500">Mulus 90-95%, baret halus pemakaian wajar.</span>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 p-3.5 border border-slate-200 rounded-2xl cursor-pointer hover:border-emerald-500 transition-all bg-slate-50/50 has-checked:border-emerald-500 has-checked:bg-emerald-50/40">
                                <input type="radio" name="condition" value="good" <?= old('condition') === 'good' ? 'checked' : '' ?> class="mt-1 text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <span class="block text-sm font-bold text-slate-800">Baik / Wajar (Good)</span>
                                    <span class="text-xs text-slate-500">80-89%, tanda pemakaian jelas, fungsi normal.</span>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 p-3.5 border border-slate-200 rounded-2xl cursor-pointer hover:border-emerald-500 transition-all bg-slate-50/50 has-checked:border-emerald-500 has-checked:bg-emerald-50/40">
                                <input type="radio" name="condition" value="fair" <?= old('condition') === 'fair' ? 'checked' : '' ?> class="mt-1 text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <span class="block text-sm font-bold text-slate-800">Cukup (Fair)</span>
                                    <span class="text-xs text-slate-500">70-79%, banyak lecet/dent tapi fungsi oke.</span>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 p-3.5 border border-slate-200 rounded-2xl cursor-pointer hover:border-emerald-500 transition-all bg-slate-50/50 has-checked:border-emerald-500 has-checked:bg-emerald-50/40">
                                <input type="radio" name="condition" value="needs_repair" <?= old('condition') === 'needs_repair' ? 'checked' : '' ?> class="mt-1 text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <span class="block text-sm font-bold text-slate-800">Butuh Servis / Rusak</span>
                                    <span class="text-xs text-slate-500">Ada minus komponen atau mati sebagian.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Checklist Transparansi C2C Bekasin -->
                    <div class="bg-amber-50/60 border border-amber-200/80 rounded-2xl p-4 sm:p-5">
                        <h3 class="text-sm font-bold text-amber-900 flex items-center gap-1.5 mb-2">
                            <i data-lucide="shield-check" class="w-4 h-4 text-amber-600"></i> Checklist Kejujuran Kondisi (Mencegah Retur/Sengketa)
                        </h3>
                        <p class="text-xs text-amber-800 mb-3">Centang sesuai kondisi barang Anda:</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="is_functional" value="1" <?= old('is_functional', '1') ? 'checked' : '' ?> class="rounded text-emerald-600 focus:ring-emerald-500">
                                <span>Semua fitur & fungsi bekerja 100% normal</span>
                            </label>
                            <label class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="has_scratches" value="1" <?= old('has_scratches') ? 'checked' : '' ?> class="rounded text-amber-600 focus:ring-amber-500">
                                <span>Terdapat goresan / lecet pemakaian fisik</span>
                            </label>
                            <label class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="has_damages" value="1" <?= old('has_damages') ? 'checked' : '' ?> class="rounded text-amber-600 focus:ring-amber-500">
                                <span>Terdapat retak / penyok / minus kosmetik</span>
                            </label>
                            <label class="flex items-center gap-2.5 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="was_repaired" value="1" <?= old('was_repaired') ? 'checked' : '' ?> class="rounded text-amber-600 focus:ring-amber-500">
                                <span>Pernah dibongkar / diservis sebelumnya</span>
                            </label>
                        </div>
                    </div>

                    <!-- Kelengkapan & Deskripsi Lengkap -->
                    <div>
                        <label for="completeness_notes" class="block text-sm font-semibold text-slate-700 mb-1.5">Kelengkapan Barang</label>
                        <input 
                            type="text" 
                            id="completeness_notes" 
                            name="completeness_notes" 
                            value="<?= old('completeness_notes') ?>"
                            placeholder="Contoh: Unit, Charger Original, Box, Nota Pembelian, Kitab-kitab lengkap"
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
                            placeholder="Jelaskan alasan dijual, lama pemakaian, performa baterai, minus jika ada, dsb..."
                            class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all"
                        ><?= old('description') ?></textarea>
                    </div>
                </div>

                <hr class="border-slate-100">

                <!-- Bagian 4: Pengiriman & Lokasi Transaksi -->
                <div class="space-y-5">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">4</span>
                        Metode Transaksi & Lokasi
                    </h2>

                    <!-- Metode Pengiriman -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Pilihan Pengiriman / Transaksi <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="flex items-center gap-3 p-3.5 border border-slate-200 rounded-2xl cursor-pointer hover:border-emerald-500 transition-all bg-slate-50/50 has-checked:border-emerald-500 has-checked:bg-emerald-50/40">
                                <input type="radio" name="delivery_method" value="both" <?= old('delivery_method', 'both') === 'both' ? 'checked' : '' ?> class="text-emerald-600 focus:ring-emerald-500" required>
                                <div>
                                    <span class="block text-sm font-bold text-slate-800">COD & Kurir Kirim</span>
                                    <span class="text-xs text-slate-500">Bisa tatap muka ataupun kirim ekspedisi.</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3.5 border border-slate-200 rounded-2xl cursor-pointer hover:border-emerald-500 transition-all bg-slate-50/50 has-checked:border-emerald-500 has-checked:bg-emerald-50/40">
                                <input type="radio" name="delivery_method" value="meetup" <?= old('delivery_method') === 'meetup' ? 'checked' : '' ?> class="text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <span class="block text-sm font-bold text-slate-800">Khusus COD (Ketemuan)</span>
                                    <span class="text-xs text-slate-500">Hanya melayani cek barang di tempat.</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3.5 border border-slate-200 rounded-2xl cursor-pointer hover:border-emerald-500 transition-all bg-slate-50/50 has-checked:border-emerald-500 has-checked:bg-emerald-50/40">
                                <input type="radio" name="delivery_method" value="shipping" <?= old('delivery_method') === 'shipping' ? 'checked' : '' ?> class="text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <span class="block text-sm font-bold text-slate-800">Khusus Kirim Ekspedisi</span>
                                    <span class="text-xs text-slate-500">JNE, J&T, SiCepat, Gosend, dsb.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Lokasi Kota / COD -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="province" class="block text-sm font-semibold text-slate-700 mb-1.5">Provinsi <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                id="province" 
                                name="province" 
                                required
                                value="<?= old('province', session()->get('province') ?? 'DKI Jakarta') ?>"
                                placeholder="Contoh: DKI Jakarta / Jawa Barat"
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
                                value="<?= old('city', session()->get('city') ?? 'Jakarta Selatan') ?>"
                                placeholder="Contoh: Jakarta Selatan / Bandung"
                                class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition-all"
                            >
                        </div>

                        <div class="sm:col-span-2">
                            <label for="meetup_location" class="block text-sm font-semibold text-slate-700 mb-1.5">Rekomendasi Titik COD / Ketemuan (Opsional)</label>
                            <input 
                                type="text" 
                                id="meetup_location" 
                                name="meetup_location" 
                                value="<?= old('meetup_location') ?>"
                                placeholder="Contoh: Area Stasiun Tebet, Mall Kota Kasablanka, atau Indomaret Point"
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
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        <span>Terbitkan Iklan Sekarang</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImages(event) {
    const container = document.getElementById('image-preview-container');
    const files = event.target.files;
    
    // Remove existing previews except label
    const previews = container.querySelectorAll('.custom-preview-item');
    previews.forEach(p => p.remove());

    if (files.length > 5) {
        alert('Maksimal 5 foto yang diperbolehkan.');
        event.target.value = '';
        return;
    }

    Array.from(files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'custom-preview-item relative aspect-square rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 shadow-xs group';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-full object-cover">
                ${index === 0 ? '<span class="absolute top-2 left-2 bg-emerald-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-xs">Foto Utama</span>' : ''}
            `;
            container.appendChild(div);
        }
        reader.readAsDataURL(file);
    });
}
</script>
<?= $this->endSection() ?>
