<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur Serveur - CargoTrack</title>
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
                        'shake': 'shake 0.5s ease-in-out infinite',
                        'blink': 'blink 1s ease-in-out infinite',
                        'gear': 'spin 3s linear infinite',
                        'gear-reverse': 'spin 4s linear infinite reverse'
                    },
                    keyframes: {
                        shake: {
                            '0%, 100%': { transform: 'translateX(0)' },
                            '25%': { transform: 'translateX(-2px)' },
                            '75%': { transform: 'translateX(2px)' }
                        },
                        blink: {
                            '0%, 50%': { opacity: '1' },
                            '51%, 100%': { opacity: '0.3' }
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="h-full bg-gradient-to-br from-red-50 to-orange-100">
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
                        <!-- Serveur en panne -->
                        <div class="relative">
                            <div class="w-40 h-32 bg-gradient-to-b from-gray-700 to-gray-900 rounded-lg shadow-xl mx-auto mb-4 animate-shake">
                                <!-- Écran du serveur -->
                                <div class="w-24 h-16 bg-red-600 rounded-md mx-auto mt-4 relative animate-blink">
                                    <div class="absolute inset-2 bg-red-400 rounded opacity-50"></div>
                                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white text-xs font-bold">
                                        ERROR
                                    </div>
                                </div>
                                <!-- Voyants -->
                                <div class="flex justify-center space-x-2 mt-2">
                                    <div class="w-2 h-2 bg-red-500 rounded-full animate-blink"></div>
                                    <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                                    <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Engrenages cassés -->
                        <div class="flex justify-center space-x-4 mb-6">
                            <i class="fas fa-cog text-4xl text-red-500 animate-gear"></i>
                            <i class="fas fa-cog text-3xl text-orange-500 animate-gear-reverse" style="animation-duration: 2s;"></i>
                            <i class="fas fa-cog text-4xl text-red-600 animate-gear" style="animation-duration: 5s;"></i>
                        </div>
                        
                        <!-- Fumée/vapeur -->
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                            <div class="w-8 h-8 bg-gray-400 rounded-full opacity-30 animate-ping"></div>
                        </div>
                    </div>
                </div>

                <!-- Titre et message -->
                <div class="mb-8">
                    <h1 class="text-6xl md:text-8xl font-bold text-red-600 mb-4">500</h1>
                    <h2 class="text-2xl md:text-4xl font-bold text-gray-900 mb-4">
                        Problème Technique
                    </h2>
                    <p class="text-lg md:text-xl text-gray-600 mb-2">
                        Nos serveurs rencontrent actuellement des difficultés techniques.
                    </p>
                    <p class="text-md text-gray-500">
                        Notre équipe technique travaille activement pour résoudre le problème.
                    </p>
                </div>
            </div>
        </main>

       
    </div>

    <script>
        // Générer un timestamp pour l'ID d'incident
        document.addEventListener('DOMContentLoaded', function() {
            const timestamp = Date.now().toString(36).toUpperCase();
            document.querySelector('span.font-mono').textContent = `CG-ERR-500-${timestamp}`;
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

        // Fonction pour signaler un problème
        function reportProblem() {
            const incidentId = document.querySelector('span.font-mono').textContent;
            const subject = encodeURIComponent(`Rapport d'incident - ${incidentId}`);
            const body = encodeURIComponent(`Bonjour,\n\nJe souhaite signaler un problème technique rencontré sur CargoTrack.\n\nID de l'incident : ${incidentId}\nHeure : ${new Date().toLocaleString()}\nPage : ${window.location.href}\n\nDescription du problème :\n[Veuillez décrire le problème rencontré]\n\nCordialement`);
            
            window.location.href = `mailto:support@cargotrack.fr?subject=${subject}&body=${body}`;
        }

        // Auto-refresh après 30 secondes
        let countdown = 30;
        const refreshButton = document.querySelector('button[onclick="location.reload()"]');
        const originalText = refreshButton.innerHTML;
        
        const countdownInterval = setInterval(() => {
            countdown--;
            refreshButton.innerHTML = `<i class="fas fa-redo mr-2"></i>Réessayer (${countdown}s)`;
            
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                refreshButton.innerHTML = originalText;
                // Optionnel : auto-refresh
                // location.reload();
            }
        }, 1000);
    </script>
</body>
</html>

