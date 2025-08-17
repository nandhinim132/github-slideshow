<?php

function bootstrap_migrations(): void
{
    $pdo = Database::pdo();

    $pdo->exec('CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        name TEXT NOT NULL,
        role TEXT NOT NULL CHECK (role IN ("admin","seller","cashier")),
        password_hash TEXT NOT NULL,
        created_at TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS employees (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        role TEXT NOT NULL CHECK (role IN ("seller","stocker","cashier")),
        salary REAL DEFAULT 0,
        phone TEXT,
        created_at TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS categories (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT UNIQUE NOT NULL,
        created_at TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS suppliers (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        phone TEXT,
        email TEXT,
        address TEXT,
        created_at TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        category_id INTEGER,
        supplier_id INTEGER,
        price REAL NOT NULL,
        mrp REAL,
        quantity INTEGER NOT NULL DEFAULT 0,
        expiry_date TEXT,
        barcode TEXT,
        stock_threshold INTEGER DEFAULT 5,
        created_at TEXT NOT NULL,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
        FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS purchases (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        supplier_id INTEGER,
        invoice_no TEXT,
        total_amount REAL NOT NULL,
        created_at TEXT NOT NULL,
        FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS purchase_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        purchase_id INTEGER NOT NULL,
        product_id INTEGER NOT NULL,
        quantity INTEGER NOT NULL,
        unit_cost REAL NOT NULL,
        FOREIGN KEY (purchase_id) REFERENCES purchases(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS sales (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        customer_name TEXT,
        subtotal REAL NOT NULL,
        discount REAL DEFAULT 0,
        tax REAL DEFAULT 0,
        total_amount REAL NOT NULL,
        created_at TEXT NOT NULL,
        user_id INTEGER,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS sale_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        sale_id INTEGER NOT NULL,
        product_id INTEGER NOT NULL,
        quantity INTEGER NOT NULL,
        unit_price REAL NOT NULL,
        FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )');

    // Seed admin if not exists
    $admin = Database::fetch('SELECT id FROM users WHERE username = :u', ['u' => 'admin']);
    if (!$admin) {
        $hash = password_hash('admin123', PASSWORD_BCRYPT);
        Database::insert('INSERT INTO users (username, name, role, password_hash, created_at) VALUES (:u,:n,:r,:p,:c)', [
            'u' => 'admin',
            'n' => 'Administrator',
            'r' => 'admin',
            'p' => $hash,
            'c' => date('c'),
        ]);
    }
}