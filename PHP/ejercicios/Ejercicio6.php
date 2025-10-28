<?php
$nombre = "Elena";
$numeroString = "1"; // string
$numeroInt = 10;
$precioFloat = 19.99;
$esValido = true;
echo gettype($nombre) . "<br/>"; echo
gettype($numeroString) . "<br/>"; echo
gettype($numeroInt) . "<br/>"; echo
gettype($precioFloat) . "<br/>"; echo gettype($esValido)
. "<br/>"; if (is_string($nombre)) {
echo "'$nombre' es un string.<br/>";
}
if (is_numeric($numeroString)) {
echo "'$numeroString' es numérico (aunque sea string).<br/>";
}
if (is_int($numeroInt)) {
echo "'$numeroInt' es un entero.<br/>"; }
?>