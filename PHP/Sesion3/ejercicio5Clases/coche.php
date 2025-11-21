<?php

class Coche {

    private String $marca;
    private String $modelo;
    private String $color;




    public function __construct(String $marca, String $modelo, String $color) {
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->color = $color;
    }
    public function getMarca()
    {
        return $this->marca;
    }
    public function getmodelo() 
    {
         return $this->modelo; 
    }
    public function getColor()
    {
        return $this->color;
    }

    public function setMarca($marca) 
    { 
        $this->marca = $marca; 
    }

    public function setmodelo($modelo) 
    { 
        $this->modelo = $modelo;
    }

    public function setColor($color) 
    { 
        $this->color = $color; 
    }


    function mostrarInfo(){
        echo "Coche: " . $this->marca . $this->modelo . " - " . $this->color . "<br>";
    }

}

