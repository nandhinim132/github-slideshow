<?php
declare(strict_types=1);

// Application configuration

// Path to SQLite database file
$DB_PATH = __DIR__ . '/../data/sms.sqlite';

// Application name
$APP_NAME = 'Supermarket Management System';

// Default currency symbol
$CURRENCY_SYMBOL = '₹';

// Session settings
ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Error reporting (toggle as needed)
error_reporting(E_ALL);
ini_set('display_errors', '1');

// CSRF token name
const CSRF_TOKEN_KEY = '_csrf';

// Roles
const ROLE_ADMIN = 'admin';
const ROLE_SELLER = 'seller';
const ROLE_CASHIER = 'cashier';

?>

