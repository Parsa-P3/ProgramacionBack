<?php
require_once "utils.php";
require_once "./models/Contacto.php";

if (!comprobarSesion()) {
    header('Location: login.php?error=Lütfen giriş yapın.');
    exit();
}

$sesion_usuario = $_SESSION['usuario'];
$contactos = [];

$es_admin = $sesion_usuario->getRolId() == 1;
$usuario_id_filtre = $_GET['usuario_id'] ?? null;

// Veri Çekme Mantığı
if ($es_admin) {
    // Admin, filtre varsa filtreli, yoksa tümünü görür
    if ($usuario_id_filtre) {
        $contactos = Contacto::obtenerPorCliente($pdo, $usuario_id_filtre);
    } else {
        $contactos = Contacto::obtenerTodos($pdo);
    }
} else {
    // Admin olmayan kullanıcı (Müşteri) sadece kendi iletişimlerini görür
    $contactos = Contacto::obtenerPorCliente($pdo, $sesion_usuario->getId());
}

$ok_mesaj = $_GET['ok'] ?? '';
if ($ok_mesaj != "") {
    echo "<script>alert('" . htmlspecialchars($ok_mesaj) . "')</script>";
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>İletişim Listesi</title>
    <link rel="stylesheet" href="./estilos/estilos.css">
    <script src="./scripts/scripts.js"></script>
</head>

<body>
    <div class="tabla-contenedor">
        <h1>İletişim Listesi</h1>

        <?php if ($es_admin): ?>
            <a href="ficha_contacto.php" class="btn primary anadir">➕ Yeni İletişim Ekle</a>
            <a href="listado.php" class="btn secondary anadir">← Kullanıcı Listesine Dön</a>
        <?php endif; ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Ad Soyad</th>
                <th>E-posta</th>
                <th>Telefon</th>
                <?php if ($es_admin): ?>
                    <th>Müşteri ID</th>
                    <th>İşlemler</th>
                <?php endif; ?>
            </tr>

            <?php foreach ($contactos as $c): ?>
            <tr>
                <td><?= $c->getId() ?></td>
                <td><?= $c->getNombre() . ' ' . $c->getApellidos() ?></td>
                <td><?= $c->getEmail() ?></td>
                <td><?= $c->getTelefono() ?></td>

                <?php if ($es_admin): ?>
                    <td><?= $c->getUsuarioId() ?></td>
                    <td class="acciones">
                        <a href="ficha_contacto.php?contacto_id=<?= $c->getId() ?>">
                            <button class="btn editar">Düzenle</button>
                        </a>
                        <button class="btn borrar" onclick="javascript:eliminarContacto(<?= $c->getId() ?>)">Sil</button>
                    </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <form action="contacto_guardar.php?action=eliminar" method="post" id="frmEliContacto" name="frmEliContacto" style="display:none;">
        <input type="hidden" name="contacto_id" id="contacto_id_sil">
    </form>
</body>
</html>