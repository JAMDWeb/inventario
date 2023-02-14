<?php
    //Evitar inyeccion de sql 
    $modulo_buscador=limpiar_cadenas($_POST['modulo_buscador']);

    $modulos=["usuario","categoria","producto"];

    if (in_array($modulo_buscador,$modulos)) {
        $modulos_url=[
            "usuario"=>"user_search",
            "categoria"=>"category_search",
            "producto"=>"product_search"
        ];

        $modulos_url=$modulos_url[$modulo_buscador];

        $modulo_buscador="busqueda_".$modulo_buscador;

        //Iniciar busqueda, definir el valor de la variable se session
        if (isset($_POST['txt_buscador'])) {
            $txt=limpiar_cadenas($_POST['txt_buscador']);

            if ($txt=="") {
                echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        Introduce un termino de busqueda
                    </div>
                ';
            } else {
                if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}",$txt)) { 
                    echo '
                        <div class="notification is-danger is-light">
                            <strong>¡Ocurrio un error inesperado!</strong><br>
                         el termino de busqueda no coincide con el formato solicitado 
                        </div>
                    ';
                } else {
                    $_SESSION[$modulo_buscador]=$txt;
                    if (headers_sent()) {
                        // Si se envio encabezado se redirecciona con JS
                        /* Esto no funciono en site en hosthing    
                        echo "
                            <script> windows.location.href='index.php?vista=home';
                            </script> 
                        ";
                        */
                        //Opcion para sito en hosthing
                        $filename = 'index.php?vista='.$modulos_url;
                        
                        echo '<script type="text/javascript">';
                        echo 'window.location.href="'.$filename.'";';
                        echo '</script>';
                        echo '<noscript>';
                        echo '<meta http-equiv="refresh" content="0;url='.$filename.'" />';
                        echo '</noscript>';
                        
                        
                    } else {
                        //  si no se  han enviado encabezados redireccionamiento con PHP
                        header("Location: index.php?vista=$modulos_url",true,303);// no funciona en hosting
                        /*
                    Warning: Cannot modify header information - headers already sent by
                     (output started at /storage/ssd3/524/20200524/public_html/index.php:1) 
                     in /storage/ssd3/524/20200524/public_html/php/buscador.php on line 39
                    */
                    }    
                    
                    exit();
                }
                
            }
        
        }
        // Eliminar busqueda, eliminar el valor de la variable de session
        if (isset($_POST['eliminar_buscador'])) {
            unset($_SESSION[$modulo_buscador]);
            if (headers_sent()) {
                // Si se envio encabezado se redirecciona con JS
                /* Esto no funciono en site en hosthing    
                echo "
                    <script> windows.location.href='index.php?vista=home';
                    </script> 
                ";
                */
                //Opcion para sito en hosthing
                $filename = 'index.php?vista='.$modulos_url;
                
                echo '<script type="text/javascript">';
                echo 'window.location.href="'.$filename.'";';
                echo '</script>';
                echo '<noscript>';
                echo '<meta http-equiv="refresh" content="0;url='.$filename.'" />';
                echo '</noscript>';
                
                
            } else {
                //  si no se  han enviado encabezados redireccionamiento con PHP
                header("Location: index.php?vista=$modulos_url",true,303);// no funciona en hosting
                /*
            Warning: Cannot modify header information - headers already sent by
             (output started at /storage/ssd3/524/20200524/public_html/index.php:1) 
             in /storage/ssd3/524/20200524/public_html/php/buscador.php on line 39
            */
            }    
           
            exit();
        }

    } else {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No podemos procesar la peticiono
        </div>
        ';
        
    }
    
?>