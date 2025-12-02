<?php

// cuando llamamos a este archivo, incluimos la conexión y la clase User
require 'db.php'; 

$message = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // pedir datos del formulario de manera segura
    $nombreUsuario = trim(htmlspecialchars($_POST['name'] ?? ''));
    $ApellidoUsuario = trim(htmlspecialchars($_POST['apellido'] ?? ''));
    $emailUsuario = trim(htmlspecialchars($_POST['email'] ?? ''));
    $contrasena = $_POST['password'] ?? '';
    
    // 2. Llamar al método de la clase para realizar la operación
    $result = $user->register($nombreUsuario, $ApellidoUsuario ,  $emailUsuario, $contrasena);

    // 3. Mostrar el resultado al usuario
    $message = $result['message'];
    
    // Si es exitoso, podemos redirigir al usuario a la página de login
    if ($result['success']) {
        header('Location: login.php');
         exit();
    }
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="style.css" >
</head>
<body>
    <div class="container" style="max-width: 500px; align-items: center;">
        <h2>Nuevo Registro de Usuario</h2>
        <p class="message" style="color: <?php echo $result['success'] ? 'green' : 'red'; ?>;"><?php echo $message; ?></p>
        <form action="register.php" method="POST">
            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Registrarse</button>
        </form>

        <p>¿Ya tienes una cuenta? <a href="login.php">Iniciar Sesión</a></p>
    </div>
</body>
</html>


