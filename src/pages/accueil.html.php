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
                    }
                }
            }
        }
    </script>
    <link href="/https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
            
            <div class="bg-gray-50 rounded-2xl p-8">
                <div class="max-w-md mx-auto">
                    <div class="mb-6">
                        <label for="tracking-code" class="block text-sm font-medium text-gray-700 mb-2">
                            Code de suivi
                        </label>
                        <div class="relative">
                            <input type="text" id="tracking-code" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Entrez votre code de suivi">
                            <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <button onclick="trackPackage()" 
                            class="w-full bg-primary hover:bg-blue-800 text-white py-3 rounded-lg font-semibold transition-colors">
                        Rechercher
                    </button>
                </div>
                
                <!-- Résultat de recherche (caché par défaut) -->
                <div id="tracking-result" class="hidden mt-8 p-6 bg-white rounded-lg border">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-box text-primary text-2xl mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900">Colis#CG123456</h4>
                            <p class="text-sm text-gray-600">Type: Maritime</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-accent rounded-full mr-3"></div>
                            <span class="text-sm text-gray-700">Colis enregistré</span>
                            <span class="ml-auto text-xs text-gray-500">12/01/2025</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-accent rounded-full mr-3"></div>
                            <span class="text-sm text-gray-700">En cours de transport</span>
                            <span class="ml-auto text-xs text-gray-500">15/01/2025</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gray-300 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-500">Arrivée prévue dans 3 jours</span>
                        </div>
                    </div>
                </div>
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
        function trackPackage() {
            const code = document.getElementById('tracking-code').value;
            const result = document.getElementById('tracking-result');
            
            if (code.trim()) {
                result.classList.remove('hidden');
                // Simulation d'une recherche
                setTimeout(() => {
                    result.scrollIntoView({ behavior: 'smooth' });
                }, 100);
            } else {
                alert('Veuillez entrer un code de suivi');
            }
        }
        
        // Permettre la recherche avec Enter
        document.getElementById('tracking-code').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                trackPackage();
            }
        });
    </script>
</body>
</html>

