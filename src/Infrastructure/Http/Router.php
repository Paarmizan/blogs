<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http;

final class Router
{
    /**
     * @var array<int, array{method: string, regex: string, handler: callable}>
     */
    private array $routes = [];

    private $notFoundHandler;

    public function __construct()
    {
        $this->notFoundHandler = static function (): string {
            http_response_code(404);

            return 'Page not found';
        };
    }

    public function get(string $pattern, callable $handler): void
    {
        $regex = $this->convertToRegex($pattern);

        $this->routes[] = [
            'method' => 'GET',
            'regex' => $regex,
            'handler' => $handler,
        ];
    }

    public function setNotFoundHandler(callable $handler): void
    {
        $this->notFoundHandler = $handler;
    }

    public function dispatch(Request $request): string
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $request->method()) {
                continue;
            }

            if (!preg_match($route['regex'], $request->path(), $matches)) {
                continue;
            }

            $params = [];
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }

            return (string) call_user_func($route['handler'], $request, $params);
        }

        return (string) call_user_func($this->notFoundHandler, $request, []);
    }

    private function convertToRegex(string $pattern): string
    {
        $regex = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[^/]+)', $pattern);
        if ($regex === null) {
            $regex = $pattern;
        }

        return '#^' . $regex . '$#';
    }
}
