<?php
declare(strict_types=1);

/**
 * Render branded 404 response and exit.
 */
function rh_render_404_page(?string $requestedPath = null): void
{
    http_response_code(404);
    if (!headers_sent()) {
        header('Content-Type: text/html; charset=utf-8');
    }
    $GLOBALS['rh_404_requested_path'] = $requestedPath ?? '';
    rh_start_output_rewrite();
    require dirname(__DIR__) . '/404.php';
    exit;
}
