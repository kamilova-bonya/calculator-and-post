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


