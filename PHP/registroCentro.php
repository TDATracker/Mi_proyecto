<?php
    require('./util/conexion.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $data = json_decode(file_get_contents("php://input"), true);
        $tmp_nombre = $data["nombre"];
        $tmp_direccion = $data["direccion"];
        $tmp_email = $data["email"];
        $tmp_telefono = $data["telefono"];
        $tmp_pass = $data["pass"];

      

        $pass_cifrada = password_hash($tmp_pass, PASSWORD_DEFAULT);
        
        $sql_insert = $_conexion->prepare("INSERT INTO Centro (Nombre, Direccion, Email, Telefono, contrasena) VALUES (?, ?, ?, ?, ?)");
        $sql_insert->bind_param("sssss", $tmp_nombre, $tmp_direccion, $tmp_email, $tmp_telefono, $pass_cifrada);

        
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