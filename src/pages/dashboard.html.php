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
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
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
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                            <option>Maritime</option>
                            <option>Aérien</option>
                            <option>Routier</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lieu de départ</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" placeholder="Ville de départ">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lieu d'arrivée</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" placeholder="Ville d'arrivée">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Poids maximum (kg)</label>
                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" placeholder="1000">
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
        function openNewCargoModal() {
            document.getElementById('newCargoModal').classList.remove('hidden');
        }

        function closeNewCargoModal() {
            document.getElementById('newCargoModal').classList.add('hidden');
        }

        // Fermer la modal en cliquant à l'extérieur
        document.getElementById('newCargoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeNewCargoModal();
            }
        });
    </script>
</body>

</html>