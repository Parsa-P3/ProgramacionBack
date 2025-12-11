<?php
require_once(__DIR__ . "/../error.php");

class Contacto
{
    public $contacto_id;
    public $usuario_id;
    public $nombre;
    public $apellidos;
    public $email;
    public $telefono;

    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->contacto_id = $data['contacto_id'] ?? null;
            $this->usuario_id  = $data['usuario_id'] ?? null;
            $this->nombre      = $data['nombre'] ?? null;
            $this->apellidos   = $data['apellidos'] ?? null;
            $this->email       = $data['email'] ?? null;
            $this->telefono    = $data['telefono'] ?? null;
        }
    }

    // Getters ve Setters
    public function getId() { return $this->contacto_id ?? 0; }
    public function getUsuarioId() { return $this->usuario_id; }
    public function getNombre() { return $this->nombre; }
    public function getApellidos() { return $this->apellidos; }
    public function getEmail() { return $this->email; }
    public function getTelefono() { return $this->telefono; }
    
    public function setUsuarioId($id) { $this->usuario_id = $id; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellidos($apellidos) { $this->apellidos = $apellidos; }
    public function setEmail($email) { $this->email = $email; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }

    // CRUD Metodları
    public function guardar($pdo)
    {
        if ($this->contacto_id === null || $this->contacto_id == 0) {
            // INSERT
            $stmt = $pdo->prepare("INSERT INTO contactos (usuario_id, nombre, apellidos, email, telefono) VALUES (:uid, :nombre, :apellidos, :email, :telefono)");
            $stmt->execute([
                ':uid'        => $this->usuario_id,
                ':nombre'     => $this->nombre,
                ':apellidos'  => $this->apellidos,
                ':email'      => $this->email,
                ':telefono'   => $this->telefono,
            ]);
            $this->contacto_id = $pdo->lastInsertId();
        } else {
            // UPDATE
            $stmt = $pdo->prepare("UPDATE contactos SET usuario_id = :uid, nombre = :nombre, apellidos = :apellidos, email = :email, telefono = :telefono WHERE contacto_id = :id");
            $stmt->execute([
                ':uid'        => $this->usuario_id,
                ':nombre'     => $this->nombre,
                ':apellidos'  => $this->apellidos,
                ':email'      => $this->email,
                ':telefono'   => $this->telefono,
                ':id'         => $this->contacto_id
            ]);
        }
    }

    public static function obtenerPorId($pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM contactos WHERE contacto_id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new self($data) : new Contacto();
    }
    
    public static function obtenerTodos($pdo)
    {
        $stmt = $pdo->query("SELECT * FROM contactos");
        $contactos = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $contactos[] = new self($row);
        }
        return $contactos;
    }
    
    public static function obtenerPorCliente($pdo, $usuario_id)
    {
        $stmt = $pdo->prepare("SELECT * FROM contactos WHERE usuario_id = :uid");
        $stmt->execute([':uid' => $usuario_id]);
        $contactos = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $contactos[] = new self($row);
        }
        return $contactos;
    }

    public static function eliminar($pdo, $id)
    {
        $stmt = $pdo->prepare("DELETE FROM contactos WHERE contacto_id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    public static function eliminarPorUsuarioId($pdo, $usuario_id)
    {
        // Kullanıcı silinirken, ona bağlı kontakları da silmek için
        $stmt = $pdo->prepare("DELETE FROM contactos WHERE usuario_id = :uid");
        return $stmt->execute([':uid' => $usuario_id]);
    }
}