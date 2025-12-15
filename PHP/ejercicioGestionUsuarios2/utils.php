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


    // Acepta formatos: 34xxxxxxxxx, +34xxxxxxxxx o 0034xxxxxxxxx
    // Requisito adicional: el primer dígito tras el prefijo 34 debe ser 6, 7, 8 o 9 (móvil o fijo)
    $patron = '/^(?:\\+34|0034|34)[6789][0-9]{8}$/';

    return preg_match($patron, $telefono) === 1;
}

/**
 * Valida que la edad sea >= 18 años
 * @param int $edad
 * @return bool
 */
function comprobarEdad($edad): bool
{
    return (int)$edad >= 18;
}

/**
 * Comprueba si un email ya existe en la tabla de usuarios
 * @param PDO $pdo
 * @param string $email
 * @param int $usuario_id (opcional) ID del usuario actual para excluir en edición
 * @return bool true si el email ya existe
 */
function emailExisteUsuario($pdo, $email, $usuario_id = 0): bool
{
    $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
    $params = [':email' => $email];
    
    if ($usuario_id != 0) {
        $sql .= " AND usuario_id != :usuario_id";
        $params[':usuario_id'] = $usuario_id;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn() > 0;
}

/**
 * Comprueba si un usuario ya existe en la tabla de usuarios
 * @param PDO $pdo
 * @param string $usuario
 * @param int $usuario_id (opcional) ID del usuario actual para excluir en edición
 * @return bool true si el usuario ya existe
 */
function usuarioExiste($pdo, $usuario, $usuario_id = 0): bool
{
    $sql = "SELECT COUNT(*) FROM usuarios WHERE usuario = :usuario";
    $params = [':usuario' => $usuario];
    
    if ($usuario_id != 0) {
        $sql .= " AND usuario_id != :usuario_id";
        $params[':usuario_id'] = $usuario_id;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn() > 0;
}

/**
 * Comprueba si un email ya existe en la tabla de clientes
 * @param PDO $pdo
 * @param string $email
 * @param int $cliente_id (opcional) ID del cliente actual para excluir en edición
 * @return bool true si el email ya existe
 */
function emailExisteCliente($pdo, $email, $cliente_id = 0): bool
{
    $sql = "SELECT COUNT(*) FROM clientes WHERE email = :email";
    $params = [':email' => $email];
    
    if ($cliente_id != 0) {
        $sql .= " AND cliente_id != :cliente_id";
        $params[':cliente_id'] = $cliente_id;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn() > 0;
}

/**
 * Comprueba si un CIF ya existe en la tabla de clientes
 * @param PDO $pdo
 * @param string $cif
 * @param int $cliente_id (opcional) ID del cliente actual para excluir en edición
 * @return bool true si el CIF ya existe
 */
function cifExisteCliente($pdo, $cif, $cliente_id = 0): bool
{
    $sql = "SELECT COUNT(*) FROM clientes WHERE cif = :cif";
    $params = [':cif' => $cif];
    
    if ($cliente_id != 0) {
        $sql .= " AND cliente_id != :cliente_id";
        $params[':cliente_id'] = $cliente_id;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn() > 0;
}

/**
 * Comprueba si un email ya existe en la tabla de contactos
 * @param PDO $pdo
 * @param string $email
 * @param int $contacto_id (opcional) ID del contacto actual para excluir en edición
 * @return bool true si el email ya existe
 */
function emailExisteContacto($pdo, $email, $contacto_id = 0): bool
{
    $sql = "SELECT COUNT(*) FROM contactos WHERE email = :email";
    $params = [':email' => $email];
    
    if ($contacto_id != 0) {
        $sql .= " AND contacto_id != :contacto_id";
        $params[':contacto_id'] = $contacto_id;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn() > 0;
}

//... (Si hay código después en utils.php, se mantiene)