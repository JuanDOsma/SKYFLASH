<?php
/*
 * Este archivo configura la conexión a la base de datos y verifica su estado.
 * Se establecen los parámetros de conexión, se crea la conexión y se verifica su éxito.
 * En caso de fallo, se muestra un mensaje de error.
 */
$servername = "localhost";  // Nombre del servidor
$username = "root";         // Nombre de usuario
$password = "RootJ899";     // Contraseña del usuario
$database = "skyflash";     // Nombre de la base de datos

// Crear conexión
$conexion = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

?>
