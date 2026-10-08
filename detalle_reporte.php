<?php
session_start();
include 'php/conexion.php';

$reporte_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($reporte_id === 0) {
    header("Location: mapa.php?error=Reporte no encontrado");
    exit;
}

$sql = "SELECT r.id, r.descripcion, r.foto, r.latitud, r.longitud, r.estado, r.fecha_creacion,
               c.nombre AS categoria, u.nombre AS usuario
        FROM reportes r
        JOIN categorias c ON r.categoria_id = c.id
        JOIN usuarios u ON r.usuario_id = u.id
        WHERE r.id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $reporte_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header("Location: mapa.php?error=Reporte no encontrado");
    exit;
}

$reporte = $resultado->fetch_assoc();
$stmt->close();

$stmt = $conexion->prepare("SELECT tipo, COUNT(*) AS total FROM verificaciones WHERE reporte_id = ? GROUP BY tipo");
$stmt->bind_param("i", $reporte_id);
$stmt->execute();
$resultado = $stmt->get_result();

$verificaciones = 0;
$denuncias = 0;

while ($row = $resultado->fetch_assoc()) {
    if ($row['tipo'] === 'verificacion') {
        $verificaciones = $row['total'];
    } elseif ($row['tipo'] === 'denuncia') {
        $denuncias = $row['total'];
    }
}
$stmt->close();

$ya_participo = false;
if (isset($_SESSION['usuario_id'])) {
    $stmt = $conexion->prepare("SELECT id FROM verificaciones WHERE reporte_id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $reporte_id, $_SESSION['usuario_id']);
    $stmt->execute();
    $stmt->store_result();
    $ya_participo = $stmt->num_rows > 0;
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Reporte - Reportes Urbanos</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
    <header>
        <h1>Reportes Urbanos</h1>
        <p>Detalle del reporte #<?php echo $reporte['id']; ?></p>
    </header>

    <nav>
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <a href="dashboard.php">Inicio</a>
            <a href="crear_reporte.php">Crear Reporte</a>
            <a href="mapa.php">Ver Mapa</a>
            <a href="php/cerrar_sesion.php">Cerrar Sesión</a>
        <?php else: ?>
            <a href="index.php">Inicio</a>
            <a href="login.php">Iniciar Sesión</a>
            <a href="registro.php">Registrarse</a>
        <?php endif; ?>
    </nav>

    <main>
        <h2><?php echo htmlspecialchars($reporte['categoria']); ?></h2>

        <?php if (isset($_GET['mensaje'])): ?>
            <p class="exito"><?php echo htmlspecialchars($_GET['mensaje']); ?></p>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <p class="error">Error: <?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <div class="detalle-reporte">
            <img src="uploads/<?php echo htmlspecialchars($reporte['foto']); ?>" alt="Foto del reporte" style="max-width: 100%; max-height: 400px;">

            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($reporte['descripcion']); ?></p>
            <p><strong>Estado:</strong> <span class="estado-<?php echo str_replace(' ', '-', $reporte['estado']); ?>"><?php echo $reporte['estado']; ?></span></p>
            <p><strong>Reportado por:</strong> <?php echo htmlspecialchars($reporte['usuario']); ?></p>
            <p><strong>Fecha:</strong> <?php echo $reporte['fecha_creacion']; ?></p>

            <p><strong>Verificaciones:</strong> <?php echo $verificaciones; ?> | <strong>Denuncias:</strong> <?php echo $denuncias; ?></p>

            <?php if ($verificaciones >= 3): ?>
                <p class="etiqueta-verificado">✔ Verificado por la comunidad</p>
            <?php endif; ?>

            <?php if ($denuncias >= 3): ?>
                <p class="etiqueta-revision">⚠ En revisión por el administrador</p>
            <?php endif; ?>
        </div>

        <div id="mapa_detalle" style="height: 300px; margin-top: 1rem;"></div>

        <?php if (isset($_SESSION['usuario_id'])): ?>
            <?php if ($ya_participo): ?>
                <p>Ya has verificado o denunciado este reporte.</p>
            <?php else: ?>
                <div class="acciones-reporte">
                    <form action="php/verificar_reporte.php" method="POST">
                        <input type="hidden" name="reporte_id" value="<?php echo $reporte['id']; ?>">
                        <input type="hidden" name="tipo" value="verificacion">
                        <button type="submit" class="btn-verificar">✔ Verificar que sigue vigente</button>
                    </form>

                    <form action="php/verificar_reporte.php" method="POST">
                        <input type="hidden" name="reporte_id" value="<?php echo $reporte['id']; ?>">
                        <input type="hidden" name="tipo" value="denuncia">
                        <button type="submit" class="btn-denunciar">⚠ Denunciar que ya no existe</button>
                    </form>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p><a href="login.php">Inicia sesión</a> para verificar o denunciar este reporte.</p>
        <?php endif; ?>
    </main>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var mapa = L.map('mapa_detalle').setView([<?php echo $reporte['latitud']; ?>, <?php echo $reporte['longitud']; ?>], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(mapa);

        L.marker([<?php echo $reporte['latitud']; ?>, <?php echo $reporte['longitud']; ?>]).addTo(mapa)
            .bindPopup('<?php echo htmlspecialchars($reporte['categoria']); ?>').openPopup();
    </script>
</body>
</html>