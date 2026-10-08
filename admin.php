<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'administrador') {
    header("Location: login.php?error=Acceso denegado");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Reportes Urbanos</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <h1>Reportes Urbanos - Administración</h1>
        <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
    </header>

    <nav>
        <a href="admin.php">Inicio</a>
        <a href="admin_reportes.php">Gestionar Reportes</a>
        <a href="mapa.php">Ver Mapa</a>
        <a href="php/cerrar_sesion.php">Cerrar Sesión</a>
    </nav>

    <main>
        <h2>Panel de Administrador</h2>
        <p>Desde aquí puedes gestionar los reportes y actualizar su estado.</p>
    </main>
</body>
</html>