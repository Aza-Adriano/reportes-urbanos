<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php?error=Debes iniciar sesión");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reporte_id = intval($_POST['reporte_id']);
    $usuario_id = $_SESSION['usuario_id'];
    $tipo = $_POST['tipo']; // 'verificacion' o 'denuncia'
    $comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';

    if (!in_array($tipo, ['verificacion', 'denuncia'])) {
        header("Location: ../detalle_reporte.php?id=$reporte_id&error=Tipo no válido");
        exit;
    }

    $stmt = $conexion->prepare("SELECT id FROM reportes WHERE id = ?");
    $stmt->bind_param("i", $reporte_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        header("Location: ../mapa.php?error=Reporte no encontrado");
        exit;
    }
    $stmt->close();

    $stmt = $conexion->prepare("SELECT id FROM verificaciones WHERE reporte_id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $reporte_id, $usuario_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: ../detalle_reporte.php?id=$reporte_id&error=Ya has verificado o denunciado este reporte");
        exit;
    }
    $stmt->close();

    $stmt = $conexion->prepare("INSERT INTO verificaciones (reporte_id, usuario_id, tipo, comentario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $reporte_id, $usuario_id, $tipo, $comentario);

    if ($stmt->execute()) {
        header("Location: ../detalle_reporte.php?id=$reporte_id&mensaje=Acción registrada correctamente");
        exit;
    } else {
        header("Location: ../detalle_reporte.php?id=$reporte_id&error=Error al registrar");
        exit;
    }

    $stmt->close();
    $conexion->close();
}
?>