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
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            }
                        },
                        slideUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
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
        crossorigin="" />
    <!-- Ajoute Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    <script type="importmap">
        {
  "imports": {
    "leaflet": "https://unpkg.com/leaflet@1.9.4/dist/leaflet-src.esm.js"
  }
}
</script>
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
                                    <h3 class="text-lg font-semibold text-gray-900" id="packageTitle"></h3>
                                    <p class="text-sm text-gray-600" id="packageType"></p>
                                </div>
                            </div>

                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Expéditeur:</span>
                                    <span class="text-sm font-medium" id="sender"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Destinataire:</span>
                                    <span class="text-sm font-medium" id="recipient"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Lieu de départ:</span>
                                    <span class="text-sm font-medium" id="departure"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Lieu d'arrivée:</span>
                                    <span class="text-sm font-medium" id="arrival"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Poids:</span>
                                    <span class="text-sm font-medium" id="weight"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Montant:</span>
                                    <span class="text-sm font-medium" id="value"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">État :</span>
                                    <span class="text-sm font-medium" id="packageStatus"></span>
                                </div>
                                <div class="flex justify-between">
    <span class="text-sm text-gray-600">Description :</span>
    <span class="text-sm font-medium" id="description"></span>
</div>
                            </div>

                            <!-- Actions -->
                            <div class="space-y-3">
                                <button onclick="centerMapOnPackage()"
                                    class="w-full px-4 py-2 bg-secondary hover:bg-orange-600 text-white rounded-lg transition-colors">
                                    <i class="fas fa-crosshairs mr-2"></i>
                                    Centrer sur le colis
                                </button>

                            </div>
                        </div>


                    </div>

                    <!-- Carte -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Localisation en temps réel</h3>
                                <div class="flex items-center space-x-2">

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
                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                    <span class="text-gray-600">Destination</span>
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

    <script type="module" src="../../dist/models/accueil.js"></script>

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