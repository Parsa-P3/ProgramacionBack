<?php
function increment_visitas() {
     if(isset($_COOKIE["visita"])){
    setcookie('visita', ++$_COOKIE['visita'], time() + 5 );
     }else{
         setcookie('visita', 1, time() + 5 );

     }
}


?>
