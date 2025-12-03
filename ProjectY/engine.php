<?php

class engine
{
    // variable para la conexión PDO
    private $pdo;

    // constructor que recibe la ruta de la base de datos SQLite
    public function __construct($db_path)
    {
        // crear la conexión PDO
        try {
            // conectar a la base de datos SQLite
            $this->pdo = new PDO("sqlite:" . $db_path);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }


        $stmt = $this->pdo->query("SELECT COUNT(*) FROM roles");
        if ($stmt->fetchColumn() === 0) {
            $this->pdo->exec("INSERT INTO roles (id, description) VALUES (1, 'admin')");
            $this->pdo->exec("INSERT INTO roles (id, description) VALUES (2, 'user')");
        }
    }

    // Método de registro
    public function register($nombreUsuario, $ApellidoUsuario, $emailUsuario, $contrasena)
    {

        // 1. Hash de la contraseña para seguridad
        $hashed_contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        $role_id = 2; // Asignar rol 'user' por defecto
        // 2. Consulta SQL para insertar un nuevo usuario
        $sql = "INSERT INTO usuarios (nombreUsuario, ApellidoUsuario, emailUsuario, contrasena, role_id) VALUES (?, ?, ?, ?, ?)";

        try {
            // 3. Prepared Statement para un registro seguro (hay que investigar más sobre esto)
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$nombreUsuario, $ApellidoUsuario, $emailUsuario, $hashed_contrasena, $role_id]);
            
            echo "
                <script>
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 2000);
                </script>
                 ";
            return ['success' => true, 'message' => "Registro completado con éxito!"];
            
            

        } catch (PDOException $e) {
            // Captura de errores (Ej: correo electrónico ya registrado)

            if ($e->errorInfo[1] == 19) { // Error de restricción UNIQUE en SQLite
                return ['success' => false, 'message' => "Esta dirección de correo electrónico ya está en uso."];
            }
            return ['success' => false, 'message' => "Ocurrió un error durante el registro: " . $e->getMessage()];
        }

    }

    public function login($emailUsuario, $contrasena)
    {

        // 1. Consulta SQL para obtener el usuario por correo electrónico con su rol utilizando un join
        $sql = "SELECT u.id, u.nombreUsuario, u.contrasena, r.description AS role_description 
                FROM usuarios u
                INNER JOIN roles r ON u.role_id = r.id
                WHERE u.emailUsuario = ?";

        // 2. Preparar y ejecutar la consulta
        try {

            // Preparar la consulta SQL
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$emailUsuario]);


            // Obtener al usuario como una sola fila
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // 3. Verificar si el usuario existe y si la contraseña es correcta
            if (!$user || !password_verify($contrasena, $user['contrasena'])) {
                return ['success' => false, 'message' => "Correo electrónico o contraseña incorrectos."];
            }

            // 4. Iniciar sesión y guardar información del usuario en la sesión
            /* ??
            no se si hay que iniciar asi o no 
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
                */
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombreUsuario'];
            $_SESSION['user_role'] = $user['role_description'];

            return ['success' => true, 'message' => "Login exitoso."];

        } catch (PDOException $e) {
            return ['success' => false, 'message' => "Ocurrido un error :" . $e->getMessage()];
        }
    }



    // Método para obtener todos los roles
    // (para poder asignar roles dinámicamente )
    public function getAllRoles()
    {
        $sql = "SELECT id, description FROM roles";
        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getAllUsers()
    {
        // Consulta SQL para obtener todos los usuarios con sus roles
        $sql = "SELECT u.id, u.nombreUsuario, u.ApellidoUsuario , u.emailUsuario, u.role_id, r.description AS role_description 
            FROM usuarios u
            INNER JOIN roles r ON u.role_id = r.id";

        try {
            $stmt = $this->pdo->query($sql);
            // PDO es la clase de PHP para acceder a bases de datos
            // FETCH_ASSOC para obtener un array asociativo
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }


    }
    public function updateUserInfo($id, $nombreUsuario, $ApellidoUsuario, $correoUsuario, $role_id)
    {
        $sql = "UPDATE usuarios SET nombreUsuario = ?, ApellidoUsuario = ?, emailUsuario = ?, role_id = ? WHERE id = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$nombreUsuario, $ApellidoUsuario, $correoUsuario, $role_id, $id]);
            return ['success' => true, 'message' => " Usuario actualizado con éxito."];
        } catch (PDOException $e) {
            // errorInfo[1] 19 es error de restricción UNIQUE en SQLite
            if ($e->errorInfo[1] == 19) {
                return ['success' => false, 'message' => "error: Rol inválido."];
            }
            return ['success' => false, 'message' => " error en actualizar  " . $e->getMessage()];
        }
    }

    public function createUser($nombreUsuario, $ApellidoUsuario, $correoUsuario, $contrasena, $role_id)
    {
        $hashed_contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nombreUsuario, ApellidoUsuario, emailUsuario, contrasena, role_id) VALUES (?, ?, ?, ?, ?)";
        try {

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$nombreUsuario, $ApellidoUsuario, $correoUsuario, $hashed_contrasena, $role_id]);

            return ['success' => true, 'message' => " Usuario creado con éxito."];

        } catch (PDOException $e) {
            // errorInfo[1] 19 es error de restricción UNIQUE en SQLite
            if ($e->errorInfo[1] == 19) {
                return ['success' => false, 'message' => "Esta dirección de correo electrónico ya está en uso."];
            }
            return ['success' => false, 'message' => " error en crear  " . $e->getMessage()];
        }
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public function getUserRole()
    {
        return $_SESSION['user_role'] ?? 'guest';
    }



    public function Delete($id)
    {
        // código para eliminar usuario
        $sql = "DELETE FROM usuarios WHERE id = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return ['success' => true, 'message' => " Usuario eliminado con éxito."];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => " error en eliminar  " . $e->getMessage()];
        }

    }
}