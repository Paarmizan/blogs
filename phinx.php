<?php

declare(strict_types=1);

$envFile = is_file(__DIR__ . '/.env') ? __DIR__ . '/.env' : __DIR__ . '/.env.example';

if (is_file($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"");

        if ($key !== '') {
            $_ENV[$key] = $value;
        }
    }
}

$env = static fn (string $key, string $default): string => $_ENV[$key] ?? $default;

return [
    'paths' => [
        'migrations' => 'db/migrations',
        'seeds' => 'db/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => $env('DB_HOST', 'mysql'),
            'name' => $env('DB_NAME', 'blog'),
            'user' => $env('DB_USER', 'blog'),
            'pass' => $env('DB_PASS', 'blog'),
            'port' => (int) $env('DB_PORT', '3306'),
            'charset' => $env('DB_CHARSET', 'utf8mb4'),
        ],
    ],
    'version_order' => 'creation',
];
