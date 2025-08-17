<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche et Gestion - CargoTrack</title>
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

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Recherche -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Code du colis</label>
                            <input type="text" id="search-code" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" placeholder="CG123456">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Code de la cargaison</label>
                            <input type="text" id="search-cargo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" placeholder="CG-MAR-001">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom du client</label>
                            <input type="text" id="search-client" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" placeholder="Nom ou prénom">
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button onclick="clearSearch()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                            Effacer
                        </button>
                        <button onclick="searchPackages()" class="px-4 py-2 bg-primary hover:bg-blue-800 text-white rounded-md transition-colors">
                            <i class="fas fa-search mr-2"></i>
                            Rechercher
                        </button>
                    </div>
                </div>

                <!-- Résultats de recherche -->
                <div id="search-results" class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Résultats de la Recherche</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code Colis</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expéditeur</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destinataire</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cargaison</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poids</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">État</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">CG123456</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Jean Dupont</div>
                                        <div class="text-sm text-gray-500">+221 77 123 45 67</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Marie Martin</div>
                                        <div class="text-sm text-gray-500">+221 77 123 45 67</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">CG-MAR-001</div>
                                        <div class="text-sm text-gray-500">Maritime</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">5.2 kg</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            En cours
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button onclick="viewPackageDetails('CG123456')" class="text-primary hover:text-blue-800" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button onclick="changePackageStatus('CG123456', 'recupere')" class="text-accent hover:text-green-600" title="Marquer récupéré">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button onclick="changePackageStatus('CG123456', 'perdu')" class="text-red-600 hover:text-red-800" title="Marquer perdu">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </button>
                                            <button onclick="archivePackage('CG123456')" class="text-gray-600 hover:text-gray-800" title="Archiver">
                                                <i class="fas fa-archive"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">CG789012</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Pierre Durand</div>
                                        <div class="text-sm text-gray-500">+221 77 123 45 67</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Sophie Leroy</div>
                                        <div class="text-sm text-gray-500">+221 77 123 45 67</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">CG-AER-002</div>
                                        <div class="text-sm text-gray-500">Aérien</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2.8 kg</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Arrivé
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button onclick="viewPackageDetails('CG789012')" class="text-primary hover:text-blue-800" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button onclick="changePackageStatus('CG789012', 'recupere')" class="text-accent hover:text-green-600" title="Marquer récupéré">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button onclick="changePackageStatus('CG789012', 'perdu')" class="text-red-600 hover:text-red-800" title="Marquer perdu">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </button>
                                            <button onclick="archivePackage('CG789012')" class="text-gray-600 hover:text-gray-800" title="Archiver">
                                                <i class="fas fa-archive"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">CG345678</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Anne Moreau</div>
                                        <div class="text-sm text-gray-500">+221 77 123 45 67</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Luc Bernard</div>
                                        <div class="text-sm text-gray-500">+221 77 123 45 67</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">CG-ROU-003</div>
                                        <div class="text-sm text-gray-500">Routier</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">12.5 kg</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            En attente
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button onclick="viewPackageDetails('CG345678')" class="text-primary hover:text-blue-800" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button onclick="changePackageStatus('CG345678', 'recupere')" class="text-accent hover:text-green-600" title="Marquer récupéré">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button onclick="changePackageStatus('CG345678', 'perdu')" class="text-red-600 hover:text-red-800" title="Marquer perdu">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </button>
                                            <button onclick="archivePackage('CG345678')" class="text-gray-600 hover:text-gray-800" title="Archiver">
                                                <i class="fas fa-archive"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Détails du Colis -->
    <div id="packageDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-medium text-gray-900">Détails du Colis</h3>
                    <button onclick="closePackageDetailsModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Informations du colis -->
                    <div class="space-y-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Informations du Colis</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Code:</span>
                                    <span class="font-medium" id="detail-code">CG123456</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Poids:</span>
                                    <span class="font-medium" id="detail-weight">5.2 kg</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Type de produit:</span>
                                    <span class="font-medium" id="detail-product-type">Électronique</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Valeur déclarée:</span>
                                    <span class="font-medium" id="detail-value">250.00 €</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Prix transport:</span>
                                    <span class="font-medium" id="detail-price">13.00 €</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Expéditeur</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nom:</span>
                                    <span class="font-medium" id="detail-sender-name">Jean Dupont</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Téléphone:</span>
                                    <span class="font-medium" id="detail-sender-phone">+221 77 123 45 67</div></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-medium" id="detail-sender-email">jean.dupont@email.com</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Destinataire</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nom:</span>
                                    <span class="font-medium" id="detail-recipient-name">Marie Martin</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Téléphone:</span>
                                    <span class="font-medium" id="detail-recipient-phone">+221 77 123 45 67</div></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-medium" id="detail-recipient-email">marie.martin@email.com</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Suivi et actions -->
                    <div class="space-y-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Cargaison</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Code:</span>
                                    <span class="font-medium" id="detail-cargo-code">CG-MAR-001</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Type:</span>
                                    <span class="font-medium" id="detail-cargo-type">Maritime</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Trajet:</span>
                                    <span class="font-medium" id="detail-cargo-route">Marseille → Alger</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">État Actuel</h4>
                            <div class="flex items-center mb-3">
                                <span id="detail-status-badge" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    En cours
                                </span>
                            </div>
                            <p class="text-sm text-gray-600" id="detail-status-description">
                                Le colis est en cours de transport. Arrivée prévue dans 3 jours.
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Changer l'État</h4>
                            <div class="space-y-3">
                                <select id="new-status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                                    <option value="">Sélectionner un nouvel état...</option>
                                    <option value="en_attente">En attente</option>
                                    <option value="en_cours">En cours</option>
                                    <option value="arrive">Arrivé</option>
                                    <option value="recupere">Récupéré</option>
                                    <option value="perdu">Perdu</option>
                                    <option value="archive">Archivé</option>
                                </select>
                                <button onclick="updatePackageStatus()" class="w-full bg-primary hover:bg-blue-800 text-white py-2 px-4 rounded-md font-medium transition-colors">
                                    Mettre à jour l'état
                                </button>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Actions Rapides</h4>
                            <div class="space-y-2">
                                <button onclick="markAsRetrieved()" class="w-full bg-accent hover:bg-green-600 text-white py-2 px-4 rounded-md font-medium transition-colors">
                                    <i class="fas fa-check mr-2"></i>
                                    Marquer comme récupéré
                                </button>
                                <button onclick="markAsLost()" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md font-medium transition-colors">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Marquer comme perdu
                                </button>
                                <button onclick="archivePackageFromModal()" class="w-full bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-md font-medium transition-colors">
                                    <i class="fas fa-archive mr-2"></i>
                                    Archiver
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end mt-6">
                    <button onclick="closePackageDetailsModal()" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-md font-medium transition-colors">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPackageCode = null;
        
        function searchPackages() {
            const code = document.getElementById('search-code').value;
            const cargo = document.getElementById('search-cargo').value;
            const client = document.getElementById('search-client').value;
            
            // Simulation de recherche
            console.log('Recherche:', { code, cargo, client });
            // Ici, on ferait un appel API pour récupérer les résultats
        }
        
        function clearSearch() {
            document.getElementById('search-code').value = '';
            document.getElementById('search-cargo').value = '';
            document.getElementById('search-client').value = '';
        }
        
        function viewPackageDetails(packageCode) {
            currentPackageCode = packageCode;
            // Ici, on chargerait les vraies données du colis
            document.getElementById('packageDetailsModal').classList.remove('hidden');
        }
        
        function closePackageDetailsModal() {
            document.getElementById('packageDetailsModal').classList.add('hidden');
            currentPackageCode = null;
        }
        
        function changePackageStatus(packageCode, status) {
            const statusNames = {
                'recupere': 'récupéré',
                'perdu': 'perdu',
                'archive': 'archivé'
            };
            
            if (confirm(`Êtes-vous sûr de vouloir marquer ce colis comme ${statusNames[status]}?`)) {
                alert(`Colis ${packageCode} marqué comme ${statusNames[status]}`);
                // Ici, on ferait un appel API pour mettre à jour le statut
            }
        }
        
        function archivePackage(packageCode) {
            changePackageStatus(packageCode, 'archive');
        }
        
        function updatePackageStatus() {
            const newStatus = document.getElementById('new-status').value;
            if (newStatus && currentPackageCode) {
                alert(`État du colis ${currentPackageCode} mis à jour: ${newStatus}`);
                closePackageDetailsModal();
            } else {
                alert('Veuillez sélectionner un nouvel état');
            }
        }
        
        function markAsRetrieved() {
            if (currentPackageCode) {
                alert(`Colis ${currentPackageCode} marqué comme récupéré`);
                closePackageDetailsModal();
            }
        }
        
        function markAsLost() {
            if (currentPackageCode) {
                if (confirm('Êtes-vous sûr de vouloir marquer ce colis comme perdu?')) {
                    alert(`Colis ${currentPackageCode} marqué comme perdu`);
                    closePackageDetailsModal();
                }
            }
        }
        
        function archivePackageFromModal() {
            if (currentPackageCode) {
                if (confirm('Êtes-vous sûr de vouloir archiver ce colis?')) {
                    alert(`Colis ${currentPackageCode} archivé`);
                    closePackageDetailsModal();
                }
            }
        }
        
        // Fermer la modal en cliquant à l'extérieur
        document.getElementById('packageDetailsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePackageDetailsModal();
            }
        });
        
        // Recherche en temps réel
        document.getElementById('search-code').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchPackages();
            }
        });
    </script>
    <script type="module" src="../../dist/models/recherche.js"></script>
    

</body>
</html>

