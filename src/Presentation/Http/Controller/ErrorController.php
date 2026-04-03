<?php

declare(strict_types=1);

namespace Blog\Presentation\Http\Controller;

use Blog\Infrastructure\Http\Request;

final class ErrorController extends BaseController
{
    public function notFound(Request $request, array $params = []): string
    {
        http_response_code(404);

        return $this->render('404.tpl', [
            'pageTitle' => '404 - Not found',
        ]);
    }
}
