<?php
require_once "utils.php";

$usuario_id = $_GET['usuario_id'] ?? '0';
$error = $_GET['error'] ?? '';
$nuevo = ($usuario_id == '0');
$es_admin = comprobarSesion() && $_SESSION['usuario']->getRolId() == 1;

// Yeni kayıt haricinde, sadece Admin erişebilir
if (!$nuevo && !$es_admin) {
    header('Location: login.php?error=Bu formu görüntüleme yetkiniz yok.');
    exit();
}


if ($error != "") {
    echo "<script>alert('" . str_replace("--", "\\n", $error) . "')</script>";
}

$usuario_obj = Usuario::obtenerPorId($pdo, (int)$usuario_id);

$usuario = $usuario_obj->getUsuario() ?? '';
$email = $usuario_obj->getEmail() ?? '';
$nombre = $usuario_obj->getNombre() ?? '';
$apellidos = $usuario_obj->getApellidos() ?? '';
$rol_id = $usuario_obj->getRolId() ?? 2; // Varsayılan rol: Usuario (2)

$roles = Rol::obtenerTodos($pdo);
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title><?= $nuevo ? 'Yeni Kullanıcı Oluştur' : 'Kullanıcıyı Düzenle' ?></title>
    <link rel="stylesheet" href="./estilos/estilos.css">
    <script src="./scripts/scripts.js"></script>
</head>

<body>
    <div class="container">
        <h1><?= $nuevo ? 'Yeni Kullanıcı Oluştur' : 'Kullanıcıyı Düzenle' ?></h1>

        <form id="frmUsuario" name="frmUsuario" method="post" action="">
            <input type="hidden" name="usuario_id" value="<?= $usuario_id ?>">

            <div>
                <label for="usuario">Kullanıcı Adı</label>
                <input type="text" id="usuario" name="usuario" placeholder="Kullanıcı Adı" required value="<?= $usuario ?>">
            </div>

            <div>
                <label for="email">E-posta</label>
                <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" required value="<?= $email ?>">
            </div>

            <div>
                <label for="nombre">Ad</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ad" required value="<?= $nombre ?>">
            </div>

            <div>
                <label for="apellidos">Soyad</label>
                <input type="text" id="apellidos" name="apellidos" placeholder="Soyad" value="<?= $apellidos ?>">
            </div>

            <?php if ($es_admin): ?>
                <div>
                    <label for="rol_id">Rol</label>
                    <select id="rol_id" name="rol_id" required>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= $r->getId() ?>" <?= $r->getId() == $rol_id ? 'selected' : '' ?>>
                                <?= $r->getRol() ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php else: ?>
                <input type="hidden" name="rol_id" value="2">
            <?php endif; ?>

            <div>
                <label for="password">Şifre</label>
                <input type="password" id="password" name="password" placeholder="••••••••" <?= $nuevo ? 'required' : '' ?> value="">
            </div>

            <?php if ($nuevo): ?>
                <button type="submit" onclick="anadirUsuario()">Kullanıcı Oluştur</button>
            <?php else: ?>
                <button type="submit" onclick="modificarUsuario()">Kullanıcıyı Güncelle</button>
            <?php endif; ?>

        </form>
        <a href="listado.php" class="btn secondary">Geri</a>
    </div>
</body>
</html>