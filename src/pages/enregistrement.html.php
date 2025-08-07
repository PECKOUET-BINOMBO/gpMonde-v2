<!DOCTYPE html>
<html lang="fr" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrement Colis - CargoTrack</title>
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
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

                    <!-- Titre -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Enregistrement d'un Nouveau Colis</h2>
                        <p class="text-lg text-gray-600">Remplissez les informations du client et du colis</p>
                    </div>

                    <!-- Formulaire -->
                    <form id="packageForm" class="space-y-8">
                        <!-- Informations Client -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center mb-6">
                                <i class="fas fa-user text-primary text-xl mr-3"></i>
                                <h3 class="text-xl font-semibold text-gray-900">Informations de l'Expéditeur</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                                    <input type="text" id="nom" name="nom" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="Nom de famille">
                                </div>

                                <div>
                                    <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                                    <input type="text" id="prenom" name="prenom" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="Prénom">
                                </div>

                                <div>
                                    <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone *</label>
                                    <input type="tel" id="telephone" name="telephone" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="+221 77 123 45 67">
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" id="email" name="email"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="email@exemple.com">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">Adresse *</label>
                                    <textarea id="adresse" name="adresse" rows="3" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="Adresse complète"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Informations Destinataire -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center mb-6">
                                <i class="fas fa-user-tag text-secondary text-xl mr-3"></i>
                                <h3 class="text-xl font-semibold text-gray-900">Informations du Destinataire</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="dest_nom" class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                                    <input type="text" id="dest_nom" name="dest_nom" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="Nom du destinataire">
                                </div>

                                <div>
                                    <label for="dest_prenom" class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                                    <input type="text" id="dest_prenom" name="dest_prenom" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="Prénom du destinataire">
                                </div>

                                <div>
                                    <label for="dest_telephone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone *</label>
                                    <input type="tel" id="dest_telephone" name="dest_telephone" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="+221 77 123 45 67">
                                </div>

                                <div>
                                    <label for="dest_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" id="dest_email" name="dest_email"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="email@exemple.com">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="dest_adresse" class="block text-sm font-medium text-gray-700 mb-2">Adresse *</label>
                                    <textarea id="dest_adresse" name="dest_adresse" rows="3" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="Adresse complète du destinataire"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Informations Colis -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center mb-6">
                                <i class="fas fa-box text-accent text-xl mr-3"></i>
                                <h3 class="text-xl font-semibold text-gray-900">Informations du Colis</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <label for="nombre_colis" class="block text-sm font-medium text-gray-700 mb-2">Nombre de colis *</label>
                                    <input type="number" id="nombre_colis" name="nombre_colis" min="1" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="1">
                                </div>

                                <div>
                                    <label for="poids" class="block text-sm font-medium text-gray-700 mb-2">Poids total (kg) *</label>
                                    <input type="number" id="poids" name="poids" step="0.1" min="0.1" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="5.5">
                                </div>

                                <div>
                                    <label for="type_produit" class="block text-sm font-medium text-gray-700 mb-2">Type de produit *</label>
                                    <select id="type_produit" name="type_produit" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent">
                                        <option value="">Sélectionner...</option>
                                        <option value="alimentaire">Alimentaire</option>
                                        <option value="chimique">Chimique</option>
                                        <option value="fragile">Matériel fragile</option>
                                        <option value="incassable">Matériel incassable</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="type_cargaison" class="block text-sm font-medium text-gray-700 mb-2">Type de transport *</label>
                                    <select id="type_cargaison" name="type_cargaison"  onchange="calculatePrice()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent">
                                        <option value="">Sélectionner...</option>
                                        <option value="maritime">Maritime</option>
                                        <option value="aerien">Aérien</option>
                                        <option value="routier">Routier</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="valeur_declaree" class="block text-sm font-medium text-gray-700 mb-2">Valeur déclarée (XAF)</label>
                                    <input type="number" id="valeur_declaree" name="valeur_declaree" step="0.01" min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="100.00">
                                </div> 

                                <div>
                                    <label for="prix_calcule" class="block text-sm font-medium text-gray-700 mb-2">Prix calculé (XAF)</label>
                                    <input type="text" id="prix_calcule" name="prix_calcule" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600"
                                        placeholder="Calculé automatiquement">
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description du contenu</label>
                                <textarea id="description" name="description" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="Description détaillée du contenu du colis"></textarea>
                            </div>
                        </div>

                        <!-- Cargaison Disponible -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center mb-6">
                                <i class="fas fa-ship text-primary text-xl mr-3"></i>
                                <h3 class="text-xl font-semibold text-gray-900">Cargaison Disponible</h3>
                            </div>

                            <div id="cargaisons-disponibles" class="space-y-3">
                                <div class="p-4 border border-gray-200 rounded-lg hover:border-primary cursor-pointer transition-colors" onclick="selectCargaison(this, 'CG-MAR-001')">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <input type="radio" name="cargaison" value="CG-MAR-001" class="mr-3">
                                            <div>
                                                <p class="font-medium text-gray-900">CG-MAR-001 - Maritime</p>
                                                <p class="text-sm text-gray-600">Marseille → Alger | Capacité restante: 150kg</p>
                                            </div>
                                        </div>
                                        <span class="text-sm text-green-600 font-medium">Ouvert</span>
                                    </div>
                                </div>

                                <div class="p-4 border border-gray-200 rounded-lg hover:border-primary cursor-pointer transition-colors" onclick="selectCargaison(this, 'CG-AER-002')">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <input type="radio" name="cargaison" value="CG-AER-002" class="mr-3">
                                            <div>
                                                <p class="font-medium text-gray-900">CG-AER-002 - Aérien</p>
                                                <p class="text-sm text-gray-600">Paris → Dakar | Capacité restante: 75kg</p>
                                            </div>
                                        </div>
                                        <span class="text-sm text-green-600 font-medium">Ouvert</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-end">
                            <button type="button" onclick="resetForm()"
                                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                                Réinitialiser
                            </button>
                            <button type="submit"
                                class="px-6 py-3 bg-primary hover:bg-blue-800 text-white rounded-lg font-medium transition-colors">
                                Enregistrer le Colis
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal de confirmation -->
    <div id="confirmationModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <i class="fas fa-check text-green-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Colis Enregistré</h3>
                <p class="text-sm text-gray-500 mb-4">Code de suivi généré:</p>
                <div class="bg-gray-100 p-3 rounded-lg mb-4">
                    <p id="tracking-code-display" class="text-lg font-mono font-bold text-primary"></p>
                </div>
                <p class="text-sm text-gray-500 mb-6">Ce code sera envoyé au destinataire par SMS/Email</p>
                <div class="flex space-x-3">
                    <button onclick="printReceipt()" class="flex-1 bg-secondary hover:bg-orange-600 text-white py-2 px-4 rounded-md font-medium transition-colors">
                        Imprimer Reçu
                    </button>
                    <button onclick="closeConfirmationModal()" class="flex-1 bg-primary hover:bg-blue-800 text-white py-2 px-4 rounded-md font-medium transition-colors">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function calculatePrice() {
            const poids = parseFloat(document.getElementById('poids').value) || 0;
            const typeCargaison = document.getElementById('type_cargaison').value;

            if (poids > 0 && typeCargaison) {
                let tarifParKg = 0;

                switch (typeCargaison) {
                    case 'maritime':
                        tarifParKg = 2.5;
                        break;
                    case 'aerien':
                        tarifParKg = 8.0;
                        break;
                    case 'routier':
                        tarifParKg = 4.0;
                        break;
                }

                let prix = poids * tarifParKg;
                // Prix minimum de 10€
                if (prix < 10) {
                    prix = 10;
                }

                document.getElementById('prix_calcule').value = prix.toFixed(2) + ' €';
            }
        }

        function selectCargaison(element, code) {
            // Désélectionner tous les autres
            document.querySelectorAll('#cargaisons-disponibles .border-primary').forEach(el => {
                el.classList.remove('border-primary', 'bg-blue-50');
                el.classList.add('border-gray-200');
            });

            // Sélectionner l'élément cliqué
            element.classList.remove('border-gray-200');
            element.classList.add('border-primary', 'bg-blue-50');
            element.querySelector('input[type="radio"]').checked = true;
        }

        function resetForm() {
            document.getElementById('packageForm').reset();
            document.getElementById('prix_calcule').value = '';

            // Réinitialiser la sélection des cargaisons
            document.querySelectorAll('#cargaisons-disponibles .border-primary').forEach(el => {
                el.classList.remove('border-primary', 'bg-blue-50');
                el.classList.add('border-gray-200');
            });
        }

        function generateTrackingCode() {
            const prefix = 'CG';
            const random = Math.random().toString(36).substr(2, 6).toUpperCase();
            return prefix + random;
        }

        function closeConfirmationModal() {
            document.getElementById('confirmationModal').classList.add('hidden');
            resetForm();
        }

        function printReceipt() {
            // Simulation d'impression
            alert('Reçu envoyé à l\'imprimante');
        }

        // Gestion du formulaire
        document.getElementById('packageForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validation
            const cargaisonSelected = document.querySelector('input[name="cargaison"]:checked');
            if (!cargaisonSelected) {
                alert('Veuillez sélectionner une cargaison');
                return;
            }

            // Générer le code de suivi
            const trackingCode = generateTrackingCode();
            document.getElementById('tracking-code-display').textContent = trackingCode;

            // Afficher la modal de confirmation
            document.getElementById('confirmationModal').classList.remove('hidden');
        });

        // Calculer le prix automatiquement quand le poids change
        document.getElementById('poids').addEventListener('input', calculatePrice);
    </script>
</body>

</html>