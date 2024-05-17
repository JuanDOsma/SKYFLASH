<?php
/*
 * Este archivo verifica la existencia de un número de tarjeta en la base de datos.
 * Se conecta a la base de datos a través del archivo de conexión 'conexion.php'.
 * Luego, obtiene el número de tarjeta enviado por el cliente a través de la solicitud POST.
 * Realiza una consulta SQL para verificar si el número de tarjeta existe en la tabla Tarjeta.
 * Si la consulta tiene éxito, devuelve una respuesta indicando si la tarjeta existe o no.
 * En caso de error en la consulta, devuelve un mensaje de error.
 */

// Conexión a la base de datos
include("conexion.php");

// Obtener el número de tarjeta enviado por el cliente
$numeroTarjeta = $_POST['numeroTarjeta'];

// Consulta para verificar si el número de tarjeta existe en la tabla Tarjeta
$sql = "SELECT COUNT(*) AS count FROM Tarjeta WHERE numero = '$numeroTarjeta'";
$resultado = mysqli_query($conexion, $sql);

if ($resultado) {
    $row = mysqli_fetch_assoc($resultado);
    if ($row['count'] > 0) {
        // La tarjeta existe en la base de datos, devolver respuesta 'existente'
        echo 'existente';
    } else {
        // La tarjeta no existe en la base de datos, devolver respuesta 'no_existente'
        echo 'no_existente';
    }
} else {
    // Error en la consulta SQL
    echo 'error_consulta';
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>
