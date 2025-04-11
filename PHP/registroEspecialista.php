<?php

    require('./util/conexion.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $data = json_decode(file_get_contents("php://input"), true);
        $tmp_nombre = $data["nombre"];
        $tmp_apellido = $data["apellido"];
        $tmp_pass = $data["pass"];
        $tmp_telefono = $data["telefono"];
        $tmp_genero = $data["genero"];
        $tmp_email = $data["email"];

      

        $pass_cifrada = password_hash($tmp_pass, PASSWORD_DEFAULT);
        
        $sql_insert = $_conexion->prepare("INSERT INTO Especialista (Nombre, Apellidos, Email, Contrasena, Genero, Telefono) VALUES (?, ?, ?, ?, ?, ?)");
        $sql_insert->bind_param("ssssss", $tmp_nombre, $tmp_apellido, $tmp_email, $pass_cifrada, $tmp_genero, $tmp_telefono);

        
        header('Content-Type: application/json');
        if ($sql_insert->execute()) {
            echo json_encode(["success" => true, "message" => "Registro exitoso."]);
        } else {
            echo json_encode(["success" => false, "error" => $sql_insert->error]);
        }


        $sql_insert->close();
    }
    $_conexion->close();
?>