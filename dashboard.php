<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php?error=Debes iniciar sesión");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel - Reportes Urbanos</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <h1>Reportes Urbanos</h1>
        <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
    </header>

    <nav>
        <a href="dashboard.php">Inicio</a>
        <a href="crear_reporte.php">Crear Reporte</a>
        <a href="mapa.php">Ver Mapa</a>
        <a href="php/cerrar_sesion.php">Cerrar Sesión</a>
    </nav>

    <main>
        <h2>Panel de Ciudadano</h2>
        <p>Desde aquí puedes crear reportes y ver el mapa de problemas urbanos.</p>
    </main>
</body>
</html>