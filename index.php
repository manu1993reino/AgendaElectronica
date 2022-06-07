<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/index.css">
    <style>

    </style>
</head>
<body>
    
<?php
include 'conexion_a_bbdd.php';

if(isset($_COOKIE["usuario"]) && ($_COOKIE["contrasena"])){
    header("location: agenda.php");
}

else{

    ?>

    <div class="login">
            <div class="login-screen">
                <div class="app-title">
                    <h1>Login</h1>
                </div>

    <div class="login-form">
        <form action='index.php' method='post'>
        <input class="login-field" type="text" placeholder="Su ID de usuario" name="usuario"><br>
        <input class="login-field" type="password" placeholder="Contraseña" name="contrasena"><br>
        <input class="btn btn-primary btn-large btn-block"  type="submit" name="btnEnviar" value="Acceder"><br>
        <input type="checkbox" value="recordar" name="recordar"> Mantener sesión iniciada
        </form>
    </div>

    <?php
/*
    $archivo = 'Table_Agenda.txt';
        //Abrimos el archivo en modo lectura
        $fp = fopen($archivo,'r');
        //Leemos el archivo
        $texto = fread($fp, filesize($archivo));
*/
    $query = "CREATE DATABASE IF NOT EXISTS agenda;
    USE agenda; 
        create table if not exists agenda(
            codigo int(6) auto_increment not null,
            nombre varchar(50) not null,
            telefono varchar(12) not null,
            correo varchar(25),
            fechaNac date not null,
            constraint pk_codigo primary key(codigo)
            );
                            
        create table if not exists usuarios(
            usuario varchar(25) not null,
            contrasena varchar(255) not null,
            constraint pk_usuario primary key(usuario)
            );
    ";
    $conn->exec($query);

    $contactos='agenda.txt';
    // Prepar la xonsulta que inserta los datos en la BD 
    $aniadir = $conn->prepare("INSERT IGNORE INTO agenda(codigo,nombre,telefono,correo,fechaNac) VALUES 
    (:codigo,:nombre,:telefono,:correo,:fechaNac)");
    // Abrimos el archivo con el modo lectura 
    $abrirArchivo = fopen($contactos, 'r');

    // file_exists, para decir que si el archivo existe me ejecute el codigo de insercción
    if (file_exists($contactos)){
        
        // feof, comprueba si el puntero a un archivo está al final del archivo
        while (!feof($abrirArchivo)) { 
            // fgets para leer linea de un archivo.
            $contenido = fgets($abrirArchivo); 
            /* Explode para separar la línea en un array usando como delimitador la coma */
            $data = explode(";", $contenido); 
            // Para entender los que tiene '$data[0]' descomentar el print_r de abajo 
            //print_r($data);
            $codigo = $data[0];
            $nombre = $data[1];
            $telefono = $data[2];
            $correo = $data[3];
            $fechaNac = $data[4];
            $aniadir->bindValue(':codigo', $codigo, PDO::PARAM_INT);
            $aniadir->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $aniadir->bindValue(':telefono', $telefono, PDO::PARAM_STR);
            $aniadir->bindValue(':correo', $correo, PDO::PARAM_STR);
            $aniadir->bindValue(':fechaNac', $fechaNac, PDO::PARAM_STR);
            $aniadir->execute();
        }
    }
   else{
        echo 'El archivo de carga no esta disponible';
    }
    //$query = $conn->prepare("$texto");
    //$query->execute();

    $usuarioLogin = "amiguis";
    $contrasenaLogin = "PHP4ever";
    $contrasenaLoginCifrada = password_hash($contrasenaLogin, PASSWORD_DEFAULT);

    $query= "INSERT IGNORE INTO usuarios (usuario, contrasena) VALUES (:usuario, :contrasena);"; 
        $datos=$conn->prepare($query); 
        $datos->bindParam(':usuario', $usuarioLogin);
        $datos->bindParam(':contrasena', $contrasenaLoginCifrada);
        $datos->execute();

    if (isset($_POST["btnEnviar"])){
        //Recogemos en estas dos variables el usuario y la contraseña del formulario de index, por el metodo post.
        $id_usuario = $_POST['usuario'];
        $contrasena = $_POST['contrasena'];
    

        //Hacemos una select en la que recogeremos el empleado_ID, en este caso valdra tanto para usaurio como para contraseña
        $query = $conn->prepare("SELECT * FROM usuarios WHERE usuario=:usuario;");
        $query->bindParam(":usuario", $id_usuario);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        //print_r($result); 

        //echo password_verify($contrasenaLogin, $result["contrasena"]);
        /*echo "<br>";
        echo $result["usuario"];
        echo "<br>";
        print_r($result);
        echo "<br>";*/

        //Entramos en los tres primeros condicionales dependiendo de que cual de las consultas nos haya devuelto datos.
        if($result){
            $verificacionHash = password_verify($contrasena, $result["contrasena"]);
            if($verificacionHash==1){
                session_start();
                $_SESSION["usuario"] = $result["usuario"];
                echo "Bienvenido/a " . $id_usuario;
                if($_POST["recordar"]=="recordar"){
                    setcookie("usuario", $result["usuario"], time() +600);
                    setcookie("contrasena", $result["contrasena"], time() +600);
                }
                header("location: agenda.php");
                die();
            }
            else{
                echo "<div class='mensaje'> Contraseña incorrecta </div>";
            }
        }
        else{echo "<div class='mensaje'> Usuario incorrecto </div>";     
        }
    }
}

?>
        </div>
	</div>
</body>
</html>