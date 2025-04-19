<?php
session_start();
require('../util/conexion.php');

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: inicio_sesion.php');
    exit;
}

$mensaje_exito = '';
$mensaje_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass_actual = $_POST['pass_actual'];
    $pass_nueva = $_POST['pass_nueva'];
    $usuario_id = $_SESSION['usuario_id']; // Usar el ID del usuario

    // Verificar la contraseña actual
    $query = "SELECT Contrasena FROM Usuario WHERE Id = ?";
    $stmt = $_conexion->prepare($query);
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();

    if ($usuario && password_verify($pass_actual, $usuario['Contrasena'])) {
        // La contraseña actual es correcta, actualizar a la nueva contraseña
        $pass_nueva_hash = password_hash($pass_nueva, PASSWORD_DEFAULT);
        
        $query_update = "UPDATE Usuario SET Contrasena = ? WHERE Id = ?";
        $stmt_update = $_conexion->prepare($query_update);
        $stmt_update->bind_param('si', $pass_nueva_hash, $usuario_id); // Usar usuario_id aquí
        
        if ($stmt_update->execute()) {
            $mensaje_exito = "Contraseña actualizada correctamente";
        } else {
            $mensaje_error = "Error al actualizar la contraseña: " . $_conexion->error;
        }
        
        $stmt_update->close();
    } else {
        $mensaje_error = "La contraseña actual no es correcta";
    }
    
    $stmt->close();
}

$_conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/estilopass.css">
</head>
<body>
    <!-- Efectos de ondas cerebrales -->
    <div class="brain-wave" style="top:10%; left:-150px;"></div>
    <div class="brain-wave" style="top:30%; right:-150px;"></div>
    <div class="brain-wave" style="bottom:20%; left:-150px;"></div>
    <div class="brain-wave" style="bottom:40%; right:-150px;"></div>

    <a href="../dashboard.php" class="btn btn-secondary btn-volver">
        <i class="fas fa-brain"></i> Volver
    </a>
    
    <div class="container">        
        <h1>Cambiar Contraseña</h1>
        
        <?php if (!empty($mensaje_exito)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($mensaje_exito); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($mensaje_error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($mensaje_error); ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="mb-4">
                <label for="pass_actual" class="form-label">
                    <i class="fas fa-lock"></i> Contraseña Actual
                </label>
                <input type="password" class="form-control" id="pass_actual" name="pass_actual" required>
            </div>
            <div class="mb-4">
                <label for="pass_nueva" class="form-label">
                    <i class="fas fa-key"></i> Nueva Contraseña
                </label>
                <input type="password" class="form-control" id="pass_nueva" name="pass_nueva" required 
                       minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                <small class="form-text text-muted">
                    La contraseña debe tener al menos 8 caracteres, incluir una mayúscula, una minúscula y un número.
                </small>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sync-alt"></i> Actualizar
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>