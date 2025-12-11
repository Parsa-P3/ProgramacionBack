<?php
require 'db.php'; 

// --- Oturum Kontrolü ---
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'client') {
    // Adminler buraya giremez, giriş yapmayanlar giremez
    header('Location: index.php'); // Veya login.php
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// --- Oturum Kapatma Mantığı ---
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

// --- Mevcut Kullanıcı Verilerini Çekme ---
// ... (Kalan mantık ilk versiyondaki ile aynıdır)
// ...
// --- Mevcut Kullanıcı Verilerini Çekme ---
try {
    $sql = "SELECT nombre, email, password FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    $message = "Veri çekme hatası: " . $e->getMessage();
    $user = ['nombre' => 'Hata', 'email' => 'hata@hata.com']; 
}


// --- POST İşlemleri (Güncelleme ve Şifre Değiştirme) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $new_nombre = trim($_POST['nombre']);
        $new_email = trim($_POST['email']);
        
        if (empty($new_nombre) || empty($new_email)) {
            $message = "Lütfen tüm alanları doldurun.";
        } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $message = "Geçerli bir e-posta adresi girin.";
        } else {
            try {
                $sql = "UPDATE users SET nombre = ?, email = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$new_nombre, $new_email, $user_id]);
                $_SESSION['user_name'] = $new_nombre;
                $user['nombre'] = $new_nombre;
                $user['email'] = $new_email; 
                $message = "Profil bilgileri başarıyla güncellendi!";
            } catch (PDOException $e) {
                if (strpos($e->getMessage(), 'UNIQUE constraint failed') !== false) {
                     $message = "Bu e-posta adresi zaten kullanılıyor.";
                } else {
                     $message = "Güncelleme sırasında bir hata oluştu: " . $e->getMessage();
                }
            }
        }
    }
    
    if (isset($_POST['change_password'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if ($new_password !== $confirm_password) {
            $message = "Yeni parolalar eşleşmiyor.";
        } elseif (strlen($new_password) < 6) { 
            $message = "Parola en az 6 karakter olmalıdır.";
        } else {
            try {
                $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET password = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$hashedPassword, $user_id]);
                $message = "Parola başarıyla değiştirildi!";
            } catch (PDOException $e) {
                 $message = "Parola değiştirme sırasında bir hata oluştu: " . $e->getMessage();
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Profil Yönetimi - Client</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div class="container">
        <h2>Client Profilin - <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
        
        <p class="logout"><a href="profile.php?action=logout" class="btn btn-secondary">Oturumu Kapat</a></p>
        
        <?php if (!empty($message)): ?>
            <p class="message success"><?php echo $message; ?></p>
        <?php endif; ?>

        <hr>

        <h3>Profil Bilgilerini Düzenle</h3>
        <form method="POST" action="profile.php">
            <div class="form-group">
                <label for="nombre">Ad:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">E-posta:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <button type="submit" name="update_profile" class="btn btn-primary">Bilgileri Güncelle</button>
        </form>

        <hr>
        
        <h3>Parola Değiştir</h3>
        <form method="POST" action="profile.php">
            <div class="form-group">
                <label for="new_password">Yeni Parola:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Parolayı Onayla:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" name="change_password" class="btn btn-warning">Parolayı Değiştir</button>
        </form>
    </div>
</body>
</html>