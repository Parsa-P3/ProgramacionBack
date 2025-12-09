<?php
require_once "utils.php"; 
require_once "./models/Contacto.php";

// Fonksiyonlar (Validation):
function comprobarPatronEmail($email): bool
{
    $patron = "/^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/";
    return preg_match($patron, $email);
}

function comprobarPatronTelefono($telefono): bool
{
    // İspanyol Telefon Numarası: 9 basamaklı, 6, 7, 8 veya 9 ile başlayan
    $patron = "/^[6-9]{1}[0-9]{8}$/"; 
    return preg_match($patron, $telefono);
}


// ====== CRUD İŞLEMLERİ BAŞLANGIÇ ======
global $pdo;
// Admin Yetki Kontrolü: Sadece Admin CRUD yapabilir.
if (!comprobarSesion() || $_SESSION['usuario']->getRolId() != 1) {
    header('Location: login.php?error=Yalnızca yönetici (Admin) bu işlemi yapabilir.');
    exit();
}

$accion = $_GET['action'] ?? '';
$contacto_id = (int)($_POST['contacto_id'] ?? $_GET['contacto_id'] ?? 0);

// ====== SILME İŞLEMİ ======
if ($accion === 'eliminar' && $contacto_id > 0) {
    Contacto::eliminar($pdo, $contacto_id);
    header('Location: listado_contactos.php?ok=Contacto eliminado correctamente');
    exit();
}

// ====== KAYDETME/EKLEME İŞLEMİ ======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario_id = (int)($_POST['usuario_id'] ?? 0); 
    $nombre     = $_POST['nombre'] ?? '';
    $apellidos  = $_POST['apellidos'] ?? '';
    $email      = $_POST['email'] ?? '';
    $telefono   = $_POST['telefono'] ?? '';
    $error      = '';

    // Validasyonlar
    if (!comprobarPatronEmail($email)) {
        $error .= "Email formatı hatalı.--";
    }
    
    if (!comprobarPatronTelefono($telefono)) {
        $error .= "Telefon formatı hatalı. İspanyol numarası (9 basamaklı, 6, 7, 8 veya 9 ile başlayan) bekleniyor.--";
    }
    
    if (empty($nombre) || empty($email) || $usuario_id == 0) {
        $error .= "Ad, Email ve Müşteri (Usuario) alanları boş olamaz.--";
    }

    if ($error !== '') {
        $redireccion = "ficha_contacto.php?contacto_id=" . $contacto_id . "&error=" . $error;
        header('Location: ' . $redireccion);
        exit();
    }

    // Veritabanına kaydetme
    $contacto = Contacto::obtenerPorId($pdo, $contacto_id);

    $contacto->setUsuarioId($usuario_id);
    $contacto->setNombre($nombre);
    $contacto->setApellidos($apellidos);
    $contacto->setEmail($email);
    $contacto->setTelefono($telefono);

    $contacto->guardar($pdo);

    $mensaje = ($contacto_id == 0) ? 'Contacto añadido correctamente.' : 'Contacto modificado correctamente.';
    header('Location: listado_contactos.php?ok=' . $mensaje);
    exit();
}
?>