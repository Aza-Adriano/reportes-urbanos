<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $codigo_admin = isset($_POST['codigo_admin']) ? trim($_POST['codigo_admin']) : '';

    if (empty($nombre) || empty($email) || empty($password)) {
        header("Location: ../registro.php?error=Campos vacíos");
        exit;
    }

    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: ../registro.php?error=El correo ya está registrado");
        exit;
    }
    $stmt->close();

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $codigo_admin_secreto = "ADMIN2026";

    $rol = 'ciudadano';
    if (!empty($codigo_admin) && $codigo_admin === $codigo_admin_secreto) {
        $rol = 'administrador';
    }

    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $email, $password_hash, $rol);

    if ($stmt->execute()) {
        header("Location: ../login.php?mensaje=Registro exitoso. Inicia sesión.");
        exit;
    } else {
        header("Location: ../registro.php?error=Error al registrar");
        exit;
    }

    $stmt->close();
    $conexion->close();
}
?>