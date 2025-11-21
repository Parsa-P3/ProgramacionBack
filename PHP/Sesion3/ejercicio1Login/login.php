<?php

session_start();
require_once("./setcookies.php");
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['nombreUsuario'];
    if(empty($username)) {
        echo "Introduce un usuario correcto!";
    }
    if ($username === "parsa" ) {
        $_SESSION['parsa'] = $username;
        $_SESSION['login-time'] = time();

        header("location:bienvenida.php");
        exit();
    }else{
        $error= "Usuario incorrecto";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div>
        <form method="POST" action="login.php">
        <div>   
        <label for="nombreUsuario">Usuario</label>
        <input type="text" name="nombreUsuario" id="nombreUsuario">
        
        <button type="submit">Login</button>
        </div>
        </form>

    </div>

</body>
</html>