<?php
require_once __DIR__ . '/../vendor/autoload.php'; // charge Stripe via Composer

// Load environment variables from .env file
if (file_exists(__DIR__ . '/../.env')) {
    $envContent = file_get_contents(__DIR__ . '/../.env');
    $lines = explode("\n", $envContent);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && !str_starts_with($line, '#')) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Use environment variable for Stripe API key
$stripe_secret_key = $_ENV['STRIPE_SECRET_KEY'] ?? 'your_stripe_secret_key_here';
\Stripe\Stripe::setApiKey($stripe_secret_key);
