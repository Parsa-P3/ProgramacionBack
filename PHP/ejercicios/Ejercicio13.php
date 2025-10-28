<?php
function calcularPromedio(...$numeros): float {
    if (count($numeros) === 0) {
        return 0.0;
    }
    $suma = array_sum($numeros);
    return $suma / count($numeros);
}

echo "Promedio de 2, 4, 6: " . calcularPromedio(2, 4, 6) . "<br/>"; // 4
echo "Promedio de 10, 20, 30, 40, 50: " . calcularPromedio(10, 20, 30, 40, 50) . "<br/>"; // 30
echo "Promedio sin números: " . calcularPromedio() . "<br/>"; // 0

?>