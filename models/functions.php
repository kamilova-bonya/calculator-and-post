<?php
function add($a, $b) {
    return $a + $b;
}

function subtract($a, $b) {
    return $a - $b;
}

function multiply($a, $b): float|int
{
    return $a * $b;
}

function divide($a, $b): float|int|string
{
    if ($b == 0) {
        return "ошибка: деление на ноль";
    }
    return $a / $b;
}

function calculate($a, $b, $operation) {
    return match ($operation) {
        '+' => add($a, $b),
        '-' => subtract($a, $b),
        '*' => multiply($a, $b),
        '/' => divide($a, $b),
        default => "неизвестная операция",
    };
}

function getPosts() 
{
    return json_decode(file_get_contents(__DIR__ . '/blog.json'), true);
}

function savePosts($posts) 
{
    file_put_contents(__DIR__ . '/blog.json', json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function validateImage($file) 
{
    $imageinfo = getimagesize($file['tmp_name']);

    if (!$imageinfo) {
        return "Файл не является изображением";
    }

    if (!in_array($imageinfo['mime'], ['image/png', 'image/gif', 'image/jpeg', 'image/webp'])) {
        return "Можно загружать только изображения (PNG, GIF, JPEG, WebP)";
    }

    if ($file["size"] > 5 * 1024 * 1024) {
        return "Размер файла не должен превышать 5 МБ";
    }

    $blacklist = [".php", ".phtml", ".php3", ".php4"];
    foreach ($blacklist as $item) {
        if (preg_match("/$item\$/i", $file['name'])) {
            return "Загрузка PHP-файлов запрещена";
        }
    }

    return null;
}

function uploadImage($file) 
{
    $imageName = uniqid() . '_' . $file['name'];
    $uploadPath = __DIR__ . '/../images/big/' . $imageName;

    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return [null, "Ошибка при загрузке изображения"];
    }

    return ['/images/big/' . $imageName, null];
}

function validatePost($title, $content) 
{
    $errors = [];

    if (empty($title)) {
        $errors[] = "Заголовок не может быть пустым";
    } elseif (mb_strlen($title) > 100) {
        $errors[] = "Заголовок слишком длинный (макс. 100 символов)";
    }

    if (empty($content)) {
        $errors[] = "Текст поста не может быть пустым";
    } elseif (mb_strlen($content) < 10) {
        $errors[] = "Текст поста должен содержать минимум 10 символов";
    }

    return $errors;
}



