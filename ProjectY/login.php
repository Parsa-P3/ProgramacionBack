<?php
// cuando llamamos a este archivo, incluimos la conexión y la clase User
require 'db.php'; 

// Asegurarse de que la sesión esté iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Si el usuario ya ha iniciado sesión (verificando la sesión), redirigir a index.php
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$message = ''; 
$result = ['success' => false]; // Inicializar $result para evitar errores en la vista 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // pedir datos del formulario de manera segura
    $email = trim(htmlspecialchars($_POST['emailUsuario'] ?? '')); 
    $password = $_POST['contrasena'] ?? '';

    // Llamar al método de la clase para realizar la operación
    $result = $user->login($email, $password);

    $message = $result['message'];
    
    // Si el inicio de sesión es exitoso, redirigir a index.php
    if ($result['success']) {
        header('Location: index.php'); 
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 400px;">
        <h2>Inicio de Sesión</h2>
        <?php if (!empty($message)): ?>
            <p class="message" style="color: <?php echo $result['success'] ? 'green' : 'red'; ?>;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label for="emailUsuario">Correo Electrónico:</label>
            <input type="email" id="emailUsuario" name="emailUsuario" required>
            <br><br>
            
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
            <br><br>

            <button type="submit">Iniciar Sesión</button>
        </form>
        
        <p>¿No tienes una cuenta? <a href="register.php">Regístrate</a></p>
    </div>
</body>
</html>