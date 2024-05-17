// Función para buscar vuelos
function buscarVuelos() {
    var origen = document.getElementById("ciudado").value;
    var destino = document.getElementById("ciudadd").value;

    // Crear una instancia de XMLHttpRequest
    var xhr = new XMLHttpRequest();

    // Abrir una conexión con el servidor
    xhr.open("POST", "homepage.blade.php", true);

    // Configurar la cabecera de la solicitud
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Función para manejar la respuesta del servidor
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            // Actualizar la tabla de vuelos con la respuesta del servidor
            var tablaVuelos = document.getElementById("tabla-vuelos");
            tablaVuelos.innerHTML = xhr.responseText;
        }
    };

    // Enviar la solicitud al servidor
    xhr.send("origen=" + encodeURIComponent(origen) + "&destino=" + encodeURIComponent(destino));
}

function realizarCompra() {
    var numeroTarjeta = document.getElementById('numeroTarjeta').value;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/verificar_tarjeta.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            if (xhr.responseText === 'existente') {
                alert('Tarjeta aprobada. Compra realizada exitosamente. ¡Gracias por elegir nuestros servicios!');
            } else {
                alert('Tarjeta no válida. Por favor, verifique el número de tarjeta.');
            }
        }
    };
    xhr.send("numeroTarjeta=" + encodeURIComponent(numeroTarjeta));
}




// Función para inicializar la página
function init() {
    // Agregar un evento de escucha al botón "Buscar"
    document.getElementById("buscar").addEventListener("click", buscarVuelos);

    // Agregar un evento de escucha al botón "Reservar"
    document.getElementById("reservar").addEventListener("click", realizarCompra);
}

// Ejecutar la función init cuando se cargue la página
window.onload = init;







// Ejecutar la función init cuando se cargue la página
window.onload = init;