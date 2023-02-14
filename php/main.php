<?php
    // Funciones que se utilizaran dentro del sistema

    #Conexion a la base de datos#   
    function conexion(){ // realizar peticion a la db
       
        if ($_SERVER['SERVER_NAME']=="localhost") {
            //Datos localhost
            $pdo= new PDO('mysql:host=localhost;dbname=inventario','root','');
            return $pdo;
        } else {
           // Datos para conexion en hosting
            $pdo= new PDO('mysql:host=localhost;dbname=id20200524_inventario','id20200524_jamweb','Jam_2263_Banf@');
            return $pdo;
        
        }
        
        
        
        // prueba de funcionamiento 
        //$pdo->query("INSERT INTO categoria(categoria_nombre,categoria_ubicacion) VALUES('prueba','texto ubicacion')");

        
    }

    // Funcion para verificar que los datos que estamos enviando desde los formularios y contengan los datos solicitados
    function verificar_datos($filtro,$cadena){
        if (preg_match("/^".$filtro."$/",$cadena)) {
            #No hay errores #
            return false;
        } else {
            #Si hay errores #
            return true;
        }
    }
    /* Ejemplo de uso de funcion verificar_datos 
    $nombre = "Carlos7";
    if (verificar_datos("[a-zA-Z]{6,10}",$nombre)) {
        echo "Los datos no coinciden";
    }
    */
    #limpiar cadenas de texto para evitar inyeccion de codigo malicioso : js y sql como filtro de seguridad
    function limpiar_cadenas($cadena){
        $cadena=trim($cadena);
        $cadena=stripslashes($cadena);
        $cadena=str_ireplace("<script>","",$cadena); // ataque xss: elimina insercion de codigo js en php
        $cadena=str_ireplace("</script>","",$cadena); // ataque xss: elimina insercion de codigo js en php
        $cadena=str_ireplace("<script src", "", $cadena);
		$cadena=str_ireplace("<script type=", "", $cadena);
		$cadena=str_ireplace("SELECT * FROM", "", $cadena);
		$cadena=str_ireplace("DELETE FROM", "", $cadena);
		$cadena=str_ireplace("INSERT INTO", "", $cadena);
		$cadena=str_ireplace("DROP TABLE", "", $cadena);
		$cadena=str_ireplace("DROP DATABASE", "", $cadena);
		$cadena=str_ireplace("TRUNCATE TABLE", "", $cadena);
		$cadena=str_ireplace("SHOW TABLES;", "", $cadena);
		$cadena=str_ireplace("SHOW DATABASES;", "", $cadena);
		$cadena=str_ireplace("<?php", "", $cadena);
		$cadena=str_ireplace("?>", "", $cadena);
		$cadena=str_ireplace("--", "", $cadena);
		$cadena=str_ireplace("^", "", $cadena);
		$cadena=str_ireplace("<", "", $cadena);
		$cadena=str_ireplace("[", "", $cadena);
		$cadena=str_ireplace("]", "", $cadena);
		$cadena=str_ireplace("==", "", $cadena);
		$cadena=str_ireplace(";", "", $cadena);
		$cadena=str_ireplace("::", "", $cadena);
		$cadena=trim($cadena);
		$cadena=stripslashes($cadena);
		return $cadena;
    }

    /* Ejemplo de uso de funcion limpiar cadenas  
    $texto = "<?php Carlos7 ?> ";
    echo limpiar_cadenas($texto);
    */
    # renombrar nombre de las fotos
    function renombrar_foto($nombre){
        $nombre=str_ireplace(" ", "_", $nombre);
		$nombre=str_ireplace("/", "_", $nombre);
		$nombre=str_ireplace("#", "_", $nombre);
		$nombre=str_ireplace("-", "_", $nombre);
		$nombre=str_ireplace("$", "_", $nombre);
		$nombre=str_ireplace(".", "_", $nombre);
		$nombre=str_ireplace(",", "_", $nombre);
		$nombre=$nombre."_".rand(0,100);
		return $nombre;
    }
    /* Ejemplo de uso de funcion renombrar fotos  
    $foto = "Play Station 5 black/edition";
    echo renombrar_foto($foto);
    */
    
    #Funcion paginador de tablas#
    function paginador_tablas($pagina,$Npaginas,$url,$botones){
        $tabla='<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';
        #Boton Anterior#
        if ($pagina<=1){
            $tabla.='
            <a class="pagination-previous is-disabled" disabled >Anterior</a>
            <ul class="pagination-list">
            
            ';
            
        }else{
            $tabla.='
            <a class="pagination-previous" href='.$url.($pagina-1).'">Anterior</a>
            <ul class="pagination-list">
                <li><a class="pagination-link" href="'.$url.'1">1</a></li>
                <li><span class="pagination-ellipsis">&hellip;</span></li>
            ';
        }

        #Boton de paginacion
        $ci=0;
        for ($i=$pagina; $i<= $Npaginas ; $i++) { 
            if($ci>$botones){
                break;
            }

            if ($pagina==$i) {
                $tabla.= '
                <li><a class="pagination-link is-current" href="'.$url.$i.'">'.$i.'</a></li>
                ';
            } else {
                $tabla.= '
                <li><a class="pagination-link" href="'.$url.$i.'">'.$i.'</a></li>
                ';
               
            }
            $ci++;
        }
        

        #Boton Siguiente#
        if ($pagina==$Npaginas){
            $tabla.='
            </ul>
            <a class="pagination-next is-disabled" disabled>Siguiente</a>
            
            ';
            
        }else{
            $tabla.='
                <li><span class="pagination-ellipsis">&hellip;</span></li>
                <li><a class="pagination-link" href="'.$url.$Npaginas.'">'.$Npaginas.'</a></li>
            </ul>
            <a class="pagination-next" href="'.$url.($pagina+1).'">Siguiente</a>
           
            ';
        }

        $tabla.='</nav>';
        return $tabla;

    }



