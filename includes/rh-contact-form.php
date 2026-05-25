<?php
declare(strict_types=1);

require_once __DIR__ . '/rh-contact-mail.php';

function rh_contact_form_handle(array $post): array
{
    $values = [
        'username' => trim((string)($post['username'] ?? '')),
        'email' => trim((string)($post['email'] ?? '')),
        'subject' => trim((string)($post['subject'] ?? '')),
        'message' => trim((string)($post['message'] ?? '')),
    ];

    if ($values['username'] === '') {
        return ['ok' => false, 'message' => 'Please enter your name.', 'values' => $values];
    }
    if ($values['email'] === '' || !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'message' => 'Please enter a valid email address.', 'values' => $values];
    }
    if ($values['subject'] === '') {
        return ['ok' => false, 'message' => 'Please enter a subject.', 'values' => $values];
    }
    if ($values['message'] === '') {
        return ['ok' => false, 'message' => 'Please enter a message.', 'values' => $values];
    }
    if (strlen($values['message']) > 10000) {
        return ['ok' => false, 'message' => 'Message is too long (max 10,000 characters).', 'values' => $values];
    }

    if (trim((string)($post['website'] ?? '')) !== '') {
        return ['ok' => true, 'message' => 'Thank you. Your message has been sent.', 'values' => []];
    }

    $result = rh_contact_send_mail(
        $values['username'],
        $values['email'],
        $values['subject'],
        $values['message']
    );

    if ($result['ok']) {
        return ['ok' => true, 'message' => $result['message'], 'values' => []];
    }

    return ['ok' => false, 'message' => $result['message'], 'values' => $values];
}

function rh_contact_form_render(array $state): string
{
    $v = $state['values'] ?? [];
    $ok = !empty($state['ok']);
    $msg = htmlspecialchars((string)($state['message'] ?? ''), ENT_QUOTES, 'UTF-8');
    $alertClass = $ok ? 'rh-contact-alert--success' : 'rh-contact-alert--error';
    $alert = $msg !== '' ? '<div class="rh-contact-alert ' . $alertClass . '" role="status">' . $msg . '</div>' : '';

    $disabledAttr = '';
    $disabledNotice = '';

    $u = htmlspecialchars((string)($v['username'] ?? ''), ENT_QUOTES, 'UTF-8');
    $e = htmlspecialchars((string)($v['email'] ?? ''), ENT_QUOTES, 'UTF-8');
    $s = htmlspecialchars((string)($v['subject'] ?? ''), ENT_QUOTES, 'UTF-8');
    $m = htmlspecialchars((string)($v['message'] ?? ''), ENT_QUOTES, 'UTF-8');

    return <<<HTML
<div class="rh-contact-page" id="main-content">
  <h1 class="sr-only">Contact</h1>
  <section class="rh-contact-hero">
    <div class="rh-contact-hero__inner">
      <h2 class="rh-contact-hero__title">Contact us</h2>
      <p class="rh-contact-hero__lead">Send us a message and we will get back to you as soon as possible.</p>
    </div>
  </section>
  <section class="rh-contact-form-section">
    <div class="rh-contact-form-wrap">
      {$disabledNotice}
      {$alert}
      <form class="rh-contact-form" method="post" action="" novalidate>
        <fieldset class="rh-contact-fieldset"{$disabledAttr}>
        <div class="rh-contact-field">
          <label for="rh-username">Name</label>
          <input type="text" id="rh-username" name="username" required autocomplete="name" maxlength="120" value="{$u}"{$disabledAttr}>
        </div>
        <div class="rh-contact-field">
          <label for="rh-email">Email</label>
          <input type="email" id="rh-email" name="email" required autocomplete="email" maxlength="254" value="{$e}"{$disabledAttr}>
        </div>
        <div class="rh-contact-field">
          <label for="rh-subject">Subject</label>
          <input type="text" id="rh-subject" name="subject" required maxlength="200" value="{$s}"{$disabledAttr}>
        </div>
        <div class="rh-contact-field">
          <label for="rh-message">Message</label>
          <textarea id="rh-message" name="message" required rows="8" maxlength="10000"{$disabledAttr}>{$m}</textarea>
        </div>
        <div class="rh-contact-hp" aria-hidden="true">
          <label for="rh-website">Website</label>
          <input type="text" id="rh-website" name="website" tabindex="-1" autocomplete="off"{$disabledAttr}>
        </div>
        <button type="submit" class="rh-contact-submit"{$disabledAttr}>Send message</button>
        </fieldset>
      </form>
    </div>
  </section>
</div>
HTML;
}
