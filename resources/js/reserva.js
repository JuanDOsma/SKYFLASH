// Función para realizar la reserva después de aprobar la tarjeta
function realizarReserva() {
    // Obtener los datos necesarios para la reserva
    var vueloSeleccionado = document.getElementById("numeroVuelo").innerText;
    var pasajeroNombre = document.getElementById("nombrePasajero").value;
    var pasajeroTelefono = document.getElementById("telefonoPasajero").value;
    var pasajeroEmail = document.getElementById("emailPasajero").value;

    // Crear una instancia de XMLHttpRequest
    var xhr = new XMLHttpRequest();

    // Abrir una conexión con el servidor
    xhr.open("POST", "../php/reservar_vuelo.php", true);

    // Configurar la cabecera de la solicitud
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Función para manejar la respuesta del servidor
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            // Mostrar mensaje de éxito o error según la respuesta del servidor
            alert(xhr.responseText);
            // Actualizar la tabla de reservas en la página
            actualizarTablaReservas();
        }
    };

    // Enviar la solicitud al servidor con los datos de la reserva
    xhr.send("vuelo=" + encodeURIComponent(vueloSeleccionado) + "&nombre=" + encodeURIComponent(pasajeroNombre) +
        "&telefono=" + encodeURIComponent(pasajeroTelefono) + "&email=" + encodeURIComponent(pasajeroEmail));
}

// Función para actualizar la tabla de reservas en la página
function actualizarTablaReservas() {
    // Lógica para actualizar la tabla de reservas
    // Puedes usar AJAX para cargar los datos actualizados y mostrarlos en la tabla de reservas
    alert("La tabla de reservas se actualizará automáticamente.");
}
