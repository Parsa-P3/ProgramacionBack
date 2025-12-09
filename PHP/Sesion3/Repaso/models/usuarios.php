<?php

require_once(__DIR__ . "/../error.php");

class Usuario
{
    public $usuario_id;
    public $usuario;
    public $email;
    public $nombre;
    public $password;
    public $apellidos;
    public $rol_id;

    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->usuario_id = $data['usuario_id'] ?? null;
            $this->usuario    = $data['usuario'] ?? null;
            $this->password   = $data['password'] ?? null;
            $this->email      = $data['email'] ?? null;
            $this->nombre     = $data['nombre'] ?? null;
            $this->apellidos  = $data['apellidos'] ?? null;
            $this->rol_id     = $data['rol_id'] ?? null;
        }
    }

    // Getters ve Setters
    public function getId() { return $this->usuario_id ?? 0; }
    public function getUsuario() { return $this->usuario; }
    public function getEmail() { return $this->email; }
    public function getNombre() { return $this->nombre; }
    public function getApellidos() { return $this->apellidos; }
    public function getRolId() { return $this->rol_id; }
    
    public function setEmail($email) { $this->email = $email; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellidos($apellidos) { $this->apellidos = $apellidos; }
    public function setUsuario($usuario) { $this->usuario = $usuario; }
    public function setPassword($password) { $this->password = $password; }
    public function setRolId($rol_id) { $this->rol_id = $rol_id; }


    // CRUD Metodları
    public function guardar($pdo)
    {
        if ($this->usuario_id === null || $this->usuario_id == 0) {
            // INSERT (Ekleme)
            $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, password, email, nombre, apellidos, rol_id) VALUES (:usu, :pass, :mail, :nom, :ape, :rol)");
            $stmt->execute([
                ':usu' => $this->usuario,
                ':pass' => $this->password,
                ':mail' => $this->email,
                ':nom' => $this->nombre,
                ':ape' => $this->apellidos,
                ':rol' => $this->rol_id,
            ]);
            $this->usuario_id = $pdo->lastInsertId();
        } else {
            // UPDATE (Güncelleme)
            $sql = "UPDATE usuarios SET usuario = :usu, email = :mail, nombre = :nom, apellidos = :ape, rol_id = :rol";
            $params = [
                ':usu' => $this->usuario,
                ':mail' => $this->email,
                ':nom' => $this->nombre,
                ':ape' => $this->apellidos,
                ':rol' => $this->rol_id,
                ':id' => $this->usuario_id
            ];

            // Şifre güncelleniyorsa
            if (!empty($this->password) && strpos($this->password, '$2y$') !== 0) {
                $sql .= ", password = :pass";
                $params[':pass'] = $this->password;
            }
            
            $sql .= " WHERE usuario_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        }
    }

    public static function obtenerPorId($pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario_id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new self($data) : new Usuario();
    }
    
    public static function obtenerTodos($pdo)
    {
        $stmt = $pdo->query("SELECT * FROM usuarios");
        $usuarios = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuarios[] = new self($row);
        }
        return $usuarios;
    }

    public static function login($pdo, $usuario, $password)
    {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
        $stmt->execute([':usuario' => $usuario]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($data && password_verify($password, $data['password'])) {
            return new self($data);
        }
        return null;
    }

    public static function eliminar($pdo, $id)
    {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE usuario_id = :id");
        return $stmt->execute([':id' => $id]);
    }
}