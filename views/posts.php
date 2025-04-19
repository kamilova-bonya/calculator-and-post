<?php
require __DIR__ . '/../models/functions.php';

$message = $_GET["message"] ?? null;
$error = $_GET["error"] ?? null;
$actionWord = "Добавить";
$action = "create";
$title = '';
$content = '';
$id = null;

$posts = getPosts();

// CRUD - UPDATE
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    $id = $_GET['id'] ?? '';
    if (array_key_exists($id, $posts)) {
        $title = $posts[$id]['title'];
        $content = $posts[$id]['content'];
        $actionWord = "Изменить";
        $action = "save";
    } else {
        header('Location: /?page=posts&error=Нет такого поста');
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'save') {
    $id = $_POST['id'] ?? '';

    if (empty($_POST['title']) || empty($_POST['content'])) {
        header('Location: /?page=posts&error=Заполните все поля');
        exit;
    }

    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);

    if (array_key_exists($id, $posts)) {
        $posts[$id]['title'] = $title;
        $posts[$id]['content'] = $content;

        if (!empty($_FILES['image']['tmp_name'])) {
            $error = validateImage($_FILES['image']);
            if ($error) {
                header("Location: /?page=posts&error=" . urlencode($error));
                exit;
            }

            [$imagePath, $error] = uploadImage($_FILES['image']);
            if ($error) {
                header("Location: /?page=posts&error=" . urlencode($error));
                exit;
            }
            $posts[$id]['image'] = $imagePath;
        }
    }

    savePosts($posts);
    header('Location: /?page=posts&message=Пост изменен');
    exit;
}

// CRUD - CREATE
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    $validationErrors = validatePost($_POST['title'] ?? '', $_POST['content'] ?? '');

    if (!empty($validationErrors)) {
        header('Location: /?page=posts&error=' . urlencode(implode("; ", $validationErrors)));
        exit;
    }

    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $imageName = null;
    if (!empty($_FILES['image']['tmp_name'])) {
        $error = validateImage($_FILES['image']);
        if ($error) {
            header("Location: /?page=posts&error=" . urlencode($error));
            exit;
        }

        [$imageName, $error] = uploadImage($_FILES['image']);
        if ($error) {
            header("Location: /?page=posts&error=" . urlencode($error));
            exit;
        }
    }

    array_unshift($posts, [
        'title' => $title,
        'content' => $content,
        'image' => $imageName,
        'likes' => 0
    ]);

    savePosts($posts);
    header('Location: /?page=posts&message=Пост добавлен');
    exit;
}

// CRUD - DELETE
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'] ?? '';

    if (array_key_exists($id, $posts)) {
        unset($posts[$id]);
    }

    savePosts($posts);
    header('Location: /?page=posts&message=Пост удален');
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
<?php include __DIR__ . '/menu.php' ?>

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
<form action="/?page=posts&action=<?=$action?>" method="post" enctype="multipart/form-data">
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
                <a href="/?page=post&id=<?= $id ?>"><?= htmlspecialchars($post['title']) ?></a>
                <a href="/?page=posts&action=update&id=<?= $id ?>">[edit]</a>
                <a href="/?page=posts&action=delete&id=<?= $id ?>">[X]</a>
            </h2>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    Нет постов.
<?php endif; ?>
</body>
</html>