<?php
require 'db.php'; 

if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit;
}

$message = ''; 

if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "Kayıt başarılı! Lütfen giriş yapın.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = "Lütfen tüm alanları doldurun.";
    } else {
        try {
            // Rol sütunu da çekiliyor
            $sql = "SELECT id, nombre, password, rol FROM users WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                $_SESSION['user_role'] = $user['rol']; // Rolü oturuma kaydet
                
                header('Location: index.php');
                exit;
            } else {
                $message = "Hata: E-posta veya parola yanlış.";
            }

        } catch (\PDOException $e) {
            $message = "Giriş sırasında bir hata oluştu: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div class="container">
        <h2>Giriş Yap</h2>
        
        <?php if (!empty($message)): ?>
            <p class="message <?php echo (isset($_GET['success']) && $_GET['success'] == 1) ? 'success' : 'error'; ?>"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">E-posta:</label>
                <input type="email" id="email" name="email" required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="password">Parola:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Giriş Yap</button>
            <p class="link-text">Hesabınız yok mu? <a href="register.php">Kayıt Ol</a></p>
        </form>
    </div>
</body>
</html>