<?php
    $_servidor = "sql208.infinityfree.com";
    $_usuario = "if0_38607017";
    $_contrasena = "sgmFjzu9YNqffX";
    $_base_de_datos = "if0_38607017_TDATracker";

    $_conexion = new mysqli($_servidor, $_usuario, $_contrasena, $_base_de_datos);

    if ($_conexion->connect_error) {
        die("Error de conexión: " . $_conexion->connect_error);
    }
    ?>