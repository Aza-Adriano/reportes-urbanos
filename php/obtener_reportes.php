<?php
include 'conexion.php';

header('Content-Type: application/json');

$categoria_id = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;
$estado = isset($_GET['estado']) ? $_GET['estado'] : '';

$sql = "SELECT r.id, r.descripcion, r.foto, r.latitud, r.longitud, r.estado, r.fecha_creacion,
               c.nombre AS categoria, u.nombre AS usuario
        FROM reportes r
        JOIN categorias c ON r.categoria_id = c.id
        JOIN usuarios u ON r.usuario_id = u.id
        WHERE 1=1";

$params = [];
$types = "";

if ($categoria_id > 0) {
    $sql .= " AND r.categoria_id = ?";
    $params[] = $categoria_id;
    $types .= "i";
}

if (!empty($estado)) {
    $sql .= " AND r.estado = ?";
    $params[] = $estado;
    $types .= "s";
}

$sql .= " ORDER BY r.fecha_creacion DESC";

$stmt = $conexion->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$resultado = $stmt->get_result();

$reportes = [];
while ($row = $resultado->fetch_assoc()) {
    $reportes[] = $row;
}

echo json_encode($reportes);

$stmt->close();
$conexion->close();
?>