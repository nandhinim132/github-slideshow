<?php

class ReportController
{
    public function index(): void
    {
        require_login();
        $from = request_param('from');
        $to = request_param('to');
        $params = [];
        $where = '';
        if ($from && $to) {
            $where = 'WHERE date(created_at) BETWEEN date(:f) AND date(:t)';
            $params = ['f' => $from, 't' => $to];
        }
        $sales = Database::fetch('SELECT COALESCE(SUM(total_amount),0) AS total FROM sales ' . $where, $params);
        $purchases = Database::fetch('SELECT COALESCE(SUM(total_amount),0) AS total FROM purchases ' . $where, $params);
        $salesTotal = (float) ($sales['total'] ?? 0);
        $purchaseTotal = (float) ($purchases['total'] ?? 0);
        $profit = $salesTotal - $purchaseTotal; // simplified

        // Employee-wise sales breakdown
        $salesWhere = '';
        $salesParams = [];
        if ($from && $to) {
            $salesWhere = 'WHERE date(s.created_at) BETWEEN date(:f) AND date(:t)';
            $salesParams = ['f' => $from, 't' => $to];
        }
        $employeeSales = Database::fetchAll('SELECT u.id, u.name, u.role, COUNT(s.id) AS sales_count, COALESCE(SUM(s.total_amount),0) AS total_sales
            FROM sales s LEFT JOIN users u ON u.id = s.user_id ' . $salesWhere . '
            GROUP BY u.id, u.name, u.role
            ORDER BY total_sales DESC', $salesParams);

        View::render('reports/index', compact('salesTotal', 'purchaseTotal', 'profit', 'from', 'to', 'employeeSales'));
    }
}