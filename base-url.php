<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/rh-php-polyfill.php';
require_once __DIR__ . '/includes/rh-sanitize-tracking.php';
require_once __DIR__ . '/includes/rh-external-urls.php';
require_once __DIR__ . '/includes/rh-404.php';

function rh_install_base_path(): string
{
    static $path = null;
    if ($path !== null) {
        return $path;
    }

    $override = getenv('RH_BASE_PATH');
    if (is_string($override)) {
        $path = ($override === '' || $override === '/') ? '' : (str_starts_with($override, '/') ? $override : '/' . $override);
        return $path;
    }

    $docRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
    $here = realpath(__DIR__);
    if ($docRoot !== '' && $here !== false) {
        $root = realpath($docRoot);
        if ($root !== false) {
            if ($root === $here) {
                $path = '';
                return $path;
            }
            $rootPrefix = $root . DIRECTORY_SEPARATOR;
            if (str_starts_with($here, $rootPrefix)) {
                $rel = substr($here, strlen($root));
                $path = str_replace('\\', '/', $rel);
                if ($path !== '' && !str_starts_with($path, '/')) {
                    $path = '/' . $path;
                }
                return $path;
            }
        }
    }

    $path = '/' . basename(__DIR__);
    return $path;
}

function rh_normalize_request_path(string $path): string
{
    $base = rh_install_base_path();
    if ($base !== '') {
        if ($path === $base) {
            $path = '/';
        } elseif (str_starts_with($path, $base . '/')) {
            $path = substr($path, strlen($base));
        }
    }

    if ($path === '') {
        $path = '/';
    }

    return $path;
}

if (!defined('RH_BASE_URL')) {
    if (isset($_SERVER['HTTP_HOST'])) {
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? '') === '443');
        $scheme = $isHttps ? 'https' : 'http';
        $host = (string)$_SERVER['HTTP_HOST'];
        $base = $scheme . '://' . $host . rh_install_base_path();
    } else {
        $base = '__RH_BASE__/' . rh_install_base_path();
    }
    define('RH_BASE_URL', rtrim($base, '/'));
}

function rh_legacy_asset_prefixes(): array
{
    static $prefixes = null;
    if ($prefixes !== null) {
        return $prefixes;
    }

    $prefixes = ['/ricoh-clone/ricoh_offline'];
    $override = getenv('RH_LEGACY_BAKE_PATH');
    if (is_string($override) && $override !== '') {
        $prefixes[] = rtrim($override, '/');
    }
    $base = rh_install_base_path();
    if ($base !== '' && !in_array($base, $prefixes, true)) {
        $prefixes[] = $base;
    }

    return $prefixes;
}

function rh_route_from_fs_path(string $fsPath): string
{
    $rel = str_replace('\\', '/', $fsPath);
    if (str_ends_with($rel, '/index.php')) {
        $rel = substr($rel, 0, -strlen('/index.php'));
    } elseif (str_ends_with($rel, '/index.html')) {
        $rel = substr($rel, 0, -strlen('/index.html'));
    }
    return trim($rel, '/');
}

function rh_is_asset_path(string $path): bool
{
    if ($path === '') {
        return false;
    }

    if (preg_match('~(^|/)(assets/)~i', $path)) {
        return true;
    }

    return (bool)preg_match('~\.(css|js|mjs|map|svg|png|jpe?g|gif|webp|woff2?|ttf|eot|ico|avif|pdf)(\?|#|$)~i', $path);
}

function rh_valid_page_routes(): array
{
    static $validRoutes = null;
    if ($validRoutes !== null) {
        return $validRoutes;
    }

    $validRoutes = ['' => true];
    $pagesDir = __DIR__ . '/pages';
    if (is_dir($pagesDir)) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($pagesDir, FilesystemIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getFilename() === 'index.php') {
                $rel = str_replace('\\', '/', substr($file->getPath(), strlen($pagesDir) + 1));
                $validRoutes[$rel] = true;
            }
        }
    }

    return $validRoutes;
}

function rh_resolve_internal_href(string $currentRoute, string $href): ?string
{
    $href = trim($href);
    if (
        $href === '' ||
        str_starts_with($href, '#') ||
        str_starts_with($href, 'mailto:') ||
        str_starts_with($href, 'tel:') ||
        str_starts_with($href, 'javascript:') ||
        preg_match('~^https?://~i', $href)
    ) {
        return null;
    }

    $parts = parse_url($href);
    $path = $parts['path'] ?? '';
    if ($path === '' || rh_is_asset_path($path)) {
        return null;
    }

    $query = isset($parts['query']) ? ('?' . $parts['query']) : '';
    $hash = isset($parts['fragment']) ? ('#' . $parts['fragment']) : '';

    if (str_ends_with($path, '/index.html')) {
        $path = substr($path, 0, -strlen('/index.html'));
    } elseif (str_ends_with($path, '/index.php')) {
        $path = substr($path, 0, -strlen('/index.php'));
    } elseif ($path === 'index.html' || $path === 'index.php') {
        $path = '';
    }

    $currentDir = $currentRoute === '' ? '' : $currentRoute;
    $baseDir = $currentDir === '' ? '' : dirname($currentDir);
    if ($baseDir === '.') {
        $baseDir = '';
    }

    if (str_starts_with($path, '/')) {
        $resolved = trim($path, '/');
        foreach (rh_legacy_asset_prefixes() as $prefix) {
            if ($prefix !== '' && str_starts_with($resolved, trim($prefix, '/') . '/')) {
                $resolved = substr($resolved, strlen(trim($prefix, '/') . '/'));
                break;
            }
        }
    } else {
        $prefix = $currentDir === '' ? '' : ($currentDir . '/');
        if (str_starts_with($path, './')) {
            $path = substr($path, 2);
        }
        $combined = $prefix . $path;
        $segments = [];
        foreach (explode('/', $combined) as $seg) {
            if ($seg === '' || $seg === '.') {
                continue;
            }
            if ($seg === '..') {
                array_pop($segments);
                continue;
            }
            $segments[] = $seg;
        }
        $resolved = implode('/', $segments);
    }

    if ($resolved === 'index.html' || str_ends_with($resolved, '/index.html')) {
        $resolved = preg_replace('~/index\.html$~', '', $resolved) ?? '';
    }
    if ($resolved === 'index.php' || str_ends_with($resolved, '/index.php')) {
        $resolved = preg_replace('~/index\.php$~', '', $resolved) ?? '';
    }

    if (rh_is_asset_path($resolved)) {
        return null;
    }

    $validRoutes = rh_valid_page_routes();
    if ($resolved !== '' && !isset($validRoutes[$resolved])) {
        // Offline mirror: keep clean URL even when page was not scraped (router returns 404).
    }

    $url = RH_BASE_URL;
    if ($resolved !== '') {
        $url .= '/' . $resolved;
    }
    return $url . $query . $hash;
}

function rh_rewrite_baked_asset_urls(string $html): string
{
    $base = RH_BASE_URL;
    foreach (rh_legacy_asset_prefixes() as $prefix) {
        if ($prefix !== '') {
            $html = str_replace($prefix . '/assets/', $base . '/assets/', $html);
        }
    }
    return $html;
}

function rh_rewrite_html_urls(string $html): string
{
    $base = RH_BASE_URL;
    $requestPath = $_SERVER['REQUEST_URI'] ?? '/';
    $path = parse_url($requestPath, PHP_URL_PATH);
    if (!is_string($path) || $path === '') {
        $path = '/';
    }
    $path = rh_normalize_request_path($path);
    $currentRoute = trim($path, '/');

    $html = rh_rewrite_baked_asset_urls($html);
    $html = str_replace('__RH_BASE__', $base, $html);
    $html = rh_rewrite_external_urls($html, $currentRoute);

    $html = preg_replace(
        '~\b(href|src)=(["\'])(?:\.\./)+(assets/[^"\']*)\2~i',
        '$1=$2' . $base . '/$3$2',
        $html
    ) ?? $html;

    $html = preg_replace(
        '~\b(href|src)=(["\'])\./(assets/[^"\']*)\2~i',
        '$1=$2' . $base . '/$3$2',
        $html
    ) ?? $html;

    $html = preg_replace(
        '~\b(href|src)=(["\'])/(assets/[^"\']*)\2~i',
        '$1=$2' . $base . '/$3$2',
        $html
    ) ?? $html;

    $html = preg_replace_callback(
        '~\b(href)=(["\'])([^"\']+)\2~i',
        static function (array $m) use ($currentRoute): string {
            $resolved = rh_resolve_internal_href($currentRoute, $m[3]);
            if ($resolved === null) {
                return $m[0];
            }
            return 'href=' . $m[2] . $resolved . $m[2];
        },
        $html
    ) ?? $html;

    $html = preg_replace(
        '~\b(href)=(["\'])' . preg_quote($base, '~') . '/index\.html\2~i',
        'href=$2' . $base . '$2',
        $html
    ) ?? $html;

    $html = preg_replace(
        '~\b(href)=(["\'])' . preg_quote($base, '~') . '/index\.php\2~i',
        'href=$2' . $base . '$2',
        $html
    ) ?? $html;

    // Strip any remaining index.html suffixes in internal hrefs
    $html = preg_replace_callback(
        '~\bhref=(["\'])([^"\']*?)index\.html\1~i',
        static function (array $m) use ($currentRoute): string {
            $resolved = rh_resolve_internal_href($currentRoute, $m[2] . $m[3]);
            if ($resolved !== null) {
                return 'href=' . $m[1] . $resolved . $m[1];
            }
            return 'href=' . $m[1] . $m[2] . $m[3] . $m[1];
        },
        $html
    ) ?? $html;

    $html = rh_strip_tracking_html($html);

    return $html;
}

function rh_detect_asset_mime_type(string $localFs): string
{
    $ext = strtolower(pathinfo($localFs, PATHINFO_EXTENSION));

    if ($ext === 'css') {
        return 'text/css; charset=utf-8';
    }
    if ($ext === 'js' || $ext === 'mjs') {
        return 'application/javascript; charset=utf-8';
    }
    if ($ext === 'svg') {
        return 'image/svg+xml';
    }
    if ($ext === 'webp') {
        return 'image/webp';
    }
    if ($ext === 'woff2') {
        return 'font/woff2';
    }
    if ($ext === 'woff') {
        return 'font/woff';
    }

    if (function_exists('mime_content_type')) {
        $mt = mime_content_type($localFs);
        if (is_string($mt) && $mt !== '' && $mt !== 'application/octet-stream') {
            return $mt;
        }
    }

    if (class_exists('finfo')) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mt = $finfo->file($localFs);
        if (is_string($mt) && $mt !== '' && $mt !== 'application/octet-stream') {
            return $mt;
        }
    }

    return 'application/octet-stream';
}

function rh_start_output_rewrite(): void
{
    static $started = false;
    if ($started) {
        return;
    }
    $started = true;
    ob_start('rh_rewrite_html_urls');
    register_shutdown_function(static function (): void {
        if (ob_get_level() > 0) {
            ob_end_flush();
        }
    });
}
