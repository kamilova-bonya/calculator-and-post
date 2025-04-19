<?php
define('BASE_DIR', dirname(__DIR__));

$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'gallery':
        require BASE_DIR . '/views/gallery.php';
        break;
    case 'calc':
        require BASE_DIR . '/views/calc.php';
        break;
    case 'bmi':
        require BASE_DIR . '/views/bmi.php';
        break;
    case 'posts':
        require BASE_DIR . '/views/posts.php';
        break;
    case 'post':
        require BASE_DIR . '/views/post.php';
        break;
    case 'test':
        require BASE_DIR . '/views/test.php';
        break;
    default:
        require BASE_DIR . '/views/menu.php';
        echo "<h1>Добро пожаловать!</h1>";
        echo "<p>Это главная страница сайта</p>";
}



