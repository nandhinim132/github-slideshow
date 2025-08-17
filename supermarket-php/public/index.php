<?php

declare(strict_types=1);

$config = require __DIR__ . '/../core/config.php';

// Timezone and session
date_default_timezone_set($config['timezone']);
session_name($config['session_name']);
session_start();

// Autoload core classes
spl_autoload_register(function ($class) {
    $paths = [__DIR__ . '/../core/' . $class . '.php', __DIR__ . '/../controllers/' . $class . '.php', __DIR__ . '/../models/' . $class . '.php'];
    foreach ($paths as $path) {
        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});

require_once __DIR__ . '/../core/Helpers.php';

// Connect DB
Database::connect($config['db_path']);

// Run migrations (idempotent)
 require __DIR__ . '/../core/migrations.php';
 bootstrap_migrations();

// Simple router
$route = $_GET['r'] ?? 'dashboard';

switch ($route) {
    case 'login':
        require __DIR__ . '/../controllers/AuthController.php';
        (new AuthController($config))->login();
        break;
    case 'logout':
        require __DIR__ . '/../controllers/AuthController.php';
        (new AuthController($config))->logout();
        break;
    case 'change-password':
        require __DIR__ . '/../controllers/AuthController.php';
        (new AuthController($config))->changePassword();
        break;

    case 'users':
        require __DIR__ . '/../controllers/UserController.php';
        (new UserController())->index();
        break;
    case 'users/create':
        require __DIR__ . '/../controllers/UserController.php';
        (new UserController())->create();
        break;
    case 'users/edit':
        require __DIR__ . '/../controllers/UserController.php';
        (new UserController())->edit();
        break;
    case 'users/delete':
        require __DIR__ . '/../controllers/UserController.php';
        (new UserController())->delete();
        break;

    case 'employees':
        require __DIR__ . '/../controllers/EmployeeController.php';
        (new EmployeeController())->index();
        break;
    case 'employees/create':
        require __DIR__ . '/../controllers/EmployeeController.php';
        (new EmployeeController())->create();
        break;
    case 'employees/edit':
        require __DIR__ . '/../controllers/EmployeeController.php';
        (new EmployeeController())->edit();
        break;
    case 'employees/delete':
        require __DIR__ . '/../controllers/EmployeeController.php';
        (new EmployeeController())->delete();
        break;

    case 'categories':
        require __DIR__ . '/../controllers/CategoryController.php';
        (new CategoryController())->index();
        break;
    case 'categories/create':
        require __DIR__ . '/../controllers/CategoryController.php';
        (new CategoryController())->create();
        break;
    case 'categories/edit':
        require __DIR__ . '/../controllers/CategoryController.php';
        (new CategoryController())->edit();
        break;
    case 'categories/delete':
        require __DIR__ . '/../controllers/CategoryController.php';
        (new CategoryController())->delete();
        break;

    case 'products':
        require __DIR__ . '/../controllers/ProductController.php';
        (new ProductController())->index();
        break;
    case 'products/create':
        require __DIR__ . '/../controllers/ProductController.php';
        (new ProductController())->create();
        break;
    case 'products/edit':
        require __DIR__ . '/../controllers/ProductController.php';
        (new ProductController())->edit();
        break;
    case 'products/delete':
        require __DIR__ . '/../controllers/ProductController.php';
        (new ProductController())->delete();
        break;

    case 'suppliers':
        require __DIR__ . '/../controllers/SupplierController.php';
        (new SupplierController())->index();
        break;
    case 'suppliers/create':
        require __DIR__ . '/../controllers/SupplierController.php';
        (new SupplierController())->create();
        break;
    case 'suppliers/edit':
        require __DIR__ . '/../controllers/SupplierController.php';
        (new SupplierController())->edit();
        break;
    case 'suppliers/delete':
        require __DIR__ . '/../controllers/SupplierController.php';
        (new SupplierController())->delete();
        break;

    case 'inventory':
        require __DIR__ . '/../controllers/InventoryController.php';
        (new InventoryController())->index();
        break;

    case 'purchases':
        require __DIR__ . '/../controllers/PurchaseController.php';
        (new PurchaseController())->index();
        break;
    case 'purchases/create':
        require __DIR__ . '/../controllers/PurchaseController.php';
        (new PurchaseController())->create();
        break;
    case 'purchases/view':
        require __DIR__ . '/../controllers/PurchaseController.php';
        (new PurchaseController())->view();
        break;

    case 'sales':
        require __DIR__ . '/../controllers/SaleController.php';
        (new SaleController())->index();
        break;
    case 'sales/create':
        require __DIR__ . '/../controllers/SaleController.php';
        (new SaleController())->create();
        break;
    case 'sales/view':
        require __DIR__ . '/../controllers/SaleController.php';
        (new SaleController())->view();
        break;

    case 'customers':
        require __DIR__ . '/../controllers/CustomerController.php';
        (new CustomerController())->index();
        break;

    case 'reports':
        require __DIR__ . '/../controllers/ReportController.php';
        (new ReportController())->index();
        break;

    case 'dashboard':
    default:
        require __DIR__ . '/../controllers/DashboardController.php';
        (new DashboardController())->index();
        break;
}