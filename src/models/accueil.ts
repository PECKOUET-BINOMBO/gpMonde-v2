declare namespace L {
    class Map {
        constructor(id: string, options?: any);
        setView(center: [number, number], zoom: number): this;
        fitBounds(bounds: any, options?: any): this;
        removeLayer(layer: any): this;
        addLayer(layer: any): this;
        invalidateSize(): void;
        getLatLng(): any;
    }
    class TileLayer {
        constructor(url: string, options?: any);
        addTo(map: Map): this;
    }
    class Polyline {
        constructor(latlngs: [number, number][], options?: any);
        addTo(map: Map): this;
    }
    class Marker {
        constructor(latlng: [number, number], options?: any);
        addTo(map: Map): this;
        bindPopup(html: string): this;
        openPopup(): void;
        getLatLng(): any;
    }
    class LatLngBounds {
        constructor(latlngs: [number, number][]);
    }
    function map(id: string): Map;
    function tileLayer(url: string, options?: any): TileLayer;
    function polyline(latlngs: [number, number][], options?: any): Polyline;
    function marker(latlng: [number, number], options?: any): Marker;
    function latLngBounds(latlngs: [number, number][]): LatLngBounds;
    class divIcon {
        constructor(options?: any);
    }
}

interface ColisRoutePoint {
    lat: number;
    lng: number;
    name: string;
    time: string;
}

interface Colis {
    numero_colis: string;
    type_transport: string;
    info_expediteur: { nom: string; prenom: string; };
    info_destinataire: { nom: string; prenom: string; };
    poids: number;
    prix: number;
    route: ColisRoutePoint[];
    currentIndex: number;
}

let map: L.Map;
let currentMarker: L.Marker | null = null;
let routeLine: L.Polyline | null = null;

function initMap() {
    map = L.map('map').setView([46.603354, 1.888334], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
}

async function fetchColis(code: string): Promise<Colis | null> {
    try {
        // Modifiez l'URL pour correspondre à votre route API
        const response = await fetch(`/api/colis?code=${encodeURIComponent(code)}`);
        if (!response.ok) {
            console.error('Erreur API:', response.status, response.statusText);
            return null;
        }
        const data = await response.json();
        
        // Vérifiez que les données sont complètes
        if (!data.numero_colis || !data.route) {
            console.error('Données de colis incomplètes:', data);
            return null;
        }
        
        return data as Colis;
    } catch (error) {
        console.error('Erreur lors de la récupération du colis:', error);
        return null;
    }
}

function displayPackageInfo(data: Colis) {
    (document.getElementById('packageTitle') as HTMLElement).textContent = `Colis #${data.numero_colis}`;
    (document.getElementById('packageType') as HTMLElement).textContent = `Transport ${data.type_transport.charAt(0).toUpperCase() + data.type_transport.slice(1)}`;
    (document.getElementById('sender') as HTMLElement).textContent = `${data.info_expediteur.nom} ${data.info_expediteur.prenom}`;
    (document.getElementById('recipient') as HTMLElement).textContent = `${data.info_destinataire.nom} ${data.info_destinataire.prenom}`;
    (document.getElementById('weight') as HTMLElement).textContent = `${data.poids} kg`;
    (document.getElementById('value') as HTMLElement).textContent = `${data.prix.toLocaleString()} FCFA`;
}

function displayRoute(data: Colis) {
    if (currentMarker) map.removeLayer(currentMarker);
    if (routeLine) map.removeLayer(routeLine);

    const route = data.route;
    const currentIndex = data.currentIndex;

    // Trajet parcouru
    const completedRoute = route.slice(0, currentIndex + 1);
    if (completedRoute.length > 1) {
        routeLine = L.polyline(completedRoute.map(p => [p.lat, p.lng]), {
            color: '#3b82f6',
            weight: 4,
            opacity: 0.8
        }).addTo(map);
    }

    // Trajet restant
    const remainingRoute = route.slice(currentIndex);
    if (remainingRoute.length > 1) {
        L.polyline(remainingRoute.map(p => [p.lat, p.lng]), {
            color: '#9ca3af',
            weight: 3,
            opacity: 0.6,
            dashArray: '10, 10'
        }).addTo(map);
    }

    // Marqueurs
    route.forEach((point, index) => {
        let color: string;
        let icon: string;
        
        if (index === 0) {
            color = 'green'; icon = 'play';
        } else if (index === route.length - 1) {
            color = 'red'; icon = 'flag';
        } else if (index === currentIndex) {
            color = 'blue'; icon = 'shipping-fast';
        } else if (index < currentIndex) {
            color = 'green'; icon = 'check';
        } else {
            color = 'gray'; icon = 'circle';
        }

        const marker = L.marker([point.lat, point.lng], {
            icon: new L.divIcon({
                className: 'custom-marker',
                html: `<div class="w-8 h-8 bg-${color}-500 rounded-full flex items-center justify-center text-white shadow-lg">
                         <i class="fas fa-${icon} text-xs"></i>
                       </div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            })
        }).addTo(map);

        marker.bindPopup(`
            <div class="text-center">
                <strong>${point.name}</strong><br>
                <small>${point.time}</small>
            </div>
        `);

        if (index === currentIndex) {
            currentMarker = marker;
        }
    });

    // Centrer la carte sur la route
    const bounds = L.latLngBounds(route.map(p => [p.lat, p.lng]));
    map.fitBounds(bounds, { padding: [20, 20] });
}

function centerMapOnPackage() {
    if (currentMarker) {
        map.setView(currentMarker.getLatLng(), 10);
        currentMarker.openPopup();
    }
}

function toggleFullscreen() {
    const mapContainer = document.getElementById('map') as HTMLElement;
    if (!document.fullscreenElement) {
        mapContainer.requestFullscreen().then(() => {
            mapContainer.style.height = '100vh';
            setTimeout(() => map.invalidateSize(), 100);
        });
    } else {
        document.exitFullscreen().then(() => {
            mapContainer.style.height = '';
            setTimeout(() => map.invalidateSize(), 100);
        });
    }
}

function resetSearch() {
    (document.getElementById('trackingCode') as HTMLInputElement).value = '';
    document.getElementById('trackingResults')?.classList.add('hidden');
    document.getElementById('errorMessage')?.classList.add('hidden');
    if (currentMarker) map.removeLayer(currentMarker);
    if (routeLine) map.removeLayer(routeLine);
}

document.addEventListener('DOMContentLoaded', () => {
    initMap();

    document.getElementById('trackingForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const code = (document.getElementById('trackingCode') as HTMLInputElement).value.trim().toUpperCase();
        
        // Validation simple
        if (!code) {
            alert('Veuillez entrer un code de suivi');
            return;
        }

        try {
            const data = await fetchColis(code);
            if (data) {
                displayPackageInfo(data);
                displayRoute(data);
                document.getElementById('trackingResults')?.classList.remove('hidden');
                document.getElementById('errorMessage')?.classList.add('hidden');
            } else {
                document.getElementById('trackingResults')?.classList.add('hidden');
                document.getElementById('errorMessage')?.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Erreur lors du suivi:', error);
            document.getElementById('trackingResults')?.classList.add('hidden');
            document.getElementById('errorMessage')?.classList.remove('hidden');
        }
    });

    // Gestion du code de suivi dans l'URL
    const urlParams = new URLSearchParams(window.location.search);
    const trackingCode = urlParams.get('trackingCode');
    if (trackingCode) {
        (document.getElementById('trackingCode') as HTMLInputElement).value = trackingCode;
        document.getElementById('trackingForm')?.dispatchEvent(new Event('submit'));
    }
    (window as any).centerMapOnPackage = centerMapOnPackage;
    (window as any).toggleFullscreen = toggleFullscreen;
    (window as any).resetSearch = resetSearch;
});