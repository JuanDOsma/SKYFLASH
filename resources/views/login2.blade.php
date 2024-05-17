<?php
/**
 * Este script maneja el inicio de sesión de usuarios.
 * Requiere un archivo de conexión 'conexion.php' para interactuar con la base de datos.
 * Utiliza sesiones para mantener la autenticación del usuario.
 */

// Requiere el archivo de conexión
require_once '../php/conexion.php';

// Inicia la sesión
session_start();

$errors = [];

// Verifica que la solicitud sea de tipo POST para procesar los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    echo "Email: $email<br>";
    echo "Password: $password<br>";

    // Verifica que los campos de correo electrónico y contraseña no estén vacíos
    if (empty($email) || empty($password)) {
        $errors[] = "Todos los campos son requeridos.";
    }

    // Verifica la conexión a la base de datos
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Verifica que la conexión esté activa
    if (!$conexion->ping()) {
        die("La conexión no está activa.");
    }

    // Prepara y ejecuta la consulta SQL para buscar al usuario por correo electrónico
    $query = "SELECT id, correo, contrasena FROM usuarios WHERE correo = ? LIMIT 1";
    $stmt = $conexion->prepare($query);
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conexion->error);
    }

    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica si se encontró un usuario
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Compara la contraseña proporcionada con la contraseña almacenada
        if ($password === $user['contrasena']) {
            // Iniciar sesión y redirigir al dashboard
            $_SESSION['usuario'] = $user['correo'];
            header("Location: homepage.blade.php");
            exit;
        } else {
            $errors[] = "Credenciales incorrectas.";
            echo "Contraseña incorrecta.<br>";
        }
    } else {
        $errors[] = "Usuario no encontrado.";
        echo "Usuario no encontrado.<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Título de tu página</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="login-form">
            <img src="../assets/logo.png" alt="Logo de la empresa">
            <h2>Iniciar sesión</h2>

            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST">
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="password-input">
                        <input type="password" id="password" name="password" required>
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    <!-- Aquí se mostrarán los errores -->
                    <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn">Ingresar</button>
            </form>
        </div>
    </div>

    <!-- Tu script JavaScript -->
    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById('password');
            var icon = document.querySelector('.toggle-password i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
