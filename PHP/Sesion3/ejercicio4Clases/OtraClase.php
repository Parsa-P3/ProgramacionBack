<?php

// Clase final: no se puede extender
final class ClaseFinal
{
    // Código de la clase
}

// Clase normal
class OtraClase
{
    // Método final: no se puede sobrescribir en clases hijas
    final public function metodoFinal()
    {
        echo "Este método no puede ser sobrescrito.";
    }
}

// Ejemplo de uso
$obj = new OtraClase();
$obj->metodoFinal();
