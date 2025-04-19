<?php 
session_start();
if (!isset($_SESSION["especialista_id"]) || !isset($_SESSION["especialista_nombre"])) {
    header("Location: ./inicio_sesion.php");
    exit;
}

$especialistaId = $_SESSION["especialista_id"];
$especialistaNombre = $_SESSION["especialista_nombre"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Especialista</title>
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Dashboard Especialista</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <button class="btn btn-custom" onclick="cambiarContrasena()">Configuración</button>
                    <button class="btn btn-outline-danger" onclick="cerrarSesion()">Cerrar Sesión</button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid mt-4">
    <div class="row">
        <!-- Panel izquierdo: Lista de usuarios y detalles -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body">
                    <h3>Bienvenido, <?php echo htmlspecialchars($_SESSION["especialista_nombre"]); ?></h3>
                    <hr>
                    <h4>Usuarios Asignados</h4>
                    <div id="usuariosLista" class="mb-4"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4>Detalles del Usuario</h4>
                    <div id="usuarioDetalles">
                        <p><strong>Nombre:</strong> <span id="usuarioNombre">-</span></p>
                        <p><strong>Apellidos:</strong> <span id="usuarioApellido">-</span></p>
                        <p><strong>Email:</strong> <span id="usuarioEmail">-</span></p>
                        <p><strong>Género:</strong> <span id="usuarioGenero">-</span></p>
                        <p><strong>Teléfono:</strong> <span id="usuarioTelefono">-</span></p>
                    </div>
                    <button class="btn btn-primary w-100" id="btnLlamar">Realizar Llamada</button>
                </div>
            </div>
        </div>

        <!-- Panel central: Calendario -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h4>Calendario</h4>
                        <div id="calendar"></div>
                </div>
            </div>
        </div>

        <!-- Panel derecho: Tareas -->
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h4>Lista de Tareas</h4>
                    <div id="tareasUsuario">
                        <!-- Aquí se mostrarán las tareas -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para la llamada -->
<div class="modal fade" id="modalLlamada" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Llamada en curso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="jitsiContainer" style="height: 600px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.js"></script>
<script src="https://meet.jit.si/external_api.js"></script>
<script>
    const especialistaId = <?php echo json_encode($especialistaId); ?>;// obtenemos el id del especialista
</script>
<script src="../JS/dashboardEspecialista.js"></script>
<link rel="stylesheet" href="../css/estilodashEspe.css">
</body>
</html>
