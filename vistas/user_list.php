<div class="container is-fluid mb-6">
    <h1 class="title">Usuarios</h1>
    <h2 class="subtitle">Lista de usuarios</h2>
</div>

<div class="container pb-6 pt-6">
    <?php
        // se incluye el archivo principal contiene las conexiones y funciones principales
        require_once "./php/main.php";
        
        // Eliminar usuario
        if (isset($_GET['user_id_del'])) {
            require_once "./php/usuario_eliminar.php";

        }


        // crear variable que se van a utilizar en usuario_lista y realizar validaciones
        if (!isset($_GET['page'])){
            $pagina=1;
        } else {
            $pagina=(int) $_GET['page']  ;
            if ($pagina<=1 ) {
                $pagina=1;
            }
        }
        
        $pagima=limpiar_cadenas($pagina);
        $url="index.php?vista=user_list&page=";
        $registros=3; //controlar la cantidad de registros que se van a mostrar en la lista
        $busqueda="";// busqueda de usuarios medienta un termino o un texto 

        require_once "./php/usuario_lista.php";// se incluye el archivo que va a generar la lista

    ?>
    
</div>