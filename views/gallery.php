<?php
$message = $_GET["message"] ?? null;

require __DIR__ . '/../models/SimpleImage.php';
require __DIR__ . '/../models/functions.php';

$messages = [
    'ok' => 'Файл загружен',
];

if (!empty($_POST) && !empty($_FILES)) {
    $validationError = validateImage($_FILES['image']);

    if ($validationError) {
        echo $validationError;
        exit;
    }

    [$uploadedPath, $uploadError] = uploadImage($_FILES['image']);

    if ($uploadError) {
        echo $uploadError;
        exit;
    }

    $image = new \claviska\models\SimpleImage();
    $image
        ->fromFile(__DIR__ . '/../images/big/' . basename($uploadedPath))
        ->autoOrient()
        ->resize(150, 100)
        ->toFile(__DIR__ . '/../images/small/' . basename($uploadedPath), 'image/jpeg');

    $message = "Файл загружен";
}

$images = array_slice(scandir(__DIR__ . '/../images/big'), 2);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<?php include __DIR__ . "/menu.php" ?>

<?php if (isset($message)): ?>
    <div>
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<?php foreach ($images as $image): ?>
    <a href="/images/big/<?= $image; ?>">
        <img src="/images/small/<?= $image; ?>" alt="" width="150">
    </a>
<?php endforeach; ?>

<form action="/?page=gallery" enctype="multipart/form-data" method="post">
    <input type="file" name="image">
    <input type="submit" value="Загрузить" name="load">
</form>
</body>
</html>




