-- ====================================================================
-- DATABASE SCHEMA & SEED DUMMY DATA FOR BEKASIN-AJA (LARAGON / MYSQL)
-- Database: bekasin_db
-- Generated for CodeIgniter 4 Marketplace C2C
-- ====================================================================

CREATE DATABASE IF NOT EXISTS `bekasin_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bekasin_db`;

SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------------------
-- 1. Table: users
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(150) NOT NULL,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `phone` VARCHAR(25) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'member') NOT NULL DEFAULT 'member',
  `status` ENUM('active', 'suspended', 'banned') NOT NULL DEFAULT 'active',
  `avatar_url` VARCHAR(255) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `province` VARCHAR(100) DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 2. Table: categories
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `icon` VARCHAR(50) NOT NULL DEFAULT 'tag',
  `description` TEXT DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 3. Table: products
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `category_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(220) NOT NULL UNIQUE,
  `description` TEXT NOT NULL,
  `price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `condition` ENUM('like_new', 'very_good', 'good', 'fair', 'needs_repair') NOT NULL DEFAULT 'good',
  `delivery_method` ENUM('meetup', 'shipping', 'both') NOT NULL DEFAULT 'both',
  `status` ENUM('draft', 'active', 'reserved', 'sold', 'archived') NOT NULL DEFAULT 'active',
  `has_scratches` TINYINT(1) NOT NULL DEFAULT 0,
  `has_damages` TINYINT(1) NOT NULL DEFAULT 0,
  `is_functional` TINYINT(1) NOT NULL DEFAULT 1,
  `was_repaired` TINYINT(1) NOT NULL DEFAULT 0,
  `completeness_notes` VARCHAR(255) DEFAULT NULL,
  `province` VARCHAR(100) NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `meetup_location` VARCHAR(255) DEFAULT NULL,
  `views_count` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_products_category` (`category_id`),
  KEY `idx_products_user` (`user_id`),
  KEY `idx_products_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 4. Table: product_images
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_product_images_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 5. Table: wishlists
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `wishlists`;
CREATE TABLE `wishlists` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_user_product_wishlist` (`user_id`, `product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 6. Table: offers
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `offers`;
CREATE TABLE `offers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `buyer_id` BIGINT UNSIGNED NOT NULL,
  `seller_id` BIGINT UNSIGNED NOT NULL,
  `offered_price` DECIMAL(15,2) NOT NULL,
  `counter_price` DECIMAL(15,2) DEFAULT NULL,
  `status` ENUM('pending', 'accepted', 'rejected', 'countered', 'cancelled') NOT NULL DEFAULT 'pending',
  `notes` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_offers_buyer` (`buyer_id`),
  KEY `idx_offers_seller` (`seller_id`),
  KEY `idx_offers_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 7. Table: conversations
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `conversations`;
CREATE TABLE `conversations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED DEFAULT NULL,
  `buyer_id` BIGINT UNSIGNED NOT NULL,
  `seller_id` BIGINT UNSIGNED NOT NULL,
  `last_message_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_conversations_participants` (`buyer_id`, `seller_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 8. Table: messages
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `conversation_id` BIGINT UNSIGNED NOT NULL,
  `sender_id` BIGINT UNSIGNED NOT NULL,
  `message_text` TEXT NOT NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_messages_conversation` (`conversation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 9. Table: transactions
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `transaction_code` VARCHAR(50) NOT NULL UNIQUE,
  `buyer_id` BIGINT UNSIGNED NOT NULL,
  `seller_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `offer_id` BIGINT UNSIGNED DEFAULT NULL,
  `agreed_price` DECIMAL(15,2) NOT NULL,
  `delivery_method` ENUM('meetup', 'shipping') NOT NULL DEFAULT 'meetup',
  `shipping_address` TEXT DEFAULT NULL,
  `meetup_location` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'confirmed',
  `payment_method` ENUM('cod', 'transfer') NOT NULL DEFAULT 'cod',
  `payment_status` ENUM('unpaid', 'paid') NOT NULL DEFAULT 'unpaid',
  `payment_proof_url` VARCHAR(255) DEFAULT NULL,
  `buyer_confirmation` TINYINT(1) NOT NULL DEFAULT 0,
  `seller_confirmation` TINYINT(1) NOT NULL DEFAULT 0,
  `completed_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_transactions_buyer` (`buyer_id`),
  KEY `idx_transactions_seller` (`seller_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 10. Table: reviews
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `transaction_id` BIGINT UNSIGNED NOT NULL,
  `seller_id` BIGINT UNSIGNED NOT NULL,
  `buyer_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `rating` TINYINT NOT NULL DEFAULT 5,
  `comment` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_reviews_seller` (`seller_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 11. Table: reports
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `reports`;
CREATE TABLE `reports` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `reporter_id` BIGINT UNSIGNED NOT NULL,
  `target_type` ENUM('product', 'user') NOT NULL,
  `target_id` BIGINT UNSIGNED NOT NULL,
  `reason` VARCHAR(150) NOT NULL,
  `description` TEXT NOT NULL,
  `status` ENUM('pending', 'resolved', 'dismissed') NOT NULL DEFAULT 'pending',
  `admin_notes` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 12. Table: notifications
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `message` TEXT NOT NULL,
  `reference_type` VARCHAR(50) DEFAULT NULL,
  `reference_id` BIGINT UNSIGNED DEFAULT NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_notifications_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 13. Table: audit_logs
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` BIGINT UNSIGNED NOT NULL,
  `action` VARCHAR(100) NOT NULL,
  `target_type` VARCHAR(50) NOT NULL,
  `target_id` BIGINT UNSIGNED DEFAULT NULL,
  `metadata` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ====================================================================
-- SEED DATA / DUMMY DATA LENGKAP
-- Password semua akun: password123 (hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi)
-- ====================================================================

-- 1. USERS
INSERT INTO `users` (`id`, `full_name`, `username`, `email`, `phone`, `password_hash`, `role`, `status`, `avatar_url`, `bio`, `province`, `city`, `address`) VALUES
(1, 'Administrator Bekasin', 'admin', 'admin@bekasin.com', '081299990001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150', 'Super Administrator Platform Bekasin-Aja.', 'DKI Jakarta', 'Jakarta Pusat', 'Jl. Sudirman Kav 21'),
(2, 'Budi Santoso (Seller)', 'budisantoso', 'budi@gmail.com', '081288881234', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'member', 'active', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150', 'Penjual barang elektronik & kamera bekas terawat di Jakarta.', 'DKI Jakarta', 'Jakarta Selatan', 'Tebet Barat Dalam No. 12'),
(3, 'Rina Wijaya (Buyer)', 'rinawijaya', 'rina@gmail.com', '085711223344', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'member', 'active', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150', 'Pencari buku langka dan fashion vintage estetik.', 'Jawa Barat', 'Bandung', 'Jl. Dago Asri No. 45'),
(4, 'Dimas Pratama', 'dimaspratama', 'dimas@gmail.com', '087855443322', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'member', 'active', 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150', 'Kolektor gadget bekas mulus original.', 'DI Yogyakarta', 'Sleman', 'Jl. Kaliurang KM 5');

-- 2. CATEGORIES
INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `description`, `sort_order`, `is_active`) VALUES
(1, 'Elektronik & Gadget', 'elektronik-gadget', 'smartphone', 'Smartphone, laptop, tablet, dan aksesoris bekas berkualitas.', 1, 1),
(2, 'Kamera & Fotografi', 'kamera-fotografi', 'camera', 'Kamera DSLR, mirrorless, lensa, tripod, dan perlengkapan studio.', 2, 1),
(3, 'Fashion & Pakaian', 'fashion-pakaian', 'shirt', 'Pakaian preloved, jaket, sepatu sneakers, dan aksesoris gaya.', 3, 1),
(4, 'Hobi & Koleksi', 'hobi-koleksi', 'gamepad-2', 'Action figure, konsol game, kaset jadul, dan barang antik langka.', 4, 1),
(5, 'Buku & Majalah', 'buku-majalah', 'book-open', 'Buku kuliah, novel terjemahan, komik jadul, dan ensiklopedia.', 5, 1),
(6, 'Otomotif & Aksesoris', 'otomotif-aksesoris', 'car', 'Helm, suku cadang motor, velg, dan perlengkapan berkendara.', 6, 1),
(7, 'Peralatan Rumah Tangga', 'peralatan-rumah-tangga', 'home', 'Furnitur, sofa, kulkas, mesin kopi, dan peralatan dapur.', 7, 1);

-- 3. PRODUCTS
INSERT INTO `products` (`id`, `user_id`, `category_id`, `title`, `slug`, `description`, `price`, `condition`, `delivery_method`, `status`, `has_scratches`, `has_damages`, `is_functional`, `was_repaired`, `completeness_notes`, `province`, `city`, `meetup_location`, `views_count`) VALUES
(1, 2, 1, 'iPhone 13 Pro 128GB Sierra Blue Fullset Mulus 98%', 'iphone-13-pro-128gb-sierra-blue-fullset-mulus-98', 'Dijual santai iPhone 13 Pro warna Sierra Blue 128GB pemakaian pribadi. Battery Health 89% awet seharian. Face ID, True Tone, 120Hz ProMotion layar normal tanpa kendala. Bodi mulus 98% terawat selalu pakai case sejak hari pertama.', 9800000.00, 'very_good', 'both', 'active', 1, 0, 1, 0, 'Unit iPhone, Dus Box Original, Kabel Type-C Lightning Original, Bonus 2 Case Spigen', 'DKI Jakarta', 'Jakarta Selatan', 'Mall Kota Kasablanka atau Stasiun Tebet', 142),
(2, 2, 2, 'Sony Alpha A6400 Kit 16-50mm Shutter Count Rendah', 'sony-alpha-a6400-kit-16-50mm-shutter-count-rendah', 'Kamera mirrorless Sony A6400 + lensa kit 16-50mm OSS. Shutter count baru 4.200 jepretan. Sensor bersih tanpa jamur/debu, autofokus super kilat Eye-AF hewan & manusia bekerja sempurna. Jarang dipakai hanya untuk tugas kuliah.', 10500000.00, 'like_new', 'both', 'active', 0, 0, 1, 0, 'Body kamera, Lensa Kit 16-50mm, Baterai Original, Charger, Strap Sony, Box Lengkap', 'DKI Jakarta', 'Jakarta Selatan', 'Citos (Cilandak Town Square) atau Gandaria City', 88),
(3, 4, 1, 'MacBook Air M1 2020 8/256GB Space Grey Baterai Normal', 'macbook-air-m1-2020-8-256gb-space-grey-baterai-normal', 'MacBook Air Chip M1 tahun 2020 RAM 8GB SSD 256GB warna Space Grey. Keyboard empuk semua fungsi normal, trackpad lancar, speaker menggelegar. CC Baterai 180, kondisi Normal. Ada lecet pemakaian halus di pojok kiri bawah tidak mempengaruhi performa.', 8750000.00, 'good', 'both', 'active', 1, 0, 1, 0, 'Unit MacBook, Magsafe/Type-C Adapter 30W Original, Kabel Original, Dusbook', 'DI Yogyakarta', 'Sleman', 'Area Kampus UGM atau Gejayan', 215),
(4, 3, 3, 'Jaket Kulit Asli Vintage Schott Perfecto Size M', 'jaket-kulit-asli-vintage-schott-perfecto-size-m', 'Jaket kulit asli vintage model biker Schott Perfecto warna hitam pekat. Kulit tebal sudah lentur (patina mantap). Ritsleting YKK kuningan original lancar tanpa macet. Cocok untuk riding atau nongkrong.', 1250000.00, 'very_good', 'shipping', 'active', 0, 0, 1, 0, 'Hanya jaket kulit unit saja', 'Jawa Barat', 'Bandung', 'Kirim JNE / J&T dari Bandung', 64),
(5, 4, 4, 'Nintendo Switch V2 Neon Fullset CFW + MicroSD 128GB', 'nintendo-switch-v2-neon-fullset-cfw-microsd-128gb', 'Nintendo Switch V2 baterai awet. Sudah CFW Atmosphere terisi game-game populer: Zelda BOTW, Mario Kart 8, Pokemon Scarlet, Smash Bros. Joycon no drift siap main bareng teman dan keluarga.', 2950000.00, 'very_good', 'both', 'active', 0, 0, 1, 0, 'Tablet Switch, Joycon L/R Neon, Dock TV, Grip Joycon, Straps, Charger Ori, MicroSD 128GB, Dus', 'DI Yogyakarta', 'Sleman', 'Hartono Mall Yogyakarta atau Seturan', 178),
(6, 3, 5, 'Paket Novel Harry Potter 1-7 Terjemahan Gramedia Lengkap', 'paket-novel-harry-potter-1-7-terjemahan-gramedia-lengkap', 'Koleksi lengkap 7 buku novel Harry Potter edisi bahasa Indonesia penerbit Gramedia. Kertas sedikit menguning karena usia buku, namun halaman utuh 100% tanpa robek atau coretan.', 450000.00, 'good', 'shipping', 'sold', 0, 0, 1, 0, 'Buku 1 sampai 7 lengkap dalam 1 box', 'Jawa Barat', 'Bandung', 'Kirim ekspedisi Bandung', 92);

-- 4. PRODUCT IMAGES
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`) VALUES
(1, 1, 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800', 1, 0),
(2, 1, 'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=800', 0, 1),
(3, 2, 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800', 1, 0),
(4, 2, 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=800', 0, 1),
(5, 3, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800', 1, 0),
(6, 4, 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800', 1, 0),
(7, 5, 'https://images.unsplash.com/photo-1578301978693-85fa9c0320b9?w=800', 1, 0),
(8, 6, 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=800', 1, 0);

-- 5. WISHLISTS
INSERT INTO `wishlists` (`id`, `user_id`, `product_id`) VALUES
(1, 3, 1),
(2, 3, 2),
(3, 4, 1);

-- 6. OFFERS
INSERT INTO `offers` (`id`, `product_id`, `buyer_id`, `seller_id`, `offered_price`, `counter_price`, `status`, `notes`) VALUES
(1, 1, 3, 2, 9300000.00, NULL, 'pending', 'Bisa nego di Rp 9.300.000 kak? Saya siap COD di Stasiun Tebet sore ini.'),
(2, 6, 4, 3, 400000.00, NULL, 'accepted', 'Apakah boleh 400rb kak untuk paket Harry Potter?');

-- 7. CONVERSATIONS & MESSAGES
INSERT INTO `conversations` (`id`, `product_id`, `buyer_id`, `seller_id`, `last_message_at`) VALUES
(1, 1, 3, 2, NOW()),
(2, 6, 4, 3, NOW());

INSERT INTO `messages` (`id`, `conversation_id`, `sender_id`, `message_text`, `is_read`, `created_at`) VALUES
(1, 1, 3, 'Halo mas Budi, iPhone 13 Pro nya masih ada? Battery Health masih asli belum pernah ganti?', 1, DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(2, 1, 2, 'Halo mbak Rina, masih ada siap cod. Baterai masih asli bawaan pabrik 89%. Semua part original.', 1, DATE_SUB(NOW(), INTERVAL 1 HOUR)),
(3, 1, 3, 'Bisa COD di Kokas sore nanti sekitar jam 4 mas?', 0, DATE_SUB(NOW(), INTERVAL 30 MINUTE)),
(4, 2, 4, 'Halo, novel Harry Potter nya apakah kertasnya ada yang copot?', 1, DATE_SUB(NOW(), INTERVAL 5 HOUR)),
(5, 2, 3, 'Kertas lengkap semua mas, jilidan masih kokoh.', 1, DATE_SUB(NOW(), INTERVAL 4 HOUR));

-- 8. TRANSACTIONS
INSERT INTO `transactions` (`id`, `transaction_code`, `buyer_id`, `seller_id`, `product_id`, `offer_id`, `agreed_price`, `delivery_method`, `shipping_address`, `meetup_location`, `status`, `payment_method`, `payment_status`, `buyer_confirmation`, `seller_confirmation`, `completed_at`) VALUES
(1, 'TRX-HPOTTER-261008', 4, 3, 6, 2, 400000.00, 'shipping', 'Jl. Kaliurang KM 5 No. 18, Sleman, Yogyakarta 55281', NULL, 'completed', 'transfer', 'paid', 1, 1, NOW());

-- 9. REVIEWS
INSERT INTO `reviews` (`id`, `transaction_id`, `seller_id`, `buyer_id`, `product_id`, `rating`, `comment`) VALUES
(1, 1, 3, 4, 6, 5, 'Paket novel dibungkus bubble wrap sangat tebal dan aman! Kondisi buku sesuai deskripsi, respon penjual cepat & ramah. Recomended seller Bekasin-Aja!');

-- 10. NOTIFICATIONS
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `reference_type`, `reference_id`, `is_read`) VALUES
(1, 2, 'new_offer', 'Penawaran Baru Diterima!', 'Rina Wijaya menawar iPhone 13 Pro sebesar Rp 9.300.000.', 'offer', 1, 0),
(2, 4, 'transaction_completed', 'Transaksi Selesai!', 'Transaksi #TRX-HPOTTER-261008 telah selesai. Berikan ulasan untuk penjual.', 'transaction', 1, 1);

-- 11. AUDIT LOGS
INSERT INTO `audit_logs` (`id`, `admin_id`, `action`, `target_type`, `target_id`, `metadata`, `ip_address`, `user_agent`) VALUES
(1, 1, 'system_initialization', 'system', NULL, '{"event":"initial_seed_data_loaded","status":"success"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0 Safari/537.36');
