<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$pageTitle|default:'Blog'|escape}</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a class="logo" href="/">Blogy.</a>
    </div>
</header>

<main class="page-main">
    <div class="container">
        {block name="content"}{/block}
    </div>
</main>

<footer class="site-footer">
    <div class="container">
        Copyright &copy; {$currentYear}. All rights reserved.
    </div>
</footer>
<script src="/assets/js/app.js"></script>
</body>
</html>
