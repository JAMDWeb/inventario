<?php
    #Almacenando datos#
    $usuario=limpiar_cadenas($_POST['login_usuario']); 
    $clave=limpiar_cadenas($_POST['login_clave']); 

    # Verificando campos obligatorios #
    if ($usuario =="" ||  $clave=="") {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }

    # Verificando integridad de los datos #
    if (verificar_datos("[a-zA-Z0-9]{4,20}",$usuario)) {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El USUARIO no coincide con el formato solicitado
        </div>
        ';
        exit();
    }

    if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave)) {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Las CLAVE  no coincide con el formato solicitado
        </div>
        ';
        exit();
    }

    # Verificando usuario #

    $check_user=conexion();
    $check_user=$check_user->query("
    SELECT * FROM usuario WHERE usuario_usuario ='$usuario'");
    if ($check_user->rowCount()==1) {
        // Array de datos de todo lo seleccionado 
        $check_user=$check_user->fetch();
        // verificar clave encriptada con password_hash()
        if ($check_user['usuario_usuario']==$usuario && password_verify
        ($clave,$check_user['usuario_clave'])) {
            // crear las variable de session
            $_SESSION['id']=$check_user['usuario_id'];
            $_SESSION['nombre']=$check_user['usuario_nombre'];
            $_SESSION['apellido']=$check_user['usuario_apellido'];
            $_SESSION['usuario']=$check_user['usuario_usuario'];
          
            if (headers_sent()) {
                // Si se envio encabezado se redirecciona con JS
                /* Esto no funciono en site en hosthing    
                echo "
                    <script> windows.location.href='index.php?vista=home';
                    </script> 
                ";
                */
                //Opcion para sito en hosthing
                $filename = 'index.php?vista=home';
                
                echo '<script type="text/javascript">';
                echo 'window.location.href="'.$filename.'";';
                echo '</script>';
                echo '<noscript>';
                echo '<meta http-equiv="refresh" content="0;url='.$filename.'" />';
                echo '</noscript>';
                
                
            } else {
                //  si no se  han enviado encabezados redireccionamiento con PHP
                header("Location:index.php?vista=home");
            }
            
            
        } else {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    El USUARIO ingresado no existe, por favor verifique y vuelva a intentarlo
                </div>
            ';
        }
        

    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El USUARIO ingresado no existe, por favor verifique y vuelva a intentarlo
            </div>
        ';
         
    }
    $check_user=null;