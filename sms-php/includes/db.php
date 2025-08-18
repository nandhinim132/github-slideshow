<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function get_pdo(): PDO {
    static $pdo = null;
    global $DB_PATH;
    if ($pdo === null) {
        if (!is_dir(dirname($DB_PATH))) {
            mkdir(dirname($DB_PATH), 0775, true);
        }
        $pdo = new PDO('sqlite:' . $DB_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->exec('PRAGMA foreign_keys = ON');
    }
    return $pdo;
}

function db_query(string $sql, array $params = []): PDOStatement {
    $stmt = get_pdo()->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

function db_fetch_all(string $sql, array $params = []): array {
    return db_query($sql, $params)->fetchAll();
}

function db_fetch(string $sql, array $params = []): ?array {
    $stmt = db_query($sql, $params);
    $row = $stmt->fetch();
    return $row === false ? null : $row;
}

function db_exec(string $sql, array $params = []): int {
    $stmt = db_query($sql, $params);
    return $stmt->rowCount();
}

?>

