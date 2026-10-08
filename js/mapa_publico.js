var mapa = L.map('mapa').setView([-16.5, -68.15], 14);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(mapa);

var marcadores = [];

function cargarReportes(categoria = '', estado = '') {
    
    marcadores.forEach(function(m) {
        mapa.removeLayer(m);
    });
    marcadores = [];

    var url = 'php/obtener_reportes.php';
    var params = [];
    if (categoria) params.push('categoria=' + categoria);
    if (estado) params.push('estado=' + encodeURIComponent(estado));
    if (params.length > 0) url += '?' + params.join('&');

    fetch(url)
        .then(response => response.json())
        .then(data => {
            data.forEach(function(reporte) {
                var color = obtenerColorEstado(reporte.estado);

                var marcador = L.circleMarker([parseFloat(reporte.latitud), parseFloat(reporte.longitud)], {
                    radius: 10,
                    fillColor: color,
                    color: '#000',
                    weight: 1,
                    opacity: 1,
                    fillOpacity: 0.8
                }).addTo(mapa);

                var popup = `
                    <strong>${reporte.categoria}</strong><br>
                    <p>${reporte.descripcion}</p>
                    <img src="uploads/${reporte.foto}" alt="Foto" style="width: 100%; max-width: 200px;"><br>
                    <small>Estado: ${reporte.estado}</small><br>
                    <small>Reportado por: ${reporte.usuario}</small><br>
                    <small>Fecha: ${reporte.fecha_creacion}</small>
                `;

                marcador.bindPopup(popup);
                marcadores.push(marcador);
            });
        })
        .catch(error => console.error('Error:', error));
}

function obtenerColorEstado(estado) {
    switch (estado) {
        case 'recibido': return '#f39c12';   // Naranja
        case 'en proceso': return '#3498db'; // Azul
        case 'resuelto': return '#27ae60';   // Verde
        default: return '#95a5a6';           // Gris
    }
}

cargarReportes();

document.getElementById('btn_filtrar').addEventListener('click', function() {
    var categoria = document.getElementById('filtro_categoria').value;
    var estado = document.getElementById('filtro_estado').value;
    cargarReportes(categoria, estado);
});