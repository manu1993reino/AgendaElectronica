<?php
include "conexion_a_bbdd.php";

if(isset($_GET['codigo'])){
    $consulta = "USE agenda; DELETE FROM agenda WHERE codigo=:codigo;";
    $datos = $conn->prepare($consulta);
    $datos->bindParam(':codigo', $_GET["codigo"]);
    $datos->execute();
}
//echo $_GET['codigo'];

if(isset($_POST['checkboxvar'])){
    $codigos = implode(',', $_POST['checkboxvar']);
    $consulta = "USE agenda; DELETE FROM agenda WHERE codigo IN ($codigos);";
    $datos = $conn->prepare($consulta);
    $datos->execute();
}

header('Location:'.$_SERVER['HTTP_REFERER']);
die();

?>