<?php

class rectangulo2{

    private float $base;
    private float $altura;

    public function __construct(float $_base, float $_altura){
        $this->base = $_base;
        $this->altura = $_altura;
    }

    public function getBase(){
        return $this->base;
    }
    public function getAltura(){
        return $this->altura;
    }
    public function setAltura($altura){
        $this->altura = $altura;
    }
    public function setBase($base){
        $this->base = $base;
    }

    public function calcularArea(): float{
        return ($this->base * $this->altura) * 2;
    }

    public function calcularPerimetro() : float {
    return ($this->base + $this->altura) * 2;
    }

    public function mostrarInfo(): void {
        echo "Base : " . $this->base . ", Altura : " . $this->altura
             . "Area de rectangulo: " . $this->calcularArea() . PHP_EOL
             . "Perimetro: " . $this->calcularPerimetro();
    }



}

?>