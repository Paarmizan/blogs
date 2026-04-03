<?php

declare(strict_types=1);

namespace Blog\Presentation\Http\Controller;

use Blog\Application\GetCategoryPage\DTO\RequestDTO;
use Blog\Application\GetCategoryPage\GetCategoryPageService;
use Blog\Infrastructure\Http\Request;

final class CategoryController extends BaseController
{
    public function __construct(
        \Smarty $smarty,
        private GetCategoryPageService $service
    ) {
        parent::__construct($smarty);
    }

    public function show(Request $request, array $params = []): string
    {
        $categoryId = isset($params['id']) ? (int) $params['id'] : 0;
        $sortBy = (string) $request->query('sort', 'date');
        $page = max(1, (int) $request->query('page', 1));

        $response = $this->service->handle(new RequestDTO($categoryId, $sortBy, $page));
        if ($response === null) {
            http_response_code(404);

            return $this->render('404.tpl', [
                'pageTitle' => 'Category not found',
            ]);
        }

        $pagination = $response->pagination;

        $pages = [];
        for ($i = 1; $i <= $pagination['totalPages']; $i++) {
            $pages[] = [
                'number' => $i,
                'isCurrent' => $i === $pagination['currentPage'],
            ];
        }

        return $this->render('category.tpl', [
            'pageTitle' => $response->category['name'],
            'category' => $response->category,
            'posts' => $response->posts,
            'sortBy' => $response->sortBy,
            'pagination' => $pagination,
            'pages' => $pages,
        ]);
    }
}
