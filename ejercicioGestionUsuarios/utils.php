<?php

//Importo mi fichero de configuracion
$config = require_once "config.php";

//Incluyo mi libreria de encriptacion
require_once "encriptador.php";

//Incluyo mi control de errores
require_once "error.php";

//Incluyo mi sanetizacion
require_once "sanetizar.php";

//Incluyo mi gestion de la sesion
require_once "sesion.php";

//Me traigo la bbdd y la instancio para poder usarla
require_once "db.php";
$db = new BaseDatos();
$pdo = $db->getPdo();

function comprobarPatronEmail($email): bool
{
    $salida = true;
    $patron = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/";
    $salida = preg_match($patron, $email);

    return $salida;
}

function comprobarDocumento($doc): bool
{
    $salida = true;
    $patron = "/^(?:\\d{8}[A-HJ-NP-TV-Z]|[XYZ]\\d{7}[A-HJ-NP-TV-Z]|[ABCDEFGHJKLMNPQRSUVW]\\d{7}[0-9A-J]|[A-Z]\\d{8})$/i";
    $salida = preg_match($patron, $doc);

    return $salida;
}

function comprobarPassword($password): bool
{
    $salida = true;
    if ($password != "") {
        $patron = "/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/";
        $salida = preg_match($patron, $password);
    }

    return $salida;
}
function comprobarTelefono($telefono): bool
{
    $salida = true;
    // Telefon numarasının +34 ile başlayıp ardından 9-15 arası rakam içermesini kontrol eder (basit bir uluslararası doğrulama için)
    // Eğer sadece İspanya (+34) gerekiyorsa: /^\+34\s?(\d{9})$/
    // Daha esnek uluslararası format: /^\+?\d{9,15}$/
    $patron = "/^\+34\s?(\d{9})$/"; // Önerilen: İspanya için kesin format
    
    // UYARI DÜZELTME İÇİN ÖNEMLİ: Eğer alert mesajında '+34' uyarısı çıkmasını istiyorsak,
    // hata mesajını daha sonra `ficha_guardar.php` ve `ficha_cliente_guardar.php`'de düzenleyeceğiz.
    
    $salida = preg_match($patron, $telefono);

    return $salida;
}


/**
 * Comprueba si el teléfono tiene un formato de España válido.
 * Patrón: (opcional +34 o 0034) seguido de 9 dígitos que empiezan por 6, 7 o 9.
 * @param string $telefono Número de teléfono.
 * @return bool
 */
function comprobarTelefonoEspana($telefono): bool
{
    // Elimina espacios, guiones y paréntesis para una validación más flexible
    $telefono = preg_replace('/[\s\-()]+/', '', $telefono);

    // Patrón regex para teléfono español
    $patron = '/^(\+34|0034)?[679]{1}[0-9]{8}$/';

    return preg_match($patron, $telefono) === 1;
}

//... (Si hay código después en utils.php, se mantiene)