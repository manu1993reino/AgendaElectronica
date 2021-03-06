<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/agenda.css">
</head>
<body>

<?php
include "conexion_a_bbdd.php";

if(isset($_SESSION["usuario"])){

    $query = $conn->prepare("USE agenda;");
    $query->execute();

    function cerrarSesion(){
        if(isset($_COOKIE["usuario"]) && ($_COOKIE["contrasena"])){
            setcookie("usuario", $result["usuario"], time() -600);
            setcookie("contrasena", $result["contrasena"], time() -600);
        }
        session_destroy();
        header("location: index.php");
        die();
    }

    echo "<div class='bienvenida'><h2>Hola " . $_SESSION["usuario"] . ", bienvenido/a a tu agenda electronica </h2></div>";
    ?>

    <div class="aniadir">
        <h3>Añadir nuevo contacto</h3>
        <form action='agenda.php' method='post'>
        <input type="text" placeholder="Nombre" name="nombre"><br>
        <input type="number" placeholder="Teléfono" name="telefono"><br>
        <input type="email" placeholder="ejemplo@email.com" name="correo"><br>
        <input type="date" placeholder="Teléfono" name="fecha"><br>
        <input class='btnAniadir' type="submit" name="insertar" value="Añadir">
        </form>
    </div>

    <?php

    if(isset($_POST['insertar'])){
        try{
            $consulta= "INSERT INTO agenda (nombre, telefono, correo, fechaNac) VALUES (:nombre, :telefono, :correo, :fecha)"; 
            $datos=$conn->prepare($consulta); 
            $datos->bindParam(':nombre', $_POST['nombre']);
            $datos->bindParam(':telefono', $_POST['telefono']);
            $datos->bindParam(':correo', $_POST['correo']);
            $datos->bindParam(':fecha', $_POST['fecha']);
            $registro = $datos->fetch(PDO:: FETCH_ASSOC);

            if($datos->execute()){
                echo "<div class='insertRegistro'>¡Contacto añadido con exito!</div>";
            }   
            
            else{
                echo "<div class='insertRegistro'>ERROR 01: Alguno de los campos introducidos no se ha podido añadir.</div>";
            }
        }
        catch(PDOException $e){
            echo "<div class='insertRegistro'>ERROR 01: Alguno de los campos introducidos no se ha podido añadir.</div>";
        }
        
    }

    if(isset($_POST["cerrar"])){
        cerrarSesion();
    }
    ?>

    <table>
        <th>Codigo</th>
        <th>Nombre</th>
        <th>Telefóno</th>
        <th>Correo</th>
        <th>Fecha de nacimiento</th>
        <th colspan="2">Herramientas</th>
        <th>Borrar varios</th>

    <?php

    $registrosPagina=6;

    if(isset($_GET["pagina"])){
        if ($_GET["pagina"]==1){
            header("Location:agenda.php");
        }
        else{
            $page=$_GET["pagina"];
        }
    }
    else{
        $page=1;
    }

    $selectTodosContactos=("SELECT * FROM agenda");
    $datos= $conn->prepare($selectTodosContactos);
    $datos->execute();
    $totalFilas=$datos->rowCount();

    echo "<div class='numContactos'><h3>Tienes " . $totalFilas . " contactos en total</h3></div>";
    $totalPaginas=ceil($totalFilas/$registrosPagina);
    $principioLimit=($page-1)*$registrosPagina;

    if(isset($_REQUEST['buscar']) || isset($_POST["rebusqueda"])){
        $consulta = "SELECT * FROM agenda WHERE nombre LIKE '%" . $_POST['nombre'] . "%' LIMIT $principioLimit, $registrosPagina;";
        $tabla = $conn->prepare($consulta); 
        $tabla->execute();
        $totalBusquedas = $tabla->rowCount();
        if($totalBusquedas>=1){
            foreach($tabla as $dato){
                if(isset($_POST['btnEditar2']) && ($dato["codigo"]==$_POST['editar'])){
                    echo "<tr>";
                    echo "<form class='formEmpleados' action='editar.php' method='post'>";
                    echo "<input type='hidden' name='codigo' value='".$dato['codigo']."'>";
                    echo "<input type='hidden' name='urlActual' value='http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]'>";
                    echo "<td>" . $dato["codigo"] . "</td>";
                    echo "<td> <input type='text' name='nombre' value='".$dato['nombre']."'> </td>";
                    echo "<td> <input type='text' name='telefono' value='".$dato['telefono']."'> </td>";
                    echo "<td> <input type='text' name='correo' value='".$dato['correo']."'> </td>";
                    echo "<td> <input type='date' name='fechaNac' value='".$dato['fechaNac']."'> </td>";
                    echo "<td><button class='btnBorrar'><a href='borrar.php?codigo=".$dato['codigo']."'>Borrar</a></button></td>";
                    echo "<td><input class='btnAceptar' type='submit' name='btnModificar' value='Aceptar'></td>";
                    echo "</form>";
                    echo "</td>";
                    echo "<form action='borrar.php' method='post' id='checkForm'>";
                    echo "<td><input type='checkbox' value='".$dato['codigo']."' name='checkboxvar[]' form='checkForm'></td>";
                    echo "</form>";
                    echo "</td>";  
                }
                else{
                    $nombre=$dato["nombre"];
                    echo "<tr>";
                    echo "<td>" . $dato["codigo"] . "</td>";
                    echo "<td>" . $dato["nombre"] . "</td>";
                    echo "<td>" . $dato["telefono"] . "</td>";
                    echo "<td>" . $dato["correo"] . "</td>";
                    echo "<td>" . $dato["fechaNac"] . "</td>";
                    echo "<td><a href='borrar.php?codigo=".$dato['codigo']."'><button class='btnBorrar'>Borrar</button></a></td>";
                    echo "<td><form action='agenda.php' method='post'>";
                    echo "<input type='hidden' value='".$dato['codigo']."' name='editar'>";
                    echo "<input type='hidden' value='".$nombre."' name='nombre'>";
                    echo "<input type='hidden' value='".$nombre."' name='rebusqueda'>";
                    echo "<input class='btnModificar' type='submit' name='btnEditar2' value='Editar'>";
                    echo "</form>";
                    echo "</td>";
                }
            }
        }
        else{
            echo "<div class='noEncontrado'><h3>No se ha econtrado ningun contacto</h3></div>";
        }
        ?>
        <div>
            <form action='agenda.php' method='post'>
                <input type="hidden" value="atras" name="atras">
                <input class='btnModificar' type="submit" name="btnAtras" value="Ver todos los contactos">
            </form>
        </div>
        <?php
        if(isset($_REQUEST['atras'])){
            header("location: agenda.php");
            die();
        }
    }

else{
    $tabla=$conn->query("SELECT * FROM agenda LIMIT $principioLimit, $registrosPagina;");
    foreach($tabla as $dato){
        if(isset($_POST['btnEditar']) && ($dato["codigo"]==$_POST['editar'])){
                echo "<tr>";
                echo "<form class='formEmpleados' action='editar.php' method='post'>";
                echo "<input type='hidden' name='codigo' value='".$dato['codigo']."'>";
                echo "<input type='hidden' name='urlActual' value='http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]'>";
                echo "<td>" . $dato["codigo"] . "</td>";
                echo "<td> <input type='text' name='nombre' value='".$dato['nombre']."'> </td>";
                echo "<td> <input type='text' name='telefono' value='".$dato['telefono']."'> </td>";
                echo "<td> <input type='text' name='correo' value='".$dato['correo']."'> </td>";
                echo "<td> <input type='date' name='fechaNac' value='".$dato['fechaNac']."'> </td>";
                echo "<td><button class='btnBorrar'><a href='borrar.php?codigo=".$dato['codigo']."'>Borrar</a></button></td>";
                echo "<td><input class='btnAceptar' type='submit' name='btnModificar' value='Aceptar'></td>";
                echo "</form>";
                echo "</td>";
                echo "<form action='borrar.php' method='post' id='checkForm'>";
                echo "<td><input type='checkbox' value='".$dato['codigo']."' name='checkboxvar[]' form='checkForm'></td>";
                echo "</form>";
                echo "</td>";  
            }
            else{
                echo "<tr>";
                echo "<td>" . $dato["codigo"] . "</td>";
                echo "<td>" . $dato["nombre"] . "</td>";
                echo "<td>" . $dato["telefono"] . "</td>";
                echo "<td>" . $dato["correo"] . "</td>";
                echo "<td>" . $dato["fechaNac"] . "</td>";
                echo "<td><a href='borrar.php?codigo=".$dato['codigo']."'><button class='btnBorrar'>Borrar</button></a></td>";
                echo "<td>";
                echo "<form action='' method='post'>";
                echo "<input type='hidden' value='".$dato['codigo']."' name='editar'>";
                echo "<input class='btnModificar' type='submit' name='btnEditar' value='Editar'>";
                echo "</form>";
                echo "<form action='borrar.php' method='post' id='checkForm'>";
                echo "<td><input type='checkbox' value='".$dato['codigo']."' name='checkboxvar[]' form='checkForm'></td>";
                echo "</form>";
                echo "</td>";    
            }  
    }    
}
    echo "";
    echo "</table>";
?>
    <div class="buscar">
        <h3>Buscar Contacto</h3>
        <form action='agenda.php' method='post'>
        <input type="text" placeholder="Nombre" name="nombre"><br>
        <input class='btnBuscar' type="submit" name="buscar" value="Buscar">
        </form>
    </div>
    
    <div class="cerrarSesion">
        <button class='btnBorrarVarios' type='submit' form='checkForm'>Borrar Seleccion</button>
        <form action='agenda.php' method='post'>
        <input type="hidden" value="cerrar" name="cerrar">
        <input class='btnCerrarSesion' type="submit" name="btnEnviar" value="Cerrar Sesión">
        </form>
    </div>

<?php
    //cel() redondea el resultado

    //***************************************PAGINACION********************************/
    echo "<div class='paginas'>Paginas<h3>";
    for($i=1; $i<=$totalPaginas; $i++){
        echo "<a class='numeros' href='?pagina=" . $i . "'> " . $i . "</a> ";
    }
    echo "</h3></div>";

}
else{
    header("location:index.php");
}
?>
</body>
</html>
