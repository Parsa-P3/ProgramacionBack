<?php
require_once(__DIR__ . "/../error.php");

class Rol
{
    private $rol_id;
    private $rol;

    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->rol_id = $data['rol_id'] ?? null;
            $this->rol    = $data['rol'] ?? null;
        }
    }

    // Getters
    public function getId() { return $this->rol_id; }
    public function getRol() { return $this->rol; }

    // Statik metodlar
    public static function obtenerTodos($pdo)
    {
        $stmt = $pdo->query("SELECT * FROM roles");
        $roles = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = new self($row);
        }
        return $roles;
    }
}