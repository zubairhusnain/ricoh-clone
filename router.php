<?php
declare(strict_types=1);

@ini_set('memory_limit', '512M');
@ini_set('max_execution_time', '120');

require_once __DIR__ . '/includes/rh-php-polyfill.php';
require_once __DIR__ . '/base-url.php';

rh_send_security_headers();

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($uri, PHP_URL_PATH);
if (!is_string($path) || $path === '') {
    $path = '/';
}

$path = rh_normalize_request_path($path);
$rootReal = realpath(__DIR__);
$pagesReal = realpath(__DIR__ . '/pages');

if (str_starts_with($path, '/assets/')) {
    $localFs = rh_resolve_asset_fs_path(__DIR__ . $path);
    if ($localFs === null) {
        http_response_code(404);
        exit;
    }

    $contentType = rh_detect_asset_mime_type($localFs);
    header('Content-Type: ' . $contentType);
    header('Cache-Control: public, max-age=86400');
    readfile($localFs);
    exit;
}

if (is_string($pagesReal) && str_starts_with($path, '/pages/') && str_ends_with($path, '.php')) {
    $candidate = realpath(__DIR__ . $path);
    if (
        is_string($candidate) &&
        is_string($pagesReal) &&
        str_starts_with($candidate, $pagesReal . DIRECTORY_SEPARATOR) &&
        is_file($candidate)
    ) {
        rh_start_output_rewrite();
        include $candidate;
        exit;
    }
}

$route = trim($path, '/');

if ($route === 'index.php' || $route === 'index.html') {
    header('Location: ' . RH_BASE_URL . '/', true, 301);
    exit;
}

if ($route === '') {
    $target = __DIR__ . '/index.php';
} else {
    $target = __DIR__ . '/pages/' . $route . '/index.php';
}

if (!is_file($target)) {
    rh_render_404_page($route);
}

rh_start_output_rewrite();
include $target;
