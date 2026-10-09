<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php?error=Debes iniciar sesión");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $categoria_id = intval($_POST['categoria_id']);
    $descripcion = trim($_POST['descripcion']);
    $latitud = floatval($_POST['latitud']);
    $longitud = floatval($_POST['longitud']);

    if (empty($categoria_id) || empty($descripcion) || empty($latitud) || empty($longitud)) {
        header("Location: ../crear_reporte.php?error=Todos los campos son obligatorios");
        exit;
    }

    // Límites aproximados del Macrodistrito Centro de La Paz
    $lat_min = -16.52;
    $lat_max = -16.485;
    $lng_min = -68.16;
    $lng_max = -68.10;

if ($latitud < $lat_min || $latitud > $lat_max || $longitud < $lng_min || $longitud > $lng_max) {
    header("Location: ../crear_reporte.php?error=La ubicación debe estar dentro del Macrodistrito Centro");
    exit;
}

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $nombre_archivo = time() . '_' . basename($_FILES['foto']['name']);
        $ruta_destino = '../uploads/' . $nombre_archivo;
        $tipo_archivo = strtolower(pathinfo($ruta_destino, PATHINFO_EXTENSION));

        $tipos_permitidos = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($tipo_archivo, $tipos_permitidos)) {
            header("Location: ../crear_reporte.php?error=Solo se permiten imágenes JPG, PNG o GIF");
            exit;
        }

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino)) {
            header("Location: ../crear_reporte.php?error=Error al subir la foto");
            exit;
        }
    } else {
        header("Location: ../crear_reporte.php?error=Debes subir una foto");
        exit;
    }

    $stmt = $conexion->prepare("INSERT INTO reportes (usuario_id, categoria_id, descripcion, foto, latitud, longitud) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssd", $usuario_id, $categoria_id, $descripcion, $nombre_archivo, $latitud, $longitud);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php?mensaje=Reporte creado exitosamente");
        exit;
    } else {
        header("Location: ../crear_reporte.php?error=Error al guardar el reporte");
        exit;
    }

    $stmt->close();
    $conexion->close();
}
?>