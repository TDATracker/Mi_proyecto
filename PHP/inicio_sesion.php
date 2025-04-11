<?php
// Incluye el archivo de conexión a la base de datos
require('./util/conexion.php'); 

// Manejo de la solicitud POST para iniciar sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $contrasena = trim($_POST["contrasena"]);

    // Preparar la consulta SQL para verificar el usuario
    if (isset($email) && isset($contrasena)) {
        $sql = $_conexion->prepare("SELECT * FROM Usuario WHERE Email = ?");
        $sql->bind_param("s", $email);
        $sql->execute();
        $resultado = $sql->get_result();
        $_conexion->close();

        // Verificar si el usuario existe
        if ($resultado->num_rows == 0) {
            $err_email = "El correo $email no está registrado";
        } else {
            $datos_usuario = $resultado->fetch_assoc();

            // Verificar si la contraseña es correcta
            $acceso_concedido = password_verify($contrasena, $datos_usuario["Contrasena"]);
            if ($acceso_concedido) {
                // Iniciar sesión y redirigir
                session_start();
                $_SESSION["usuario"] = $datos_usuario["Nombre"];
                header("location:../dashboard.php");
                exit;
            } else {
                $err_contrasena = "La contraseña es incorrecta";

            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <!-- Enlace a Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Enlace para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Iniciar sesión</h1>
        
        <a class="nav-link btn btn-primary w-100" href="../index.html">Inicio</a>
        
        <!-- Contenedor principal -->
        <div class="container d-flex justify-content-center align-items-center vh-100">
            <!-- Tarjeta con sombra -->
            <div class="card p-4 shadow-lg" style="width: 350px;">
                <h3 class="text-center mb-3">Iniciar Sesión</h3>
                <!-- Formulario de inicio de sesión -->
                <form id="login-form" action="./inicio_sesion.php" method="post" novalidate>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Ingrese su correo" required>
                        </div>
                        <!-- Mostrar mensaje de error si el correo no está registrado -->
                        <?php if (isset($err_email)) echo "<span class='error'>$err_email</span>"; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                            <input type="password" class="form-control" id="password" name="contrasena" placeholder="Ingrese su contraseña" required>
                        </div>
                        <!-- Mostrar mensaje de error si la contraseña es incorrecta -->
                        <?php if (isset($err_contrasena)) echo "<span class='error'>$err_contrasena</span>"; ?>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                </form>            

                <!-- Recuperar la contraseña -->
                <div class="text-center mt-3">
                    <a href="#">¿Olvidaste tu contraseña?</a>
                </div>

                <div class="text-center mt-3">
                    <a href="../registro_usuario.html">Registrarse</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Script de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
