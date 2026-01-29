<?php
//Me traigo el fichero que tiene todas las librerias básicas del proyecto
require_once "utils.php";
class BaseDatos
{
    protected $pdo; //Conexion global de la db

     public function __construct()
    {
        global $config;
        //Inicio las propiedades necesarias en el constructor y hago la conexión la BBDD
        $bbdd = $config['database']['dbname'];
        // Conectar a SQLite
       
        $this->pdo = new PDO('sqlite:' . __DIR__ . '/bbdd/usuarios.db');
    }

    public function getPdo() { return $this->pdo; }
}