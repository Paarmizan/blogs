<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Bootstrap;

use Blog\Application\GetCategoryPage\GetCategoryPageService;
use Blog\Application\GetHomePage\GetHomePageService;
use Blog\Application\GetPostPage\GetPostPageService;
use Blog\Infrastructure\Database\PdoConnectionFactory;
use Blog\Infrastructure\Http\Request;
use Blog\Infrastructure\Http\Router;
use Blog\Infrastructure\Persistence\MySql\CategoryRepository;
use Blog\Infrastructure\Persistence\MySql\PostRepository;
use Blog\Infrastructure\Templating\SmartyFactory;
use Blog\Presentation\Http\Controller\CategoryController;
use Blog\Presentation\Http\Controller\ErrorController;
use Blog\Presentation\Http\Controller\HomeController;
use Blog\Presentation\Http\Controller\PostController;

final class App
{
    private Router $router;

    public function __construct(string $projectRoot)
    {
        $pdo = PdoConnectionFactory::createFromEnv();

        $categoryRepository = new CategoryRepository($pdo);
        $postRepository = new PostRepository($pdo);

        $homeService = new GetHomePageService($categoryRepository);
        $categoryService = new GetCategoryPageService($categoryRepository, $postRepository);
        $postService = new GetPostPageService($postRepository);

        $smarty = SmartyFactory::create($projectRoot);

        $homeController = new HomeController($smarty, $homeService);
        $categoryController = new CategoryController($smarty, $categoryService);
        $postController = new PostController($smarty, $postService);
        $errorController = new ErrorController($smarty);

        $router = new Router();
        $router->get('/', [$homeController, 'index']);
        $router->get('/category/{id}', [$categoryController, 'show']);
        $router->get('/post/{id}', [$postController, 'show']);
        $router->setNotFoundHandler([$errorController, 'notFound']);

        $this->router = $router;
    }

    public function handle(Request $request): string
    {
        return $this->router->dispatch($request);
    }
}
