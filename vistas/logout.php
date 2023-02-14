<?php
    session_destroy();
    if (headers_sent()) {
        /* No funciona en hosting
         Si se envio encabezado se redirecciona con JS
        echo "
            <script> windows.location.href='index.php?vista=login';
            </script> 
        ";
        */
        //Opcion para sito en hosthing
        $filename = 'index.php?vista=login';
                
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$filename.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$filename.'" />';
        echo '</noscript>';

    } else {
        //  si no se  han enviado encabezados redireccionamiento con PHP
        header("Location:index.php?vista=login");
    }