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
        crossorigin="" />
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

                <div id="successMessage" class="hidden my-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded relative">
                    <p></p>
                </div>
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100">
                                <i class="fas fa-boxes text-primary text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Cargaisons Actives</p>
                                <p class="text-2xl font-semibold text-gray-900" id="nb-cargaisons">0</p>
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
                                <p class="text-2xl font-semibold text-gray-900" id="nb-colis-attente">0</p>
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
                                <p class="text-2xl font-semibold text-gray-900" id="nb-colis-livres">0</p>
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
                                <p class="text-2xl font-semibold text-gray-900" id="nb-colis-problemes">0</p>
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
                                <div class="space-y-4" id="recent-cargaisons"></div>

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
                            <div id="dashboard-alerts" class="space-y-3"></div>
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

                <form class="space-y-4" id="newCargoForm">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type de transport</label>
                        <select name="type_transport" id="transportType" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                            <option>Maritime</option>
                            <option>Aérien</option>
                            <option>Routier</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lieu de départ</label>
                            <div class="relative">
                                <input name="lieu_depart" type="text" id="departurePlace"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary"
                                    placeholder="Tapez une ville (ex: Dakar, Sénégal)"
                                    autocomplete="off">

                                <!-- Liste de suggestions -->
                                <div id="departureSuggestions" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-40 overflow-y-auto"></div>
                            </div>
                            <div id="departureStatus" class="text-sm mt-1"></div>
                            <input type="hidden" id="departureLat" name="latitude_depart">
                            <input type="hidden" id="departureLng" name="longitude_depart">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lieu d'arrivée</label>
                            <div class="relative">
                                <input name="lieu_arrive" type="text" id="arrivalPlace"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary"
                                    placeholder="Tapez une ville (ex: Libreville, Gabon)"
                                    autocomplete="off">

                                <!-- Liste de suggestions -->
                                <div id="arrivalSuggestions" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-40 overflow-y-auto"></div>
                            </div>
                            <div id="arrivalStatus" class="text-sm mt-1"></div>
                            <input type="hidden" id="arrivalLat" name="latitude_arrivee">
                            <input type="hidden" id="arrivalLng" name="longitude_arrivee">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Distance (km)</label>
                            <input name="distance" type="text" id="distance" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Latitude sélectionnée</label>
                            <input name="latitude" type="text" id="selectedLat" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Longitude sélectionnée</label>
                            <input name="longitude" type="text" id="selectedLng" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                        </div>
                    </div>

                    <!-- Carte pour sélection -->
                    <div class="h-64 rounded-lg border border-gray-300">
                        <div id="cargoMap" class="h-full w-full rounded-lg"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Poids maximum (kg)</label>
                        <input type="number" id="maxWeight" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" placeholder="1000" name="poids_max">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date de départ</label>
                            <input type="datetime-local" id="dateDepart" name="date_depart" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date d'arrivée</label>
                            <input type="datetime-local" id="dateArrivee" name="date_arrivee" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" placeholder="Description de la cargaison..."></textarea>
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
        let searchTimeout = null;

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
                const {
                    lat,
                    lng
                } = e.latlng;

                // Mettre à jour les coordonnées sélectionnées
                document.getElementById('selectedLat').value = lat.toFixed(6);
                document.getElementById('selectedLng').value = lng.toFixed(6);

                // Mettre à jour le marqueur approprié
                if (currentSelection === 'departure') {
                    updateDeparture(lat, lng);
                    // Géocodage inverse pour obtenir le nom de la ville
                    reverseGeocode(lat, lng, 'departure');
                } else {
                    updateArrival(lat, lng);
                    // Géocodage inverse pour obtenir le nom de la ville
                    reverseGeocode(lat, lng, 'arrival');
                }

                // Calculer la distance si les deux points sont définis
                calculateDistance();
            });

            // Gérer le focus sur les champs pour déterminer la sélection active
            document.getElementById('departurePlace').addEventListener('focus', () => {
                currentSelection = 'departure';
                hideSuggestions('arrival');
            });
            document.getElementById('arrivalPlace').addEventListener('focus', () => {
                currentSelection = 'arrival';
                hideSuggestions('departure');
            });

            // Gérer la saisie dans les champs avec suggestions
            setupAutocomplete('departurePlace', 'departure');
            setupAutocomplete('arrivalPlace', 'arrival');
        }

        function setupAutocomplete(inputId, type) {
            const input = document.getElementById(inputId);

            input.addEventListener('input', function() {
                const query = this.value.trim();

                // Effacer le timeout précédent
                if (searchTimeout) {
                    clearTimeout(searchTimeout);
                }

                if (query.length >= 3) {
                    // Attendre 500ms après la dernière saisie avant de chercher
                    searchTimeout = setTimeout(() => {
                        searchPlaces(query, type);
                    }, 500);
                } else {
                    hideSuggestions(type);
                }
            });

            // Gérer la touche Entrée
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    geocodePlace(type);
                }
            });

            // Cacher les suggestions quand on clique ailleurs
            document.addEventListener('click', function(e) {
                if (!e.target.closest(`#${inputId}`) && !e.target.closest(`#${type}Suggestions`)) {
                    hideSuggestions(type);
                }
            });
        }

        async function searchPlaces(query, type) {
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&addressdetails=1`);
                const places = await response.json();

                showSuggestions(places, type);
            } catch (error) {
                console.error('Erreur lors de la recherche:', error);
            }
        }

        function showSuggestions(places, type) {
            const suggestionsDiv = document.getElementById(`${type}Suggestions`);

            if (places.length === 0) {
                hideSuggestions(type);
                return;
            }

            suggestionsDiv.innerHTML = '';

            places.forEach(place => {
                const div = document.createElement('div');
                div.className = 'px-3 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0';
                div.innerHTML = `
                    <div class="font-medium">${place.display_name}</div>
                    <div class="text-xs text-gray-500">${place.lat}, ${place.lon}</div>
                `;

                div.addEventListener('click', () => {
                    selectPlace(place, type);
                });

                suggestionsDiv.appendChild(div);
            });

            suggestionsDiv.classList.remove('hidden');
        }

        function hideSuggestions(type) {
            const suggestionsDiv = document.getElementById(`${type}Suggestions`);
            suggestionsDiv.classList.add('hidden');
        }

        function selectPlace(place, type) {
            const lat = parseFloat(place.lat);
            const lng = parseFloat(place.lon);

            // Mettre à jour le champ de saisie
            document.getElementById(`${type}Place`).value = place.display_name;

            // Mettre à jour la carte
            if (type === 'departure') {
                updateDeparture(lat, lng);
            } else {
                updateArrival(lat, lng);
            }

            // Mettre à jour les coordonnées sélectionnées
            document.getElementById('selectedLat').value = lat.toFixed(6);
            document.getElementById('selectedLng').value = lng.toFixed(6);

            // Cacher les suggestions
            hideSuggestions(type);

            // Calculer la distance
            calculateDistance();

            // Afficher le statut de succès
            showStatus(type, `✓ ${place.display_name}`, 'success');
        }

        async function geocodePlace(type) {
            const input = document.getElementById(`${type}Place`);
            const query = input.value.trim();

            if (!query) {
                showStatus(type, 'Veuillez saisir un nom de lieu', 'error');
                return;
            }

            showStatus(type, 'Recherche en cours...', 'loading');

            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1&addressdetails=1`);
                const places = await response.json();

                if (places.length === 0) {
                    showStatus(type, 'Lieu non trouvé. Essayez avec plus de détails (ex: "Paris, France")', 'error');
                    return;
                }

                const place = places[0];
                selectPlace(place, type);

            } catch (error) {
                console.error('Erreur lors du géocodage:', error);
                showStatus(type, 'Erreur lors de la recherche', 'error');
            }
        }

        async function reverseGeocode(lat, lng, type) {
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`);
                const place = await response.json();

                if (place && place.display_name) {
                    document.getElementById(`${type}Place`).value = place.display_name;
                    showStatus(type, `✓ ${place.display_name}`, 'success');
                }
            } catch (error) {
                console.error('Erreur lors du géocodage inverse:', error);
            }
        }

        function showStatus(type, message, status) {
            const statusDiv = document.getElementById(`${type}Status`);
            statusDiv.textContent = message;

            // Supprimer les anciennes classes de statut
            statusDiv.classList.remove('text-green-600', 'text-red-600', 'text-blue-600');

            // Ajouter la nouvelle classe selon le statut
            switch (status) {
                case 'success':
                    statusDiv.classList.add('text-green-600');
                    break;
                case 'error':
                    statusDiv.classList.add('text-red-600');
                    break;
                case 'loading':
                    statusDiv.classList.add('text-blue-600');
                    break;
            }
        }

        function updateDeparture(lat, lng) {
            // Mettre à jour les champs cachés
            document.getElementById('departureLat').value = lat;
            document.getElementById('departureLng').value = lng;

            // Mettre à jour ou créer le marqueur
            if (departureMarker) {
                departureMarker.setLatLng([lat, lng]);
            } else {
                departureMarker = L.marker([lat, lng], {
                    icon: L.divIcon({
                        className: 'custom-marker',
                        html: `<div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white shadow-lg border-2 border-white">
                                 <i class="fas fa-play text-xs"></i>
                               </div>`,
                        iconSize: [32, 32],
                        iconAnchor: [16, 16]
                    })
                }).addTo(cargoMap);

                // Ajouter un popup
                departureMarker.bindPopup("Point de départ");
            }

            // Mettre à jour la ligne de route si nécessaire
            updateRouteLine();
        }

        function updateArrival(lat, lng) {
            // Mettre à jour les champs cachés
            document.getElementById('arrivalLat').value = lat;
            document.getElementById('arrivalLng').value = lng;

            // Mettre à jour ou créer le marqueur
            if (arrivalMarker) {
                arrivalMarker.setLatLng([lat, lng]);
            } else {
                arrivalMarker = L.marker([lat, lng], {
                    icon: L.divIcon({
                        className: 'custom-marker',
                        html: `<div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white shadow-lg border-2 border-white">
                                 <i class="fas fa-flag text-xs"></i>
                               </div>`,
                        iconSize: [32, 32],
                        iconAnchor: [16, 16]
                    })
                }).addTo(cargoMap);

                // Ajouter un popup
                arrivalMarker.bindPopup("Point d'arrivée");
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
                    weight: 4,
                    dashArray: '10, 5',
                    opacity: 0.8
                }).addTo(cargoMap);

                // Ajuster la vue pour voir toute la route
                cargoMap.fitBounds([departureLatLng, arrivalLatLng], {
                    padding: [50, 50]
                });
            }
        }

        function calculateDistance() {
            if (departureMarker && arrivalMarker) {
                const departureLatLng = departureMarker.getLatLng();
                const arrivalLatLng = arrivalMarker.getLatLng();

                // Calculer la distance en km (formule de Haversine)
                const R = 6371; // Rayon de la Terre en km
                const dLat = (arrivalLatLng.lat - departureLatLng.lat) * Math.PI / 180;
                const dLng = (arrivalLatLng.lng - departureLatLng.lng) * Math.PI / 180;
                const a =
                    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(departureLatLng.lat * Math.PI / 180) * Math.cos(arrivalLatLng.lat * Math.PI / 180) *
                    Math.sin(dLng / 2) * Math.sin(dLng / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                const distance = R * c;

                document.getElementById('distance').value = Math.round(distance) + ' km';
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

            // Réinitialiser les statuts
            document.getElementById('departureStatus').textContent = '';
            document.getElementById('arrivalStatus').textContent = '';

            // Cacher les suggestions
            hideSuggestions('departure');
            hideSuggestions('arrival');

            // Par défaut, sélectionner le départ
            currentSelection = 'departure';
        }

        // Fermer la modal en cliquant à l'extérieur
        document.getElementById('newCargoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeNewCargoModal();
            }
        });

        // Afficher le message de succès s'il existe dans localStorage
        document.addEventListener('DOMContentLoaded', () => {
            const successMessage = localStorage.getItem('cargoCreationSuccess');
            if (successMessage) {
                const messageDiv = document.getElementById('successMessage');
                messageDiv.textContent = successMessage;
                messageDiv.classList.remove('hidden');

                // Supprimer le message après 5 secondes
                setTimeout(() => {
                    messageDiv.classList.add('hidden');
                    localStorage.removeItem('cargoCreationSuccess');
                }, 5000);
            }
        });
    </script>
    <script type="module" src="../../dist/services/setupLogout.js"></script>
    <script type="module" src="../../dist/services/createCargo.js"></script>
    <script type="module" src="../../dist/models/dashboard.js"></script>

    <style>
        .custom-marker {
            background: transparent !important;
            border: none !important;
        }
    </style>
</body>

</html>