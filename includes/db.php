<?php
function db(): PDO {
    static $pdo = null;
    global $config;
    if ($pdo instanceof PDO) return $pdo;

    $url = (string)($config['db']['url'] ?? '');
    if ($url !== '') {
        $parts = parse_url($url);
        if ($parts === false || empty($parts['host']) || empty($parts['path'])) {
            throw new RuntimeException('DATABASE_URL inválida.');
        }
        $host = $parts['host'];
        $port = $parts['port'] ?? 5432;
        $name = ltrim($parts['path'], '/');
        $user = urldecode($parts['user'] ?? '');
        $pass = urldecode($parts['pass'] ?? '');
    } else {
        foreach (['host','name','user'] as $required) {
            if (empty($config['db'][$required])) {
                throw new RuntimeException('Banco não configurado. Defina DATABASE_URL no Render.');
            }
        }
        $host = $config['db']['host'];
        $port = $config['db']['port'] ?: '5432';
        $name = $config['db']['name'];
        $user = $config['db']['user'];
        $pass = $config['db']['pass'];
    }

    $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', $host, $port, $name);
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}
