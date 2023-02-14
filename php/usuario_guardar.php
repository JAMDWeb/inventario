<?php
    require_once "main.php";
    //echo "Hola Mundo";
    #Almacenando datos#
    $nombre=limpiar_cadenas($_POST['usuario_nombre']); // Obligatorio
    $apellido=limpiar_cadenas($_POST['usuario_apellido']); // Obligatorio
    
    $usuario=limpiar_cadenas($_POST['usuario_usuario']); // Obligatorio
    $email=limpiar_cadenas($_POST['usuario_email']);

    $clave_1=limpiar_cadenas($_POST['usuario_clave_1']);// Obligatorio
    $clave_2=limpiar_cadenas($_POST['usuario_clave_2']);// Obligatorio

    # Verificando campos obligatorios #
    if ($nombre =="" ||  $apellido=="" || $usuario=="" || $clave_1==""|| 
    $clave_2=="" ) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }

    # Verificando integridad de los datos #
    if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)) {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El NOMBRE no coincide con el formato solicitado
        </div>
        ';
        exit();
    }

    if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)) {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El APELLIDO no coincide con el formato solicitado
        </div>
        ';
        exit();
    }
    if (verificar_datos("[a-zA-Z0-9]{4,20}",$usuario)) {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El USUARIO no coincide con el formato solicitado
        </div>
        ';
        exit();
    }

    if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_1) ||
     verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_2) ) {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Las CLAVES  no coincide con el formato solicitado
        </div>
        ';
        exit();
    }

    # Verificando el email #
    if ($email!="") {
        if (filter_var($email,FILTER_VALIDATE_EMAIL)) {
            $check_email=conexion();
            $check_email=$check_email->query("
            SELECT usuario_email FROM usuario WHERE usuario_email ='$email'");
            if ($check_email->rowCount()>0) {
                echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        El EMAIL ingresado ya se encuentra registrado, por favor elija otro
                    </div>
                ';
                exit(); 
            }
            $check_email=null;
        } else {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    El EMAIL ingresado no es valido
                </div>
            ';
            exit();
        }
        
    }

    # Verificando usuario #

    $check_usuario=conexion();
    $check_usuario=$check_usuario->query("
    SELECT usuario_usuario FROM usuario WHERE usuario_usuario ='$usuario'");
    if ($check_usuario->rowCount()>0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El USUARIO ingresado ya se encuentra registrado, por favor elija otro
            </div>
        ';
        exit(); 
    }
    $check_usuario=null;

    # Verificar las contraseñas sean iguales
    if ($clave_1 != $clave_2) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Las CLAVES que ha ingresdo no coinciden, por favor verifique
            </div>
        ';
        exit();         
    } else {
        # Encriptar clave
        $clave=password_hash($clave_1,PASSWORD_BCRYPT,["cost"=>10]);

    }
    
    # Guardando datos validados
    $guardar_usuario=conexion();
    /*
    Este metodo no es tan seguro ya que permite inyeccion de SQL
    $guardar_usuario=$guardar_usuario->query("INSERT INTO usuario
    (usuario_nombre,usuario_apellido,usuario_usuario,usuario_clave,
    usuario_email) VALUES('$nombre','$apellido','$usuario','$clave','$email')
    ");   
    */
    # El metodo que se utiliza es prepare(), que utiliza marcadores en los VALUES #
    $guardar_usuario=$guardar_usuario->prepare("INSERT INTO usuario
    (usuario_nombre,usuario_apellido,usuario_usuario,usuario_clave,
    usuario_email) VALUES(:nombre,:apellido,:usuario,:clave,:email)");   

    $marcadores=[
        ":nombre"=>$nombre,
        ":apellido"=>$apellido,
        ":usuario"=>$usuario,
        ":clave"=>$clave,
        ":email"=>$email
    ];

    $guardar_usuario->execute($marcadores);

    if ($guardar_usuario->rowCount()==1) {
        echo '
            <div class="notification is-info is-light">
                <strong>¡Usuario registrado!</strong><br>
                El USUARIO se registro con exito
            </div>
        ';
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo registrar el USUARIO, por favor intente nuevamente
            </div>
        ';
    }
    $guardar_usuario=null;

    

