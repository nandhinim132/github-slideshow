<?php

class View
{
    public static function render(string $template, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $viewBase = __DIR__ . '/../views/';
        $templateFile = $viewBase . $template . '.php';
        $header = $viewBase . 'partials/header.php';
        $footer = $viewBase . 'partials/footer.php';

        if (!is_file($templateFile)) {
            http_response_code(404);
            echo 'View not found: ' . e($template);
            return;
        }

        include $header;
        include $templateFile;
        include $footer;
    }
}