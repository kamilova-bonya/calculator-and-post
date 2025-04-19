<?php
if (isset($_GET['weight']) && isset($_GET['height'])) {
    $weight = $_GET['weight'];
    $height = $_GET['height'];

    if (empty($weight) || empty($height)) {
        $error = "заполните оба поля";
    }
    elseif (!is_numeric($weight) || !is_numeric($height)) {
        $error = "нужно вводить только числа";
    }
    elseif ($weight <= 0 || $height <= 0) {
        $error = "числа должны быть больше нуля";
    }
    else {
        $height_in_meters = $height / 100;
        $bmi = $weight / ($height_in_meters * $height_in_meters);
        $bmi = round($bmi, 2);

        $category = match (true) {
            $bmi < 18.5 => "недостаточный вес",
            $bmi < 25 => "нормальный вес",
            $bmi < 30 => "избыточный вес",
            default => "ожирение",
        };
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
        .error { color: red; }
    </style>
</head>
<body>
<?php include __DIR__ . "/menu.php" ?>
<form>
    <input type="hidden" name="page" value="bmi">
    <input type="text" name="weight" placeholder="Вес">
    <input type="text" name="height" placeholder="Рост">
    <input type="submit" value="Рассчитать">
</form>
<?php if (isset($error)): ?>
    <p class="error"><?= $error; ?></p>
<?php elseif (isset($bmi)): ?>
    <p>Ваш ИМТ: <?= $bmi; ?></p>
    <p>У вас: <?= $category; ?></p>
<?php endif; ?>
</body>
</html>
