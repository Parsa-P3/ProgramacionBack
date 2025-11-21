<?php

require_once "Cuadrado.php";
class Rectangulo extends Cuadrado
{
    // private $base;
    private $altura;

    public function __construct($b = 1, $a = 1)
    {
        $this->base = $b;
        $this->altura = $a;
    }
    // Destructor
    public function __destruct()
    {
        echo "Rectángulo de base={$this->base} y altura={$this->altura} destruido.<br/>";
    }

    // public function getBase()
    // {
    //     return $this->base;
    // }

    public function getAltura()
    {
        return $this->altura;
    }

    public function setBase($base)
    {
        $this->base = $base;
    }

    public function setAltura($altura)
    {
        $this->altura = $altura;
    }

    public function getSuperficie()
    {
        return parent::getBase() * $this->altura;
    }

}

$rectangulo = new Rectangulo();
$rectangulo->setBase(10);
$rectangulo->setAltura(3);

echo "El rectángulo de " . $rectangulo->getBase() . " * " . $rectangulo->getAltura();
echo " tiene una superficie de " . $rectangulo->getSuperficie();
