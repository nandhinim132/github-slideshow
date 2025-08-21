<?php

// Database configuration
const DB_HOST = '127.0.0.1';
const DB_PORT = '3306';
const DB_NAME = 'supermarket_db';
const DB_USER = 'root';
const DB_PASS = '';
const DB_CHARSET = 'utf8mb4';

// Application configuration
const APP_NAME = 'Supermarket Management System';
const APP_ENV = 'development';
const APP_TIMEZONE = 'Asia/Kolkata';

// GST configuration
const GST_RATE = 0.18; // 18%

// Roles
const ROLE_ADMIN = 'admin';
const ROLE_CASHIER = 'cashier';
const ROLE_SELLER = 'seller';
const ROLE_STOCKER = 'stocker';

// Paths
define('APP_BASE_PATH', dirname(__DIR__, 2));
define('APP_PUBLIC_PATH', APP_BASE_PATH . '/public');

// URL base (adjust if deploying under subdirectory)
// You can change this to the domain or subpath where the app is hosted
// Example: '/supermarket/public'
$__appUrlBase = '/public';
if (!defined('APP_URL_BASE')) {
	define('APP_URL_BASE', $__appUrlBase);
}