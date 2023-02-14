<?php
    require_once "../inc/session_start.php";
    require_once "main.php";
    
    //Almacenando datos
    $codigo=limpiar_cadenas($_POST['producto_codigo']);  // Obligatorio
    $nombre=limpiar_cadenas($_POST['producto_nombre']);  // Obligatorio

    $precio=limpiar_cadenas($_POST['producto_precio']);  // Obligatorio
    $stock=limpiar_cadenas($_POST['producto_stock']);  // Obligatorio

    $categoria=limpiar_cadenas($_POST['producto_categoria']); 

    # Verificando campos obligatorios #
    if ($codigo =="" ||  $nombre=="" || $precio=="" || $stock==""|| 
    $categoria=="" ) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }
    
    # Verificando integridad de los datos #
    if (verificar_datos("[a-zA-Z0-9- ]{1,70}",$codigo)) {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El CODIGO de BARRAS no coincide con el formato solicitado
        </div>
        ';
        exit();
    }

    if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$nombre)) {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El NOMBRE no coincide con el formato solicitado
        </div>
        ';
        exit();
    }

    if (verificar_datos("[0-9.]{1,25}",$precio)) {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El PRECIO no coincide con el formato solicitado
        </div>
        ';
        exit();
    }

    if (verificar_datos("[0-9]{1,25}",$stock)) {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El STOCK no coincide con el formato solicitado
        </div>
        ';
        exit();
    }

    # Verificando Codigo Barra #
    $check_codigo=conexion();
    $check_codigo=$check_codigo->query("SELECT producto_codigo FROM producto WHERE 
    producto_codigo ='$codigo'");
    if ($check_codigo->rowCount()>0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El CODIGO de BARRA ingresado ya se encuentra registrado, por favor elija otro
            </div>
        ';
        exit(); 
    }
    $check_codigo=null;

    # Verificando Nombre #
    $check_nombre=conexion();
    $check_nombre=$check_nombre->query("SELECT producto_nombre FROM producto WHERE 
    producto_nombre ='$nombre'");
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

    # Verificando Categoria #
    $check_categoria=conexion();
    $check_categoria=$check_categoria->query("SELECT categoria_id FROM categoria WHERE 
    categoria_id ='$categoria'");
    if ($check_categoria->rowCount()<=0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La CATEGORIA seleccionada no existe
            </div>
        ';
        exit(); 
    }
    $check_categoria=null;

    #Directoria de imagenes#
    $img_dir="../img/producto/";

    #Comprobar si se selecciono una imagen#
    if ($_FILES['producto_foto']['name']!="" && $_FILES['producto_foto']['size'] >0) {
        # Se seleccion archivo
        # Verificando si existe directorio de imagenes#
        if (!file_exists($img_dir)) {
            if (!mkdir($img_dir,0777)) {
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    Error al crear el directorio para imagenes 
                </div>
                ';
                exit(); 
            }
        }

        #Verificar el formato de las imagenes#
        if (mime_content_type($_FILES['producto_foto']['tmp_name'])!="image/jpeg" && 
        mime_content_type($_FILES['producto_foto']['tmp_name'])!="image/png") {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    La imagen que ha seleccionado es de un formato no permitido
                </div>
            ';
            exit(); 
        }

        #Verificar tamaño(peso) permitido#
        if (($_FILES['producto_foto']['size']/1024)>(3*1024)) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    La imagen que ha seleccionado supera el peso permitido
                </div>
            ';
            exit(); 
        }

        #Verificar la extencion de la imagen#
        switch (mime_content_type($_FILES['producto_foto']['tmp_name'])) {
            case 'image/jpeg':
                $img_ext=".jpg";
                break;
            case 'image/png':
                $img_ext=".png";
                break;
        }

        #Permisos de escritura/lectura#
        chmod($img_dir,0777);

        $img_nombre=renombrar_foto($nombre);
        $foto=$img_nombre.$img_ext;

        #Moviendo imagen al directorio#
        if (!move_uploaded_file($_FILES['producto_foto']['tmp_name'],$img_dir.$foto)) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    No podemos subir la imagen al sistema en este momento
                </div>
            ';
            exit(); 
        }
    } else {
        $foto="";
    }

    # Guardando datos validados
    $guardar_producto=conexion();
    # El metodo que se utiliza es prepare(), que utiliza marcadores en los VALUES #
    $guardar_producto=$guardar_producto->prepare("INSERT INTO producto
    (producto_codigo,producto_nombre,producto_precio,producto_stock,
    producto_foto,categoria_id,usuario_id) 
    VALUES(:codigo,:nombre,:precio,:stock,:foto,:categoria,:usuario)");   

    $marcadores=[
        ":codigo"=>$codigo,
        ":nombre"=>$nombre,
        ":precio"=>$precio,
        ":stock"=>$stock,
        ":foto"=>$foto,
        ":categoria"=>$categoria,
        ":usuario"=>$_SESSION['id']
    ];

    $guardar_producto->execute($marcadores);

    if ($guardar_producto->rowCount()==1) {
        echo '
            <div class="notification is-info is-light">
                <strong>¡Producto registrado!</strong><br>
                El producto se registro con exito
            </div>
        ';
    } else {

        if (is_file($img_dir.$foto)) {
            chmod($img_dir.$foto,0777);
            unlink($img_dir.$foto);
        }

        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo registrar el producto, por favor intente nuevamente
            </div>
        ';
    }
    $guardar_producto=null;



?>

