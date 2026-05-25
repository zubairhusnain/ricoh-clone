<?php
declare(strict_types=1);

/**
 * Strip marketing / analytics markup from HTML output (safety net after static cleanup).
 */
function rh_strip_tracking_html(string $html): string
{
    $html = preg_replace(
        '~<!-- Google Tag Manager -->[\s\S]*?<!-- End Google Tag Manager -->\s*~i',
        '',
        $html
    ) ?? $html;

    $html = preg_replace(
        '~<!-- Google Tag Manager \(noscript\) -->[\s\S]*?<!-- End Google Tag Manager \(noscript\) -->\s*~i',
        '',
        $html
    ) ?? $html;

    $html = preg_replace(
        '~<!-- CookiePro[\s\S]*?<!-- CookiePro[\s\S]*?-->\s*~i',
        '',
        $html
    ) ?? $html;

    $html = preg_replace(
        '~<script type="text/javascript">\s*function OptanonWrapper\(\)\s*\{\s*\}\s*</script>\s*~i',
        '',
        $html
    ) ?? $html;

    $html = preg_replace(
        '~<!-- CookiePro Cookies Settings button start -->[\s\S]*?<!-- CookiePro Cookies Settings button end -->\s*~i',
        '',
        $html
    ) ?? $html;

    $html = preg_replace(
        '~<li class="gl-footer-utility_item">\s*<button[^>]*id="ot-sdk-btn"[^>]*>[\s\S]*?</button>\s*</li>\s*~i',
        '',
        $html
    ) ?? $html;

    $html = preg_replace(
        '~<!--MARS FINDER-->[\s\S]*?<!--/MARS FINDER-->\s*~i',
        '',
        $html
    ) ?? $html;

    $html = preg_replace(
        '~<!-- MARS FINDER -->[\s\S]*?<!-- /MARS FINDER -->[\s\S]*?<!--/MARS FINDER-->\s*~i',
        '',
        $html
    ) ?? $html;

    $dropRe = '~googletagmanager|youtube\.com/iframe|youtube\.com/s/player|trackingCode\.js|marsflag|blog\.ricoh\.co\.jp.*\.js|cookiepro|OtAutoBlock|otSDKStub|tools\.euroland|eurolandtoolsintegration~i';

    $html = preg_replace_callback(
        '~<script\b[^>]*>[\s\S]*?</script>\s*~i',
        static function (array $m) use ($dropRe): string {
            return preg_match($dropRe, $m[0]) ? '' : $m[0];
        },
        $html
    ) ?? $html;

    $html = preg_replace_callback(
        '~<script\b[^>]*/>\s*~i',
        static function (array $m) use ($dropRe): string {
            return preg_match($dropRe, $m[0]) ? '' : $m[0];
        },
        $html
    ) ?? $html;

    $html = preg_replace(
        '~<link[^>]*cdn\.fonts\.net/t/1\.css[^>]*>\s*~i',
        '',
        $html
    ) ?? $html;

    $html = preg_replace(
        '~<link[^>]*rel=["\']preconnect["\'][^>]*cdn\.fonts\.net[^>]*>\s*~i',
        '',
        $html
    ) ?? $html;

    $html = preg_replace('~<meta name="eloqua"[^>]*>\s*~i', '', $html) ?? $html;

    $html = preg_replace_callback(
        '~<iframe[^>]*src="(?:https?:)?//[^"]*"[^>]*>[\s\S]*?</iframe>\s*~i',
        static function (array $m): string {
            return preg_match('~googletagmanager|euroland|asia\.tools~i', $m[0]) ? '' : $m[0];
        },
        $html
    ) ?? $html;

    $html = preg_replace(
        '~<noscript>\s*<iframe[^>]*googletagmanager[^>]*>[\s\S]*?</iframe>\s*</noscript>\s*~i',
        '',
        $html
    ) ?? $html;

    return $html;
}

function rh_send_security_headers(): void
{
    if (headers_sent()) {
        return;
    }

    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    $frameSrc = "'self'";
    if (defined('RH_BASE_URL')) {
        $frameSrc .= ' ' . RH_BASE_URL . '/';
    }
    header(
        'Content-Security-Policy: ' .
        "default-src 'self'; " .
        "script-src 'self' 'unsafe-inline' https://cdn.fonts.net; " .
        "style-src 'self' 'unsafe-inline'; " .
        "img-src 'self' data: blob:; " .
        "font-src 'self' data:; " .
        "connect-src 'self'; " .
        "frame-src {$frameSrc}; " .
        "object-src 'none'; " .
        "base-uri 'self'; " .
        "form-action 'self'"
    );
}
