<!DOCTYPE html>
<html lang="fr" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - CargoTrack</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e3a8a',
                        secondary: '#f97316',
                        accent: '#10b981',
                        neutral: '#6b7280'
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
        crossorigin=""></script>
</head>

<body class="h-full bg-gray-50">
    <div class="flex h-full">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../partials/sideBar.html.php'; ?>

        <!-- Main content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top bar -->

            <?php include __DIR__ . '/../partials/topBar.html.php'; ?>
            <!-- Dashboard content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100">
                                <i class="fas fa-boxes text-primary text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Cargaisons Actives</p>
                                <p class="text-2xl font-semibold text-gray-900">24</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-orange-100">
                                <i class="fas fa-clock text-secondary text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">En Attente</p>
                                <p class="text-2xl font-semibold text-gray-900">8</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100">
                                <i class="fas fa-check-circle text-accent text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Livrés</p>
                                <p class="text-2xl font-semibold text-gray-900">156</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-red-100">
                                <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Problèmes</p>
                                <p class="text-2xl font-semibold text-gray-900">3</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">Cargaisons Récentes</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <i class="fas fa-ship text-primary text-lg mr-3"></i>
                                            <div>
                                                <p class="font-medium text-gray-900">CG-MAR-001</p>
                                                <p class="text-sm text-gray-600">Marseille → Alger</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                En cours
                                            </span>
                                            <p class="text-sm text-gray-500 mt-1">15 colis</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <i class="fas fa-plane text-secondary text-lg mr-3"></i>
                                            <div>
                                                <p class="font-medium text-gray-900">CG-AER-002</p>
                                                <p class="text-sm text-gray-600">Paris → Dakar</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Arrivé
                                            </span>
                                            <p class="text-sm text-gray-500 mt-1">8 colis</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <i class="fas fa-truck text-accent text-lg mr-3"></i>
                                            <div>
                                                <p class="font-medium text-gray-900">CG-ROU-003</p>
                                                <p class="text-sm text-gray-600">Lyon → Genève</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                En attente
                                            </span>
                                            <p class="text-sm text-gray-500 mt-1">22 colis</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <a href="/cargaisons" class="text-primary hover:text-blue-800 font-medium">
                                        Voir toutes les cargaisons →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Actions Rapides</h3>
                            <div class="space-y-3">
                                <a href="/enregistrement" class="w-full bg-primary hover:bg-blue-800 text-white py-2 px-4 rounded-lg font-medium transition-colors flex items-center">
                                    <i class="fas fa-plus mr-2"></i>
                                    Nouveau Colis
                                </a>
                                <button onclick="openNewCargoModal()" class="w-full bg-secondary hover:bg-orange-600 text-white py-2 px-4 rounded-lg font-medium transition-colors flex items-center">
                                    <i class="fas fa-ship mr-2"></i>
                                    Nouvelle Cargaison
                                </button>
                                <a href="/recherche" class="w-full bg-accent hover:bg-green-600 text-white py-2 px-4 rounded-lg font-medium transition-colors flex items-center">
                                    <i class="fas fa-search mr-2"></i>
                                    Rechercher
                                </a>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Alertes</h3>
                            <div class="space-y-3">
                                <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-sm text-red-800">3 colis en retard</p>
                                </div>
                                <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <p class="text-sm text-yellow-800">5 cargaisons à fermer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

   <!-- Modal Nouvelle Cargaison -->
<div id="newCargoModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Nouvelle Cargaison</h3>
                <button onclick="closeNewCargoModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de transport</label>
                    <select id="transportType" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                        <option>Maritime</option>
                        <option>Aérien</option>
                        <option>Routier</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lieu de départ</label>
                        <input type="text" id="departurePlace" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" placeholder="Ville de départ">
                        <input type="hidden" id="departureLat">
                        <input type="hidden" id="departureLng">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lieu d'arrivée</label>
                        <input type="text" id="arrivalPlace" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" placeholder="Ville d'arrivée">
                        <input type="hidden" id="arrivalLat">
                        <input type="hidden" id="arrivalLng">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Distance (km)</label>
                        <input type="text" id="distance" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                        <input type="text" id="selectedLat" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                        <input type="text" id="selectedLng" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                    </div>
                </div>

                <!-- Carte pour sélection -->
                <div class="h-64 rounded-lg border border-gray-300">
                    <div id="cargoMap" class="h-full w-full rounded-lg"></div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Poids maximum (kg)</label>
                    <input type="number" id="maxWeight" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" placeholder="1000">
                </div>

                <div class="flex space-x-3 pt-4">
                    <button type="submit" class="flex-1 bg-primary hover:bg-blue-800 text-white py-2 px-4 rounded-md font-medium transition-colors">
                        Créer
                    </button>
                    <button type="button" onclick="closeNewCargoModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded-md font-medium transition-colors">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

   <script>
    let cargoMap;
    let departureMarker = null;
    let arrivalMarker = null;
    let routeLine = null;
    let currentSelection = 'departure'; // 'departure' or 'arrival'

    function openNewCargoModal() {
        document.getElementById('newCargoModal').classList.remove('hidden');
        
        // Initialiser la carte si ce n'est pas déjà fait
        if (!cargoMap) {
            initCargoMap();
        } else {
            // Réinitialiser la carte si elle existe déjà
            cargoMap.invalidateSize();
        }
        
        // Réinitialiser les sélections
        resetMapSelections();
    }

    function closeNewCargoModal() {
        document.getElementById('newCargoModal').classList.add('hidden');
    }

    function initCargoMap() {
        // Créer la carte centrée sur l'Europe
        cargoMap = L.map('cargoMap').setView([46.603354, 1.888334], 5);
        
        // Ajouter la couche OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(cargoMap);
        
        // Gérer le clic sur la carte
        cargoMap.on('click', function(e) {
            const { lat, lng } = e.latlng;
            
            // Mettre à jour les coordonnées sélectionnées
            document.getElementById('selectedLat').value = lat.toFixed(6);
            document.getElementById('selectedLng').value = lng.toFixed(6);
            
            // Mettre à jour le marqueur approprié
            if (currentSelection === 'departure') {
                updateDeparture(lat, lng);
            } else {
                updateArrival(lat, lng);
            }
            
            // Calculer la distance si les deux points sont définis
            calculateDistance();
        });
        
        // Boutons de sélection
        document.getElementById('departurePlace').addEventListener('focus', () => currentSelection = 'departure');
        document.getElementById('arrivalPlace').addEventListener('focus', () => currentSelection = 'arrival');
    }

    function updateDeparture(lat, lng) {
        // Mettre à jour les champs texte
        document.getElementById('departureLat').value = lat;
        document.getElementById('departureLng').value = lng;
        
        // Mettre à jour ou créer le marqueur
        if (departureMarker) {
            departureMarker.setLatLng([lat, lng]);
        } else {
            departureMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: `<div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white shadow-lg">
                             <i class="fas fa-play text-xs"></i>
                           </div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                })
            }).addTo(cargoMap);
            
            // Ajouter un popup
            departureMarker.bindPopup("Point de départ").openPopup();
        }
        
        // Mettre à jour la ligne de route si nécessaire
        updateRouteLine();
    }

    function updateArrival(lat, lng) {
        // Mettre à jour les champs texte
        document.getElementById('arrivalLat').value = lat;
        document.getElementById('arrivalLng').value = lng;
        
        // Mettre à jour ou créer le marqueur
        if (arrivalMarker) {
            arrivalMarker.setLatLng([lat, lng]);
        } else {
            arrivalMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: `<div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-white shadow-lg">
                             <i class="fas fa-flag text-xs"></i>
                           </div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                })
            }).addTo(cargoMap);
            
            // Ajouter un popup
            arrivalMarker.bindPopup("Point d'arrivée").openPopup();
        }
        
        // Mettre à jour la ligne de route si nécessaire
        updateRouteLine();
    }

    function updateRouteLine() {
        // Supprimer l'ancienne ligne si elle existe
        if (routeLine) {
            cargoMap.removeLayer(routeLine);
        }
        
        // Dessiner une nouvelle ligne si les deux points sont définis
        if (departureMarker && arrivalMarker) {
            const departureLatLng = departureMarker.getLatLng();
            const arrivalLatLng = arrivalMarker.getLatLng();
            
            routeLine = L.polyline([departureLatLng, arrivalLatLng], {
                color: '#3b82f6',
                weight: 3,
                dashArray: '5, 5'
            }).addTo(cargoMap);
            
            // Ajuster la vue pour voir toute la route
            cargoMap.fitBounds([departureLatLng, arrivalLatLng], { padding: [50, 50] });
        }
    }

    function calculateDistance() {
        if (departureMarker && arrivalMarker) {
            const departureLatLng = departureMarker.getLatLng();
            const arrivalLatLng = arrivalMarker.getLatLng();
            
            // Calculer la distance en km (formule simplifiée)
            const R = 6371; // Rayon de la Terre en km
            const dLat = (arrivalLatLng.lat - departureLatLng.lat) * Math.PI / 180;
            const dLng = (arrivalLatLng.lng - departureLatLng.lng) * Math.PI / 180;
            const a = 
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(departureLatLng.lat * Math.PI / 180) * Math.cos(arrivalLatLng.lat * Math.PI / 180) * 
                Math.sin(dLng/2) * Math.sin(dLng/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            const distance = R * c;
            
            document.getElementById('distance').value = distance.toFixed(2) + ' km';
        }
    }

    function resetMapSelections() {
        // Réinitialiser les marqueurs et la ligne
        if (departureMarker) cargoMap.removeLayer(departureMarker);
        if (arrivalMarker) cargoMap.removeLayer(arrivalMarker);
        if (routeLine) cargoMap.removeLayer(routeLine);
        
        departureMarker = null;
        arrivalMarker = null;
        routeLine = null;
        
        // Réinitialiser les champs
        document.getElementById('departurePlace').value = '';
        document.getElementById('arrivalPlace').value = '';
        document.getElementById('departureLat').value = '';
        document.getElementById('departureLng').value = '';
        document.getElementById('arrivalLat').value = '';
        document.getElementById('arrivalLng').value = '';
        document.getElementById('distance').value = '';
        document.getElementById('selectedLat').value = '';
        document.getElementById('selectedLng').value = '';
        
        // Par défaut, sélectionner le départ
        currentSelection = 'departure';
    }

    // Fermer la modal en cliquant à l'extérieur
    document.getElementById('newCargoModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeNewCargoModal();
        }
    });
</script>

<style>
    .custom-marker {
        background: transparent !important;
        border: none !important;
    }
</style>
</body>

</html>