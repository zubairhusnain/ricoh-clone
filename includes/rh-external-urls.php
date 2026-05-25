<?php
declare(strict_types=1);

/**
 * Map external absolute URLs to offline site base URL or local assets.
 */
function rh_decode_attr_url(string $url): string
{
    return html_entity_decode($url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function rh_encode_attr_url(string $url): string
{
    return htmlspecialchars($url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function rh_ricoh_global_hosts(): array
{
    return ['www.ricoh.com', 'ricoh.com', 'ricoh.com.pk', 'www.ricoh.com.pk'];
}

function rh_map_external_url(string $url, string $currentRoute = ''): string
{
    $url = rh_decode_attr_url(trim($url));
    $base = RH_BASE_URL;

    if (
        $url === '' ||
        preg_match('~^(?:mailto:|tel:|javascript:|data:|blob:|#)~i', $url)
    ) {
        return $url;
    }

    if (str_starts_with($url, '//')) {
        $url = 'https:' . $url;
    }

    if (str_starts_with($url, $base)) {
        return $url;
    }

    $parts = parse_url($url);
    if ($parts === false || !isset($parts['host'])) {
        return $base . '/';
    }

    $host = strtolower($parts['host']);
    $localHost = parse_url($base, PHP_URL_HOST);
    if (is_string($localHost) && $host === strtolower($localHost)) {
        return $url;
    }

    $installPath = rh_install_base_path();
    if ($installPath !== '' && str_contains($url, $installPath)) {
        return $url;
    }
    $path = $parts['path'] ?? '/';
    $query = isset($parts['query']) ? ('?' . $parts['query']) : '';
    $fragment = isset($parts['fragment']) ? ('#' . $parts['fragment']) : '';

    if (in_array($host, rh_ricoh_global_hosts(), true)) {
        if (preg_match('#^/-/?media/#i', $path)) {
            $mediaPath = preg_replace('#^/-/?media/#i', '/-/Media/', $path) ?? $path;
            $local = __DIR__ . '/../assets/www.ricoh.com' . $mediaPath;
            if (!is_file($local)) {
                $alt = __DIR__ . '/../assets/www.ricoh.com' . preg_replace('#^/-/?Media/#i', '/-/media/', $path);
                if (is_file($alt)) {
                    $mediaPath = preg_replace('#^/-/?Media/#i', '/-/media/', $path) ?? $path;
                }
            }
            return $base . '/assets/www.ricoh.com' . $mediaPath . $query . $fragment;
        }

        $route = trim($path, '/');
        if ($route === '' || $route === 'index.html') {
            return $base . '/';
        }
        if (str_ends_with($route, '/index.html')) {
            $route = substr($route, 0, -strlen('/index.html'));
        }
        if (str_ends_with($route, '/index.php')) {
            $route = substr($route, 0, -strlen('/index.php'));
        }

        return $base . '/' . $route . $query . $fragment;
    }

    if ($host === 'prv.www.ricoh.com' && preg_match('#^/-/?media/#i', $path)) {
        return $base . '/assets/prv.www.ricoh.com' . $path . $query . $fragment;
    }

    if ($host === 'blog.ricoh.co.jp' && preg_match('#^/-/?media/#i', $path)) {
        return $base . '/assets/blog.ricoh.co.jp' . $path . $query . $fragment;
    }

    return $base . '/';
}

function rh_rewrite_external_urls(string $html, string $currentRoute = ''): string
{
    $base = RH_BASE_URL;

    // Remove OGP/XML namespace prefixes that point off-site
    $html = preg_replace(
        '~\s+prefix="og:\s*https?://ogp\.me/[^"]*"~i',
        '',
        $html
    ) ?? $html;

    $html = preg_replace(
        '~\s+xmlns:og="https?://ogp\.me/[^"]*"~i',
        '',
        $html
    ) ?? $html;

    // Attribute URLs (href, src, content in meta, etc.) — only rewrite non-local http(s)
    $html = preg_replace_callback(
        '~\b(href|src|content|action|poster|cite|data-href|data-url|data-src|data-poster)=(["\'])(https?://[^"\']+)\2~i',
        static function (array $m) use ($currentRoute, $base): string {
            $val = rh_decode_attr_url($m[3]);
            if (str_starts_with($val, $base)) {
                return $m[0];
            }
            $localHost = parse_url($base, PHP_URL_HOST);
            $host = parse_url($val, PHP_URL_HOST);
            if (is_string($localHost) && is_string($host) && strtolower($host) === strtolower($localHost)) {
                return $m[0];
            }
            $mapped = rh_map_external_url($m[3], $currentRoute);
            return $m[1] . '=' . $m[2] . rh_encode_attr_url($mapped) . $m[2];
        },
        $html
    ) ?? $html;

    // Protocol-relative attributes
    $html = preg_replace_callback(
        '~\b(href|src|content|action|poster|cite|data-href|data-url|data-src)=(["\'])(//[^"\']+)\2~i',
        static function (array $m) use ($currentRoute): string {
            $mapped = rh_map_external_url('https:' . $m[3], $currentRoute);
            return $m[1] . '=' . $m[2] . rh_encode_attr_url($mapped) . $m[2];
        },
        $html
    ) ?? $html;

    // Plain-text www.ricoh.com URLs in copy (do not match ricoh.com.pk — TLD continues after .com)
    $html = preg_replace_callback(
        '~https?://(?:www\.)?ricoh\.com(?!\.[a-z0-9-])(/[^\s<"]*)?~i',
        static function (array $m) use ($currentRoute): string {
            $path = $m[1] ?? '/';
            return rh_map_external_url('__RH_BASE__/' . $path, $currentRoute);
        },
        $html
    ) ?? $html;

    // Visible URL text in body (spans, paragraphs) — skip w3.org namespaces in SVG
    $html = preg_replace_callback(
        '~https?://[^\s"\'<>]+~i',
        static function (array $m) use ($base, $currentRoute): string {
            $raw = $m[0];
            if (
                str_starts_with($raw, $base)
                || str_contains($raw, '__RH_BASE__')
                || preg_match('~^https?://(?:www\.)?ricoh\.com\.pk~i', $raw)
            ) {
                return $raw;
            }
            if (preg_match('~^https?://www\.w3\.org/~i', $raw)) {
                return $raw;
            }
            return rh_map_external_url($raw, $currentRoute);
        },
        $html
    ) ?? $html;

    return $html;
}
