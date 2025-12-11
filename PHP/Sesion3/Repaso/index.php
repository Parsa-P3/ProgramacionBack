<?php
require_once "utils.php";

// Giriş sayfasında her zaman oturumu sıfırlarız.
borrarSesion();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" href="./estilos/estilos.css">
    <title>Giriş</title>
    <script src="scripts/scripts.js"></script>
</head>

<body>
    <div class="container">
        <form id="frmInicio" name="frmInicio" action="" method="post">
            <h1>Müşteri İletişim Yönetimi</h1>
            <button class="btn primary" type="button" onclick="IrFicha()">Yeni Kullanıcı Oluştur</button>
            <button class="btn secondary" type="button" onclick="IrLogin()">Giriş Yap</button>
        </form>
    </div>
</body>
</html>