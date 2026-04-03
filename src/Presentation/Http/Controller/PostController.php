<?php

declare(strict_types=1);

namespace Blog\Presentation\Http\Controller;

use Blog\Application\GetPostPage\DTO\RequestDTO;
use Blog\Application\GetPostPage\GetPostPageService;
use Blog\Infrastructure\Http\Request;

final class PostController extends BaseController
{
    public function __construct(
        \Smarty $smarty,
        private GetPostPageService $service
    ) {
        parent::__construct($smarty);
    }

    public function show(Request $request, array $params = []): string
    {
        $postId = isset($params['id']) ? (int) $params['id'] : 0;
        $response = $this->service->handle(new RequestDTO($postId));

        if ($response === null) {
            http_response_code(404);

            return $this->render('404.tpl', [
                'pageTitle' => 'Post not found',
            ]);
        }

        $post = $response->post;

        return $this->render('post.tpl', [
            'pageTitle' => $post['title'],
            'post' => $post,
            'similarPosts' => $response->similarPosts,
        ]);
    }
}
