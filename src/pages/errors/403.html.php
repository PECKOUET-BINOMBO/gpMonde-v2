<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès Interdit - CargoTrack</title>
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
                        'lock-shake': 'lockShake 0.6s ease-in-out',
                        'warning-pulse': 'warningPulse 2s ease-in-out infinite',
                        'scan-line': 'scanLine 3s linear infinite'
                    },
                    keyframes: {
                        lockShake: {
                            '0%, 100%': { transform: 'rotate(0deg)' },
                            '25%': { transform: 'rotate(-5deg)' },
                            '75%': { transform: 'rotate(5deg)' }
                        },
                        warningPulse: {
                            '0%, 100%': { opacity: '1', transform: 'scale(1)' },
                            '50%': { opacity: '0.7', transform: 'scale(1.05)' }
                        },
                        scanLine: {
                            '0%': { transform: 'translateX(-100%)' },
                            '100%': { transform: 'translateX(100%)' }
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="h-full bg-gradient-to-br from-red-50 to-pink-100">
    <div class="min-h-full flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="index.html" class="flex items-center">
                            <i class="fas fa-shipping-fast text-primary text-2xl mr-3"></i>
                            <h1 class="text-xl font-bold text-primary">CargoTrack</h1>
                        </a>
                    </div>
                    <nav class="hidden md:flex space-x-8">
                        <a href="index.html" class="text-gray-700 hover:text-primary transition-colors">Accueil</a>
                        <a href="index.html#suivi" class="text-gray-700 hover:text-primary transition-colors">Suivi Colis</a>
                        <a href="dashboard.html" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition-colors">Espace Gestionnaire</a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <!-- Illustration -->
                <div class="mb-8">
                    <div class="relative inline-block">
                        <!-- Zone sécurisée -->
                        <div class="relative">
                            <!-- Cadenas principal -->
                            <div class="w-32 h-32 mx-auto mb-6 relative">
                                <div class="w-20 h-24 bg-gradient-to-b from-red-600 to-red-800 rounded-lg mx-auto relative animate-lock-shake">
                                    <!-- Corps du cadenas -->
                                    <div class="absolute top-4 left-1/2 transform -translate-x-1/2 w-3 h-3 bg-red-300 rounded-full"></div>
                                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white text-xs font-bold">403</div>
                                </div>
                                <!-- Anse du cadenas -->
                                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 w-12 h-12 border-4 border-red-600 rounded-t-full bg-transparent"></div>
                            </div>
                        </div>
                        
                        <!-- Barrières de sécurité -->
                        <div class="flex justify-center space-x-4 mb-6">
                            <div class="w-1 h-16 bg-red-500 transform rotate-12 animate-warning-pulse"></div>
                            <div class="w-1 h-16 bg-yellow-500 transform -rotate-12 animate-warning-pulse" style="animation-delay: 0.5s;"></div>
                            <div class="w-1 h-16 bg-red-500 transform rotate-12 animate-warning-pulse" style="animation-delay: 1s;"></div>
                        </div>
                        
                        <!-- Scanner de sécurité -->
                        <div class="relative w-64 h-2 bg-gray-200 rounded-full mx-auto mb-4 overflow-hidden">
                            <div class="absolute top-0 left-0 w-16 h-full bg-gradient-to-r from-transparent via-red-500 to-transparent animate-scan-line"></div>
                        </div>
                        
                        <!-- Icône d'alerte -->
                        <div class="animate-warning-pulse">
                            <i class="fas fa-exclamation-triangle text-4xl text-red-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Titre et message -->
                <div class="mb-8">
                    <h1 class="text-6xl md:text-8xl font-bold text-red-600 mb-4">403</h1>
                    <h2 class="text-2xl md:text-4xl font-bold text-gray-900 mb-4">
                        Accès Interdit
                    </h2>
                    <p class="text-lg md:text-xl text-gray-600 mb-2">
                        Vous n'avez pas l'autorisation d'accéder à cette zone sécurisée.
                    </p>
                    <p class="text-md text-gray-500">
                        Cette section est réservée au personnel autorisé uniquement.
                    </p>
                </div>
            </div>
        </main>

        
    </div>

    <script>
        // Afficher l'heure d'accès
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('access-time').textContent = new Date().toLocaleString();
        });

        // Animation d'entrée
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('main > div > *');
            elements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Fonction pour demander l'accès
        function requestAccess() {
            const currentUrl = encodeURIComponent(window.location.href);
            const timestamp = new Date().toISOString();
            const subject = encodeURIComponent('Demande d\'accès - CargoTrack');
            const body = encodeURIComponent(`Bonjour,\n\nJe souhaite demander l'accès à la page suivante :\n\nURL : ${decodeURIComponent(currentUrl)}\nHeure de la demande : ${new Date().toLocaleString()}\n\nRaison de la demande :\n[Veuillez expliquer pourquoi vous avez besoin d'accéder à cette page]\n\nCordialement`);
            
            window.location.href = `mailto:admin@cargotrack.fr?subject=${subject}&body=${body}`;
        }

        // Effet de sécurité - clignotement des barrières
        setInterval(() => {
            const barriers = document.querySelectorAll('.animate-warning-pulse');
            barriers.forEach(barrier => {
                barrier.style.animationDuration = Math.random() * 2 + 1 + 's';
            });
        }, 3000);

        // Simulation d'IP utilisateur (en production, cela viendrait du serveur)
        document.addEventListener('DOMContentLoaded', function() {
            // Simulation d'une IP pour la démonstration
            const simulatedIP = '192.168.1.' + Math.floor(Math.random() * 255);
            document.querySelector('span.font-mono').textContent = simulatedIP;
        });
    </script>
</body>
</html>

