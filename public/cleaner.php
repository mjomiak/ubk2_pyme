

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Optimizador Hosting Compartido</title>

  <!-- Enlaces al CDN de Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">




<hr>


<?php
$nombreScript = basename($_SERVER['PHP_SELF']);


$userhash='';
$passhash='';
$directorioActual = getcwd();
//echo '$directorioActual';

//Cargar la contraseña---------------------------------------------------------------
$nombreArchivo = $directorioActual.DIRECTORY_SEPARATOR.'.note';

// Intentar abrir el archivo en modo lectura
$archivo = fopen($nombreArchivo, 'r');

// Verificar si se abrió correctamente el archivo
if ($archivo) {
    // Leer la primera línea del archivo
    $primeraLinea = fgets($archivo);

    // Leer la segunda línea del archivo
    $segundaLinea = fgets($archivo);

    // Cerrar el archivo
    fclose($archivo);

    // Imprimir o hacer lo que necesites con la primera línea
    $userhash = trim($primeraLinea);
    $passhash = trim($segundaLinea);
  
} else {
    // Manejar el caso en que no se pueda abrir el archivo
    echo "No se pudo abrir el archivo.";
}



//-----------------------------------------------------------------

// if (password_verify($contrasenaPlana, $hashGuardado))



if(isset($_POST['optimiza'])&&($_POST['optimiza']=='Acceder')){
if(isset($_POST['user']) && password_verify($_POST['user'],$userhash) && isset($_POST['pass'])&& password_verify($_POST['pass'],$passhash)){

echo '<hr>';
echo '<h5>Optimización y prueba para hosting compartido laravel</h5>';
echo '<hr>';


chdir('..');
$directorioNuevo = getcwd();
$fullPath = $directorioNuevo.DIRECTORY_SEPARATOR.'artisan';
//echo $fullPath;

//str_replace(que_buscar, reemplazar_con, donde_buscar);

leerArchivoLineaPorLinea($directorioNuevo.DIRECTORY_SEPARATOR.'.env');

$fullCommand = "php $fullPath optimize:clear";
$result = shell_exec($fullCommand);
$result=nl2br($result);
$result=preg_replace('/<br\s*\/?>+/', '<br>',$result);
$result=str_replace('DONE', '<span style="color:green"><b>DONE</b></span>',$result);
echo  '<br><u> Resultado de <i><b>php artisan optimize:clear</b></i>:</u> <br>';
echo'<div style="background-color:black;color:white;margin:10px;padding:10px">';
echo $result;
echo '</div>';



$fullCommand = "php $fullPath optimize";
$result = shell_exec($fullCommand);
$result=nl2br($result);
$result=preg_replace('/<br\s*\/?>+/', '<br>',$result);
$result=str_replace('DONE', '<span style="color:green"><b>DONE</b></span>',$result);
echo '<br><u> Resultado de <i><b>php artisan optimize</b></i>:</u> <br> ';
echo'<div style="background-color:black;color:white;margin:10px;padding:10px">';
echo $result;
echo '</div>';


$fullCommand = "php $fullPath config:clear";
$result = shell_exec($fullCommand);
$result=nl2br($result);
$result=preg_replace('/<br\s*\/?>+/', '<br>',$result);
$result=str_replace('DONE', '<span style="color:green"><b>DONE</b></span>',$result);
echo '<br><u> Resultado de <i><b>php artisan config:clear</b></i>:</u> <br> ';
echo'<div style="background-color:black;color:white;margin:10px;padding:10px">';
echo $result;
echo '</div>';

phpinfo();
}
} else{ 
    ?>
    <form action="<?php echo $nombreScript?>" method="post">
    <div class="card">
      <div class="card-header">
      <h5>Optimización y prueba para hosting compartido laravel</h5>
      </div>
      <div class="card-body">
        <div class="alert alert-info">
            Para ejecutar la optimización, ingrese usuario y contraseña.

        </div>
      <input type="text" name="user">
      <input type="password" name="pass">
      </div>
      <div class="card-footer">
        <input type="submit" value="Acceder" name="optimiza">
      </div>
    </div>
  </div>
  </form>
  <?php
}

?>




</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php
function leerArchivoLineaPorLinea($rutaArchivo) {
    $app_url='';
    $db_host='';
    $db_database='';
    $db_username='';
    $db_password='';
    
    // Abre el archivo en modo lectura
    $archivo = fopen($rutaArchivo, 'r');

    // Verifica si el archivo se abrió correctamente
    if ($archivo) {
        // Itera sobre cada línea del archivo
        while (($linea = fgets($archivo)) !== false) {
            // Procesa la línea como desees
            if (strpos($linea, 'APP_URL') !== false) {
                // Si sí, agrega la línea a la variable
                $lineasConString = $linea;
                $partes = explode("=", $lineasConString);
                $app_url=trim($partes[1]);
                echo 'APP_URL: '.$app_url.' <a href="'.$app_url.'" class="btn btn-sm btn-success" target="_blank"> Ir al sitio </a><br>';
            }
            if (strpos($linea, 'DB_HOST') !== false) {
                // Si sí, agrega la línea a la variable
                $lineasConString = $linea;
                $partes = explode("=", $lineasConString);
                $db_host=trim($partes[1]);
                echo 'DB HOST: '.$db_host.'<br>';
            }
            if (strpos($linea, 'DB_DATABASE') !== false) {
                // Si sí, agrega la línea a la variable
                $lineasConString = $linea;
                $partes = explode("=", $lineasConString);
                $db_database=trim($partes[1]);
                echo 'DB DATABASE: '.$db_database.'<br>';
            }
            if (strpos($linea, 'DB_USERNAME') !== false) {
                // Si sí, agrega la línea a la variable
                $lineasConString = $linea;
                $partes = explode("=", $lineasConString);
                $db_username=trim($partes[1]);
                echo 'DB USERNAME: '.$db_username.'<br>';
            }
            if (strpos($linea, 'DB_PASSWORD') !== false) {
                // Si sí, agrega la línea a la variable
                $lineasConString = $linea;
                $partes = explode("=", $lineasConString);
                $db_password=trim($partes[1]);
                echo 'DB PASSWORD: '.$db_password.'<br>';
            }
            if (strpos($linea, 'MAIL_MAILER') !== false) {
                // Si sí, agrega la línea a la variable
                $lineasConString = $linea;
                $partes = explode("=", $lineasConString);
                $mailer=trim($partes[1]);
                echo 'MAIL MAILER: '.$mailer.'<br>';
            }
           
           
        }

        // Cierra el archivo al finalizar
        fclose($archivo);
    } else {
        // Maneja el caso en el que no se pudo abrir el archivo
        echo "No se pudo abrir el archivo: $rutaArchivo";
    }
    $conn = new mysqli($db_host, $db_username, $db_password, $db_database);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    
    echo '<div class="alert alert-success">Conexión exitosa a la base de datos</div>';
    
    // Cerrar la conexión
    $conn->close();


}

// Uso de la función


?>