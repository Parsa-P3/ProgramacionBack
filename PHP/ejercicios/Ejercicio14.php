<?php
function incrementarValor(int &$numero) { // El & indica paso por referencia
    $numero++;
    echo "Dentro de la función, número es: $numero <br/>";
}

$miNumero = 5;
echo "Antes de llamar a la función, miNumero es: $miNumero <br/>"; // 5
incrementarValor($miNumero);
echo "Después de llamar a la función, miNumero es: $miNumero <br/>"; // 6 (cambió)

?>