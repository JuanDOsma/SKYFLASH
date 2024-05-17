<?php

/**
 * Sistema de Reservas de Vuelos
 * 
 * Este archivo PHP maneja el sistema de reservas de vuelos, permitiendo a los usuarios
 * buscar vuelos disponibles entre aeropuertos y visualizar sus reservas existentes.
 * 
 * Funcionalidades:
 * - Conexión a la base de datos MySQL.
 * - Consulta de aeropuertos para poblar las opciones de origen y destino.
 * - Manejo de solicitudes AJAX para buscar vuelos disponibles según el origen y destino seleccionados.
 * - Generación de la tabla de vuelos disponibles.
 * - Función para generar la tabla HTML de reservas de vuelos.
 * 
 * Requisitos:
 * - Archivo de conexión a la base de datos: ../php/conexion.php.
 * - Archivos CSS y JavaScript para la presentación y la interacción del usuario.
 */

include ("../php/conexion.php");

// Inicializar la variable $tabla con una cadena vacía
$tabla = "";

// Consulta en ciudad
$consulta_ciudad = "SELECT * FROM Aeropuerto";
$resultado_c = mysqli_query($conexion, $consulta_ciudad) or die("No se pudo ejecutar la consulta");

$op = "";
while ($linea_c = mysqli_fetch_array($resultado_c, MYSQLI_ASSOC)) {
    $op .= "<option value='" . $linea_c['nombre'] . "'>" . $linea_c['nombre'] . "</option>";
}

// Manejar la solicitud AJAX
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $origen = mysqli_real_escape_string($conexion, $_POST['origen']);
    $destino = mysqli_real_escape_string($conexion, $_POST['destino']);

    // Consulta para obtener los vuelos que coincidan con el origen y destino
    $consulta_vuelos = "SELECT V.numero, V.Aerolinea, V.aeropuertoOrigen, V.aeropuertoDestino, 
                            Hs.fecha AS fechaSalida, Hl.fecha AS fechaLlegada
                            FROM Vuelo V
                            INNER JOIN Horario Hs ON V.fechaSalida = Hs.id
                            INNER JOIN Horario Hl ON V.fechaLlegada = Hl.id
                            WHERE V.aeropuertoOrigen = '$origen' AND V.aeropuertoDestino = '$destino'";
    $resultado_vuelos = mysqli_query($conexion, $consulta_vuelos) or die('No se pudo ejecutar la consulta de vuelos: ' . mysqli_error($conexion));

    // Construir el HTML con los vuelos encontrados
    $tabla_vuelos = "";
    if (mysqli_num_rows($resultado_vuelos) > 0) {
        while ($linea_vuelos = mysqli_fetch_array($resultado_vuelos, MYSQLI_ASSOC)) {
            $tabla_vuelos .= "<tr>";
            $tabla_vuelos .= "<td>{$linea_vuelos['numero']}</td>";
            $tabla_vuelos .= "<td>{$linea_vuelos['Aerolinea']}</td>";
            $tabla_vuelos .= "<td>{$linea_vuelos['aeropuertoOrigen']}</td>";
            $tabla_vuelos .= "<td>{$linea_vuelos['aeropuertoDestino']}</td>";
            $tabla_vuelos .= "<td>{$linea_vuelos['fechaSalida']}</td>";
            $tabla_vuelos .= "<td>{$linea_vuelos['fechaLlegada']}</td>";
            $tabla_vuelos .= "<td><button onclick='realizarCompra()'>Reservar</button></td>";
            $tabla_vuelos .= "</tr>";
        }

    } else {
        $tabla_vuelos .= "<tr><td colspan='6'>No se encontraron vuelos disponibles.</td></tr>";
    }

    // Devolver el HTML construido
    echo $tabla_vuelos;
    exit; // Detener la ejecución del script después de devolver la tabla de vuelos
}

// Función para generar la tabla HTML de reservas
function generarTablaReservas($resultado_reserva) {
    echo '<table bgcolor="white">';
    echo '<tr>';
    echo '<th>Codigo de reserva</th>';
    echo '<th>Vuelo</th>';
    echo '<th>Nombre de pasajero</th>';
    echo '<th>Numero de telefono</th>';
    echo '<th>Codigo de pasajero</th>';
    echo '<th>Numero de asiento</th>';
    echo '<th>Ciudad de origen</th>';
    echo '<th>Ciudad de destino</th>';
    echo '<th>Mostrar</th>';
    echo '<th>Eliminar</th>';
    echo '</tr>';

    while ($linea_reserva = mysqli_fetch_array($resultado_reserva, MYSQLI_ASSOC)) {
        echo "<tr>";
        echo "<td>{$linea_reserva['codigoReserva']}</td>";
        echo "<td>{$linea_reserva['Vuelo']}</td>";
        echo "<td>{$linea_reserva['nombre']}</td>";
        echo "<td>{$linea_reserva['numeroTelefonico']}</td>";
        echo "<td>{$linea_reserva['codigoPasajero']}</td>";
        echo "<td>{$linea_reserva['fila']}{$linea_reserva['letra']}</td>"; 
        echo "<td>{$linea_reserva['aeropuertoOrigen']}</td>";
        echo "<td>{$linea_reserva['aeropuertoDestino']}</td>";
        echo "<td><button onclick='mostrarDetalleReserva({$linea_reserva['codigoReserva']})'>Mostrar</button></td>";
        echo "<td><button onclick='eliminarReserva({$linea_reserva['codigoReserva']})'>Eliminar</button></td>";
        echo "</tr>";
    }

    echo '</table>';
}

// Consulta para obtener la información de la reserva
$consulta_reserva = "SELECT R.codigoReserva, R.Vuelo, P.nombre, P.numeroTelefonico, P.identificacion AS codigoPasajero, A.fila, A.letra, V.aeropuertoOrigen, V.aeropuertoDestino 
FROM Reserva R 
INNER JOIN Pasajero P ON R.Pasajero = P.identificacion 
INNER JOIN Asiento A ON R.Vuelo = A.Vuelo
INNER JOIN Vuelo V ON R.Vuelo = V.numero";

$resultado_reserva = mysqli_query($conexion, $consulta_reserva) or die("Error al obtener la reserva: " . mysqli_error($conexion));

// Llamar a la función para generar la tabla HTML de reservas


// Cerrar la conexión después de realizar todas las operaciones necesarias

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reservas disponibles</title>
    <link href="../css/homepage.css" rel="stylesheet">
    <script src="../js/vuelos.js"></script>
    <script src="../js/reserva.js"></script>
</head>

<body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="collapse navbar-collapse">
            </div><!--/.nav-collapse -->
        </div>
    </div>
    <div class="cabecera">
        <img src="../assets/logo.png" alt="Logo de la empresa">
    </div>
    <div class="separacion"></div>
    <div class="cuerpo formulario-wrapper">
        <h1>Reserva tu pasaje</h1>
        <div class="formulario-wrapper">
            <form>
                <div class="opciones">
                    <div class="titulo">
                        <label id="caption" for="ciudado"><b>Ciudad de origen :</b></label><br>
                        <label id="caption" for="ciudadd"><b>Ciudad de destino :</b></label>
                    </div>
                    <div class="datos">
                        <select name="ciudado" id="ciudado">
                            <?php echo $op; ?>
                        </select><br>
                        <select name="ciudadd" id="ciudadd">
                            <?php echo $op; ?>
                        </select><br>
                    </div>
                    <label for="numeroTarjeta"><b>Número de tarjeta de crédito:</b></label>
                    <input type="text" id="numeroTarjeta" name="numeroTarjeta"
                        placeholder="Ingrese el número de tarjeta"><br>
                    <input id="buscar" type="button" value="Buscar">
                </div>
            </form>
        </div>
        <div class="vuelos">
            <div class="mostrar-vuelos formulario-wrapper2">
                <h2>Vuelos encontrados:</h2>
                <table border="1">
                    <tr>
                        <th>Número de vuelo</th>
                        <th>Aerolínea</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Fecha de salida</th>
                        <th>Fecha de llegada</th>
                    </tr>
                    <tbody id="tabla-vuelos">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="reserva">
    <h2>Vuelos reservados</h2>
    <table bgcolor="white">
        <tr>
            <th>Codigo de reserva</th>
            <th>Vuelo</th>
            <th>Nombre de pasajero</th>
            <th>Numero de telefono</th>
            <th>Codigo de pasajero</th>
            <th>Numero de asiento</th>
            <th>Ciudad de origen</th>
            <th>Ciudad de destino</th>
            <th>Mostrar</th>
            <th>Eliminar</th>
        </tr>
        <?php
        $consulta_reserva = "SELECT R.codigoReserva, R.Vuelo, P.nombre, P.numeroTelefonico, P.identificacion AS codigoPasajero, A.fila, A.letra, V.aeropuertoOrigen, V.aeropuertoDestino 
        FROM Reserva R 
        INNER JOIN Pasajero P ON R.Pasajero = P.identificacion 
        INNER JOIN Asiento A ON R.Vuelo = A.Vuelo
        INNER JOIN Vuelo V ON R.Vuelo = V.numero";

        $resultado_reserva = mysqli_query($conexion, $consulta_reserva) or die("Error al obtener la reserva: " . mysqli_error($conexion));

        while ($linea_reserva = mysqli_fetch_array($resultado_reserva, MYSQLI_ASSOC)) {
            echo "<tr>";
            echo "<td>{$linea_reserva['codigoReserva']}</td>";
            echo "<td>{$linea_reserva['Vuelo']}</td>";
            echo "<td>{$linea_reserva['nombre']}</td>";
            echo "<td>{$linea_reserva['numeroTelefonico']}</td>";
            echo "<td>{$linea_reserva['codigoPasajero']}</td>";
            echo "<td>{$linea_reserva['fila']}{$linea_reserva['letra']}</td>"; 
            echo "<td>{$linea_reserva['aeropuertoOrigen']}</td>";
            echo "<td>{$linea_reserva['aeropuertoDestino']}</td>";
            echo "<td><button onclick='mostrarDetalleReserva({$linea_reserva['codigoReserva']})'>Mostrar</button></td>";
            echo "<td><button onclick='eliminarReserva({$linea_reserva['codigoReserva']})'>Eliminar</button></td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

    </div>
</body>
<footer>
    <span>SKYFLASH - Derechos reservados &copy; 2024 |</span>
    <span>Teléfono: +1-800-123-4567 | Email: info@skyflash.com</span>
    <span>| Av. Viajero Feliz #123, Silicon Valley, USA</span>
</footer>



</html>