<div class="container is-fluid mb-6">
    <h1 class="title">Usuarios</h1>
    <h2 class="subtitle">Buscar usuario</h2>
</div>

<div class="container pb-6 pt-6">
    <?php
        require_once "./php/main.php";
        if (isset($_POST['modulo_buscador'])) {
            require_once "./php/buscador.php"; 

        }

        // Formulario de busqueda
        if (!isset($_SESSION['busqueda_usuario'])  && empty($_SESSION['busqueda_usuario']) ) {
    ?>
        <div class="columns">
            <div class="column">
                <form action="" method="POST" autocomplete="off" >
                    <input type="hidden" name="modulo_buscador" value="usuario">   
                    <div class="field is-grouped">
                        <p class="control is-expanded">
                            <input class="input is-rounded" type="text" name="txt_buscador" placeholder="¿Qué estas buscando?"
                             pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" maxlength="30" >
                        </p>
                        <p class="control">
                            <button class="button is-info" type="submit" >Buscar</button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    <?php } else{?>
        <div class="columns">
            <div class="column">
                <form class="has-text-centered mt-6 mb-6" action="" method="POST" autocomplete="off" >
                    <input type="hidden" name="modulo_buscador" value="usuario"> 
                    <input type="hidden" name="eliminar_buscador" value="usuario">
                    <p>Estas buscando <strong>"
                        <?php echo $_SESSION['busqueda_usuario'];?>
                        "</strong></p>
                    <br>
                    <button type="submit" class="button is-danger is-rounded">Eliminar busqueda</button>
                </form>
            </div>
        </div>
    <?php
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
        $url="index.php?vista=user_search&page=";
        $registros=2; //controlar la cantidad de registros que se van a mostrar en la lista
        $busqueda=$_SESSION['busqueda_usuario'];// busqueda de usuarios medienta un termino o un texto 

        require_once "./php/usuario_lista.php";// se incluye el archivo que va a generar la lista

        }
    ?>
</div>