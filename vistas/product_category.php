<div class="container is-fluid mb-6">
    <h1 class="title">Productos</h1>
    <h2 class="subtitle">Lista de productos por categoría</h2>
</div>

<div class="container pb-6 pt-6">
    <?php
        require_once "./php/main.php";

    ?>

    <div class="columns">

        <div class="column is-one-third">
            <h2 class="title has-text-centered">Categorías</h2>
            <?php
                $categorias=conexion();
                $categorias=$categorias->query("SELECT * FROM categoria");
                if($categorias->rowCount()>0){
                    $categorias=$categorias->fetchAll();
                    foreach ($categorias as $row) {
                        echo '<a href="index.php?vista=product_category&category_id='.$row['categoria_id'].'" 
                        class="button is-link is-inverted is-fullwidth">'.$row['categoria_nombre'].'</a>';                                
                    }
                }else{
                    echo '<p class="has-text-centered" >No hay categorías 
                    registradas</p>';
                };
                $categorias=null;
            ?>
        </div>



        <div class="column">
            <?php
                $categoria_id= (isset($_GET['category_id'])) ? $_GET['category_id']:0;
               
                $categoria=conexion();
                $categoria=$categoria->query("SELECT * FROM categoria WHERE 
                categoria_id='$categoria_id'");
                if($categoria->rowCount()>0){
                    
                    
                    $categoria=$categoria->fetch();
                    
                    echo '
                        <h2 class="title has-text-centered">'.$categoria['categoria_nombre'].'</h2>
                        <p class="has-text-centered pb-6" >'.$categoria['categoria_ubicacion'].'</p>
                    ';  
                      
                    // Eliminar categoria
                    if (isset($_GET['product_id_del'])) {
                        require_once "./php/producto_eliminar.php";

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
                    $url="index.php?vista=product_category&category_id=$categoria_id&page=";
                    $registros=3; //controlar la cantidad de registros que se van a mostrar en la lista
                    $busqueda="";// busqueda de usuarios medienta un termino o un texto 

                    require_once "./php/producto_lista.php";// se incluye el archivo que va a generar la lista
                    
                }else{
                    echo '<h2 class="has-text-centered title" >Seleccione una categoría para empezar</h2>
                    ';
                };
                $categoria=null;
            ?>
        </div>

    </div>
</div>