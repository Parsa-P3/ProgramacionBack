<?php

require_once "OtraClase.php";
class Persona
{
    private $nombre;
    private $apellidos;

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellidos()
    {
        return $this->apellidos;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }
}

$unaPersona = new Persona();
$unaPersona->setNombre("Jose Luis");
$unaPersona->setApellidos("González");

// Comprobar si es instancia de OtraClase
if ($unaPersona instanceof OtraClase) {
    echo "<br/>" . $unaPersona->getNombre() . " es otra clase";
}

// Mostrar clase real
echo "<br/>" . $unaPersona->getNombre() . " es de la clase " . get_class($unaPersona);
