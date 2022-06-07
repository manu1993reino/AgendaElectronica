<?php
include "conexion_a_bbdd.php";

if(isset($_GET['codigo']) || isset($_GET['borrarVarios'])){
    $consulta = "USE agenda; DELETE FROM agenda WHERE codigo=:codigo;";
    $datos = $conn->prepare($consulta);
    $datos->bindParam(':codigo', $_GET["codigo"]);
    $datos->execute();
}

header("location: agenda.php");
die();

?>