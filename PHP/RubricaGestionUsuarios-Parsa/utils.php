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


// Comprueba que una cadena cumple el patrón de un email válido
function comprobarPatronEmail($email): bool
{
    $salida = true;
    $patron = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/";
    $salida = preg_match($patron, $email);

    return $salida;
}
// Comprueba si una cadena cumple formatos válidos de documento/CIF
function comprobarDocumento($doc): bool
{
    $salida = true;
    $patron = "/^(?:\\d{8}[A-HJ-NP-TV-Z]|[XYZ]\\d{7}[A-HJ-NP-TV-Z]|[ABCDEFGHJKLMNPQRSUVW]\\d{7}[0-9A-J]|[A-Z]\\d{8})$/i";
    $salida = preg_match($patron, $doc);

    return $salida;
}

// Comprueba que la contraseña cumple requisitos mínimos (mayúscula, número, símbolo y longitud)
function comprobarPassword($password): bool
{
    $salida = true;
    if ($password != "") {
        $patron = "/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/";
        $salida = preg_match($patron, $password);
    }

    return $salida;
}

// Comprueba que un teléfono tiene formato válido de España (+34/0034/34 y 9 dígitos)
function comprobarTelefonoEspana($telefono): bool
{
    // Elimina espacios, guiones y paréntesis para una validación más flexible
    $telefono = preg_replace('/[\s\-()]+/', '', $telefono);


    // Acepta formatos: 34xxxxxxxxx, +34xxxxxxxxx o 0034xxxxxxxxx
    // Requisito adicional: el primer dígito tras el prefijo 34 debe ser 6, 7, 8 o 9 (móvil o fijo)
    $patron = '/^(?:\\+34|0034|34)[6789][0-9]{8}$/';

    return preg_match($patron, $telefono) === 1;
}

// Valida que la edad sea mayor o igual a 18
function comprobarEdad($edad): bool
{
    return (int)$edad >= 18;
}

// Comprueba en la tabla `usuarios` si ya existe un email (opcionalmente excluyendo un id)
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

// Comprueba en la tabla `usuarios` si ya existe un nombre de usuario (excluyendo id si se pasa)
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

// Comprueba en `clientes` si ya existe un email (excluye un cliente por id si se pasa)
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

// Comprueba en `clientes` si un CIF ya está registrado (excluye id opcional)
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

// Comprueba en `contactos` si un email ya existe (excluye un contacto por id si se pasa)
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
