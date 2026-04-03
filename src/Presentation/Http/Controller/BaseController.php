<?php

declare(strict_types=1);

namespace Blog\Presentation\Http\Controller;

use Smarty;

abstract class BaseController
{
    public function __construct(protected Smarty $smarty)
    {
    }

    protected function render(string $template, array $data = []): string
    {
        $this->smarty->assign('currentYear', date('Y'));

        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        return $this->smarty->fetch($template);
    }
}
