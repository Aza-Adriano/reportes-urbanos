// Coordenadas del Macrodistrito Centro de La Paz
var mapa = L.map('mapa').setView([-16.5, -68.15], 14);

// Límites del Macrodistrito Centro
var limites = [
    [-16.53, -68.17], // Suroeste
    [-16.48, -68.12]  // Noreste
];

mapa.setMaxBounds(limites);
mapa.setMinZoom(14);

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
                
                var latStr = String(reporte.latitud).trim();
                var lngStr = String(reporte.longitud).trim();
                var lat = parseFloat(latStr);
                var lng = parseFloat(lngStr);

                if (isNaN(lat) || isNaN(lng)) {
                    console.warn('Reporte con coordenadas inválidas:', reporte.id, latStr, lngStr);
                    return;
                }

                var color = obtenerColorEstado(reporte.estado);

                var marcador = L.circleMarker([lat, lng], {
                    radius: 10,
                    fillColor: color,
                    color: '#000',
                    weight: 1,
                    opacity: 1,
                    fillOpacity: 0.8
                }).addTo(mapa);

                var popup = `
                    <div style="font-family: 'Inter', sans-serif; max-width: 220px;">
                        <h4 style="margin: 0 0 0.5rem 0; color: #1a1a1a;">${reporte.categoria}</h4>
                        <img src="uploads/${reporte.foto}" alt="Foto" style="width: 100%; border-radius: 8px; margin-bottom: 0.5rem;">
                        <p style="margin: 0 0 0.5rem 0; font-size: 0.9rem; color: #6b6b6b;">${reporte.descripcion}</p>
                        <p style="margin: 0 0 0.25rem 0; font-size: 0.85rem;">
                            <strong>Estado:</strong> <span style="color: ${color};">${reporte.estado}</span>
                        </p>
                        <p style="margin: 0 0 0.25rem 0; font-size: 0.8rem; color: #6b6b6b;">
                            Reportado por: ${reporte.usuario}
                        </p>
                        <p style="margin: 0 0 0.5rem 0; font-size: 0.8rem; color: #6b6b6b;">
                            Fecha: ${reporte.fecha_creacion}
                        </p>
                        <a href="detalle_reporte.php?id=${reporte.id}" style="display: inline-block; background-color: #34e0a1; color: #1a1a1a; text-decoration: none; padding: 0.4rem 0.8rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600;">Ver detalle</a>
                    </div>
                `;

                marcador.bindPopup(popup);
                marcadores.push(marcador);
            });
        })
        .catch(error => console.error('Error:', error));
}


function obtenerColorEstado(estado) {
    switch (estado) {
        case 'recibido': return '#f39c12';
        case 'en proceso': return '#3498db';
        case 'resuelto': return '#27ae60';
        default: return '#95a5a6';
    }
}

cargarReportes();

document.getElementById('btn_filtrar').addEventListener('click', function() {
    var categoria = document.getElementById('filtro_categoria').value;
    var estado = document.getElementById('filtro_estado').value;
    cargarReportes(categoria, estado);
});