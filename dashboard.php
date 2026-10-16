<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php?error=Debes iniciar sesión");
    exit;
}

include 'php/conexion.php';
include 'header.php';

// Obtener los reportes del usuario
$stmt = $conexion->prepare("SELECT r.id, r.descripcion, r.estado, r.fecha_creacion, c.nombre AS categoria
                            FROM reportes r
                            JOIN categorias c ON r.categoria_id = c.id
                            WHERE r.usuario_id = ?
                            ORDER BY r.fecha_creacion DESC");
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$mis_reportes = $stmt->get_result();
?>

<h2>Panel de Ciudadano</h2>
<p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>.</p>
<p>Desde aquí puedes crear reportes y ver el mapa de problemas urbanos.</p>

<h3>Mis reportes</h3>

<?php if ($mis_reportes->num_rows === 0): ?>
    <p>No has creado ningún reporte todavía.</p>
    <p><a href="crear_reporte.php">Crear mi primer reporte</a></p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Categoría</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($r = $mis_reportes->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $r['id']; ?></td>
                    <td><?php echo htmlspecialchars($r['categoria']); ?></td>
                    <td><?php echo htmlspecialchars(substr($r['descripcion'], 0, 50)) . '...'; ?></td>
                    <td>
                        <span class="estado-<?php echo str_replace(' ', '-', $r['estado']); ?>">
                            <?php echo $r['estado']; ?>
                        </span>
                    </td>
                    <td><?php echo $r['fecha_creacion']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include 'footer.php'; ?>