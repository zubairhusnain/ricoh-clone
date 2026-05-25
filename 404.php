<?php
declare(strict_types=1);

if (!defined('RH_BASE_URL')) {
    require_once __DIR__ . '/base-url.php';
    http_response_code(404);
    rh_start_output_rewrite();
}

$requestedPath = isset($GLOBALS['rh_404_requested_path'])
    ? (string)$GLOBALS['rh_404_requested_path']
    : '';
$requestedPath = trim($requestedPath, '/');
$showPath = $requestedPath !== '';
$pathDisplay = $showPath ? htmlspecialchars('/' . $requestedPath, ENT_QUOTES, 'UTF-8') : '';
?>
<!DOCTYPE html>
<html lang="EN" class="js-on">
<head>
<link rel="stylesheet" type="text/css" media="all" href="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/ScAssets/System/CSS/sc_common.css">
<link rel="stylesheet" type="text/css" media="all" href="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/ScAssets/System/CSS/gl_header_localize.css">
<link rel="stylesheet" type="text/css" media="all" href="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/ScAssets/System/CSS/sc_common_us.css">
<link rel="stylesheet" type="text/css" media="all" href="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/Ricoh/Sites/com/cmn_us_v1/css/common.css">
<link rel="stylesheet" type="text/css" media="all" href="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/Ricoh/Sites/com/cmn_us_v1/css/gl_header.css">
<link rel="stylesheet" type="text/css" media="all" href="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/Ricoh/Sites/com/cmn_us_v1/css/component.css">
<link rel="stylesheet" type="text/css" media="all" href="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/Ricoh/Sites/com/cmn_us_v1/css/option.css">
<link rel="stylesheet" type="text/css" media="all" href="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/Ricoh/Sites/com/cmn_us_v1/css/ex_component.css">
<link rel="stylesheet" type="text/css" media="all" href="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/Ricoh/Common/cmn_g_header_footer/css/template.css">
<link rel="stylesheet" type="text/css" media="all" href="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/Ricoh/Common/cmn_g_header_footer/css/conflict_clear.css">
<link rel="stylesheet" type="text/css" media="all" href="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/Ricoh/Common/cmn_v3/css/template-global-addition2024.css">
<link rel="stylesheet" type="text/css" media="all" href="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/Ricoh/Common/cmn_v3/css/import.css">
<link rel="stylesheet" type="text/css" media="all" href="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/Ricoh/Common/cmn_v3/css/solution-product-template.css">
<script src="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/ScAssets/System/Lib/jquery.min.js"></script>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Page not found | Ricoh</title>
<meta name="Description" content="Sorry, the page cannot be found. The page you are looking for might have been removed, might have its name changed, or is temporarily unavailable.">
<meta property="og:title" content="Page not found | Ricoh">
<meta property="og:image" content="__RH_BASE__/assets/www.ricoh.com/-/Media/Ricoh/Common/cmn_v3/img/og-image.webp">
<meta property="og:type" content="website">
<meta property="og:url" content="__RH_BASE__/">
<meta property="og:site_name" content="Ricoh Global Website">
<link rel="icon" type="image/vnd.microsoft.icon" href="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/media/ScAssets/System/Images/favicon.ico">
<link rel="apple-touch-icon" href="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/media/ScAssets/System/Images/apple_icon.png">
<script type="text/javascript" src="/ricoh-clone/ricoh_offline/assets/cdn.fonts.net/kit/3c059441-5218-4b9b-974d-a8185a3bd584/3c059441-5218-4b9b-974d-a8185a3bd584_enhanced.js" async=""></script>
<link rel="stylesheet" type="text/css" href="/ricoh-clone/ricoh_offline/assets/cdn.fonts.net/kit/3c059441-5218-4b9b-974d-a8185a3bd584/3c059441-5218-4b9b-974d-a8185a3bd584_enhanced.css">
<script src="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/Ricoh/Common/cmn_v3/js/common.vanilla.js" defer=""></script>
<style>
.rh-404-hero {
    text-align: center;
    padding: 48px 24px 32px;
    max-width: 720px;
    margin: 0 auto;
}
.rh-404-hero__code {
    font-size: clamp(3rem, 12vw, 5.5rem);
    line-height: 1;
    font-weight: 700;
    color: #cf142b;
    letter-spacing: 0.02em;
    margin: 0 0 16px;
}
.rh-404-hero__path {
    display: inline-block;
    max-width: 100%;
    margin: 24px auto 0;
    padding: 12px 20px;
    background: #f4f4f4;
    border-left: 4px solid #cf142b;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    font-size: 0.875rem;
    color: #333;
    word-break: break-all;
    text-align: left;
}
.rh-404-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    justify-content: center;
    margin-top: 48px;
}
.rh-404-actions .c-button-a {
    min-width: 200px;
}
@media screen and (max-width: 640px) {
    .rh-404-actions {
        flex-direction: column;
        align-items: stretch;
    }
    .rh-404-actions .c-button-a {
        width: 100%;
        min-width: 0;
    }
}
</style>
</head>
<body>
<div id="wrapper">
<header class="gl-header -horizontal">
    <span class="hide"></span>
    <div class="gl-header_inner">
        <div class="gl-header_logo">
            <a href="__RH_BASE__/"><img src="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/Ricoh/Common/cmn_g_header_footer/img/logo/logo.svg" alt="RICOH imagine. change." width="125" height="43"></a>
        </div>
        <div class="gl-header_menu">
            <nav class="gl-menu js-global-menu" data-label="Menu" aria-label="Main navigation">
                <div class="gl-menu_content fn-menu_hamburger-menu">
                    <div class="gi-menu-globe">
                        <a href="__RH_BASE__/gateway/" class="gi-menu_globe-btn">
                            <span class="gi-menu_globe-txt">Country/Area Selector</span>
                            <img src="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/Ricoh/Common/cmn_g_header_footer/img/icon/globe.svg" class="gi-menu_globe-icon" alt="Change Country/Area">
                        </a>
                    </div>
                    <a class="hide" href="#contents">Skip to main content</a>
                    <ul class="gl-menu_main">
                        <li><a href="__RH_BASE__/about/" class="gl-menu_link fn-menu_main"><span>About RICOH</span></a></li>
                        <li><a href="__RH_BASE__/IR/" class="gl-menu_link fn-menu_main"><span>Investor Relations</span></a></li>
                        <li><a href="__RH_BASE__/sustainability/" class="gl-menu_link fn-menu_main"><span>Sustainability</span></a></li>
                        <li><a href="__RH_BASE__/products/" class="gl-menu_link fn-menu_main"><span>Products</span></a></li>
                        <li><a href="__RH_BASE__/technology/" class="gl-menu_link fn-menu_main"><span>Technology</span></a></li>
                        <li><a href="__RH_BASE__/support/" class="gl-menu_link fn-menu_main"><span>Support &amp; Downloads</span></a></li>
                        <li><a href="__RH_BASE__/news/" class="gl-menu_link fn-menu_main"><span>News</span></a></li>
                    </ul>
                </div>
                <template class="fn-menu_tpl-hamburger">
                    <button class="gl-menu_toggle-btn" data-alt-open="Open" data-alt-close="Close"><span class="hide">Menu</span></button>
                </template>
                <template class="fn-menu_tpl-back">
                    <button class="gl-menu_back-btn">Home</button>
                </template>
                <template class="fn-menu_tpl-close">
                    <button class="gl-menu_close-btn" data-alt-close="Close"></button>
                </template>
            </nav>
        </div>
    </div>
</header>

<div id="contents">
    <div id="Main">
        <div class="breadcrumb breadcrumb--borderBottom">
            <div class="breadcrumb__container">
                <div class="breadcrumb__inner">
                    <ul class="breadcrumb__list">
                        <li><a href="__RH_BASE__/">Home</a></li>
                        <li class="act"><span>Page not found.</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="c-container">
            <div class="c-h1-b" id="freepagetitle">
                <div class="c-margin-b">
                    <div class="c-margin-b__inner">
                        <h1 class="c-h1-b__text">Page not found.</h1>
                    </div>
                </div>
            </div>

            <div class="fxb-section">
                <section class="c-section" id="section404">
                    <div class="f-child">
                        <div class="c-margin-b">
                            <div class="c-margin-b__inner">
                                <div class="c-text-a rh-404-hero">
                                    <p class="rh-404-hero__code" aria-hidden="true">404</p>
                                    <div class="c-h2-a u-mb-16">
                                        <h2 class="c-h2-a__text u-fc-9d9d9d u-align-left">This page is not available offline</h2>
                                    </div>
                                    <div class="c-text-a u-max-w-100p">
                                        <p class="c-text-a__text">
                                            Sorry, the page cannot be found.<br>
                                            The page you are looking for might have been removed, might have its name changed,
                                            or is not included in this offline mirror.
                                        </p>
                                    </div>
                                    <?php if ($showPath) : ?>
                                    <p class="rh-404-hero__path" aria-label="Requested path"><?php echo $pathDisplay; ?></p>
                                    <?php endif; ?>
                                    <div class="rh-404-actions">
                                        <a href="__RH_BASE__/" class="c-button-a c-button-a--red c-button-a--arrow c-button-a--iconWhite">
                                            <span class="c-button-a__text">Back to Home</span>
                                        </a>
                                        <a href="__RH_BASE__/sitemap/" class="c-button-a c-button-a--white c-button-a--arrow c-button-a--iconBlack">
                                            <span class="c-button-a__text">Sitemap</span>
                                        </a>
                                        <a href="__RH_BASE__/contact/" class="c-button-a c-button-a--white c-button-a--arrow c-button-a--iconBlack">
                                            <span class="c-button-a__text">Contact</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <div class="breadcrumb breadcrumb--borderTop">
        <div class="breadcrumb__container">
            <div class="breadcrumb__inner">
                <ul class="breadcrumb__list">
                    <li><a href="__RH_BASE__/">Home</a></li>
                    <li class="act"><span>Page not found.</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<footer class="gl-footer">
  <div class="gl-footer-link-area">
    <div class="gl-footer-link-area_inner">
      <div class="gl-footer-link-area-pc">
        <div class="gl-footer-link-area-pc_list">
          <div class="gl-footer-link-area-pc_item">
            <a href="__RH_BASE__/about/" class="gl-footer-link-area-pc_link-hdg">About RICOH</a>
          </div>
          <div class="gl-footer-link-area-pc_item">
            <a href="__RH_BASE__/IR/" class="gl-footer-link-area-pc_link-hdg">Investor Relations</a>
          </div>
          <div class="gl-footer-link-area-pc_item">
            <a href="__RH_BASE__/sustainability/" class="gl-footer-link-area-pc_link-hdg">Sustainability</a>
          </div>
          <div class="gl-footer-link-area-pc_item">
            <a href="__RH_BASE__/technology/" class="gl-footer-link-area-pc_link-hdg">Technology</a>
          </div>
          <div class="gl-footer-link-area-pc_item">
            <a href="__RH_BASE__/news/" class="gl-footer-link-area-pc_link-hdg">News</a>
          </div>
        </div>
      </div>
      <div class="gl-footer-link-area-sp">
        <ul class="gl-footer-link-area-sp_list">
          <li class="gl-footer-link-area-sp_item"><a href="__RH_BASE__/about/">About RICOH</a></li>
          <li class="gl-footer-link-area-sp_item"><a href="__RH_BASE__/IR/">Investor Relations</a></li>
          <li class="gl-footer-link-area-sp_item"><a href="__RH_BASE__/sustainability/">Sustainability</a></li>
          <li class="gl-footer-link-area-sp_item"><a href="__RH_BASE__/technology/">Technology</a></li>
          <li class="gl-footer-link-area-sp_item"><a href="__RH_BASE__/news/">News</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="gl-footer-area-01">
    <div class="gl-footer-area-01_inner">
      <div class="gl-footer-pageTop">
        <a href="#wrapper" class="gl-footer-pageTop_link js-top-button">Page Top</a>
      </div>
    </div>
  </div>
  <div class="gl-footer-area-02">
    <div class="gl-footer-area-02_inner">
      <div class="gl-footer-area-02_wrap">
        <div class="gl-footer-utility">
          <ul class="gl-footer-utility_inner">
            <li class="gl-footer-utility_item"><a href="__RH_BASE__/contact/" class="gl-footer-utility_link">Contact</a></li>
            <li class="gl-footer-utility_item"><a href="__RH_BASE__/privacy/" class="gl-footer-utility_link">Privacy policy</a></li>
            <li class="gl-footer-utility_item"><a href="__RH_BASE__/terms-of-use/" class="gl-footer-utility_link">Terms of use</a></li>
            <li class="gl-footer-utility_item"><a href="__RH_BASE__/sitemap/" class="gl-footer-utility_link">Sitemap</a></li>
          </ul>
        </div>
        <div class="gl-footer-copyright">
          <div class="gl-footer-copyright_inner">
            <small class="gl-footer-copyright_text">©&nbsp;Ricoh</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>
</div>
<script src="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/ScAssets/System/JS/common.js"></script>
<script src="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/Ricoh/Common/cmn_g_header_footer/js/init.js"></script>
<script src="/ricoh-clone/ricoh_offline/assets/www.ricoh.com/-/Media/Ricoh/Common/cmn_g_header_footer/js/template.js"></script>
</body>
</html>
