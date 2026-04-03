<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Config;

final class EnvLoader
{
    public function __construct(private string $filePath)
    {
    }

    public function load(): void
    {
        if (!is_file($this->filePath)) {
            return;
        }

        $lines = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '' || str_starts_with($trimmed, '#')) {
                continue;
            }

            [$name, $value] = array_pad(explode('=', $trimmed, 2), 2, '');
            $name = trim($name);
            $value = trim($value, " \t\n\r\0\x0B\"");

            if ($name === '') {
                continue;
            }

            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}
