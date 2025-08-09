<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CargoTrack - Gestion de Cargaisons</title>
    

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
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s infinite',
                        'bounce-slow': 'bounce 2s infinite',
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Ajoute Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
          crossorigin=""/>
    <!-- Ajoute Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
            crossorigin=""></script>
</head>
<body class="h-full bg-gray-50">
    <!-- Header -->
    <?php include __DIR__ . '/../partials/header.html.php'; ?>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-primary to-blue-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h2 class="text-4xl md:text-6xl font-bold mb-6">
                    Gestion de Cargaisons
                    <span class="text-secondary">Simplifiée</span>
                </h2>
                <p class="text-xl md:text-2xl mb-8 text-blue-100">
                    Transport maritime, aérien et routier en toute sécurité
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#suivi" class="bg-secondary hover:bg-orange-600 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                        Suivre un Colis
                    </a>
                    <!-- <a href="/enregistrement" class="bg-transparent border-2 border-white hover:bg-white hover:text-primary text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                        Envoyer un Colis
                    </a> -->
                </div>
            </div>
        </div>
    </section>

    <!-- Suivi Colis Section -->
    <section id="suivi" class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold text-gray-900 mb-4">Suivre votre Colis</h3>
                <p class="text-lg text-gray-600">Entrez votre code de suivi pour connaître l'état de votre colis</p>
            </div>
            
           <!-- Formulaire de recherche -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 animate-slide-up">
            <form id="trackingForm" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label for="trackingCode" class="block text-sm font-medium text-gray-700 mb-2">
                        Code de suivi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="trackingCode" name="trackingCode" 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="Ex: CG123456, CG789012, CG345678">
                    </div>
                </div>
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full md:w-auto px-6 py-3 bg-primary hover:bg-blue-800 text-white font-semibold rounded-lg transition-colors transform hover:scale-105">
                        <i class="fas fa-search mr-2"></i>
                        Rechercher
                    </button>
                </div>
            </form>
        </div>

        <!-- Résultats de suivi -->
        <div id="trackingResults" class="hidden">
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Informations du colis -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-box text-primary text-2xl mr-3"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900" id="packageTitle">Colis #CG123456</h3>
                                <p class="text-sm text-gray-600" id="packageType">Transport Maritime</p>
                            </div>
                        </div>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Expéditeur:</span>
                                <span class="text-sm font-medium" id="sender">Jean Dupont</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Destinataire:</span>
                                <span class="text-sm font-medium" id="recipient">Marie Martin</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Poids:</span>
                                <span class="text-sm font-medium" id="weight">15.5 kg</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Valeur:</span>
                                <span class="text-sm font-medium" id="value">250 000 FCFA</span>
                            </div>
                        </div>

                        <!-- Statut actuel -->
                        <div class="p-4 bg-blue-50 rounded-lg mb-6">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-primary rounded-full mr-3 animate-pulse-slow"></div>
                                <div>
                                    <p class="font-medium text-primary" id="currentStatus">En cours de transport</p>
                                    <p class="text-xs text-gray-600" id="currentLocation">Port de Dakar</p>
                                </div>
                            </div>
                        </div>

                        <!-- Progression -->
                        <div class="mb-6">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Progression</span>
                                <span id="progressPercent">65%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-primary h-2 rounded-full transition-all duration-500" id="progressBar" style="width: 65%"></div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="space-y-3">
                            <button onclick="centerMapOnPackage()" 
                                    class="w-full px-4 py-2 bg-secondary hover:bg-orange-600 text-white rounded-lg transition-colors">
                                <i class="fas fa-crosshairs mr-2"></i>
                                Centrer sur le colis
                            </button>
                            <button onclick="toggleAnimation()" 
                                    class="w-full px-4 py-2 bg-accent hover:bg-green-600 text-white rounded-lg transition-colors">
                                <i class="fas fa-play mr-2" id="animationIcon"></i>
                                <span id="animationText">Voir l'animation</span>
                            </button>
                        </div>
                    </div>

                    <!-- Historique -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h4 class="font-semibold text-gray-900 mb-4">Historique du transport</h4>
                        <div class="space-y-4" id="trackingHistory">
                            <!-- L'historique sera généré dynamiquement -->
                        </div>
                    </div>
                </div>

                <!-- Carte -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Localisation en temps réel</h3>
                            <div class="flex items-center space-x-2">
                                <button onclick="toggleMapView()" 
                                        class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                    <i class="fas fa-layer-group mr-1"></i>
                                    <span id="mapViewText">Satellite</span>
                                </button>
                                <button onclick="toggleFullscreen()" 
                                        class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                    <i class="fas fa-expand mr-1"></i>
                                    Plein écran
                                </button>
                            </div>
                        </div>
                        
                        <!-- Carte Leaflet -->
                        <div id="map" class="w-full h-96 lg:h-[500px] rounded-lg border border-gray-200"></div>
                        
                        <!-- Légende -->
                        <div class="mt-4 flex flex-wrap gap-4 text-sm">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-gray-600">Point de départ</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-primary rounded-full mr-2 animate-pulse"></div>
                                <span class="text-gray-600">Position actuelle</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                <span class="text-gray-600">Destination</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-1 bg-blue-400 mr-2"></div>
                                <span class="text-gray-600">Trajet parcouru</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-1 border-2 border-dashed border-gray-400 mr-2"></div>
                                <span class="text-gray-600">Trajet restant</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Message d'erreur -->
        <div id="errorMessage" class="hidden bg-red-50 border border-red-200 rounded-xl p-6 text-center">
            <i class="fas fa-exclamation-triangle text-red-500 text-3xl mb-4"></i>
            <h3 class="text-lg font-semibold text-red-900 mb-2">Colis non trouvé</h3>
            <p class="text-red-700 mb-4">Le code de suivi que vous avez saisi n'existe pas ou est incorrect.</p>
            <button onclick="resetSearch()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                Nouvelle recherche
            </button>
        </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold text-gray-900 mb-4">Nos Services</h3>
                <p class="text-lg text-gray-600">Transport adapté à tous vos besoins</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-md transition-shadow">
                    <div class="text-center">
                        <i class="fas fa-ship text-4xl text-primary mb-4"></i>
                        <h4 class="text-xl font-semibold text-gray-900 mb-3">Transport Maritime</h4>
                        <p class="text-gray-600">Solution économique pour vos envois volumineux</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-md transition-shadow">
                    <div class="text-center">
                        <i class="fas fa-plane text-4xl text-secondary mb-4"></i>
                        <h4 class="text-xl font-semibold text-gray-900 mb-3">Transport Aérien</h4>
                        <p class="text-gray-600">Livraison rapide pour vos envois urgents</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-md transition-shadow">
                    <div class="text-center">
                        <i class="fas fa-truck text-4xl text-accent mb-4"></i>
                        <h4 class="text-xl font-semibold text-gray-900 mb-3">Transport Routier</h4>
                        <p class="text-gray-600">Flexibilité et proximité pour vos livraisons</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
   <?php include __DIR__ . '/../partials/footer.html.php'; ?>

    <script>
        // Variables globales
        let map;
        let currentMarker;
        let routeLine;
        let animationMarker;
        let isAnimating = false;
        let currentMapView = 'street';
        
        // Données de démonstration
        const packageData = {
            'CG123456': {
                title: 'Colis #CG123456',
                type: 'Transport Maritime',
                sender: 'Jean Dupont',
                recipient: 'Marie Martin',
                weight: '15.5 kg',
                value: '250 €',
                status: 'En cours de transport',
                location: 'Port de Marseille',
                progress: 65,
                route: [
                    { lat: 43.2965, lng: 5.3698, name: 'Port de Marseille (Départ)', time: '12/01/2025 08:00' },
                    { lat: 43.7102, lng: 7.2620, name: 'Nice', time: '13/01/2025 14:30' },
                    { lat: 43.6047, lng: 1.4442, name: 'Toulouse', time: '14/01/2025 10:15' },
                    { lat: 44.8378, lng: -0.5792, name: 'Bordeaux (Position actuelle)', time: '15/01/2025 16:45' },
                    { lat: 47.2184, lng: -1.5536, name: 'Nantes', time: '16/01/2025 12:00' },
                    { lat: 48.8566, lng: 2.3522, name: 'Paris (Destination)', time: '17/01/2025 18:00' }
                ],
                currentIndex: 3
            },
            'CG789012': {
                title: 'Colis #CG789012',
                type: 'Transport Aérien',
                sender: 'Sophie Dubois',
                recipient: 'Pierre Moreau',
                weight: '2.3 kg',
                value: '150 €',
                status: 'En vol',
                location: 'Aéroport Charles de Gaulle',
                progress: 80,
                route: [
                    { lat: 48.8566, lng: 2.3522, name: 'Paris CDG (Départ)', time: '15/01/2025 09:00' },
                    { lat: 50.8503, lng: 4.3517, name: 'Bruxelles', time: '15/01/2025 10:30' },
                    { lat: 52.3676, lng: 4.9041, name: 'Amsterdam (Position actuelle)', time: '15/01/2025 12:15' },
                    { lat: 53.3498, lng: -6.2603, name: 'Dublin (Destination)', time: '15/01/2025 14:45' }
                ],
                currentIndex: 2
            },
            'CG345678': {
                title: 'Colis #CG345678',
                type: 'Transport Routier',
                sender: 'Marc Leroy',
                recipient: 'Julie Bernard',
                weight: '8.7 kg',
                value: '320 €',
                status: 'En livraison',
                location: 'Centre de tri Lyon',
                progress: 90,
                route: [
                    { lat: 45.7640, lng: 4.8357, name: 'Lyon (Départ)', time: '14/01/2025 07:00' },
                    { lat: 46.5197, lng: 6.6323, name: 'Lausanne', time: '14/01/2025 11:30' },
                    { lat: 46.9481, lng: 7.4474, name: 'Berne', time: '14/01/2025 14:20' },
                    { lat: 47.3769, lng: 8.5417, name: 'Zurich (Position actuelle)', time: '15/01/2025 09:45' },
                    { lat: 47.0502, lng: 8.3093, name: 'Lucerne (Destination)', time: '15/01/2025 16:00' }
                ],
                currentIndex: 3
            }
        };

        // Initialisation de la carte
        function initMap() {
            map = L.map('map').setView([46.603354, 1.888334], 6);
            
            // Couche de base OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
        }

        // Gestion du formulaire de recherche
        document.getElementById('trackingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const code = document.getElementById('trackingCode').value.trim().toUpperCase();
            searchPackage(code);
        });

        // Recherche d'un colis
        function searchPackage(code) {
            document.getElementById('trackingCode').value = code;
            
            if (packageData[code]) {
                displayPackageInfo(packageData[code]);
                displayRoute(packageData[code]);
                document.getElementById('trackingResults').classList.remove('hidden');
                document.getElementById('errorMessage').classList.add('hidden');
            } else {
                document.getElementById('trackingResults').classList.add('hidden');
                document.getElementById('errorMessage').classList.remove('hidden');
            }
        }

        // Affichage des informations du colis
        function displayPackageInfo(data) {
            document.getElementById('packageTitle').textContent = data.title;
            document.getElementById('packageType').textContent = data.type;
            document.getElementById('sender').textContent = data.sender;
            document.getElementById('recipient').textContent = data.recipient;
            document.getElementById('weight').textContent = data.weight;
            document.getElementById('value').textContent = data.value;
            document.getElementById('currentStatus').textContent = data.status;
            document.getElementById('currentLocation').textContent = data.location;
            document.getElementById('progressPercent').textContent = data.progress + '%';
            document.getElementById('progressBar').style.width = data.progress + '%';
            
            // Historique
            const historyContainer = document.getElementById('trackingHistory');
            historyContainer.innerHTML = '';
            
            data.route.forEach((point, index) => {
                const isCompleted = index <= data.currentIndex;
                const isCurrent = index === data.currentIndex;
                
                const historyItem = document.createElement('div');
                historyItem.className = 'flex items-center';
                historyItem.innerHTML = `
                    <div class="w-3 h-3 ${isCompleted ? (isCurrent ? 'bg-primary animate-pulse' : 'bg-accent') : 'bg-gray-300'} rounded-full mr-3"></div>
                    <div class="flex-1">
                        <span class="text-sm ${isCompleted ? 'text-gray-900' : 'text-gray-500'}">${point.name}</span>
                        <div class="text-xs text-gray-500">${point.time}</div>
                    </div>
                `;
                historyContainer.appendChild(historyItem);
            });
        }

        // Affichage de la route sur la carte
        function displayRoute(data) {
            // Nettoyer la carte
            if (currentMarker) map.removeLayer(currentMarker);
            if (routeLine) map.removeLayer(routeLine);
            if (animationMarker) map.removeLayer(animationMarker);
            
            const route = data.route;
            const currentIndex = data.currentIndex;
            
            // Trajet parcouru (ligne bleue)
            const completedRoute = route.slice(0, currentIndex + 1);
            if (completedRoute.length > 1) {
                routeLine = L.polyline(completedRoute.map(p => [p.lat, p.lng]), {
                    color: '#3b82f6',
                    weight: 4,
                    opacity: 0.8
                }).addTo(map);
            }
            
            // Trajet restant (ligne pointillée)
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
                let color, icon;
                if (index === 0) {
                    color = 'green';
                    icon = 'play';
                } else if (index === route.length - 1) {
                    color = 'red';
                    icon = 'flag';
                } else if (index === currentIndex) {
                    color = 'blue';
                    icon = 'shipping-fast';
                } else if (index < currentIndex) {
                    color = 'green';
                    icon = 'check';
                } else {
                    color = 'gray';
                    icon = 'circle';
                }
                
                const marker = L.marker([point.lat, point.lng], {
                    icon: L.divIcon({
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

        // Centrer la carte sur le colis
        function centerMapOnPackage() {
            if (currentMarker) {
                map.setView(currentMarker.getLatLng(), 10);
                currentMarker.openPopup();
            }
        }

        // Animation du trajet
        function toggleAnimation() {
            const code = document.getElementById('trackingCode').value.trim().toUpperCase();
            const data = packageData[code];
            
            if (!data) return;
            
            if (isAnimating) {
                stopAnimation();
            } else {
                startAnimation(data);
            }
        }

        function startAnimation(data) {
            isAnimating = true;
            document.getElementById('animationIcon').className = 'fas fa-pause mr-2';
            document.getElementById('animationText').textContent = 'Arrêter l\'animation';
            
            const route = data.route;
            let currentStep = 0;
            
            // Créer un marqueur d'animation
            animationMarker = L.marker([route[0].lat, route[0].lng], {
                icon: L.divIcon({
                    className: 'animation-marker',
                    html: `<div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center text-white shadow-lg animate-bounce">
                             <i class="fas fa-truck text-xs"></i>
                           </div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                })
            }).addTo(map);
            
            function animateStep() {
                if (!isAnimating || currentStep >= route.length - 1) {
                    stopAnimation();
                    return;
                }
                
                const start = route[currentStep];
                const end = route[currentStep + 1];
                const duration = 2000; // 2 secondes par segment
                const steps = 50;
                let step = 0;
                
                const interval = setInterval(() => {
                    if (!isAnimating) {
                        clearInterval(interval);
                        return;
                    }
                    
                    const progress = step / steps;
                    const lat = start.lat + (end.lat - start.lat) * progress;
                    const lng = start.lng + (end.lng - start.lng) * progress;
                    
                    animationMarker.setLatLng([lat, lng]);
                    
                    step++;
                    if (step > steps) {
                        clearInterval(interval);
                        currentStep++;
                        setTimeout(animateStep, 500); // Pause entre les segments
                    }
                }, duration / steps);
            }
            
            animateStep();
        }

        function stopAnimation() {
            isAnimating = false;
            document.getElementById('animationIcon').className = 'fas fa-play mr-2';
            document.getElementById('animationText').textContent = 'Voir l\'animation';
            
            if (animationMarker) {
                map.removeLayer(animationMarker);
                animationMarker = null;
            }
        }

        // Changer la vue de la carte
        function toggleMapView() {
            // Cette fonction pourrait basculer entre différentes couches de carte
            // Pour la démonstration, on change juste le texte
            const viewText = document.getElementById('mapViewText');
            if (currentMapView === 'street') {
                viewText.textContent = 'Plan';
                currentMapView = 'satellite';
            } else {
                viewText.textContent = 'Satellite';
                currentMapView = 'street';
            }
        }

        // Mode plein écran
        function toggleFullscreen() {
            const mapContainer = document.getElementById('map');
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

        // Réinitialiser la recherche
        function resetSearch() {
            document.getElementById('trackingCode').value = '';
            document.getElementById('trackingResults').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
            
            // Nettoyer la carte
            if (currentMarker) map.removeLayer(currentMarker);
            if (routeLine) map.removeLayer(routeLine);
            if (animationMarker) map.removeLayer(animationMarker);
            
            stopAnimation();
        }

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            
            // Animation d'entrée
            const elements = document.querySelectorAll('.animate-slide-up');
            elements.forEach((el, index) => {
                el.style.animationDelay = (index * 0.1) + 's';
            });
        });
    </script>

     <style>
        .custom-marker {
            background: transparent !important;
            border: none !important;
        }
        
        .animation-marker {
            background: transparent !important;
            border: none !important;
        }
        
        .leaflet-popup-content-wrapper {
            border-radius: 8px;
        }
        
        .leaflet-popup-content {
            margin: 8px 12px;
        }
    </style>
</body>
</html>

