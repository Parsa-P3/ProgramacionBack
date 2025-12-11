<?php
require_once "utils.php";
require_once "./models/Contacto.php";
require_once "./models/Usuarios.php";

// Admin Yetki Kontrolü
if (!comprobarSesion() || $_SESSION['usuario']->getRolId() != 1) {
    header('Location: login.php?error=Yalnızca yönetici iletişim ekleyip düzenleyebilir.');
    exit();
}

$contacto_id = $_GET['contacto_id'] ?? '0';
$error = $_GET['error'] ?? '';
$nuevo = ($contacto_id == '0');

if ($error != "") {
    echo "<script>alert('" . str_replace("--", "\\n", $error) . "')</script>";
}

$contacto = Contacto::obtenerPorId($pdo, (int)$contacto_id);

// Form alanlarını doldurma
$usuario_id = $contacto->getUsuarioId() ?? ($_SESSION['usuario']->getId() ?? 0);
$nombre     = $contacto->getNombre() ?? '';
$apellidos  = $contacto->getApellidos() ?? '';
$email      = $contacto->getEmail() ?? '';
$telefono   = $contacto->getTelefono() ?? '';

// Müşteri listesini çek (Tüm Kullanıcılar)
$usuarios = Usuario::obtenerTodos($pdo);
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title><?= $nuevo ? 'Yeni İletişim Ekle' : 'İletişimi Düzenle' ?></title>
    <link rel="stylesheet" href="./estilos/estilos.css">
    <script src="./scripts/scripts.js"></script>
</head>

<body>
    <div class="container">
        <h1><?= $nuevo ? 'Yeni İletişim Ekle' : 'İletişimi Düzenle' ?></h1>

        <form id="frmContacto" name="frmContacto" method="post" action="">
            <input type="hidden" name="contacto_id" value="<?= $contacto_id ?>">

            <div>
                <label for="usuario_id">Müşteri</label>
                <select id="usuario_id" name="usuario_id" required>
                    <option value="">-- Müşteri Seçiniz --</option>
                    <?php foreach ($usuarios as $u): ?>
                        <option value="<?= $u->getId() ?>" <?= $u->getId() == $usuario_id ? 'selected' : '' ?>>
                            <?= $u->getNombre() . ' ' . $u->getApellidos() . ' (' . $u->getUsuario() . ')' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="nombre">Ad</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ad" required value="<?= $nombre ?>">
            </div>
            <div>
                <label for="apellidos">Soyad</label>
                <input type="text" id="apellidos" name="apellidos" placeholder="Soyad" value="<?= $apellidos ?>">
            </div>
            <div>
                <label for="email">E-posta</label>
                <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" required value="<?= $email ?>">
            </div>
            <div>
                <label for="telefono">Telefon (İspanya: 9 basamaklı)</label>
                <input type="text" id="telefono" name="telefono" placeholder="6xxxxxxxx" required value="<?= $telefono ?>">
            </div>

            <?php if ($nuevo): ?>
                <button type="submit" onclick="guardarContacto('anadir')">İletişim Oluştur</button>
            <?php else: ?>
                <button type="submit" onclick="guardarContacto('guardar')">İletişimi Güncelle</button>
            <?php endif; ?>

        </form>
    </div>
</body>
</html>