<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado de la División</title>
</head>
<body>
    <h1>Resultado</h1>
    
    <?php
    // class segundoNum0 extends Exception{
    //     public function __construct() {
    //         parent::__construct("Segundo numero no puede ser 0");
    // }}

    function dividirNumeros($num1, $num2) {

        try {   
                

                if($num2 === 0) {
                    throw new Exception("Segundo numero no puede ser 0");
                   
                }
                $ResultadoSuma = $num1 + $num2;
                // Realiza la división
                $resultadoDivicion = $num1 / $num2;
                
            
            

                return "<p>$num1 dividido por $num2 es igual a: <strong>$resultadoDivicion</strong></p><br>
                        <p>$num1 suma por $num2 es igual a :<strong>$ResultadoSuma</strong></p>";
    
            

        }catch (DivisionByZeroError $e) {
                echo "<p> ¡Error! no se puede dividir por 0 </p>";
        }catch (Exception $e) {
            echo "<p> ¡Error! " . $e->getMessage() . "</p>";
        }
        
        
        
        
        
        
    }

    // ----------------------------------------------------
    // Lógica para procesar el formulario
    // ----------------------------------------------------

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Asegurarse de que los valores existen y convertirlos a float
        $num1 =$_POST['num1'];
        $num2 =$_POST['num2'];
        
        // Llamada a la función y muestra el resultado
        echo dividirNumeros($num1, $num2);

    }
    ?>

    <br>
    <p><a href="index.php">Volver al formulario</a></p>
</body>
</html>