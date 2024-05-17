<?php
/*
 * Este archivo maneja la lógica de inserción de reservas en la base de datos.
 * Se verifica si se recibió una solicitud POST y se obtienen y escapan las variables del formulario.
 * Luego se inserta la reserva en la base de datos y se devuelve una respuesta de éxito o error.
 */

// Incluir el archivo de conexión a la base de datos
include ("conexion.php");

// Verificar si la solicitud es de tipo POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener y escapar las variables del formulario
    $vuelo = mysqli_real_escape_string($conexion, $_POST['vuelo']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $sexo = mysqli_real_escape_string($conexion, $_POST['sexo']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);

    // Insertar la reserva en la base de datos
    $insertar_reserva = "INSERT INTO Reserva (codigoReserva, Vuelo, Pasajero) VALUES (UUID(), '$vuelo', (SELECT identificacion FROM Pasajero WHERE email = '$email'))";
    mysqli_query($conexion, $insertar_reserva) or die('Error al insertar la reserva: ' . mysqli_error($conexion));

    // Devolver una respuesta de éxito
    echo "¡Reserva realizada con éxito!";
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>
