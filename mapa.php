<?php
session_start();
include 'php/conexion.php';

$categorias = $conexion->query("SELECT id, nombre FROM categorias");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Reportes - Reportes Urbanos</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
    <header>
        <h1>Reportes Urbanos</h1>
        <p>Mapa de problemas - Macrodistrito Centro</p>
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
        <h2>Mapa de Reportes</h2>

        <div class="filtros">
            <label for="filtro_categoria">Categoría:</label>
            <select id="filtro_categoria">
                <option value="">Todas</option>
                <?php while ($cat = $categorias->fetch_assoc()): ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nombre']); ?></option>
                <?php endwhile; ?>
            </select>

            <label for="filtro_estado">Estado:</label>
            <select id="filtro_estado">
                <option value="">Todos</option>
                <option value="recibido">Recibido</option>
                <option value="en proceso">En proceso</option>
                <option value="resuelto">Resuelto</option>
            </select>

            <button id="btn_filtrar">Filtrar</button>
        </div>

        <div id="mapa" style="height: 500px; margin-top: 1rem;"></div>
    </main>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="js/mapa_publico.js"></script>
</body>
</html>