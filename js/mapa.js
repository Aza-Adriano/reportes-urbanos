
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

var marcador;

mapa.on('click', function(e) {
    var lat = e.latlng.lat;
    var lng = e.latlng.lng;

    if (marcador) {
        mapa.removeLayer(marcador);
    }

    marcador = L.marker([lat, lng]).addTo(mapa)
        .bindPopup('Ubicación del problema').openPopup();

    document.getElementById('latitud').value = lat;
    document.getElementById('longitud').value = lng;
});