<?php
require 'db.php'; 

$message = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($nombre) || empty($email) || empty($password)) {
        $message = "Lütfen tüm alanları doldurun.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Geçerli bir e-posta adresi girin.";
    } elseif (strlen($password) < 6) {
        $message = "Parola en az 6 karakter olmalıdır.";
    } else {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Rol sütunu eklendi: Herkes varsayılan olarak 'client'
            $sql = "INSERT INTO users (nombre, email, password, rol) VALUES (?, ?, ?, 'client')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $email, $hashedPassword]);

            header('Location: login.php?success=1');
            exit;

        } catch (\PDOException $e) {
             if (strpos($e->getMessage(), 'UNIQUE constraint failed') !== false) {
                 $message = "Bu e-posta adresi zaten kullanılıyor.";
            } else {
                 $message = "Bir veritabanı hatası oluştu: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kullanıcı Kaydı</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div class="container">
        <h2>Kayıt Ol</h2>
        
        <?php if (!empty($message)): ?>
            <p class="message error"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="nombre">Ad:</label>
                <input type="text" id="nombre" name="nombre" required value="<?php echo isset($nombre) ? htmlspecialchars($nombre) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="email">E-posta:</label>
                <input type="email" id="email" name="email" required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="password">Parola (min 6 karakter):</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Kayıt Ol</button>
            <p class="link-text">Zaten bir hesabınız var mı? <a href="login.php">Giriş Yap</a></p>
        </form>
    </div>
</body>
</html>