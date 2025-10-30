<?php

// === Meses ===
$Meses = [
    ["nombre" => "Enero", "dias" => 31],
    ["nombre" => "Febrero", "dias" => 29],
    ["nombre" => "Marzo", "dias" => 31],
    ["nombre" => "Abril", "dias" => 30],
    ["nombre" => "Mayo", "dias" => 31],
    ["nombre" => "Junio", "dias" => 30],
    ["nombre" => "Julio", "dias" => 31],
    ["nombre" => "Agosto", "dias" => 31],
    ["nombre" => "Septiembre", "dias" => 30],
    ["nombre" => "Octubre", "dias" => 31],
    ["nombre" => "Noviembre", "dias" => 30],
    ["nombre" => "Diciembre", "dias" => 31]
];

// === Días de la semana ===
$diasSemana = [
    "domingo",
    "lunes",
    "martes",
    "miércoles",
    "jueves",
    "viernes",
    "sábado"
];

// === Función para obtener el día de la semana ===
function diaDeLaSemana($fecha)
{
    global $diasSemana;
    $timestamp = strtotime($fecha);
    $indice = date("w", $timestamp); // 0 = domingo, 6 = sábado
    return $diasSemana[$indice];
}