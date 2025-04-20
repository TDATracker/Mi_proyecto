<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <a href="../../dashboardCentro.php" class="btn btn-secondary btn-volver">
        <i class="fas fa-arrow-left"></i>
    </a>
    <?php
    session_start();
    require('../util/conexion.php');

    // Verificar sesión del centro
    if (!isset($_SESSION['centro_id'])) {
        header("Location: ../inicio_sesion.php");
        exit;
    }

    // Variables de error y mensajes
    $error_actual = $error_nueva = "";
    $mensaje_exito = "";
    $mensaje_error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $centro_id = $_SESSION['centro_id'];
        $email = $_SESSION['centro_email'];
        $pass_actual = trim($_POST['pass_actual']);
        $pass_nueva = trim($_POST['pass_nueva']);

        // Validaciones
        if (empty($pass_actual)) {
            $error_actual = "Debes introducir tu contraseña.";
        }
        
        if (empty($pass_nueva)) {
            $error_nueva = "La contraseña es obligatoria.";
        } elseif (strlen($pass_nueva) < 6) {
            $error_nueva = "Mínimo 6 caracteres.";
        } elseif (!preg_match('/[A-Z]/', $pass_nueva)) {
            $error_nueva = "Debe contener al menos una mayúscula.";
        } elseif (!preg_match('/[a-z]/', $pass_nueva)) {
            $error_nueva = "Debe contener al menos una minúscula.";
        } elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $pass_nueva)) {
            $error_nueva = "Falta caracter especial: (!@#$%^&*)";
        } elseif ($pass_actual === $pass_nueva) {
            $error_nueva = "La nueva contraseña no puede ser igual a la actual.";
        }
        
        // Si no hay errores, proceder
        if (empty($error_actual) && empty($error_nueva)) {
            // 1. Primero obtener la contraseña actual del centro
            $query = "SELECT contrasena FROM Centro WHERE Id = ?";
            $stmt = $_conexion->prepare($query);
            $stmt->bind_param('i', $centro_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $centro = $result->fetch_assoc();
                
                if (password_verify($pass_actual, $centro['contrasena'])) {
                    // La contraseña actual es correcta, actualizar a la nueva contraseña
                    $pass_nueva_hash = password_hash($pass_nueva, PASSWORD_DEFAULT);
                    
                    $query_update = "UPDATE Centro SET contrasena = ? WHERE Id = ?";
                    $stmt_update = $_conexion->prepare($query_update);
                    $stmt_update->bind_param('si', $pass_nueva_hash, $centro_id);
                    
                    if ($stmt_update->execute()) {
                        $mensaje_exito = "Contraseña actualizada correctamente";
                        echo "<script>
                                alert('Contraseña cambiada con éxito.');
                                window.location.href='../dashboardCentro.php';
                              </script>";
                    } else {
                        $mensaje_error = "Error al actualizar la contraseña: " . $_conexion->error;
                    }
                    
                    $stmt_update->close();
                } else {
                    $mensaje_error = "La contraseña actual no es correcta";
                }
            } else {
                $mensaje_error = "No se encontró el centro en la base de datos";
            }
            $stmt->close();
        }
        $_conexion->close();
    }
    ?>

    <div class="container">
        <h1 class="text-center mb-4">Cambiar Contraseña</h1>
        
        <?php if (!empty($mensaje_exito)): ?>
            <div class="alert alert-success"><?php echo $mensaje_exito; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($mensaje_error)): ?>
            <div class="alert alert-danger"><?php echo $mensaje_error; ?></div>
        <?php endif; ?>

        <form method="post" action="" class="border p-4 rounded shadow-sm bg-white">
            <div class="mb-3">
                <label for="pass_actual" class="form-label">Contraseña Actual</label>
                <input type="password" class="form-control" id="pass_actual" name="pass_actual" required>
                <?php if (!empty($error_actual)) echo "<span class='error'>$error_actual</span>"; ?>
            </div>
            <div class="mb-3">
                <label for="pass_nueva" class="form-label">Nueva Contraseña</label>
                <input type="password" class="form-control" id="pass_nueva" name="pass_nueva" required>
                <small class="text-muted">Mínimo 6 caracteres, con mayúsculas, minúsculas y caracteres especiales.</small>
                <?php if (!empty($error_nueva)) echo "<span class='error'>$error_nueva</span>"; ?>
            </div>
            <button type="submit" class="btn btn-primary w-100">Cambiar Contraseña</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>