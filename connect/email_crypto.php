<?php
/**
 * Reversible encryption for the shared Gmail app password.
 *
 * IMPORTANT: this is intentionally encryption, not hashing.
 * PHPMailer has to hand Gmail the real app password on every send,
 * so a one-way hash (password_hash/bcrypt) cannot be used here —
 * there is no way to "verify" your way into an SMTP session.
 *
 * The secret key lives OUTSIDE the database (in config.php / an env
 * var), so a database leak alone does not expose the app password.
 * A DB leak + source leak together would — same tradeoff every app
 * with "log back into a third-party service" credentials accepts
 * (this is exactly what Laravel's APP_KEY / Django's SECRET_KEY do).
 *
 * Setup required (do this once):
 * 1. Generate a random 32+ character string, e.g. via:
 *      php -r "echo bin2hex(random_bytes(32));"
 * 2. Add it to connect/config.php (NOT committed to version control):
 *      define('EMAIL_SECRET_KEY', 'paste-the-generated-value-here');
 * 3. Never log, echo, or return EMAIL_SECRET_KEY or the decrypted
 *    password to the browser.
 */

function emailCryptoKey(): string
{
    if (!defined('EMAIL_SECRET_KEY') || EMAIL_SECRET_KEY === '') {
        throw new RuntimeException('EMAIL_SECRET_KEY is not configured in config.php');
    }
    // Normalize whatever length the admin picked into a proper 32-byte AES-256 key.
    return hash('sha256', EMAIL_SECRET_KEY, true);
}

function encryptAppPassword(string $plainPassword): string
{
    $key = emailCryptoKey();
    $iv = random_bytes(16); // AES-256-CBC IV size
    $cipherText = openssl_encrypt($plainPassword, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

    if ($cipherText === false) {
        throw new RuntimeException('Failed to encrypt app password');
    }

    // Store IV alongside the ciphertext (IV does not need to be secret).
    return base64_encode($iv . $cipherText);
}

function decryptAppPassword(string $encoded): string
{
    $key = emailCryptoKey();
    $raw = base64_decode($encoded, true);

    if ($raw === false || strlen($raw) <= 16) {
        throw new RuntimeException('Stored app password is malformed');
    }

    $iv = substr($raw, 0, 16);
    $cipherText = substr($raw, 16);
    $plain = openssl_decrypt($cipherText, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

    if ($plain === false) {
        throw new RuntimeException('Failed to decrypt app password');
    }

    return $plain;
}