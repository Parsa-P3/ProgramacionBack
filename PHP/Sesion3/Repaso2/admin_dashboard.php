<?php
require 'db.php'; 

// --- Yetkilendirme Kontrolü ---
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: index.php'); 
    exit;
}

$current_user_id = $_SESSION['user_id'];
$message = '';
$edit_user = null; 

// --- CRUD İşlemleri Mantığı ---

// 1. SILME (DELETE - GET Metodu)
if (isset($_GET['delete_id'])) {
    $delete_id = filter_var($_GET['delete_id'], FILTER_VALIDATE_INT);

    if ($delete_id === false) {
        $message = "Hata: Geçersiz silme kimliği.";
    } elseif ((int)$delete_id === (int)$current_user_id) {
        $message = "Güvenlik Hatası: Kendi admin hesabınızı silemezsiniz.";
    } else {
        try {
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$delete_id]);
            
            // Başarılı silme sonrası yönlendirme
            header('Location: admin_dashboard.php?msg=del_success');
            exit;
        } catch (PDOException $e) {
            $message = "Silme hatası: " . $e->getMessage();
        }
    }
}

// 2. DÜZENLEME İÇİN VERİ ÇEKME (READ for Update - GET Metodu)
if (isset($_GET['edit_id'])) {
    $edit_id = filter_var($_GET['edit_id'], FILTER_VALIDATE_INT);

    if ($edit_id !== false) {
        try {
            $sql = "SELECT id, nombre, email, rol FROM users WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$edit_id]);
            $edit_user = $stmt->fetch();
            if (!$edit_user) {
                $message = "Düzenlenecek kullanıcı bulunamadı.";
            }
        } catch (PDOException $e) {
            $message = "Düzenlenecek veri çekme hatası: " . $e->getMessage();
        }
    }
}

// 3. OLUŞTURMA/GÜNCELLEME (CREATE/UPDATE - POST Metodu)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $rol = $_POST['rol'];
    $user_id = isset($_POST['user_id']) ? filter_var($_POST['user_id'], FILTER_VALIDATE_INT) : null;
    $is_update = $user_id !== null;

    if (empty($nombre) || empty($email) || empty($rol)) {
        $message = "Lütfen tüm alanları doldurun.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Geçerli bir e-posta adresi girin.";
    } else {
        try {
            if ($is_update) {
                // UPDATE (Güncelleme)
                $sql = "UPDATE users SET nombre = ?, email = ?, rol = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nombre, $email, $rol, $user_id]);
                // Başarılı güncelleme sonrası yönlendirme
                header('Location: admin_dashboard.php?msg=upd_success');
                exit;
            } else {
                // CREATE (Oluşturma)
                $password = $_POST['password'] ?? 'varsayilanparola123'; 
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (nombre, email, password, rol) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nombre, $email, $hashedPassword, $rol]);
                // Başarılı ekleme sonrası yönlendirme
                header('Location: admin_dashboard.php?msg=add_success');
                exit;
            }
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'UNIQUE constraint failed') !== false) {
                 $message = "Bu e-posta adresi zaten kullanılıyor.";
            } else {
                 $message = "Veritabanı hatası: " . $e->getMessage();
            }
        }
    }
}

// 4. TÜM KULLANICILARI LİSTELEME (READ)
// Mesajları GET parametresinden al
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'del_success') {
        $message = "Kullanıcı başarıyla silindi!";
    } elseif ($_GET['msg'] === 'upd_success') {
        $message = "Kullanıcı başarıyla güncellendi!";
    } elseif ($_GET['msg'] === 'add_success') {
        $message = "Yeni kullanıcı başarıyla eklendi!";
    }
}

try {
    $users = $pdo->query("SELECT id, nombre, email, rol, created_at FROM users ORDER BY id DESC")->fetchAll();
} catch (PDOException $e) {
    $message = "Kullanıcı listesi çekilemedi: " . $e->getMessage();
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli - Kullanıcı Yönetimi</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div class="container-full">
        <div class="header">
            <h2>Admin Yönetim Paneli - Merhaba, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
            <p class="logout"><a href="logout.php" class="btn btn-secondary">Oturumu Kapat</a></p>
        </div>
        
        <?php if (!empty($message)): ?>
            <p class="message <?php echo strpos($message, 'başarılı') !== false || isset($_GET['msg']) ? 'success' : 'error'; ?>"><?php echo $message; ?></p>
        <?php endif; ?>

        <hr>

        <h3><?php echo $edit_user ? 'Kullanıcı Düzenle (ID: ' . htmlspecialchars($edit_user['id']) . ')' : 'Yeni Kullanıcı Ekle'; ?></h3>
        <form method="POST" action="admin_dashboard.php" class="crud-form">
            <?php if ($edit_user): ?>
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($edit_user['id']); ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="nombre">Ad:</label>
                <input type="text" id="nombre" name="nombre" required 
                       value="<?php echo $edit_user ? htmlspecialchars($edit_user['nombre']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="email">E-posta:</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo $edit_user ? htmlspecialchars($edit_user['email']) : ''; ?>">
            </div>
            
            <?php if (!$edit_user): ?>
            <div class="form-group">
                <label for="password">Parola:</label>
                <input type="password" id="password" name="password" placeholder="Varsayılan parola (varsayilanparola123) ayarlanır">
            </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="rol">Rol:</label>
                <select id="rol" name="rol" required>
                    <option value="client" <?php echo ($edit_user && $edit_user['rol'] == 'client') ? 'selected' : ''; ?>>Client</option>
                    <option value="admin" <?php echo ($edit_user && $edit_user['rol'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <?php echo $edit_user ? 'Güncellemeyi Kaydet' : 'Kullanıcı Ekle'; ?>
            </button>
            <?php if ($edit_user): ?>
                <a href="admin_dashboard.php" class="btn btn-secondary">İptal</a>
            <?php endif; ?>
        </form>

        <hr>

        <h3>Tüm Kullanıcıların Listesi</h3>
        <?php if (empty($users)): ?>
            <p>Sistemde henüz kayıtlı kullanıcı yok.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ad</th>
                            <th>E-posta</th>
                            <th>Rol</th>
                            <th>Kayıt Tarihi</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($u['id']); ?></td>
                            <td><?php echo htmlspecialchars($u['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td class="role-<?php echo htmlspecialchars($u['rol']); ?>"><?php echo htmlspecialchars(ucfirst($u['rol'])); ?></td>
                            <td><?php echo htmlspecialchars($u['created_at']); ?></td>
                            <td>
                                <a href="admin_dashboard.php?edit_id=<?php echo $u['id']; ?>" class="btn btn-warning btn-small">Düzenle</a>
                                <?php if ((int)$u['id'] !== (int)$current_user_id): ?>
                                    <a href="admin_dashboard.php?delete_id=<?php echo $u['id']; ?>" 
                                       onclick="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?');" 
                                       class="btn btn-danger btn-small">Sil</a>
                                <?php else: ?>
                                    <span class="btn btn-danger btn-small disabled" title="Kendi hesabınızı silemezsiniz.">Sil (Engellendi)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>