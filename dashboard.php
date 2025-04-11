<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TDATracker</title>
    <!-- Cargar el CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Cargar el CSS de FullCalendar (calendario) -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <!-- Cargar los íconos de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Cargar el CSS personalizado -->
    <link rel="stylesheet" href="./css/dashboard.css">
</head>

<body>
    <!-- Barra de navegación mejorada y responsiva -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <!-- Botón modo oscuro -->
            <button class="btn btn-custom order-1 order-lg-1 modo-switch me-2" type="button">🌙 Modo Oscuro</button>

            <!-- Logo -->
            <a class="navbar-brand mx-auto order-2 order-lg-2" href="#">TDATracker</a>

            <!-- Botón hamburguesa para móviles -->
            <button class="navbar-toggler order-3" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarOpciones" aria-controls="navbarOpciones" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Opciones colapsables -->
            <div class="collapse navbar-collapse order-4 mt-2 mt-lg-0 justify-content-end" id="navbarOpciones">
                <div class="d-flex flex-column flex-lg-row gap-2">
                    <button class="btn btn-custom" onclick="cambiarContrasena()">Configuración</button>
                    <button class="btn btn-custom" onclick="cerrarSesion()">Cerrar Sesión</button>
                </div>
            </div>
        </div>
    </nav>
    <?php
    session_start();
    if (isset($_SESSION["usuario"])) { ?>
        <h1 class="text-center">Hola <?php echo  $_SESSION["usuario"]; ?></h1>
    <?php } else {
        header("location: ./PHP/inicio_sesion.php"); //he añadido los puntos
        exit;
    }
    ?>

    <!-- Contenido principal -->
    <div class="container mt-4">
        <h1 class="text-center">Bienvenido a TDATracker</h1>
        <div class="container-grid">
            <div class="calendar-container">
                <div id="calendar"></div>
                <div class="mt-3">
                    <button class="btn btn-custom" onclick="agregarEmocion('😟')">😟</button>
                    <button class="btn btn-custom" onclick="agregarEmocion('😐')">😐</button>
                    <button class="btn btn-custom" onclick="agregarEmocion('😊')">😊</button>
                    <textarea id="nota" class="form-control mt-2" placeholder="Escribe una nota"></textarea>
                    <button class="btn btn-custom mt-2" onclick="agregarNota()">Añadir Nota</button>
                </div>
            </div>

            <div class="tareas-container">
                <h3>Lista de Tareas</h3>
                <div class="tareas-lista" id="tareasLista"></div>
                <input type="text" id="nuevaTarea" class="form-control mt-2" placeholder="Nueva tarea">
                <button class="btn btn-custom mt-2" onclick="agregarTarea()">Agregar Tarea</button>

                <div class="musica-container">
                    <h4>🎵 Música relajante e inspiradora</h4>
                    <iframe width="560" height="315"
                        src="https://www.youtube.com/embed/videoseries?list=PLc1QuYTjzGnYxoRylLoyJB-Qs-5NKrGh6"
                        title="YouTube playlist" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <div class="recuadros-adicionales">
        <div class="recuadro">🎮 Minijuegos <br> En construcción</div>
        <!-- Botón de videollamada -->
        <div class="text-center">
            <button class="btn btn-primary mt-4" onclick="iniciarVideollamada()">📹 Iniciar Videollamada</button>
        </div>
    </div>



    <!-- Modal de Nota -->
    <div class="modal fade" id="modalNota" tabindex="-1" aria-labelledby="modalNotaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNotaLabel">Nota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="notaContenido"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btnBorrarNota">Borrar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para videollamada -->
    <div class="modal fade" id="modalVideollamada" tabindex="-1" aria-labelledby="modalVideollamadaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Videollamada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0" style="height: 70vh;">
                    <div id="jitsiContainer" style="width: 100%; height: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>
    <script src="https://meet.jit.si/external_api.js"></script>
    <script src="./JS/dashboard.js"></script>
</body>

</html>