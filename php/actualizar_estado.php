<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'administrador') {
    header("Location: ../login.php?error=Acceso denegado");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reporte_id = intval($_POST['reporte_id']);
    $nuevo_estado = $_POST['nuevo_estado'];

    $estados_permitidos = ['recibido', 'en proceso', 'resuelto'];
    if (!in_array($nuevo_estado, $estados_permitidos)) {
        header("Location: ../admin_reportes.php?error=Estado no válido");
        exit;
    }

    $stmt = $conexion->prepare("UPDATE reportes SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevo_estado, $reporte_id);

    if ($stmt->execute()) {
        header("Location: ../admin_reportes.php?mensaje=Estado actualizado correctamente");
        exit;
    } else {
        header("Location: ../admin_reportes.php?error=Error al actualizar");
        exit;
    }

    $stmt->close();
    $conexion->close();
}
?>