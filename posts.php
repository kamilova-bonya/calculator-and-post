<?php
$message = $_GET["message"] ?? null;
$error = $_GET["error"] ?? null;
$actionWord = "Добавить";
$action = "create";
$title = '';
$content = '';
$id = null;

$validationErrors = [];

// CRUD - READ
$posts = json_decode(file_get_contents('blog.json'), true);

// CRUD - UPDATE
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    $id = $_GET['id'] ?? '';
    if (array_key_exists($id, $posts)) {
        $title = $posts[$id]['title'];
        $content = $posts[$id]['content'];
        $actionWord = "Изменить";
        $action = "save";
    } else {
        header('Location: posts.php?error=Нет такого поста');
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'save') {
    $id = $_POST['id'] ?? '';

    if (empty($_POST['title']) || empty($_POST['content'])) {
        header('Location: posts.php?error=Заполните все поля');
        exit;
    }

    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);

    if (array_key_exists($id, $posts)) {
        $posts[$id]['title'] = $title;
        $posts[$id]['content'] = $content;

        if (!empty($_FILES['image']['tmp_name'])) {
            $imageinfo = getimagesize($_FILES['image']['tmp_name']);

            if (!$imageinfo) {
                header("Location: posts.php?message=ok");
                exit;
            }

            if ($imageinfo['mime'] != 'image/png'
                && $imageinfo['mime'] != 'image/gif'
                && $imageinfo['mime'] != 'image/jpeg'
                && $imageinfo['mime'] != 'image/webp') {
                echo "Можно загружать только jpg-файлы, неверное содержание файла, не изображение.";
                exit;
            }

            if ($_FILES["image"]["size"] > 1024 * 5 * 1024) {
                echo("Размер файла не больше 5 мб");
                exit;
            }

            $blacklist = [".php", ".phtml", ".php3", ".php4"];
            foreach ($blacklist as $item) {
                if (preg_match("/$item\$/i", $_FILES['image']['name'])) {
                    echo "Загрузка php-файлов запрещена!";
                    exit;
                }
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], "images/big/" . $_FILES['image']['name'])) {
                $posts[$id]['image'] = $_FILES['image']['name'];
            } else {
                echo "Sorry, there was an error uploading your file.";
                die();
            }
        }
    }

    file_put_contents('blog.json', json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: posts.php?message=Пост изменен');
    exit;
}

// CRUD - CREATE
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    if (empty($_POST['title'])) {
        $validationErrors[] = "Заголовок не может быть пустым";
    } elseif (strlen($_POST['title']) > 100) {
        $validationErrors[] = "Заголовок слишком длинный (макс. 100 символов)";
    }

    if (empty($_POST['content'])) {
        $validationErrors[] = "Текст поста не может быть пустым";
    } elseif (strlen($_POST['content']) < 10) {
        $validationErrors[] = "Текст поста должен содержать минимум 10 символов";
    }

    if (!empty($validationErrors)) {
        $errorString = implode("; ", $validationErrors);
        header('Location: posts.php?error=' . urlencode($errorString));
        exit;
    }

    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $imageName = null;

    if (!empty($_FILES['image']['tmp_name'])) {
        $imageinfo = getimagesize($_FILES['image']['tmp_name']);

        if (!$imageinfo) {
            header("Location: posts.php?error=Файл не является изображением");
            exit;
        }

        if (!in_array($imageinfo['mime'], ['image/png', 'image/gif', 'image/jpeg', 'image/webp'])) {
            header("Location: posts.php?error=Можно загружать только изображения (PNG, GIF, JPEG, WebP)");
            exit;
        }

        if ($_FILES["image"]["size"] > 5 * 1024 * 1024) {
            header("Location: posts.php?error=Размер файла не должен превышать 5 МБ");
            exit;
        }

        $blacklist = [".php", ".phtml", ".php3", ".php4"];
        foreach ($blacklist as $item) {
            if (preg_match("/$item\$/i", $_FILES['image']['name'])) {
                header("Location: posts.php?error=Загрузка PHP-файлов запрещена");
                exit;
            }
        }

        $imageName = uniqid() . '_' . $_FILES['image']['name'];if (!move_uploaded_file($_FILES['image']['tmp_name'], "images/big/" . $imageName)) {
            header("Location: posts.php?error=Ошибка при загрузке изображения");
            exit;
        }
    }

    array_unshift($posts, [
        'title' => $title,
        'content' => $content,
        'image' => $imageName,
        'likes' => 0
    ]);

    file_put_contents('blog.json', json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: posts.php?message=Пост добавлен');
    exit;
}

// CRUD - DELETE
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'] ?? '';

    if (array_key_exists($id, $posts)) {
        unset($posts[$id]);
    }

    file_put_contents('blog.json', json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: posts.php?message=Пост удален');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
<?php include __DIR__ . "/menu.php" ?>

<?php if (isset($message)): ?>
    <div class="success">
        <b><?= htmlspecialchars($message) ?></b>
    </div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="error">
        <?php
        $errors = explode("; ", $error);
        foreach ($errors as $err): ?>
            <div><?= htmlspecialchars($err) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div><?=$actionWord?> пост</div>
<form action="?action=<?=$action?>" method="post" enctype="multipart/form-data">
    <input type="text" name="title" value="<?=htmlspecialchars($title)?>" placeholder="Введите название поста"><br>
    <input type="hidden" name="id" value="<?=$id?>">
    <textarea name="content" cols="30" rows="3"><?=htmlspecialchars($content)?></textarea><br>
    <input type="file" name="image">
    <input type="submit" value="<?=$actionWord?>">
</form>

<?php if (!empty($posts)): ?>
    <?php foreach ($posts as $id => $post): ?>
        <div>
            <h2>
                <a href="post.php?id=<?= $id ?>"><?= htmlspecialchars($post['title']) ?></a>
                <a href="posts.php?action=update&id=<?= $id ?>">[edit]</a>
                <a href="posts.php?action=delete&id=<?= $id ?>">[X]</a>
            </h2>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    Нет постов.
<?php endif; ?>
</body>
</html