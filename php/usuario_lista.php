<?php
// Desde donde vamos a espesar a contar en la consulta SELECT ussando LIMIT
    $inicio= ($pagina>0) ?(($pagina*$registros)-$registros): 0; 
    $tabla=""; //la que va ir generando el listdo de usuarios
    if (isset($busqueda) && $busqueda!="") {// si viene definida y no esta vacia
        // seleccionar los registros para mostras los datos en lista segun la condicion de busqueda
        $consulta_datos="SELECT * FROM usuario WHERE ((usuario_id !='".$_SESSION['id']."') AND 
        (usuario_nombre LIKE'%$busqueda%' OR usuario_apellido LIKE'%$busqueda%' 
        OR usuario_usuario LIKE'%$busqueda%' OR usuario_email LIKE'%$busqueda%'))
        ORDER BY usuario_nombre ASC LIMIT $inicio, $registros";

        //Contar la cantidad de registros que cumplen esta condicion
        $consulta_total="SELECT COUNT(usuario_id) FROM usuario WHERE ((usuario_id !='".$_SESSION['id']."') AND 
        (usuario_nombre LIKE'%$busqueda%' OR usuario_apellido LIKE'%$busqueda%' 
        OR usuario_usuario LIKE'%$busqueda%' OR usuario_email LIKE'%$busqueda%'))";

    } else {
        // seleccionar los registros para mostras los datos en lista
        $consulta_datos="SELECT * FROM usuario WHERE usuario_id != 
        '".$_SESSION['id']."' ORDER BY usuario_nombre ASC LIMIT $inicio, $registros";

        //Contar la cantidad de registros que cumplen esta condicion
        $consulta_total= "SELECT COUNT(usuario_id) FROM usuario WHERE usuario_id != 
        '".$_SESSION['id']."'";

    }

    //crear la conexion a la DB
    $conexion=conexion();

    $datos=$conexion->query($consulta_datos);
    $datos=$datos->fetchAll();// ferchAll hace un array de todos los registros, se utiliza cuando son + de un registro

    $total=$conexion->query($consulta_total);
    $total=(int) $total->fetchColumn();// Devolver un valor de una columna 

    $Npaginas=ceil($total/$registros) ; // Contener el numero de paginas , se realiza por  calculo. 
                                        //Con ceil() se redondea los decimales
    
    $tabla.='
        <div class="table-container">
            <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                <thead>
                    <tr class="has-text-centered">
                            <!-- con <tr> no funciona el "has-text-centered" -->
                            <td><strong>#</strong></td>
                            <td><strong>Nombres</strong></td>
                            <td><strong>Apellidos</strong></td>
                            <td><strong>Usuario</strong></td>
                            <td><strong>Usuario</strong></td>
                            <td><strong>Email</strong></td>
                                                    
                            <td colspan="2"> <strong>Opciones</strong></td>
                        
                    </tr>
                </thead>
                <tbody>   
    ';

    if ($total>=1 && $pagina<=$Npaginas) { // Si existen registros en la DB y si la cantidad de paginas no existe  
        // si se cumple hay registros y estamos en una pagina valida, se mostrara el listado
        $contador=$inicio+1;
        $pag_inicio=$inicio+1;
        foreach ($datos as $rows) {
            $tabla.='
            <tr class="has-text-centered" >
                <td>'.$contador.'</td>
                <td>'.$rows['usuario_nombre'].'</td>
                <td>'.$rows['usuario_apellido'].'</td>
                <td>'.$rows['usuario_usuario'].'</td>
                <td>'.$rows['usuario_email'].'</td>
                <td>
                    <a href="index.php?vista=user_update&user_id_up='.$rows['usuario_id'].'" 
                    class="button is-success is-rounded is-small">Actualizar</a>
                </td>
                <td>
                    <a href="'.$url.$pagina.'&user_id_del='.$rows['usuario_id'].'" class="button is-danger is-rounded 
                    is-small">Eliminar</a>
                </td>
            </tr>

            ';
            $contador++;
        }

        $pag_final=$contador-1;


    } else {
        // pero sino se mostrara el texto "No hay registros" 
        // o el boton "Haga clic para recargar" cuando estamos en una pagina que no exista
        if ($total>=1){
            $tabla.='
            <tr class="has-text-centered" >
                <td colspan="7">
                    <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                        Haga clic acá para recargar el listado
                    </a>
                </td>
            </tr>
            ';
        } else {
            $tabla.='
            <tr class="has-text-centered" >
                <td colspan="7">
                    No hay registros en el sistema
                </td>
            </tr>
            ';
        }
        
    }
    
    $tabla.='</tbody></table></div>';

    if ($total>=1 && $pagina<=$Npaginas) {
        $tabla.='
        <p class="has-text-right">Mostrando usuarios <strong>'.$pag_inicio.'</strong> 
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