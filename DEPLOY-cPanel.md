# cPanel deployment (ricoh.com.pk)

## Asset URLs must match where files live

Working asset URL:

`https://ricoh.com.pk/assets/www.ricoh.com/...`

Broken (404) when HTML adds an extra `/.pk/` segment:

`https://ricoh.com.pk/.pk/assets/...`

That happened when a regex treated `ricoh.com` inside **`ricoh.com.pk`** as an external URL and injected `/.pk/` into asset paths (fixed in `includes/rh-external-urls.php`).

It can also happen when PHP runs from a subfolder named `.pk` but `assets/` was uploaded to **public_html** root.

## Recommended layout (your setup)

Upload the **contents** of `ricoh_offline/` into `public_html/`:

- `public_html/router.php`
- `public_html/assets/`
- `public_html/pages/`
- `public_html/.htaccess`
- `public_html/index.php`

Do **not** put the site inside a `.pk` folder.

The included `.htaccess` sets `RH_BASE_PATH` empty on `ricoh.com.pk` so CSS/JS use `/assets/...`.

Upload at minimum:

- `base-url.php`
- `includes/rh-external-urls.php`
- `.htaccess`

After upload, view source and confirm:

`https://ricoh.com.pk/assets/www.ricoh.com/.../sc_common.css`

—not `https://ricoh.com.pk/.pk/assets/...`

## If you keep a subfolder

Use a normal name (`ricoh`, `site`) — avoid dot-prefixed folders (`.pk`); many hosts block them.

Set the matching path:

```apache
SetEnv RH_BASE_PATH /ricoh
```

## After uploading

1. Enable **AllowOverride All** (or at least FileInfo) for the domain in cPanel.
2. Run `composer install` in the site root if you use the contact form.
3. Hard-refresh the homepage and confirm CSS loads (Network tab → `sc_common.css` → 200).
