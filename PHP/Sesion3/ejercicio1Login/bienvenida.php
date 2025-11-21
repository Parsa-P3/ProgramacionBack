<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>
</head>
<body>
    
    <?php
    session_start();
    require_once("./setcookies.php");
    $visitas = $_COOKIE["visita"] ?? 1;
    increment_visitas(); 
    if (isset($_SESSION["parsa"])){
        echo "Hola, " . $_SESSION["parsa"] ;
        $maxTime = 5;

        
        echo "<br>";
        echo "Has iniciado sesión " . $visitas . " veces.";


        if (isset($_SESSION["login-time"]) && time() - ($_SESSION["login-time"]) > $maxTime){
            echo "zaman asimina ugradi!";
            session_destroy();
            unset($_COOKIE["visitas"]);
            header("location:login.php") ;
            exit();

        }
    }else{
        echo "Debes iniciar sesiòn";
    }

    ?>

</body>
</html>