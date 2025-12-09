<?php
require_once "utils.php";

// Giriş sayfasında her zaman oturumu sıfırlarız.
borrarSesion();

$error = $_GET['error'] ?? '';
$ok_mesaj = $_GET['ok'] ?? '';
$usuario = $_GET['usuario'] ?? '';
$accion = $_GET['accion'] ?? '';

if ($error != "") {
    echo "<script>alert('" . str_replace("--", "\\n", $error) . "')</script>";
} 
if ($ok_mesaj != "") {
    echo "<script>alert('" . htmlspecialchars($ok_mesaj) . "')</script>";
} 
if ($accion == "sesioncaducada") {
    echo "<script>alert('Oturumunuz sona ermiştir, lütfen tekrar giriş yapın.')</script>";
} 
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Kullanıcı Girişi</title>
    <link rel="stylesheet" href="./estilos/estilos.css">
    <script src="./scripts/scripts.js"></script>
</head>

<body>
    <div class="container">
        <h1>Kullanıcı Girişi</h1>

        <form method="post">
            <div>
                <label for="usuario">Kullanıcı Adı</label>
                <input type="text" id="usuario" name="usuario" placeholder="Kullanıcı Adı" required value="<?= $usuario ?>">
            </div>
            <div>
                <label for="password">Şifre</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            <button class="btn primary" type="submit" onclick="login()">Giriş Yap</button>
            <a href="index.php" class="btn secondary">Geri</a>
        </form>
    </div>
</body>
</html>