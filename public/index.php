<?php

declare(strict_types=1);

use Blog\Infrastructure\Bootstrap\App;
use Blog\Infrastructure\Config\EnvLoader;
use Blog\Infrastructure\Http\Request;

require dirname(__DIR__) . '/vendor/autoload.php';

$projectRoot = dirname(__DIR__);
$envPath = is_file($projectRoot . '/.env')
    ? $projectRoot . '/.env'
    : $projectRoot . '/.env.example';

(new EnvLoader($envPath))->load();

try {
    $app = new App($projectRoot);
    $request = Request::fromGlobals();

    echo $app->handle($request);
} catch (Throwable $exception) {
    http_response_code(500);

    echo '<h1>500 - Internal Server Error</h1>';
    if (($_ENV['APP_ENV'] ?? 'production') === 'development') {
        echo '<pre>' . htmlspecialchars($exception->getMessage(), ENT_QUOTES) . '</pre>';
    }
}
