<?php
// Vamos a hacer proceso recursivo que me sume los numeros de un array
// me tiene que valer cualquier array y de cualquier tipo elementos

function sumarArray(array $elementos){
    if (empty($elementos)) {
        return 0; // caso base: array vacío
    }
    
    return array_shift($elementos) + sumarArray($elementos); // sumamos el primer elemento con el resto
}

// Ejemplo de uso
$miArray = [1, 2, 3, 4, 5];
echo "La suma del array es: " . sumarArray($miArray); // 15

?>