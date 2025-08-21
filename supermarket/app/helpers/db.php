<?php

function getPdo(): PDO {
	static $pdo = null;
	if ($pdo instanceof PDO) {
		return $pdo;
	}

	$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', DB_HOST, DB_PORT, DB_NAME, DB_CHARSET);
	$options = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES => false,
	];
	$pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
	return $pdo;
}

function dbQuery(string $sql, array $params = []): PDOStatement {
	$pdo = getPdo();
	$stmt = $pdo->prepare($sql);
	$stmt->execute($params);
	return $stmt;
}

function dbFetchOne(string $sql, array $params = []): ?array {
	$stmt = dbQuery($sql, $params);
	$row = $stmt->fetch();
	return $row === false ? null : $row;
}

function dbFetchAll(string $sql, array $params = []): array {
	$stmt = dbQuery($sql, $params);
	return $stmt->fetchAll();
}