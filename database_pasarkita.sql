-- Database PasarKita: Marketplace Tradisional
-- Buat database melalui panel hosting, pilih database tersebut di phpMyAdmin, lalu import file ini.
-- File ini tidak menghapus tabel atau data yang sudah ada.

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
  status ENUM('active','inactive') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NULL,
  name VARCHAR(160) NOT NULL,
  description TEXT NULL,
  price DECIMAL(12,2) NOT NULL DEFAULT 0,
  stock INT NOT NULL DEFAULT 0,
  image VARCHAR(255) NULL,
  status ENUM('active','inactive') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  INDEX idx_products_name(name),
  INDEX idx_products_status(status)
) ENGINE=InnoDB;

CREATE TABLE orders (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  total_amount DECIMAL(14,2) NOT NULL DEFAULT 0,
  address TEXT NOT NULL,
  phone VARCHAR(30) NOT NULL,
  notes TEXT NULL,
  status ENUM('pending','processed','shipped','completed','cancelled') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX idx_orders_status(status),
  INDEX idx_orders_created(created_at)
) ENGINE=InnoDB;

CREATE TABLE order_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NULL,
  product_name VARCHAR(160) NOT NULL,
  price DECIMAL(12,2) NOT NULL,
  quantity INT NOT NULL,
  subtotal DECIMAL(14,2) NOT NULL,
  CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  INDEX idx_order_items_product(product_id)
) ENGINE=InnoDB;

INSERT INTO categories(name) VALUES
('Sayuran'),('Buah-buahan'),('Bahan Pokok'),('Bumbu Dapur'),('Hasil Ternak');

INSERT INTO products(category_id,name,description,price,stock,status) VALUES
(1,'Bayam Segar','Bayam segar pilihan dari petani lokal.',5000,40,'active'),
(1,'Tomat Merah','Tomat merah segar, cocok untuk masakan harian.',12000,30,'active'),
(1,'Cabai Merah','Cabai merah pilihan dengan kualitas baik.',28000,25,'active'),
(2,'Pisang Kepok','Pisang kepok matang dengan rasa manis.',15000,20,'active'),
(2,'Jeruk Lokal','Jeruk lokal segar dan kaya vitamin.',18000,35,'active'),
(3,'Beras 5 Kg','Beras kualitas baik untuk kebutuhan keluarga.',72000,18,'active'),
(4,'Bawang Merah','Bawang merah segar untuk bumbu dapur.',32000,22,'active'),
(5,'Telur Ayam 1 Kg','Telur ayam segar dari peternak lokal.',29000,24,'active');

-- Buat akun melalui halaman Register. Untuk menjadikan akun sebagai admin:
-- UPDATE users SET role='admin' WHERE email='email-akun-anda@example.com';
