<?php
require_once "utils.php";
require_once "./models/Usuarios.php";
require_once "./models/Roles.php";

// Sadece Admin görmeli
if (!comprobarSesion() || $_SESSION['usuario']->getRolId() != 1) {
    header('Location: login.php?error=Yalnızca yönetici bu listeyi görebilir.');
    exit();
}

$usuarios = Usuario::obtenerTodos($pdo);
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Kullanıcı Listesi</title>
    <link rel="stylesheet" href="./estilos/estilos.css">
    <script src="./scripts/scripts.js"></script>
</head>

<body>
    <div class="tabla-contenedor">
        <h1>Kullanıcı Listesi</h1>

        <a href="ficha.php" class="btn primary anadir">➕ Yeni Kullanıcı Ekle</a>

        <table>
            <tr>
                <th>ID</th>
                <th>Kullanıcı Adı</th>
                <th>Email</th>
                <th>Ad</th>
                <th>Soyad</th>
                <th>Rol</th>
                <th>İletişimler</th>
                <th>İşlemler</th>
            </tr>

            <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= $u->getId() ?></td>
                <td><?= $u->getUsuario() ?></td>
                <td><?= $u->getEmail() ?></td>
                <td><?= $u->getNombre() ?></td>
                <td><?= $u->getApellidos() ?></td>
                <td><?= $u->getRolId() == 1 ? 'Admin' : 'Usuario' ?></td>

                <td>
                    <a href="listado_contactos.php?usuario_id=<?= $u->getId() ?>">
                        <button class="btn secondary">İletişimleri Gör</button>
                    </a>
                </td>

                <td class="acciones">
                    <a href="ficha.php?usuario_id=<?= $u->getId() ?>">
                        <button class="btn editar">Düzenle</button>
                    </a>
                    <button class="btn borrar" onclick="javascript:eliminarUsuario(<?= $u->getId() ?>)">Sil</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <form action="ficha_guardar.php?action=eliminar" method="post" id="frmEli" name="frmEli" style="display: none;">
        <input type="hidden" name="usuario_id" id="usuario_id_sil">
    </form>
</body>
</html>