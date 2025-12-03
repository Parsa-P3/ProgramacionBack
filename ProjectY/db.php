<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'engine.php';
$database_file = __DIR__ . '/users.db';

// Crear una instancia de la clase engine
// y pasar la ruta de la base de datos
$user = new engine($database_file);

?>