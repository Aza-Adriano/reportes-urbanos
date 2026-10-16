<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'administrador') {
    header("Location: login.php?error=Acceso denegado");
    exit;
}

include 'php/conexion.php';
include 'header.php';

$sql = "SELECT r.id, r.descripcion, r.foto, r.estado, r.fecha_creacion,
               c.nombre AS categoria, u.nombre AS usuario
        FROM reportes r
        JOIN categorias c ON r.categoria_id = c.id
        JOIN usuarios u ON r.usuario_id = u.id
        ORDER BY r.fecha_creacion DESC";

$reportes = $conexion->query($sql);
?>

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
                        <a href="detalle_reporte.php?id=<?php echo $r['id']; ?>" class="btn-ver-detalle">Ver detalle</a>

                        <form action="php/actualizar_estado.php" method="POST" style="display:inline;">
                            <input type="hidden" name="reporte_id" value="<?php echo $r['id']; ?>">
                            <select name="nuevo_estado">
                                <option value="en proceso" <?php echo $r['estado'] === 'en proceso' ? 'selected' : ''; ?>>En proceso</option>
                                <option value="resuelto" <?php echo $r['estado'] === 'resuelto' ? 'selected' : ''; ?>>Resuelto</option>
                            </select>
                            <button type="submit">Actualizar</button>
                        </form>

                        <form action="php/eliminar_reporte.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este reporte? Esta acción no se puede deshacer.');">
                            <input type="hidden" name="reporte_id" value="<?php echo $r['id']; ?>">
                            <button type="submit" class="btn-eliminar">Eliminar</button>
                        </form>
                    </td>   
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include 'footer.php'; ?>