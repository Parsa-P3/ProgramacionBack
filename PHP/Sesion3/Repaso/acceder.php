<?php
require_once "utils.php";
require_once "./models/Usuarios.php";
require_once "ficha_guardar.php"; // validate() fonksiyonu için gereklidir.

// Obtenemos la acción del query string
$accion = $_GET['action'] ?? '';

// Verificamos si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';
    $error = "";

    // Login Validasyonu
    $usu = Usuario::login($pdo, $usuario, $password);

    if ($usu === null) {
        $error .= "Kullanıcı adı veya şifre hatalı.--";
    }

    if ($error == "") {
        // Oturumu oluştur
        crearSesion($usu);
        // Başarılı giriş: Admin ise listeye, değilse kendi kontak listesine
        if ($usu->getRolId() == 1) {
            header('Location: listado.php');
        } else {
            header('Location: listado_contactos.php');
        }
        
    } else {
        header('Location: login.php?error=' . $error . '&usuario=' . $usuario);
    }
    exit();
}
?>