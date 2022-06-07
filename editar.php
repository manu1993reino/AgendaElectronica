<?php
include "conexion_a_bbdd.php";

if(isset($_POST['btnModificar'])){
    $consulta = "use agenda; UPDATE agenda SET nombre=:nombre, correo=:correo, telefono=:telefono, fechaNac=:fechaNac WHERE codigo=:codigo;";
    $datos=$conn->prepare($consulta); 
    $datos->bindParam(':codigo', $_POST['codigo']);
    $datos->bindParam(':nombre', $_POST['nombre']);
    $datos->bindParam(':correo', $_POST['correo']);
    $datos->bindParam(':telefono', $_POST['telefono']);
    $datos->bindParam(':fechaNac', $_POST['fechaNac']);
    $datos->execute();
}


if(isset($_POST['urlActual'])){
    header('Location:'.$_POST['urlActual']);
}
else{
    header('Location:agenda.php');
}
die();

?>