<?php

declare(strict_types=1);

namespace Blog\Application\GetCategoryPage\Enum;

enum SortBy: string
{
    case Views = 'views';
    case Date = 'date';

    public static function fromRaw(string $value): self
    {
        return self::tryFrom($value) ?? self::Date;
    }
}

