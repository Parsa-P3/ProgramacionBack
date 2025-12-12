<?php

//Incluyo el control de errores
require_once(__DIR__ . "/../engine/error.php");

//Modelado de clase de Contacto
class Contacto
{
    public $contacto_id;
    public $cliente_id; // FK a la tabla clientes
    public $nombre;
    public $apellidos;
    public $email;
    public $telefono;
    
    // Variable auxiliar para el listado global
    public $cliente_nombre; 

    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->contacto_id    = $data['contacto_id'] ?? null;
            $this->cliente_id     = $data['cliente_id'] ?? null;
            $this->nombre         = $data['nombre'] ?? null;
            $this->apellidos      = $data['apellidos'] ?? null;
            $this->email          = $data['email'] ?? null;
            $this->telefono       = $data['telefono'] ?? null;
            // Para el listado global
            $this->cliente_nombre = $data['cliente_nombre'] ?? null; 
        }
    }

    // ====== Getters y Setters ======

    public function getId() { return $this->contacto_id ?? 0; }
    public function setId($id) { $this->contacto_id = $id; }

    public function getClienteId() { return $this->cliente_id ?? 0; }
    public function setClienteId($cliente_id) { $this->cliente_id = $cliente_id; }

    public function getNombre() { return $this->nombre ?? ''; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getApellidos() { return $this->apellidos ?? ''; }
    public function setApellidos($apellidos) { $this->apellidos = $apellidos; }

    public function getEmail() { return $this->email ?? ''; }
    public function setEmail($email) { $this->email = $email; }

    public function getTelefono() { return $this->telefono ?? ''; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }


    // ====== CRUD con PDO (B-1, R-0.5, M-0) ======

    /**
     * Guarda o actualiza un contacto en la base de datos.
     */
    public function guardar($pdo)
    {
        if ($this->contacto_id === null || $this->contacto_id == 0) {
            // Insertar
            $stmt = $pdo->prepare("INSERT INTO contactos 
                                    (cliente_id, nombre, apellidos, email, telefono) 
                                    VALUES (:cliente_id, :nombre, :apellidos, :email, :telefono)");

            $stmt->execute([
                ':cliente_id' => $this->cliente_id,
                ':nombre'     => $this->nombre,
                ':apellidos'  => $this->apellidos,
                ':email'      => $this->email,
                ':telefono'   => $this->telefono
            ]);
            $this->contacto_id = $pdo->lastInsertId();
        } else {
            // Actualizar
            $stmt = $pdo->prepare("UPDATE contactos SET
                                    cliente_id = :cliente_id,
                                    nombre = :nombre,
                                    apellidos = :apellidos,
                                    email = :email,
                                    telefono = :telefono
                                   WHERE contacto_id = :id");

            $stmt->execute([
                ':cliente_id' => $this->cliente_id,
                ':nombre'     => $this->nombre,
                ':apellidos'  => $this->apellidos,
                ':email'      => $this->email,
                ':telefono'   => $this->telefono,
                ':id'         => $this->contacto_id
            ]);
        }
    }

    /**
     * Obtiene un contacto por su ID.
     */
    public static function obtenerPorId($pdo, $id): Contacto
    {
        $stmt = $pdo->prepare("SELECT * FROM contactos WHERE contacto_id = :id");
        $stmt->execute([':id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new self($data) : new Contacto();
    }

    /**
     * Obtiene todos los contactos, o solo los de un cliente (Filtra por cada cliente).
     */
    public static function obtenerTodos($pdo, $cliente_id = 0): array
    {
        // Se hace un JOIN para obtener el nombre del cliente en el listado global
        $sql = "SELECT c.*, cl.nombre as cliente_nombre FROM contactos c 
                INNER JOIN clientes cl ON c.cliente_id = cl.cliente_id";
        $params = [];

        if ($cliente_id != 0) {
            $sql .= " WHERE c.cliente_id = :cliente_id";
            $params[':cliente_id'] = $cliente_id;
        }
        $sql .= " ORDER BY c.contacto_id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $contactos = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $contactos[] = new self($row);
        }

        return $contactos;
    }

    /**
     * Elimina un contacto por su ID.
     */
    public function eliminar($pdo)
    {
        if ($this->contacto_id !== null && $this->contacto_id != 0) {
            $stmt = $pdo->prepare("DELETE FROM contactos WHERE contacto_id = :id");
            $stmt->execute([':id' => $this->contacto_id]);
        }
    }
}