<?php
session_start();

// Application settings
define('APP_NAME', 'Store Saathi');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost/supermarket-management-php/');

// Security settings
define('HASH_ALGO', PASSWORD_DEFAULT);
define('SESSION_TIMEOUT', 3600); // 1 hour

// GST settings
define('GST_RATE', 18); // 18% GST

// Currency settings
define('CURRENCY_SYMBOL', '₹');
define('CURRENCY_CODE', 'INR');

// Include database connection
require_once __DIR__ . '/database.php';

// Start database connection
$database = new Database();
$db = $database->getConnection();

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Check user role
function hasPermission($required_role) {
    if (!isLoggedIn()) return false;
    
    $user_role = $_SESSION['user_role'];
    $role_hierarchy = ['admin' => 4, 'seller' => 3, 'cashier' => 2, 'stocker' => 1];
    
    return $role_hierarchy[$user_role] >= $role_hierarchy[$required_role];
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . 'auth/login.php');
        exit();
    }
}

// Format currency
function formatCurrency($amount) {
    return CURRENCY_SYMBOL . number_format($amount, 2);
}

// Generate invoice number
function generateInvoiceNumber() {
    return 'INV' . date('Y') . date('m') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
}

// Generate PO number
function generatePONumber() {
    return 'PO' . date('Y') . date('m') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
}
?>