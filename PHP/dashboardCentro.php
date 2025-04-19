<?php
session_start();
if (!isset($_SESSION["centro_id"]) || !isset($_SESSION["centro_nombre"])) {
    header("Location: ./inicio_sesion.php");
    exit;
}

$centroId = $_SESSION["centro_id"];
$centroNombre = $_SESSION["centro_nombre"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Centro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../css/dashboardCentro.css" rel="stylesheet">

</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Dashboard Centro</a>
    <button class="navbar-t oggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <button class="btn btn-outline-danger" id="logoutButton" onclick="cerrarSesion()">Cerrar Sesión</button>
          <button class="btn btn-custom" onclick="cambiarContrasena()">Configuración</button>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container mt-5">
<h3>Bienvenido centro:  <?php echo htmlspecialchars($centroNombre); ?> </h3>
<input type="text" id="buscar" class="form-control mt-2" placeholder="Busca aqui">
  <label>Especialistas:</label>
  <div id="especialista"></div>
  <button class="btn btn-custom" id="btnEnlazar">Añadir</button>
  <button class="btn btn-outline-danger" id="btnBorrar">Borrar</button>
  <br>
  <label>Usuarios:</label>
  <div id="usuario" ></div>
<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>
<script>
    const centroId = <?php echo json_encode($centroId); ?>;// obtenemos el id del centro
</script>
<script src="../JS/dashboardCentro.js"></script>
</body>
</html>
