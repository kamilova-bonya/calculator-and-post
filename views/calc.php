<?php
require __DIR__ . '/../models/functions.php';

$arg1 = $_GET['arg1'] ?? 0;
$arg2 = $_GET['arg2'] ?? 0;
$result = 0;

if (isset($_GET['operation'])) {
    $arg1 = (int)$arg1;
    $arg2 = (int)$arg2;
    $operation = $_GET['operation'];

    $result = calculate($arg1, $arg2, $operation);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<?php include __DIR__ . "/menu.php" ?>
<h2>Калькулятор</h2>
<form action="" method="get">
    <input type="hidden" name="page" value="calc">
    <input type="number" name="arg1" value="<?= htmlspecialchars($arg1) ?>">

    <input type="submit" name="operation" value="+">
    <input type="submit" name="operation" value="-">
    <input type="submit" name="operation" value="*">
    <input type="submit" name="operation" value="/">

    <input type="number" name="arg2" value="<?= htmlspecialchars($arg2) ?>">
    =
    <input type="text" name="result" readonly value="<?= htmlspecialchars($result) ?>">
</form>
</body>
</html>
