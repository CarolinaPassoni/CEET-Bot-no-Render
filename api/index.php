<?php
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = '/' . ltrim($path, '/');
if ($path === '/') $path = '/index.php';
if (str_ends_with($path, '/')) $path .= 'index.php';

$allowed = [
    '/index.php' => 'index.php',
    '/login.php' => 'login.php',
    '/logout.php' => 'logout.php',
    '/setup.php' => 'setup.php',
    '/admin/index.php' => 'admin/index.php',
    '/admin/crud.php' => 'admin/crud.php',
    '/admin/users.php' => 'admin/users.php',
    '/admin/whatsapp.php' => 'admin/whatsapp.php',
    '/webhook/whatsapp.php' => 'webhook/whatsapp.php',
];
if (!isset($allowed[$path])) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Página não encontrada.";
    exit;
}
$_SERVER['PHP_SELF'] = $path;
$_SERVER['SCRIPT_NAME'] = $path;
$target = dirname(__DIR__) . '/' . $allowed[$path];
require $target;
