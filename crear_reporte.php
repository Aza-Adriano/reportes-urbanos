<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php?error=Debes iniciar sesión");
    exit;
}

include 'php/conexion.php';
include 'header.php';

$categorias = $conexion->query("SELECT id, nombre FROM categorias");
?>

<h2>Crear Reporte</h2>

<?php if (isset($_GET['error'])): ?>
    <p class="error">Error: <?php echo htmlspecialchars($_GET['error']); ?></p>
<?php endif; ?>

<form action="php/guardar_reporte.php" method="POST" enctype="multipart/form-data">
    <label for="categoria_id">Categoría:</label>
    <select id="categoria_id" name="categoria_id" required>
        <option value="">Selecciona una categoría</option>
        <?php while ($cat = $categorias->fetch_assoc()): ?>
            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nombre']); ?></option>
        <?php endwhile; ?>
    </select>

    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="descripcion" rows="4" required></textarea>

    <label for="foto">Foto:</label>
    <input type="file" id="foto" name="foto" accept="image/*" required>

    <label>Ubicación (haz clic en el mapa):</label>

    <div id="mapa" style="height: 400px; margin-bottom: 1rem;"></div>
    <input type="hidden" id="latitud" name="latitud" required>
    <input type="hidden" id="longitud" name="longitud" required>

    <button type="submit">Enviar Reporte</button>
</form>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="js/mapa.js"></script>

<?php include 'footer.php'; ?>