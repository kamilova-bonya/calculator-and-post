<?php
$posts = [
    [
        'title' => 'Как начать программировать на PHP',
        'content' => 'PHP — один из самых популярных языков для веб-разработки. В этой статье мы разберём основы синтаксиса, установку сервера и напишем первый "Hello, World!"',
        'image' => 'https://example.com/images/php-beginner.jpg'
    ],
    [
        'title' => '10 лучших фреймворков для фронтенд-разработки в 2024 году',
        'content' => 'React, Vue, Svelte или SolidJS? В этом обзоре сравним популярные инструменты для создания современных веб-приложений и поможем выбрать подходящий.',
        'image' => 'https://example.com/images/frontend-frameworks.jpg'
    ],
    [
        'title' => 'Оптимизация SQL-запросов: практические советы',
        'content' => 'Медленные запросы могут убить производительность приложения. Узнайте, как правильно индексировать таблицы, избегать N+1 проблемы и использовать EXPLAIN.',
        'image' => 'https://example.com/images/sql-optimization.jpg'
    ],
    [
        'title' => 'Docker для начинающих: развертывание проекта',
        'content' => 'Docker упрощает разработку и деплой приложений. В этом руководстве настроим контейнер с PHP, Nginx и MySQL для локальной разработки.',
        'image' => 'https://example.com/images/docker-guide.jpg'
    ],
    [
        'title' => 'Как защитить API от атак: лучшие практики',
        'content' => 'JWT, OAuth2, rate limiting и CORS. Разбираем основные угрозы и способы защиты RESTful API от злоумышленников.',
        'image' => 'https://example.com/images/api-security.jpg'
    ]
];
?>
<?php include __DIR__ . "/menu.php" ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <?php foreach ($posts as $key => $post): ?>
        <?php
            extract($post);
        ?>
    <div>
        <h2><?=$title?></h2>
        <p><?=$content?></p>
    </div>
    <?php endforeach; ?>
</body>
</html>
