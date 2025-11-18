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

    if (isset($_SESSION["parsa"])){
        echo "Hola, " . $_SESSION["parsa"] ;
    }else{
        echo "Debes iniciar sesiòn";
    }

    ?>

</body>
</html>