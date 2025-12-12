<?php

//Importo mi fichero de configuracion
$config = require_once "engine/config.php";

//Incluyo mi libreria de encriptacion
require_once "engine/encriptador.php";

//Incluyo mi control de errores
require_once "engine/error.php";

//Incluyo mi sanetizacion
require_once "engine/sanetizar.php";

//Incluyo mi gestion de la sesion
require_once "engine/sesion.php";

//Me traigo la bbdd y la instancio para poder usarla
require_once "db.php";
$db = new BaseDatos();
$pdo = $db->getPdo();

// metodo de comprobar formato de correo
function comprobarPatronEmail($email): bool
{
    $salida = true;
    $patron = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/";
    $salida = preg_match($patron, $email);

    return $salida;
}


// metodo para comprobar el documento (CIF)
function comprobarCif($doc): bool
{
    $salida = true;
    $patron = "/^(?:\\d{8}[A-HJ-NP-TV-Z]|[XYZ]\\d{7}[A-HJ-NP-TV-Z]|[ABCDEFGHJKLMNPQRSUVW]\\d{7}[0-9A-J]|[A-Z]\\d{8})$/i";
    $salida = preg_match($patron, $doc);

    return $salida;
}

// metodo comprobar contraseña
//   La contraseña es Valida si
//          Tiene un mínimo de 8 caracteres.
//          Contiene al menos una letra mayuscula.
//          Contiene al menos un dígito (0-9).
//          Contiene al menos un simbolo o caracter especial
function comprobarPassword($password): bool
{
    $salida = true;
    if ($password != "") {
        $patron = "/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/";
        $salida = preg_match($patron, $password);
    }

    return $salida;
}

// comprobar telefono españa 
//  telefono es Valida si
//      empieza con +34
//      despues de +34 tiene 6 , 7 o 9
//      y 8 digitos que sea entre 0 - 9 
function comprobarTelefonoEspana($telefono): bool
{
    $telefono = preg_replace('/[\s\-()]+/', '', $telefono);

    $patron = '/^(\+34|0034)?[679]{1}[0-9]{8}$/';

    return preg_match($patron, $telefono) === 1;
}