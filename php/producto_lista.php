<?php
// Desde donde vamos a espesar a contar en la consulta SELECT ussando LIMIT
    $inicio= ($pagina>0) ?(($pagina*$registros)-$registros): 0; 
    
    $tabla=""; //la que va ir generando el listdo de usuarios

    $campos="producto.producto_id,producto.producto_codigo,producto.producto_nombre,
    producto.producto_precio,producto.producto_stock,producto.producto_foto,
    categoria.categoria_nombre,usuario.usuario_nombre,usuario.usuario_apellido";

    if (isset($busqueda) && $busqueda!="") {// si viene definida y no esta vacia
        // seleccionar los registros para mostras los datos en lista segun la condicion de busqueda
        $consulta_datos="SELECT $campos FROM producto INNER JOIN categoria ON 
        producto.categoria_id=categoria.categoria_id INNER JOIN usuario ON producto.
        usuario_id=usuario.usuario_id WHERE producto.producto_codigo LIKE 
        '%$busqueda%' OR  producto.producto_nombre LIKE '%$busqueda%' ORDER BY 
        producto.producto_nombre ASC LIMIT $inicio, $registros";

        //Contar la cantidad de registros que cumplen esta condicion
        $consulta_total="SELECT COUNT(producto_id) FROM producto WHERE 
        producto_codigo LIKE '%$busqueda%' OR producto_nombre LIKE '%$busqueda%'";

    } elseif ($categoria_id>0) {
        // seleccionar los registros para mostras los datos en lista segun la condicion de busqueda
        $consulta_datos="SELECT $campos FROM producto INNER JOIN categoria ON 
        producto.categoria_id=categoria.categoria_id INNER JOIN usuario ON producto.
        usuario_id=usuario.usuario_id WHERE producto.categoria_id='$categoria_id' 
        ORDER BY producto.producto_nombre ASC LIMIT $inicio, $registros";

        //Contar la cantidad de registros que cumplen esta condicion
        $consulta_total="SELECT COUNT(producto_id) FROM producto WHERE 
        categoria_id='$categoria_id'";
    } else{
        // seleccionar los registros para mostras los datos en lista
        $consulta_datos="SELECT $campos FROM producto INNER JOIN categoria ON 
        producto.categoria_id=categoria.categoria_id INNER JOIN usuario ON producto.
        usuario_id=usuario.usuario_id ORDER BY producto.producto_nombre ASC LIMIT 
        $inicio, $registros";

        //Contar la cantidad de registros que cumplen esta condicion
        $consulta_total= "SELECT COUNT(producto_id) FROM producto";

    }

    //crear la conexion a la DB
    $conexion=conexion();

    $datos=$conexion->query($consulta_datos);
    $datos=$datos->fetchAll();// ferchAll hace un array de todos los registros, se utiliza cuando son + de un registro

    $total=$conexion->query($consulta_total);
    $total=(int) $total->fetchColumn();// Devolver un valor de una columna 

    $Npaginas=ceil($total/$registros) ; // Contener el numero de paginas , se realiza por  calculo. 
                                        //Con ceil() se redondea los decimales
    
    if ($total>=1 && $pagina<=$Npaginas) { // Si existen registros en la DB y si la cantidad de paginas no existe  
        // si se cumple hay registros y estamos en una pagina valida, se mostrara el listado
        $contador=$inicio+1;
        $pag_inicio=$inicio+1;
        foreach ($datos as $rows) {
            //<td>'.substr($rows['categoria_ubicacion'],0,25).'</td>
            $tabla.='
            <article class="media">
                <figure class="media-left">
                    <p class="image is-64x64">';
                        if (is_file("./img/producto/".$rows['producto_foto'])) {
                            $tabla.='<img src="./img/producto/'.$rows['producto_foto'].'">';
                        } else {
                            $tabla.='<img src="./img/producto.png">';
                        }
                       
            $tabla.='</p>
                </figure>
                <div class="media-content">
                    <div class="content">
                        <p>
                            <strong>'.$contador.' - '.$rows['producto_nombre'].'</strong><br>
                            <strong>CODIGO:</strong> '.$rows['producto_codigo'].', 
                            <strong>PRECIO:</strong> $'.$rows['producto_precio'].', 
                            <strong>STOCK:</strong> '.$rows['producto_stock'].', 
                            <strong>CATEGORIA:</strong> '.$rows['categoria_nombre'].', 
                            <strong>REGISTRADO POR:</strong> '.$rows
                            ['usuario_nombre'].' '.$rows['usuario_apellido'].'
                        </p>
                    </div>
                    <div class="has-text-right">
                        <a href="index.php?vista=product_img&product_id_up='.$rows
                        ['producto_id'].'" class="button is-link is-rounded is-small">Imagen</a>

                        <a href="index.php?vista=product_update&product_id_up='.$rows
                        ['producto_id'].'" class="button is-success is-rounded is-small">Actualizar</a>

                        <a href="'.$url.$pagina.'&product_id_del='.$rows
                        ['producto_id'].'" class="button is-danger is-rounded is-small">Eliminar</a>
                    </div>
                </div>
            </article>


            <hr>
            ';
            $contador++;
        }

        $pag_final=$contador-1;


    } else {
        // pero sino se mostrara el texto "No hay registros" 
        // o el boton "Haga clic para recargar" cuando estamos en una pagina que no exista
        if ($total>=1){
            $tabla.='
            <p class="has-text-centered">
                <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                    Haga clic acá para recargar el listado
                </a>
            </p>
            ';
        }else{
            $tabla.='<p class="has-text-centered">No hay registros en el sistema</p>';
        }
        
    }
    
    if ($total>=1 && $pagina<=$Npaginas) {
        $tabla.='<p class="has-text-right">Mostrando productos <strong>'.$pag_inicio.'</strong> 
            al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total .'</strong></p>

        ';   
    }
    //cerrar conexion a db
    $conexion=null;
    echo $tabla;

    if ($total>=1 && $pagina<=$Npaginas) {
        echo paginador_tablas($pagina,$Npaginas,$url,7);
    }

?>