<?php
// va bien porque nombreAnimal se convierte en un variable y obtiene el valor de perro
$nombreAnimal = "perro";
$perro = "Fido";
echo $$nombreAnimal;
//va mal porque no hay un variable que se llame perro1
$nombreAnimal = "perro1";
$perro = "Fido";
echo $$nombreAnimal;

$saludo_en = "Hello";
$saludo_es = "Hola";
$saludo_fr = "Bonjour";
$idioma = "fr";
$nombreVariableSaludo = "saludo_" . $idioma;
echo $$nombreVariableSaludo . ",Monde!"; // Bonjour, Monde!
?>