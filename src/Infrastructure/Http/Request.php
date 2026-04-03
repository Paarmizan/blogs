<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http;

final class Request
{
    /**
     * @param array<string, mixed> $query
     */
    public function __construct(
        private string $method,
        private string $path,
        private array $query
    ) {
    }

    public static function fromGlobals(): self
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = (string) parse_url($uri, PHP_URL_PATH);

        return new self(
            strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET')),
            $path === '' ? '/' : $path,
            $_GET
        );
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function query(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }
}
