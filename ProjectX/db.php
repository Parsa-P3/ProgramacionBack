<?php

session_start();

$database_file = __DIR__ . '/users.db';

try {
    // Acceder a la base de datos SQLite
    $pdo = new PDO("sqlite:" . $database_file);

    // Establecer el modo de error (Esto nos permite ver errores SQL)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Si hay un error de conexión, mostrarlo y detener la ejecución
    die("error en conexion con base de datos: " . $e->getMessage());
}
?>