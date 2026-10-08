-- ====================================================================
-- SUPABASE POSTGRESQL SCHEMA & SEED DATA UNTUK BEKASIN-AJA
-- Jalankan file ini di Supabase Dashboard -> SQL Editor -> Run
-- ====================================================================

-- 1. USERS TABLE
CREATE TABLE IF NOT EXISTS public.users (
  id BIGSERIAL PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(150) NOT NULL UNIQUE,
  phone VARCHAR(25) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'member' CHECK (role IN ('admin', 'member')),
  status VARCHAR(20) NOT NULL DEFAULT 'active' CHECK (status IN ('active', 'suspended', 'banned')),
  avatar_url VARCHAR(255) DEFAULT NULL,
  bio TEXT DEFAULT NULL,
  province VARCHAR(100) DEFAULT NULL,
  city VARCHAR(100) DEFAULT NULL,
  address TEXT DEFAULT NULL,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 2. CATEGORIES TABLE
CREATE TABLE IF NOT EXISTS public.categories (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(120) NOT NULL UNIQUE,
  icon VARCHAR(50) NOT NULL DEFAULT 'tag',
  description TEXT DEFAULT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_active BOOLEAN NOT NULL DEFAULT TRUE,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 3. PRODUCTS TABLE
CREATE TABLE IF NOT EXISTS public.products (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
  category_id INT NOT NULL REFERENCES public.categories(id) ON DELETE RESTRICT,
  title VARCHAR(200) NOT NULL,
  slug VARCHAR(220) NOT NULL UNIQUE,
  description TEXT NOT NULL,
  price NUMERIC(15,2) NOT NULL DEFAULT 0.00,
  condition VARCHAR(30) NOT NULL DEFAULT 'good' CHECK (condition IN ('like_new', 'very_good', 'good', 'fair', 'needs_repair')),
  delivery_method VARCHAR(20) NOT NULL DEFAULT 'both' CHECK (delivery_method IN ('meetup', 'shipping', 'both')),
  status VARCHAR(20) NOT NULL DEFAULT 'active' CHECK (status IN ('draft', 'active', 'reserved', 'sold', 'archived')),
  has_scratches BOOLEAN NOT NULL DEFAULT FALSE,
  has_damages BOOLEAN NOT NULL DEFAULT FALSE,
  is_functional BOOLEAN NOT NULL DEFAULT TRUE,
  was_repaired BOOLEAN NOT NULL DEFAULT FALSE,
  completeness_notes VARCHAR(255) DEFAULT NULL,
  province VARCHAR(100) NOT NULL,
  city VARCHAR(100) NOT NULL,
  meetup_location VARCHAR(255) DEFAULT NULL,
  views_count INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 4. PRODUCT IMAGES TABLE
CREATE TABLE IF NOT EXISTS public.product_images (
  id BIGSERIAL PRIMARY KEY,
  product_id BIGINT NOT NULL REFERENCES public.products(id) ON DELETE CASCADE,
  image_path VARCHAR(255) NOT NULL,
  is_primary BOOLEAN NOT NULL DEFAULT FALSE,
  sort_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 5. WISHLISTS TABLE
CREATE TABLE IF NOT EXISTS public.wishlists (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
  product_id BIGINT NOT NULL REFERENCES public.products(id) ON DELETE CASCADE,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT uq_user_product_wishlist UNIQUE (user_id, product_id)
);

-- 6. OFFERS TABLE
CREATE TABLE IF NOT EXISTS public.offers (
  id BIGSERIAL PRIMARY KEY,
  product_id BIGINT NOT NULL REFERENCES public.products(id) ON DELETE CASCADE,
  buyer_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
  seller_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
  offered_price NUMERIC(15,2) NOT NULL,
  counter_price NUMERIC(15,2) DEFAULT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'accepted', 'rejected', 'countered', 'cancelled')),
  notes VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 7. CONVERSATIONS & MESSAGES
CREATE TABLE IF NOT EXISTS public.conversations (
  id BIGSERIAL PRIMARY KEY,
  product_id BIGINT NOT NULL REFERENCES public.products(id) ON DELETE CASCADE,
  buyer_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
  seller_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
  last_message_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS public.messages (
  id BIGSERIAL PRIMARY KEY,
  conversation_id BIGINT NOT NULL REFERENCES public.conversations(id) ON DELETE CASCADE,
  sender_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
  message_text TEXT NOT NULL,
  is_read BOOLEAN NOT NULL DEFAULT FALSE,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 8. TRANSACTIONS TABLE
CREATE TABLE IF NOT EXISTS public.transactions (
  id BIGSERIAL PRIMARY KEY,
  transaction_code VARCHAR(50) NOT NULL UNIQUE,
  buyer_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE RESTRICT,
  seller_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE RESTRICT,
  product_id BIGINT NOT NULL REFERENCES public.products(id) ON DELETE RESTRICT,
  offer_id BIGINT DEFAULT NULL REFERENCES public.offers(id) ON DELETE SET NULL,
  agreed_price NUMERIC(15,2) NOT NULL,
  delivery_method VARCHAR(20) NOT NULL CHECK (delivery_method IN ('meetup', 'shipping')),
  shipping_address TEXT DEFAULT NULL,
  meetup_location VARCHAR(255) DEFAULT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'deal_agreed' CHECK (status IN ('deal_agreed', 'waiting_payment', 'paid_confirmed', 'processing', 'completed', 'cancelled')),
  payment_method VARCHAR(30) DEFAULT 'transfer' CHECK (payment_method IN ('cash_on_meetup', 'transfer', 'rekber')),
  payment_status VARCHAR(20) NOT NULL DEFAULT 'unpaid' CHECK (payment_status IN ('unpaid', 'paid', 'refunded')),
  payment_proof VARCHAR(255) DEFAULT NULL,
  buyer_confirmation BOOLEAN NOT NULL DEFAULT FALSE,
  seller_confirmation BOOLEAN NOT NULL DEFAULT FALSE,
  completed_at TIMESTAMP WITH TIME ZONE DEFAULT NULL,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 9. REVIEWS TABLE
CREATE TABLE IF NOT EXISTS public.reviews (
  id BIGSERIAL PRIMARY KEY,
  transaction_id BIGINT NOT NULL REFERENCES public.transactions(id) ON DELETE CASCADE,
  seller_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
  buyer_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
  product_id BIGINT NOT NULL REFERENCES public.products(id) ON DELETE CASCADE,
  rating SMALLINT NOT NULL DEFAULT 5 CHECK (rating BETWEEN 1 AND 5),
  comment TEXT DEFAULT NULL,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 10. REPORTS TABLE
CREATE TABLE IF NOT EXISTS public.reports (
  id BIGSERIAL PRIMARY KEY,
  reporter_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
  target_type VARCHAR(20) NOT NULL CHECK (target_type IN ('product', 'user')),
  target_id BIGINT NOT NULL,
  reason VARCHAR(150) NOT NULL,
  description TEXT NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'resolved', 'dismissed')),
  admin_notes TEXT DEFAULT NULL,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 11. NOTIFICATIONS TABLE
CREATE TABLE IF NOT EXISTS public.notifications (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
  type VARCHAR(50) NOT NULL,
  title VARCHAR(150) NOT NULL,
  message TEXT NOT NULL,
  reference_type VARCHAR(50) DEFAULT NULL,
  reference_id BIGINT DEFAULT NULL,
  is_read BOOLEAN NOT NULL DEFAULT FALSE,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 12. AUDIT LOGS TABLE
CREATE TABLE IF NOT EXISTS public.audit_logs (
  id BIGSERIAL PRIMARY KEY,
  admin_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
  action VARCHAR(100) NOT NULL,
  target_type VARCHAR(50) NOT NULL,
  target_id BIGINT DEFAULT NULL,
  metadata TEXT DEFAULT NULL,
  ip_address VARCHAR(45) DEFAULT NULL,
  user_agent VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- ====================================================================
-- SEED DATA AWAL LENGKAP (POSTGRESQL / SUPABASE)
-- ====================================================================

-- 1. USERS (Admin & Members)
-- Password untuk semua akun: admin123 / password123
INSERT INTO public.users (id, full_name, username, email, phone, password_hash, role, status, avatar_url, bio, province, city, address) VALUES
(1, 'Administrator Bekasin', 'admin', 'admin@bekasin.com', '081299990001', '$2y$10$lUus3WURyBrJ/8uGDsPIg.LNB6SSHqUUQ.0mi/eurObt1jKRDbHV6', 'admin', 'active', NULL, 'Super Administrator Platform Bekasin-Aja.', 'DKI Jakarta', 'Jakarta Pusat', 'Jl. Sudirman Kav 21'),
(2, 'Budi Santoso (Seller)', 'budisantoso', 'budi@gmail.com', '081288881234', '$2y$10$lUus3WURyBrJ/8uGDsPIg.LNB6SSHqUUQ.0mi/eurObt1jKRDbHV6', 'member', 'active', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150', 'Penjual barang elektronik & kamera bekas terawat di Jakarta.', 'DKI Jakarta', 'Jakarta Selatan', 'Tebet Barat Dalam No. 12'),
(3, 'Rina Wijaya (Buyer)', 'rinawijaya', 'rina@gmail.com', '085711223344', '$2y$10$lUus3WURyBrJ/8uGDsPIg.LNB6SSHqUUQ.0mi/eurObt1jKRDbHV6', 'member', 'active', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150', 'Pencari buku langka dan fashion vintage estetik.', 'Jawa Barat', 'Bandung', 'Jl. Dago Asri No. 45'),
(4, 'Dimas Pratama', 'dimaspratama', 'dimas@gmail.com', '087855443322', '$2y$10$lUus3WURyBrJ/8uGDsPIg.LNB6SSHqUUQ.0mi/eurObt1jKRDbHV6', 'member', 'active', 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150', 'Kolektor gadget bekas mulus original.', 'DI Yogyakarta', 'Sleman', 'Jl. Kaliurang KM 5')
ON CONFLICT (id) DO NOTHING;

-- 2. CATEGORIES
INSERT INTO public.categories (id, name, slug, icon, description, sort_order, is_active) VALUES
(1, 'Elektronik & Gadget', 'elektronik-gadget', 'smartphone', 'Smartphone, laptop, tablet, dan aksesoris bekas berkualitas.', 1, TRUE),
(2, 'Kamera & Fotografi', 'kamera-fotografi', 'camera', 'Kamera DSLR, mirrorless, lensa, tripod, dan perlengkapan studio.', 2, TRUE),
(3, 'Fashion & Pakaian', 'fashion-pakaian', 'shirt', 'Pakaian preloved, jaket, sepatu sneakers, dan aksesoris gaya.', 3, TRUE),
(4, 'Hobi & Koleksi', 'hobi-koleksi', 'gamepad-2', 'Action figure, konsol game, kaset jadul, dan barang antik langka.', 4, TRUE),
(5, 'Buku & Majalah', 'buku-majalah', 'book-open', 'Buku kuliah, novel terjemahan, komik jadul, dan ensiklopedia.', 5, TRUE),
(6, 'Otomotif & Aksesoris', 'otomotif-aksesoris', 'car', 'Helm, suku cadang motor, velg, dan perlengkapan berkendara.', 6, TRUE),
(7, 'Peralatan Rumah Tangga', 'peralatan-rumah-tangga', 'home', 'Furnitur, sofa, kulkas, mesin kopi, dan peralatan dapur.', 7, TRUE)
ON CONFLICT (id) DO NOTHING;

-- 3. PRODUCTS
INSERT INTO public.products (id, user_id, category_id, title, slug, description, price, condition, delivery_method, status, has_scratches, has_damages, is_functional, was_repaired, completeness_notes, province, city, meetup_location, views_count) VALUES
(1, 2, 1, 'iPhone 13 Pro 128GB Sierra Blue Fullset Mulus 98%', 'iphone-13-pro-128gb-sierra-blue-fullset-mulus-98', 'Dijual santai iPhone 13 Pro warna Sierra Blue 128GB pemakaian pribadi. Battery Health 89% awet seharian. Face ID, True Tone, 120Hz ProMotion layar normal tanpa kendala.', 9800000.00, 'very_good', 'both', 'active', TRUE, FALSE, TRUE, FALSE, 'Unit iPhone, Dus Box Original, Kabel Type-C Lightning Original, Bonus 2 Case Spigen', 'DKI Jakarta', 'Jakarta Selatan', 'Mall Kota Kasablanka atau Stasiun Tebet', 142),
(2, 2, 2, 'Sony Alpha A6400 Kit 16-50mm Shutter Count Rendah', 'sony-alpha-a6400-kit-16-50mm-shutter-count-rendah', 'Kamera mirrorless Sony A6400 + lensa kit 16-50mm OSS. Shutter count baru 4.200 jepretan. Sensor bersih tanpa jamur/debu, autofokus super kilat Eye-AF.', 10500000.00, 'like_new', 'both', 'active', FALSE, FALSE, TRUE, FALSE, 'Body kamera, Lensa Kit 16-50mm, Baterai Original, Charger, Strap Sony, Box Lengkap', 'DKI Jakarta', 'Jakarta Selatan', 'Citos atau Gandaria City', 88),
(3, 4, 1, 'MacBook Air M1 2020 8/256GB Space Grey Baterai Normal', 'macbook-air-m1-2020-8-256gb-space-grey-baterai-normal', 'MacBook Air Chip M1 tahun 2020 RAM 8GB SSD 256GB warna Space Grey. Keyboard empuk semua fungsi normal, trackpad lancar, speaker menggelegar.', 8750000.00, 'good', 'both', 'active', TRUE, FALSE, TRUE, FALSE, 'Unit MacBook, Magsafe/Type-C Adapter 30W Original, Kabel Original, Dusbook', 'DI Yogyakarta', 'Sleman', 'Area Kampus UGM atau Gejayan', 215),
(4, 3, 3, 'Jaket Kulit Asli Vintage Schott Perfecto Size M', 'jaket-kulit-asli-vintage-schott-perfecto-size-m', 'Jaket kulit asli vintage model biker Schott Perfecto warna hitam pekat. Kulit tebal sudah lentur (patina mantap). Ritsleting YKK kuningan original.', 1250000.00, 'very_good', 'shipping', 'active', FALSE, FALSE, TRUE, FALSE, 'Hanya jaket kulit unit saja', 'Jawa Barat', 'Bandung', 'Kirim JNE / J&T dari Bandung', 64),
(5, 4, 4, 'Nintendo Switch V2 Neon Fullset CFW + MicroSD 128GB', 'nintendo-switch-v2-neon-fullset-cfw-microsd-128gb', 'Nintendo Switch V2 baterai awet. Sudah CFW Atmosphere terisi game-game populer: Zelda BOTW, Mario Kart 8, Pokemon Scarlet, Smash Bros.', 2950000.00, 'very_good', 'both', 'active', FALSE, FALSE, TRUE, FALSE, 'Tablet Switch, Joycon L/R Neon, Dock TV, Grip Joycon, Straps, Charger Ori, MicroSD 128GB, Dus', 'DI Yogyakarta', 'Sleman', 'Hartono Mall Yogyakarta atau Seturan', 178),
(6, 3, 5, 'Paket Novel Harry Potter 1-7 Terjemahan Gramedia Lengkap', 'paket-novel-harry-potter-1-7-terjemahan-gramedia-lengkap', 'Koleksi lengkap 7 buku novel Harry Potter edisi bahasa Indonesia penerbit Gramedia. Kertas sedikit menguning karena usia buku, namun halaman utuh 100%.', 450000.00, 'good', 'shipping', 'sold', FALSE, FALSE, TRUE, FALSE, 'Buku 1 sampai 7 lengkap dalam 1 box', 'Jawa Barat', 'Bandung', 'Kirim ekspedisi Bandung', 92)
ON CONFLICT (id) DO NOTHING;

-- 4. PRODUCT IMAGES
INSERT INTO public.product_images (id, product_id, image_path, is_primary, sort_order) VALUES
(1, 1, 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800', TRUE, 0),
(2, 1, 'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=800', FALSE, 1),
(3, 2, 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800', TRUE, 0),
(4, 2, 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=800', FALSE, 1),
(5, 3, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800', TRUE, 0),
(6, 4, 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800', TRUE, 0),
(7, 5, 'https://images.unsplash.com/photo-1578301978693-85fa9c0320b9?w=800', TRUE, 0),
(8, 6, 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=800', TRUE, 0)
ON CONFLICT (id) DO NOTHING;

-- 5. TRANSACTIONS
INSERT INTO public.transactions (id, transaction_code, buyer_id, seller_id, product_id, offer_id, agreed_price, delivery_method, shipping_address, meetup_location, status, payment_method, payment_status, buyer_confirmation, seller_confirmation, completed_at) VALUES
(1, 'TRX-HPOTTER-261008', 4, 3, 6, NULL, 400000.00, 'shipping', 'Jl. Kaliurang KM 5 No. 18, Sleman, Yogyakarta 55281', NULL, 'completed', 'transfer', 'paid', TRUE, TRUE, CURRENT_TIMESTAMP)
ON CONFLICT (id) DO NOTHING;

-- Reset Auto-Increment Sequences
SELECT setval('public.users_id_seq', (SELECT MAX(id) FROM public.users));
SELECT setval('public.categories_id_seq', (SELECT MAX(id) FROM public.categories));
SELECT setval('public.products_id_seq', (SELECT MAX(id) FROM public.products));
SELECT setval('public.product_images_id_seq', (SELECT MAX(id) FROM public.product_images));
SELECT setval('public.transactions_id_seq', (SELECT MAX(id) FROM public.transactions));
