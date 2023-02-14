<?php
    require_once "main.php";

    #Almacenando datos#
    $nombre=limpiar_cadenas($_POST['categoria_nombre']); // Obligatorio
    $ubicacion=limpiar_cadenas($_POST['categoria_ubicacion']); // Obligatorio

    # Verificando campos obligatorios #
    if ($nombre =="") {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }

    # Verificando integridad de los datos #
    if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}",$nombre)) {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El NOMBRE no coincide con el formato solicitado
        </div>
        ';
        exit();
    }

    if ($ubicacion!="") {
        if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}",$ubicacion)) {
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La UBICACION no coincide con el formato solicitado
            </div>
            ';
            exit();
        }      
    } 

    # Verificando nombre categoria #
    $check_nombre=conexion();
    $check_nombre=$check_nombre->query("SELECT categoria_nombre FROM categoria WHERE 
    categoria_nombre ='$nombre'");
    if ($check_nombre->rowCount()>0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE ingresado ya se encuentra registrado, por favor elija otro
            </div>
        ';
        exit(); 
    }
    $check_nombre=null;
     
    # Guardando datos validados
    $guardar_categoria=conexion();
    # El metodo que se utiliza es prepare(), que utiliza marcadores en los VALUES #
    $guardar_categoria=$guardar_categoria->prepare("INSERT INTO categoria
    (categoria_nombre,categoria_ubicacion) VALUES(:nombre,:ubicacion)");   

    $marcadores=[
        ":nombre"=>$nombre,
        ":ubicacion"=>$ubicacion
    ];

    $guardar_categoria->execute($marcadores);

    if ($guardar_categoria->rowCount()==1) {
        echo '
            <div class="notification is-info is-light">
                <strong>¡CATEGORIA registrado!</strong><br>
                La Categoria se registro con exito
            </div>
        ';
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo registrar la Categoria, por favor intente nuevamente
            </div>
        ';
    }
    $guardar_categoria=null;
?>