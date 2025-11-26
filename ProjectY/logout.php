<?php
// logout.php

// primero iniciar la sesión para poder destruirla
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Limpiar todas las variables de sesión
$_SESSION = array();

// 3. Eliminar la cookie de sesión
// Esto generalmente elimina el identificador de sesión almacenado en el navegador.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Destruir la sesión completamente
session_destroy();

// 5. Redirigir al usuario a la página de inicio de sesión
header("Location: login.php");
exit();
?>