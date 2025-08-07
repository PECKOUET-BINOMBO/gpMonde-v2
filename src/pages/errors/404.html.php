<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page non trouvée - CargoTrack</title>
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
                        'float': 'float 3s ease-in-out infinite',
                        'bounce-slow': 'bounce 2s infinite',
                        'pulse-slow': 'pulse 3s infinite'
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' }
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="h-full bg-gradient-to-br from-blue-50 to-indigo-100">
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
                        <!-- Container perdu -->
                        <div class="animate-float">
                            <div class="w-32 h-20 bg-gradient-to-r from-primary to-blue-600 rounded-lg shadow-lg relative mx-auto mb-4">
                                <div class="absolute top-2 left-2 w-4 h-4 bg-white rounded opacity-30"></div>
                                <div class="absolute top-2 right-2 w-4 h-4 bg-white rounded opacity-30"></div>
                                <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 text-white text-xs font-bold">404</div>
                            </div>
                        </div>
                        
                        <!-- Points de suspension animés -->
                        <div class="flex justify-center space-x-2 mb-4">
                            <div class="w-2 h-2 bg-secondary rounded-full animate-bounce" style="animation-delay: 0s"></div>
                            <div class="w-2 h-2 bg-secondary rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-2 h-2 bg-secondary rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                        
                        <!-- Icône de recherche -->
                        <div class="animate-pulse-slow">
                            <i class="fas fa-search text-6xl text-gray-400 mb-6"></i>
                        </div>
                    </div>
                </div>

                <!-- Titre et message -->
                <div class="mb-8">
                    <h1 class="text-6xl md:text-8xl font-bold text-primary mb-4">404</h1>
                    <h2 class="text-2xl md:text-4xl font-bold text-gray-900 mb-4">
                        Cargaison Introuvable
                    </h2>
                    <p class="text-lg md:text-xl text-gray-600 mb-2">
                        Oups ! La page que vous recherchez semble avoir pris un autre itinéraire.
                    </p>
                    <p class="text-md text-gray-500">
                        Elle pourrait être en transit vers une nouvelle destination ou avoir été déplacée.
                    </p>
                </div>
            </div>
        </main>

    </div>

    <script>
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

        // Effet de particules flottantes
        function createFloatingParticle() {
            const particle = document.createElement('div');
            particle.className = 'fixed w-2 h-2 bg-blue-200 rounded-full opacity-30 pointer-events-none';
            particle.style.left = Math.random() * window.innerWidth + 'px';
            particle.style.top = window.innerHeight + 'px';
            particle.style.animation = 'float 8s linear infinite';
            document.body.appendChild(particle);

            setTimeout(() => {
                particle.remove();
            }, 8000);
        }

        // Créer des particules périodiquement
        setInterval(createFloatingParticle, 2000);
    </script>
</body>
</html>

