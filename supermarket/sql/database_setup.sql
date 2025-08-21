-- Database: supermarket_db

CREATE DATABASE IF NOT EXISTS supermarket_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE supermarket_db;

-- Users
CREATE TABLE IF NOT EXISTS users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(64) NOT NULL UNIQUE,
	password_hash VARCHAR(255) NOT NULL,
	full_name VARCHAR(128) NOT NULL,
	role ENUM('admin','cashier','seller','stocker') NOT NULL,
	status ENUM('active','inactive') NOT NULL DEFAULT 'active',
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Categories
CREATE TABLE IF NOT EXISTS categories (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(100) NOT NULL UNIQUE,
	status ENUM('active','inactive') NOT NULL DEFAULT 'active',
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Suppliers
CREATE TABLE IF NOT EXISTS suppliers (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(150) NOT NULL,
	contact VARCHAR(150) NULL,
	phone VARCHAR(30) NULL,
	gst_number VARCHAR(32) NULL,
	status ENUM('active','inactive') NOT NULL DEFAULT 'active',
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Products
CREATE TABLE IF NOT EXISTS products (
	id INT AUTO_INCREMENT PRIMARY KEY,
	barcode VARCHAR(64) NULL UNIQUE,
	name VARCHAR(150) NOT NULL,
	category_id INT NULL,
	purchase_price DECIMAL(12,2) NOT NULL DEFAULT 0,
	selling_price DECIMAL(12,2) NOT NULL DEFAULT 0,
	mrp DECIMAL(12,2) NULL,
	stock INT NOT NULL DEFAULT 0,
	low_stock_threshold INT NOT NULL DEFAULT 5,
	expiry_date DATE NULL,
	status ENUM('active','inactive') NOT NULL DEFAULT 'active',
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Supplier-Product mapping
CREATE TABLE IF NOT EXISTS supplier_products (
	supplier_id INT NOT NULL,
	product_id INT NOT NULL,
	PRIMARY KEY (supplier_id, product_id),
	FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE,
	FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Customers
CREATE TABLE IF NOT EXISTS customers (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(150) NOT NULL,
	phone VARCHAR(30) NULL,
	email VARCHAR(150) NULL,
	loyalty_points INT NOT NULL DEFAULT 0,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Purchases
CREATE TABLE IF NOT EXISTS purchases (
	id INT AUTO_INCREMENT PRIMARY KEY,
	supplier_id INT NULL,
	total_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
	gst_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS purchase_items (
	id INT AUTO_INCREMENT PRIMARY KEY,
	purchase_id INT NOT NULL,
	product_id INT NOT NULL,
	quantity INT NOT NULL,
	price DECIMAL(12,2) NOT NULL,
	total DECIMAL(12,2) NOT NULL,
	FOREIGN KEY (purchase_id) REFERENCES purchases(id) ON DELETE CASCADE,
	FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Sales
CREATE TABLE IF NOT EXISTS sales (
	id INT AUTO_INCREMENT PRIMARY KEY,
	customer_id INT NULL,
	subtotal DECIMAL(12,2) NOT NULL DEFAULT 0,
	gst_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
	total_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
	payment_method ENUM('cash','card','upi','other') NOT NULL DEFAULT 'cash',
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sale_items (
	id INT AUTO_INCREMENT PRIMARY KEY,
	sale_id INT NOT NULL,
	product_id INT NOT NULL,
	quantity INT NOT NULL,
	price DECIMAL(12,2) NOT NULL,
	total DECIMAL(12,2) NOT NULL,
	FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
	FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Inventory movements
CREATE TABLE IF NOT EXISTS inventory_movements (
	id INT AUTO_INCREMENT PRIMARY KEY,
	product_id INT NOT NULL,
	change_qty INT NOT NULL,
	reason ENUM('purchase','sale','manual_adjustment') NOT NULL,
	note VARCHAR(255) NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Demo users
INSERT INTO users (username, password_hash, full_name, role, status) VALUES
	('admin',   '$2y$10$Wq5kLZ8dX1j9O8vU0mGJqe0WZ1gqzYzaz1fZUxHn1JQ9qvYz7tE2a', 'Administrator', 'admin', 'active'),
	('cashier1', '$2y$10$Wq5kLZ8dX1j9O8vU0mGJqe0WZ1gqzYzaz1fZUxHn1JQ9qvYz7tE2a', 'Cashier One',  'cashier', 'active'),
	('seller1',  '$2y$10$Wq5kLZ8dX1j9O8vU0mGJqe0WZ1gqzYzaz1fZUxHn1JQ9qvYz7tE2a', 'Seller One',   'seller', 'active'),
	('stocker1', '$2y$10$Wq5kLZ8dX1j9O8vU0mGJqe0WZ1gqzYzaz1fZUxHn1JQ9qvYz7tE2a', 'Stocker One',  'stocker', 'active')
ON DUPLICATE KEY UPDATE full_name=VALUES(full_name);

-- The above hash corresponds to the plaintext password: password