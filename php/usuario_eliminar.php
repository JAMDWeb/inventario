<?php
    $user_id_del=limpiar_cadenas($_GET['user_id_del']); 

    // verificando usuario
    $chek_usuario=conexion();
    $chek_usuario=$chek_usuario->query("SELECT usuario_id FROM usuario WHERE 
    usuario_id='$user_id_del'");
    if ($chek_usuario->rowCount()==1) {
        // verificando usuario tiene productos dados de alta
        $chek_productos=conexion();
        $chek_productos=$chek_productos->query("SELECT usuario_id FROM producto WHERE 
        usuario_id='$user_id_del' LIMIT 1");
        
        if ($chek_productos->rowCount()<=0) {
            
            $eliminar_usuario=conexion();
            $eliminar_usuario=$eliminar_usuario->prepare("DELETE FROM usuario  WHERE 
            usuario_id=:id");

            $eliminar_usuario->execute([":id"=> $user_id_del]);

            if ($eliminar_usuario->rowCount()==1) {
                echo '
                    <div class="notification is-info is-light">
                        <strong>¡Usuario eliminado!</strong><br>
                        Los datos del usuario se eliminaron con exito
                    </div>
                ';
            } else {
                echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        No se pudo eliminar el usuario, por favor intente nuevamente
                    </div>
                ';
            }
            $eliminar_usuario=null;
            
        } else {
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No podemos eliminar el usuario ya que tiene productos registrados
            </div>
            ';
        }
        $chek_productos= null;

    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El usuario que intenta eliminar no existe
            </div>
        ';
    }

    $chek_usuario=null;


?>