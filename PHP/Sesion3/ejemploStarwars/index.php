<?php
require_once 'filtrar.php';

$resultado = filtrarNaves($sw);

$moreThan100 = $resultado['moreThan100'];
$lessThan100 = $resultado['lessThan100'];

echo "<h2>Naves con más de 100 pasajeros</h2>";
foreach ($moreThan100 as $ship) {
    echo "<b>Nombre:</b> " . $ship['name'] . "<br>";
    echo "<b>Modelo:</b> " . $ship['model'] . "<br><br>";
}

echo "<h2>Naves con 100 o menos pasajeros o sin información</h2>";
foreach ($lessThan100 as $ship) {
    echo "<b>Nombre:</b> " . $ship['name'] . "<br>";
    echo "<b>Modelo:</b> " . $ship['model'] . "<br><br>";
}
?>