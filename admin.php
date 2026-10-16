<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'administrador') {
    header("Location: login.php?error=Acceso denegado");
    exit;
}

include 'header.php';
?>

<h2>Panel de Administrador</h2>
<p>Bienvenido/a, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>.</p>
<p>Desde aquí puedes gestionar los reportes y actualizar su estado.</p>
<p><a href="admin_reportes.php">Gestionar Reportes</a></p>

<?php include 'footer.php'; ?>