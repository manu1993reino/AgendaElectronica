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
        background-color: black;
        border-left: 1px solid #555;
        border-right: 1px solid #777;
        border-top: 1px solid #555;
        border-bottom: 1px solid #333;
        box-shadow: inset 0 1px 0 #999;
        color: #fff;
        font-weight: bold;
        padding: 10px 15px;
        position: relative;
        text-shadow: 0 1px 0 #000;	
    }


    th:first-child {
        border-left: 1px solid #777;	
        box-shadow: inset 1px 1px 0 #999;
    }

    th:last-child {
        box-shadow: inset -1px 1px 0 #999;
    }

    td {
        border-right: 1px solid #fff;
        border-left: 1px solid #e8e8e8;
        border-top: 1px solid #fff;
        border-bottom: 1px solid #e8e8e8;
        padding: 10px 15px;
        position: relative;
        transition: all 300ms;
    }

    td:first-child {
        box-shadow: inset 1px 0 0 #fff;
    }	

    td:last-child {
        border-right: 1px solid #e8e8e8;
        box-shadow: inset -1px 0 0 #fff;
    }	

    tr:nth-child(odd) td {
        background: #f1f1f1;	
    }
    </style>
</head>
<body>

<?php

include "conexion_a_bbdd.php";

$columnas=$conn->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'agenda' AND TABLE_NAME = 'agenda'");
$registroColmn = $columnas->fetchAll(PDO::FETCH_ASSOC);
echo "<table border>";
foreach($registroColmn as $columnas) {
    foreach($columnas as $nombres)

    echo "<th>" .strtoupper($nombres) ."</th>";
    if($nombres=="fechaNac"){
        echo "<th colspan='2'> HERRAMIENTAS </th>";
    }
    } 

$tabla=$conn->query("SELECT * FROM agenda");
    foreach($tabla as $indice=>$dato){
        echo "<tr>";
        echo "<td>" . $dato["codigo"] . "</td>";
        echo "<td>" . $dato["nombre"] . "</td>";
        echo "<td>" . $dato["telefono"] . "</td>";
        echo "<td>" . $dato["correo"] . "</td>";
        echo "<td>" . $dato["fechaNac"] . "</td>";
        echo "<td>
        <form action='agenda.php' method='post'>
            <input type='hidden' value='".$dato['codigo']."' name='borrar'>
            <input type='submit' name='btnBorrar' value='BORRAR'>
        </form>
        ";
        
        "</td>";
    }

echo "</table>";

if(isset($_POST['borrar'])){
    $consulta = "DELETE FROM agenda WHERE codigo=:codigo;";
    $datos = $conn->prepare($consulta);
    $datos->bindParam(':codigo', $_POST["borrar"]);
    $datos->execute();
}
?>

</body>
</html>