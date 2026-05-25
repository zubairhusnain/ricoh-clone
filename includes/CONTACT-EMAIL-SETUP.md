# Contact form email (SMTP)

## Quick setup (Gmail)

1. Copy `includes/contact-mail.local.php.example` to `includes/contact-mail.local.php`
2. Set your Gmail address and [App Password](https://myaccount.google.com/apppasswords)
3. Set `'enabled' => true` under `smtp`
4. Open `http://localhost/ricoh-clone/ricoh_offline/contact` and submit a test message

## Files

| File | Purpose |
|------|---------|
| `contact-mail-config.php` | Default settings (committed) |
| `contact-mail.local.php` | Your passwords (not in git) |
| `contact-mail.local.php.example` | Template |

## Gmail example (`contact-mail.local.php`)

```php
return [
    'recipient' => 'zubairhusnain58@gmail.com',
    'from_email' => 'you@gmail.com',
    'from_name' => 'Ricoh Contact Form',
    'smtp' => [
        'enabled' => true,
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'encryption' => 'tls',
        'username' => 'you@gmail.com',
        'password' => 'xxxx xxxx xxxx xxxx',
    ],
];
```

If SMTP fails and `fallback_mail` is true in config, PHP `mail()` is tried as backup.

Install PHPMailer once:

```bash
cd ricoh_offline
composer install
```
