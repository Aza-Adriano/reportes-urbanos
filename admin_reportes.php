<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'administrador') {
    header("Location: login.php?error=Acceso denegado");
    exit;
}

include 'php/conexion.php';

$sql = "SELECT r.id, r.descripcion, r.foto, r.estado, r.fecha_creacion,
               c.nombre AS categoria, u.nombre AS usuario
        FROM reportes r
        JOIN categorias c ON r.categoria_id = c.id
        JOIN usuarios u ON r.usuario_id = u.id
        ORDER BY r.fecha_creacion DESC";

$reportes = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Reportes - Admin</title>
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
        <h2>Gestionar Reportes</h2>

        <?php if (isset($_GET['mensaje'])): ?>
            <p class="exito"><?php echo htmlspecialchars($_GET['mensaje']); ?></p>
        <?php endif; ?>

        <?php if ($reportes->num_rows === 0): ?>
            <p>No hay reportes registrados todavía.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Categoría</th>
                        <th>Descripción</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($r = $reportes->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $r['id']; ?></td>
                            <td><?php echo htmlspecialchars($r['categoria']); ?></td>
                            <td><?php echo htmlspecialchars(substr($r['descripcion'], 0, 50)) . '...'; ?></td>
                            <td><?php echo htmlspecialchars($r['usuario']); ?></td>
                            <td><?php echo $r['fecha_creacion']; ?></td>
                            <td>
                                <span class="estado-<?php echo str_replace(' ', '-', $r['estado']); ?>">
                                    <?php echo $r['estado']; ?>
                                </span>
                            </td>
                            <td>
                                <form action="php/actualizar_estado.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="reporte_id" value="<?php echo $r['id']; ?>">
                                    <select name="nuevo_estado">
                                        <option value="recibido" <?php echo $r['estado'] === 'recibido' ? 'selected' : ''; ?>>Recibido</option>
                                        <option value="en proceso" <?php echo $r['estado'] === 'en proceso' ? 'selected' : ''; ?>>En proceso</option>
                                        <option value="resuelto" <?php echo $r['estado'] === 'resuelto' ? 'selected' : ''; ?>>Resuelto</option>
                                    </select>
                                    <button type="submit">Actualizar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>