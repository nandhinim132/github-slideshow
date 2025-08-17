<?php

class InventoryController
{
    public function index(): void
    {
        require_login();
        $products = Database::fetchAll('SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id = p.category_id ORDER BY p.name');
        View::render('inventory/index', compact('products'));
    }
}