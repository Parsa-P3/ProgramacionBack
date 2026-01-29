<?php
// Sanitiza todas las entradas de `$_POST` y `$_GET` eliminando etiquetas y codificando caracteres
//este fichero limpia todos los entradas de $_POST 
foreach ($_POST as $clave => $valor) {
    $_POST[$clave]=strip_tags(htmlspecialchars($valor));
}

//este fichero limpia todos los entradas de $_GET 
foreach ($_GET as $clave => $valor) {
    $_GET[$clave]=strip_tags(htmlspecialchars($valor));
}

// dip not : ** segun investigacion sobre los metodos anteriores he consegido que su uso tiene un ventaja importante para mas seguridad (prevenir ataques xss) **