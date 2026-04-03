<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Templating;

use Smarty;

final class SmartyFactory
{
    public static function create(string $projectRoot): Smarty
    {
        $smarty = new Smarty();
        $smarty->setTemplateDir($projectRoot . '/templates');
        $smarty->setCompileDir($projectRoot . '/storage/templates_c');
        $smarty->setCacheDir($projectRoot . '/storage/cache');
        $smarty->setCaching(Smarty::CACHING_OFF);

        return $smarty;
    }
}
