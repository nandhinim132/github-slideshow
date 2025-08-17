<?php

class DashboardController
{
    public function index(): void
    {
        require_login();
        $totalProducts = (int) Database::fetch('SELECT COUNT(*) AS c FROM products')['c'] ?? 0;
        $lowStock = (int) Database::fetch('SELECT COUNT(*) AS c FROM products WHERE quantity < stock_threshold')['c'] ?? 0;
        $todaySales = Database::fetch('SELECT COALESCE(SUM(total_amount),0) AS total FROM sales WHERE date(created_at) = date("now","localtime")');
        $todaySalesAmount = (float) ($todaySales['total'] ?? 0);
        View::render('dashboard/index', compact('totalProducts', 'lowStock', 'todaySalesAmount'));
    }
}