<?php
require_once "utils.php"; 
require_once "./models/Usuarios.php";
require_once "./models/Contacto.php"; // Kullanıcı silinirken kontakları silmek için

// Fonksiyonlar (Validation ve Password):
function comprobarPatronEmail($email): bool
{
    $patron = "/^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/";
    return preg_match($patron, $email);
}

function comprobarPatronPassword($password): bool
{
    // En az 8 karakter, en az bir büyük harf, bir küçük harf ve bir rakam
    $patron = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d).{8,}$/";
    return preg_match($patron, $password);
}

function validar($usuario, $password, $email, $es_yeni): string
{
    $error = '';

    if (!comprobarPatronEmail($email)) {
        $error .= "Email formatı hatalı.--";
    }
    
    // Yeni kayıt veya şifre alanı doluysa
    if ($es_yeni || (!empty($password) && strlen($password) > 0)) {
        if (!comprobarPatronPassword($password)) {
            $error .= "Şifre en az bir büyük harf, bir küçük harf, bir sayı, bir alfanümerik karakter ve en az 8 karakter içermelidir.--";
        }
    }
    return $error;
}


// ====== CRUD İŞLEMLERİ BAŞLANGIÇ ======
global $pdo;
$accion = $_GET['action'] ?? '';
$usuario_id = (int)($_POST['usuario_id'] ?? 0);

// Admin Kontrolü: Yeni kayıt haricinde Admin yetkisi ister.
if ($accion !== 'anadir' && (!comprobarSesion() || $_SESSION['usuario']->getRolId() != 1)) {
    header('Location: login.php?error=Yalnızca yönetici (Admin) bu işlemi yapabilir.');
    exit();
}


// ====== SILME İŞLEMİ ======
if ($accion === 'eliminar' && $usuario_id > 0) {
    // Önce kullanıcının tüm kontaklarını sil
    Contacto::eliminarPorUsuarioId($pdo, $usuario_id);
    
    // Ardından kullanıcıyı sil
    Usuario::eliminar($pdo, $usuario_id);
    
    header('Location: listado.php?ok=Kullanıcı ve tüm ilgili iletişimler başarıyla silindi.');
    exit();
}

// ====== KAYDETME/EKLEME İŞLEMİ ======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';
    $rol_id = (int)($_POST['rol_id'] ?? 2); 
    
    $es_yeni = ($usuario_id == 0);
    $error = validar($usuario, $password, $email, $es_yeni);

    if ($error !== '') {
        $redireccion = "ficha.php?usuario_id=" . $usuario_id . "&error=" . $error;
        header('Location: ' . $redireccion);
        exit();
    }

    $usu = Usuario::obtenerPorId($pdo, $usuario_id);

    $usu->setEmail($email);
    $usu->setNombre($nombre);
    $usu->setApellidos($apellidos);
    $usu->setUsuario($usuario);
    $usu->setRolId($rol_id);
    
    // Şifre boş değilse hash'le ve kaydet (Güncelleme modunda boş bırakılabilir)
    if (!empty($password)) {
         $usu->setPassword(password_hash($password, PASSWORD_DEFAULT));
    }
    
    $usu->guardar($pdo);

    $mensaje = $es_yeni ? 'Kullanıcı başarıyla oluşturuldu.' : 'Kullanıcı başarıyla güncellendi.';
    
    // Yeni kullanıcı kaydı ise login sayfasına yönlendir
    if ($es_yeni && !comprobarSesion()) {
         header('Location: login.php?ok=' . $mensaje);
    } else {
        // Admin listesinden geliyorsa listeye yönlendir
        header('Location: listado.php?ok=' . $mensaje);
    }

    exit();
}
?>