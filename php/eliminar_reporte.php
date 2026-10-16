<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'administrador') {
    header("Location: ../login.php?error=Acceso denegado");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reporte_id = intval($_POST['reporte_id']);
    
    $stmt = $conexion->prepare("SELECT foto FROM reportes WHERE id = ?");
    $stmt->bind_param("i", $reporte_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        header("Location: ../admin_reportes.php?error=Reporte no encontrado");
        exit;
    }

    $reporte = $resultado->fetch_assoc();
    $foto = $reporte['foto'];
    $stmt->close();
    
    $stmt = $conexion->prepare("DELETE FROM verificaciones WHERE reporte_id = ?");
    $stmt->bind_param("i", $reporte_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conexion->prepare("DELETE FROM reportes WHERE id = ?");
    $stmt->bind_param("i", $reporte_id);

    if ($stmt->execute()) {

        $ruta_foto = '../uploads/' . $foto;
        if (!empty($foto) && file_exists($ruta_foto)) {
            unlink($ruta_foto);
        }

        header("Location: ../admin_reportes.php?mensaje=Reporte eliminado correctamente");
        exit;
    } else {
        header("Location: ../admin_reportes.php?error=Error al eliminar el reporte");
        exit;
    }

    $stmt->close();
    $conexion->close();
}
?>