<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'user.php';
$database_file = __DIR__ . '/users.db';

// Crear una instancia de la clase User
// y pasar la ruta de la base de datos
$user = new User($database_file);

?>