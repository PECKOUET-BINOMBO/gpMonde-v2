import { DatabaseService } from '../services/DatabaseService.js';
declare const L: any;

interface Colis {
    id: number;
    numero_colis: string;
    nbr_colis: number;
    poids: number;
    type_produit: string;
    type_transport: string;
    prix: number;
    description: string;
    cargaison_id: number;
    etat: string;
    info_expediteur: {
        nom: string;
        prenom: string;
        adresse: string;
        tel: string;
        email: string;
    };
    info_destinataire: {
        nom: string;
        prenom: string;
        adresse: string;
        tel: string;
        email: string;
    };
}

let map: any;
let departureMarker: any = null;
let arrivalMarker: any = null;
let routeLine: any = null;

function initMap() {
    map = L.map('map').setView([46.603354, 1.888334], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
}

async function fetchColis(code: string): Promise<Colis | null> {
    const dbService = DatabaseService.getInstance();
    await dbService.loadDatabase();
    const db = dbService.getDatabase();
    const colis = db.colis.find((c: Colis) => c.numero_colis.toUpperCase() === code.toUpperCase());
    return colis || null;
}

async function displayPackageInfo(data: Colis) {
    // Récupérer la cargaison liée
    const dbService = DatabaseService.getInstance();
    await dbService.loadDatabase();
    const db = dbService.getDatabase();
    const cargaison = db.cargaisons.find((c: any) => c.id === data.cargaison_id);

    (document.getElementById('packageTitle') as HTMLElement).textContent = `Colis #${data.numero_colis}`;
    (document.getElementById('packageType') as HTMLElement).textContent = data.type_transport;
    (document.getElementById('weight') as HTMLElement).textContent = `${data.poids} kg`;
    (document.getElementById('value') as HTMLElement).textContent = `${data.prix} XAF`;
    (document.getElementById('sender') as HTMLElement).textContent =
        `${data.info_expediteur.nom} ${data.info_expediteur.prenom}`;
    (document.getElementById('recipient') as HTMLElement).textContent =
        `${data.info_destinataire.nom} ${data.info_destinataire.prenom}`;
    (document.getElementById('packageStatus') as HTMLElement).textContent = data.etat;
    (document.getElementById('description') as HTMLElement).textContent = data.description || '';

    // Affiche le lieu de départ et d'arrivée
    (document.getElementById('departure') as HTMLElement).textContent = cargaison ? cargaison.lieu_depart : '';
    (document.getElementById('arrival') as HTMLElement).textContent = cargaison ? cargaison.lieu_arrive : '';

    // Affiche la carte avec les points de départ et d'arrivée
    if (cargaison) {
        displayDepartureArrivalOnMap(cargaison);
    }
}

function displayDepartureArrivalOnMap(cargaison: any) {
    if (departureMarker) map.removeLayer(departureMarker);
    if (arrivalMarker) map.removeLayer(arrivalMarker);
    if (routeLine) map.removeLayer(routeLine);

    const depLat = parseFloat(cargaison.latitude_depart);
    const depLng = parseFloat(cargaison.longitude_depart);
    const arrLat = parseFloat(cargaison.latitude_arrivee);
    const arrLng = parseFloat(cargaison.longitude_arrivee);

    departureMarker = L.marker([depLat, depLng], {
        icon: new L.divIcon({
            className: 'custom-marker',
            html: `<div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white shadow-lg">
                     <i class="fas fa-play text-xs"></i>
                   </div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        })
    }).addTo(map);
    departureMarker.bindPopup(`<strong>Départ</strong><br>${cargaison.lieu_depart}`);

    arrivalMarker = L.marker([arrLat, arrLng], {
        icon: new L.divIcon({
            className: 'custom-marker',
            html: `<div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white shadow-lg">
                     <i class="fas fa-flag text-xs"></i>
                   </div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        })
    }).addTo(map);
    arrivalMarker.bindPopup(`<strong>Arrivée</strong><br>${cargaison.lieu_arrive}`);

    routeLine = L.polyline([[depLat, depLng], [arrLat, arrLng]], {
        color: '#3b82f6',
        weight: 4,
        opacity: 0.8
    }).addTo(map);

    map.fitBounds([[depLat, depLng], [arrLat, arrLng]], { padding: [20, 20] });
}

function centerMapOnPackage() {
    if (departureMarker) {
        map.setView(departureMarker.getLatLng(), 10);
        departureMarker.openPopup();
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
    if (departureMarker) map.removeLayer(departureMarker);
    if (arrivalMarker) map.removeLayer(arrivalMarker);
    if (routeLine) map.removeLayer(routeLine);
}

document.addEventListener('DOMContentLoaded', () => {
    initMap();

    document.getElementById('trackingForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const code = (document.getElementById('trackingCode') as HTMLInputElement).value.trim().toUpperCase();
        if (!code) {
            alert('Veuillez entrer un code de suivi');
            return;
        }

        try {
            const data = await fetchColis(code);
            if (data) {
                await displayPackageInfo(data);
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