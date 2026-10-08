<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("Location: ../login.php?error=Campos vacíos");
        exit;
    }

    $stmt = $conexion->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($password, $usuario['password'])) {
            
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            if ($usuario['rol'] === 'administrador') {
                header("Location: ../admin.php");
            } else {
                header("Location: ../dashboard.php");
            }
            exit;
        } else {
            header("Location: ../login.php?error=Contraseña incorrecta");
            exit;
        }
    } else {
        header("Location: ../login.php?error=El correo no está registrado");
        exit;
    }

    $stmt->close();
    $conexion->close();
}
?>