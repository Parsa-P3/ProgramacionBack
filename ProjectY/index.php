<?php
// index.php
require 'db.php'; 

if (!$user->isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$userName = $_SESSION['user_name'] ?? 'Usuario';
$userRole = $user->getUserRole(); // será 'admin' o 'user'
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página Principal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>¡Bienvenido, <?php echo htmlspecialchars($userName); ?>!</h2>
        <p>Tu rol: <strong><?php echo htmlspecialchars($userRole); ?></strong></p>
        
        <?php if ($userRole === 'admin'): ?>
            <hr>
            <a href="admin.php" style="display: block; padding: 10px; background-color: #28a745; color: white; text-align: center; border-radius: 5px; text-decoration: none; margin-top: 15px;">
                Ir al Panel de Administración
            </a>
        <?php endif; ?>
        
        <hr>
        <p><a href="logout.php" style="color: red;">Cerrar Sesión</a></p>
    </div>
</body>
</html>