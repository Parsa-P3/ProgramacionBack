<?php
require 'db.php';

// controlar si el usuario ha iniciado sesión y es admin
if (!$user->isLoggedIn() || $user->getUserRole() !== 'admin') {
    header('Location: index.php');
    exit();
}

$message = '';
$result = ['success' => true];
// Obtener todos los roles para el formulario de edición
$allRoles = $user->getAllRoles();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'update') {
    // Obtener datos POST según los nombres de los campos actualizados
    $id = (int) $_POST['id'];
    $nombreUsuario = trim(htmlspecialchars($_POST['nombreUsuario'] ?? '')); // Nuevo nombre de campo
    $ApellidoUsuario = trim(htmlspecialchars($_POST['ApellidoUsuario'] ?? '')); // Nuevo nombre de campo
    $emailUsuario = trim(htmlspecialchars($_POST['emailUsuario'] ?? ''));     // Nuevo nombre de campo
    $role_id = (int) $_POST['role_id'];

    // Llamar al método para actualizar la información del usuario
    $result = $user->updateUserInfo($id, $nombreUsuario, $ApellidoUsuario, $emailUsuario, $role_id);
    $message = $result['message'];
} else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'delete') {
    // Obtener el ID del usuario a eliminar
    $id = (int) $_POST['id'];

    // Llamar al método para eliminar el usuario
    $result = $user->Delete($id);
    $message = $result['message'];

} else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'create') {
    // Obtener datos POST para crear un nuevo usuario
    $nombreUsuario = trim(htmlspecialchars($_POST['nombreUsuario'] ?? ''));
    $ApellidoUsuario = trim(htmlspecialchars($_POST['ApellidoUsuario'] ?? ''));
    $emailUsuario = trim(htmlspecialchars($_POST['emailUsuario'] ?? ''));
    $contrasena = $_POST['contrasena'] ?? '';
    $role_id = 2; // Asignar rol por defecto (por ejemplo, usuario regular)

    // Llamar al método para crear un nuevo usuario
    $result = $user->createUser($nombreUsuario, $ApellidoUsuario, $emailUsuario, $contrasena, $role_id);
    $message = $result['message'];
}

// Obtener la lista actualizada de usuarios (debe llamarse después del POST para obtener los datos nuevos)
$allUsers = $user->getAllUsers();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .edit-form input[type="text"],
        .edit-form input[type="email"],
        .edit-form select {
            width: 90%;
            padding: 5px;
            box-sizing: border-box;
        }

        .edit-form button {
            padding: 5px 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2> Panel de administración para admin </h2>

        <?php if (!empty($message)): ?>
            <p class="message" style="color: <?php echo $result['success'] ? 'green' : 'red'; ?>;"><?php echo $message; ?>
            </p>
        <?php endif; ?>

        <p><a href="index.php">Volver a la página principal</a></p>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo electrónico</th>
                    <th>Rol</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                      <p><a href="register.php">Crear usuario</a></p>

                <?php foreach ($allUsers as $u): ?>
                    <tr>
                        <form method="POST" class="edit-form">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $u['id']; ?>">

                            <td><?php echo $u['id']; ?></td>
                            <td><input type="text" name="nombreUsuario"
                                    value="<?php echo htmlspecialchars($u['nombreUsuario']); ?>" required></td>

                            <td><input type="text" name="ApellidoUsuario"
                                    value="<?php echo htmlspecialchars($u['ApellidoUsuario'] ?? ''); ?>"></td>

                            <td><input type="email" name="emailUsuario"
                                    value="<?php echo htmlspecialchars($u['emailUsuario']); ?>" required></td>

                            <td>
                                <select name="role_id">
                                    <?php foreach ($allRoles as $role): ?>
                                        <option value="<?php echo $role['id']; ?>" <?php echo ($u['role_id'] == $role['id']) ? 'selected' : ''; ?>>
                                            <?php
                                            if ($u['id'] !== $_SESSION['user_id']): // Evitar que el admin cambie su propio rol
                                                echo htmlspecialchars($role['description']);
                                            else:
                                                echo htmlspecialchars($u['role_description']);
                                            endif;
                                            ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <button type="submit" name="action" value="update">Actualizar</button>
                                <?php if ($u['id'] !== $_SESSION['user_id']): // Evitar que el admin se elimine a sí mismo ?>
                                    <button type="submit" name="action" value="delete"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</button>
                                <?php endif; ?>
                                
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p><a href="logout.php">Cerrar sesión</a></p>

    </div>

</body>

</html>