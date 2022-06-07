<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/index.css">
    <style>

    table {
        color:black;
        background: #f5f5f5;
        border-collapse: separate;
        box-shadow: inset 0 1px 0 #fff;
        font-size: 12px;
        line-height: 24px;
        text-align: center;
        width: 80%;
        margin-top: 50px;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 50px;
    }	

    th {
        background-color: #3498DB;
        box-shadow: inset 0 1px 0 #999;
        color: #fff;
        font-weight: bold;
        padding: 10px 15px;
        position: relative;
        text-shadow: 0 1px 0 #000;
        width:120px;	
    }

    td {
        padding: 10px 15px;
        position: relative;
        transition: all 300ms;
        width: 120px;
    }

    td:first-child {
        box-shadow: inset 1px 0 0 #fff;
    }	

    td:last-child {
        box-shadow: inset -1px 0 0 #fff;
    }	

    tr:nth-child(odd) td {
        background: #f1f1f1;	
    }

    .aniadir{
        color:#3498DB;
        background: #f5f5f5;
        border-collapse: separate;
        box-shadow: inset 0 1px 0 #fff;
        font-size: 20px;
        line-height: 24px;
        text-align: center;
        width: 250px;
        margin-left: auto;
        margin-right: auto;
    }

    .buscar{
        position:relative;
        color:#3498DB;
        background: #f5f5f5;
        border-collapse: separate;
        box-shadow: inset 0 1px 0 #fff;
        font-size: 20px;
        line-height: 24px;
        text-align: center;
        width: 250px;
        margin-left: 10%;
        margin-right: 90%;
        margin-top:-20px;
    }

    .cerrarSesion{
        margin-left: 84%;
        margin-right:16%;
        margin-top: -70px;
  
    }

    .borrarVarios{
        margin-left: 84%;
        margin-right:16%;
        margin-top: 0px;
  
    }

    .paginas{
        margin-left: 47%;
        color:#3498DB;
        background: #f5f5f5;
        box-shadow: inset 0 1px 0 #fff;
        font-size: 20px;
        line-height: 24px;
        text-align: center;
        width: 100px;
        margin-top:-70px;

    }

    .numeros{
        text-decoration:none;
        color:#3498DB;
    }

    .insertRegistro{
        color:#3498DB;
        background: #f5f5f5;
        border-collapse: separate;
        box-shadow: inset 0 1px 0 #fff;
        font-size: 20px;
        line-height: 24px;
        text-align: center;
        width: 250px;
        margin-left: auto;
        margin-right: auto;
    }

    .bienvenida{
        color:#3498DB;
    }


    .btnModificar {
        width: 70px;
        height: 30px;
        color: #fff;
        padding: 5px 10px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        display: inline-block;
        outline: none;
        border-radius: 5px;
        border: none;
        background: #3498DB;
        box-shadow: 0 5px #4433ff
    }

    .btnAceptar {
        width: 70px;
        height: 30px;
        color: #fff;
        padding: 5px 10px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        display: inline-block;
        outline: none;
        border-radius: 5px;
        border: none;
        background: #00bb2b;
        box-shadow: 0 5px #00801d
    }

    a{
        text-decoration:none;
        color:white;
        
    }

    .btnBorrar{
        border:none;
        border-radius: 5px;
        width: 70px;
        height: 30px;
        background-color: #f51e1e;
        box-shadow: 0 5px #a00000
    }

    </style>
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
        <input type="submit" name="insertar" value="Añadir">
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
                echo "<div class='insertRegistro'>ERROR 01: Alguno de los campos introducidos no es correcto.</div>";
            }
        }
        catch(PDOException $e){
            echo "<div class='insertRegistro'>ERROR 01: Alguno de los campos introducidos no es correcto.</div>";
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
        <th colspan="3">Herramientas</th>

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

 
    echo "<div class='numContactos'><h3> Tienes un total de $totalFilas contactos</h3></div>";
    $totalPaginas=ceil($totalFilas/$registrosPagina);
    $principioLimit=($page-1)*$registrosPagina;
    

    if(isset($_REQUEST['buscar'])){
        $consulta = "SELECT * FROM agenda WHERE nombre LIKE '%" . $_POST['nombre'] . "%' LIMIT $principioLimit, $registrosPagina;";
        $datos = $conn->prepare($consulta); 
        $datos->execute();
        $totalBusquedas = $datos->rowCount();
        if($totalBusquedas>=1){
            foreach($datos as $dato){
                echo "<tr>";
                echo "<td>" . $dato["codigo"] . "</td>";
                echo "<td>" . $dato["nombre"] . "</td>";
                echo "<td>" . $dato["telefono"] . "</td>";
                echo "<td>" . $dato["correo"] . "</td>";
                echo "<td>" . $dato["fechaNac"] . "</td>";
                echo "<td><button class='btnBorrar'><a href='borrar.php?codigo=".$dato['codigo']."'>BORRAR</a></button></td>";
                echo "<td>
                            <form action='agenda.php' method='post'>
                                <input type='hidden' value='".$dato['codigo']."' name='editar'>
                                <input class='btnModificar' type='submit' name='btnEditar' value='Editar'>
                            </form>
                        </td>";
            }
        }
        else{
            echo "No existe ningun registro que contenga esos caracteres en el nombre";
        }
        ?>
        <div>
            <form action='agenda.php' method='post'>
                <input type="hidden" value="atras" name="atras">
                <input type="submit" name="btnAtras" value="Ver todos los contactos">
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
                echo "<tr>
                        <form class='formEmpleados' action='editar.php' method='post'>
                        <input type='hidden' name='codigo' value='".$dato['codigo']."'>
                        <input type='hidden' name='urlActual' value='http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]'>";
                echo  "<td>" . $dato["codigo"] . "</td>";
                echo "<td> <input type='text' name='nombre' value='".$dato['nombre']."'> </td>";
                echo "<td> <input type='text' name='telefono' value='".$dato['telefono']."'> </td>";
                echo "<td> <input type='text' name='correo' value='".$dato['correo']."'> </td>";
                echo "<td> <input type='date' name='fechaNac' value='".$dato['fechaNac']."'> </td>";
                echo "<td><button class='btnBorrar'><a href='borrar.php?codigo=".$dato['codigo']."'>BORRAR</a></button></td>";
                echo "<td><input class='btnAceptar' type='submit' name='btnModificar' value='Aceptar'></td>
                        </form>
                        </td>";
            }
            else{
                echo "<tr>";
                echo "<td>" . $dato["codigo"] . "</td>";
                echo "<td>" . $dato["nombre"] . "</td>";
                echo "<td>" . $dato["telefono"] . "</td>";
                echo "<td>" . $dato["correo"] . "</td>";
                echo "<td>" . $dato["fechaNac"] . "</td>";
                echo "<td><button class='btnBorrar'><a href='borrar.php?codigo=".$dato['codigo']."'>BORRAR</a></button></td>";
                echo "<td>
                        <form action='' method='post'>
                            <input type='hidden' value='".$dato['codigo']."' name='editar'>
                            <input class='btnModificar' type='submit' name='btnEditar' value='Editar'>
                        </form>
                    </td>";    
            }  
        }
    }
    
?>  
    <div class="buscar">
        <h3>Buscar Contacto</h3>
        <form action='agenda.php' method='post'>
        <input type="text" placeholder="Nombre" name="nombre"><br>
        <input type="submit" name="buscar" value="Buscar">
        </form>
    </div>


    <div class="cerrarSesion">
        <form action='agenda.php' method='post'>
        <input type="hidden" value="cerrar" name="cerrar">
        <input type="submit" name="btnEnviar" value="Cerrar Sesión">
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
