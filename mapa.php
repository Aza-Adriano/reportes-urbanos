<?php
session_start();
include 'php/conexion.php';
include 'header.php';

$categorias = $conexion->query("SELECT id, nombre FROM categorias");
?>

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
        <option value="en proceso">En proceso</option>
        <option value="resuelto">Resuelto</option>
    </select>

    <button id="btn_filtrar">Filtrar</button>
</div>

<div id="mapa" style="height: 500px; margin-top: 1rem;"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="js/mapa_publico.js"></script>

<?php include 'footer.php'; ?>