<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Reportes Urbanos</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
    <header>
        <h1>Reportes Urbanos</h1>
        <p>Macrodistrito Centro - La Paz</p>
    </header>

    <nav>
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <?php if ($_SESSION['usuario_rol'] === 'administrador'): ?>
                <a href="admin.php">Inicio</a>
                <a href="admin_reportes.php">Gestionar Reportes</a>
                <a href="mapa.php">Ver Mapa</a>
                <a href="php/cerrar_sesion.php">Cerrar Sesión</a>
            <?php else: ?>
                <a href="dashboard.php">Inicio</a>
                <a href="crear_reporte.php">Crear Reporte</a>
                <a href="mapa.php">Ver Mapa</a>
                <a href="php/cerrar_sesion.php">Cerrar Sesión</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="index.php">Inicio</a>
            <a href="login.php">Iniciar Sesión</a>
            <a href="registro.php">Registrarse</a>
        <?php endif; ?>
    </nav>

    <main>