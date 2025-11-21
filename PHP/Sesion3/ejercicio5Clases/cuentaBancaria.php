<?php

    class cuentaBancaria{
        private String $titular;
        private float $saldo;

        public function __construct(String $_titular , float $_saldo){
            $this->titular=$_titular;
            $this->saldo=$_saldo;

        }

        public function getTitular(){
            return $this->titular;
        }
        public function getSaldo(){
            return $this->saldo;
        }
        public function setTitular($titular){
            $this->titular=$titular;
        }
        public function setSaldo($saldo){
            $this->saldo=$saldo;
        }

        public function mostrarInfo(){
            echo "<br> <br>" ."Titular cuenta : " . $this->getTitular() . "<br>"
                 ."Saldo de la cuenta : " . $this->getSaldo() ;
        }

        public function depositar(float $cantidadIngreso){
            $this->saldo= $this->saldo + $cantidadIngreso ;
            echo "<br> <br>" . "Saldo actualizado : " . $this->getSaldo();
            return $this->saldo;
            
        }

        public function retirar(float $cantidadRetirar){
            if( $this->saldo >= $cantidadRetirar){
            $this->saldo= $this->saldo - $cantidadRetirar ;
            echo "<br> <br>" . "Operacion Realizado correctamente . Saldo actualizado : " . $this->getSaldo();
            return $this->saldo;
            }else{
                echo "<br> <br> No se puede retirar dinero ";
                echo "Saldo disponible para retirar : " . $this->getSaldo();
            }
        }
    }
?>