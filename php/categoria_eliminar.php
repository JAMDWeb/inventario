<?php
    $category_id_del=limpiar_cadenas($_GET['category_id_del']); 

    // verificando categoria
    $chek_categoria=conexion();
    $chek_categoria=$chek_categoria->query("SELECT categoria_id FROM categoria 
    WHERE categoria_id='$category_id_del'");
    if ($chek_categoria->rowCount()==1) {
        
        // verificando categoria si tiene productos dados de alta
        $chek_productos=conexion();
        $chek_productos=$chek_productos->query("SELECT categoria_id FROM producto WHERE 
        categoria_id='$category_id_del' LIMIT 1");

        if ($chek_productos->rowCount()<=0) {
            $eliminar_categoria=conexion();
            $eliminar_categoria=$eliminar_categoria->prepare("DELETE FROM categoria WHERE 
            categoria_id=:id");

            $eliminar_categoria->execute([":id"=> $category_id_del]);
            
            if ($eliminar_categoria->rowCount()==1) {
                echo '
                    <div class="notification is-info is-light">
                        <strong>¡Categoria eliminada!</strong><br>
                        Los datos de la categoria se eliminaron con exito
                    </div>
                ';
            } else {
                echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        No se pudo eliminar la categoria, por favor intente nuevamente
                    </div>
                ';
            }
            $eliminar_categoria=null;
        } else {
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No podemos eliminar la categoria ya que tiene productos registrados
            </div>
            ';
        }
        $chek_productos=null;

    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La CATEGORIA que intenta eliminar no existe
            </div>
        ';
    }
    
    $chek_categoria=null;


?>