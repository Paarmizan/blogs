<?php

declare(strict_types=1);

namespace Blog\Presentation\Http\Controller;

use Blog\Application\GetHomePage\DTO\RequestDTO;
use Blog\Application\GetHomePage\GetHomePageService;
use Blog\Infrastructure\Http\Request;

final class HomeController extends BaseController
{
    public function __construct(
        \Smarty $smarty,
        private GetHomePageService $service
    ) {
        parent::__construct($smarty);
    }

    public function index(Request $request, array $params = []): string
    {
        $response = $this->service->handle(new RequestDTO(3));

        return $this->render('home.tpl', [
            'pageTitle' => 'Blog',
            'sections' => $response->sections,
        ]);
    }
}
