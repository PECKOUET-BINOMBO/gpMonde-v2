        <!DOCTYPE html>
        <html lang="fr" class="h-full">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Connexion Gestionnaire - CargoTrack</title>
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
                                'fade-in': 'fadeIn 0.6s ease-out',
                                'slide-up': 'slideUp 0.5s ease-out',
                                'pulse-slow': 'pulse 3s infinite',
                                'security-scan': 'securityScan 2s ease-in-out infinite'
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
                                },
                                securityScan: {
                                    '0%, 100%': {
                                        opacity: '0.5',
                                        transform: 'scale(1)'
                                    },
                                    '50%': {
                                        opacity: '1',
                                        transform: 'scale(1.05)'
                                    }
                                }
                            }
                        }
                    }
                }
            </script>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        </head>

        <body class="h-full bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
            <div class="min-h-full flex">
                <!-- Section gauche - Illustration et informations -->
                <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary to-blue-800 relative overflow-hidden">
                    <!-- Motif de fond -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-10 left-10 w-32 h-32 border border-white rounded-full"></div>
                        <div class="absolute top-32 right-20 w-24 h-24 border border-white rounded-full"></div>
                        <div class="absolute bottom-20 left-32 w-40 h-40 border border-white rounded-full"></div>
                        <div class="absolute bottom-32 right-10 w-20 h-20 border border-white rounded-full"></div>
                    </div>

                    <div class="relative z-10 flex flex-col justify-center px-12 py-12 text-white">
                        <!-- Logo et titre -->
                        <div class="mb-12">
                            <div class="flex items-center mb-6">
                                <i class="fas fa-shipping-fast text-4xl mr-4"></i>
                                <h1 class="text-3xl font-bold">CargoTrack</h1>
                            </div>
                            <h2 class="text-2xl font-semibold mb-4">Espace Gestionnaire</h2>
                            <p class="text-blue-100 text-lg leading-relaxed">
                                Accédez à votre tableau de bord pour gérer les cargaisons,
                                suivre les expéditions et administrer votre plateforme logistique.
                            </p>
                        </div>

                        <!-- Fonctionnalités -->
                        <div class="space-y-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-boxes text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold">Gestion des Cargaisons</h3>
                                    <p class="text-blue-200 text-sm">Maritime, aérienne et routière</p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-chart-line text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold">Suivi en Temps Réel</h3>
                                    <p class="text-blue-200 text-sm">Tableaux de bord et analytics</p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-shield-alt text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold">Sécurité Avancée</h3>
                                    <p class="text-blue-200 text-sm">Authentification multi-facteurs</p>
                                </div>
                            </div>
                        </div>

                        <!-- Statistiques -->
                        <div class="mt-12 grid grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="text-2xl font-bold">1,250+</div>
                                <div class="text-blue-200 text-sm">Cargaisons</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold">98.5%</div>
                                <div class="text-blue-200 text-sm">Fiabilité</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold">24/7</div>
                                <div class="text-blue-200 text-sm">Support</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section droite - Formulaire de connexion -->
                <div class="flex-1 flex flex-col justify-center px-6 py-12 lg:px-8">
                    <div class="sm:mx-auto sm:w-full sm:max-w-md">
                        <!-- Logo mobile -->
                        <div class="lg:hidden text-center mb-8">
                            <div class="flex items-center justify-center mb-4">
                                <i class="fas fa-shipping-fast text-primary text-3xl mr-3"></i>
                                <h1 class="text-2xl font-bold text-primary">CargoTrack</h1>
                            </div>
                            <p class="text-gray-600">Espace Gestionnaire</p>
                        </div>

                        <!-- Titre -->
                        <div class="text-center mb-8 animate-fade-in">
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">Connexion</h2>
                            <p class="text-gray-600">Accédez à votre espace de gestion</p>
                        </div>

                    </div>

                    <div class="sm:mx-auto sm:w-full sm:max-w-md">
                        <!-- Formulaire de connexion -->
                        <form id="loginForm" method="post" class="space-y-6 animate-slide-up" style="animation-delay: 0.2s;">
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Adresse email
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="text" id="email" name="email"
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                                        placeholder="gestionnaire@cargotrack.fr">
                                </div>
                                <div id="email-error" class="hidden mt-1 text-sm text-red-600"></div>
                            </div>

                            <!-- Mot de passe -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Mot de passe
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input type="password" id="password" name="password"
                                        class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                                        placeholder="••••••••">
                                    <button type="button"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i id="password-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                    </button>
                                </div>
                                <div id="password-error" class="hidden mt-1 text-sm text-red-600"></div>
                            </div>


                            <!-- Bouton de connexion -->
                            <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary ransition-colors transform hover:scale-105">
                                <span id="login-text">Se connecter</span>
                                <i id="login-spinner" class="hidden fas fa-spinner fa-spin ml-2"></i>
                            </button>

                            <!-- Message d'erreur global -->
                            <div id="global-error" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                    <span class="text-sm text-red-800" id="error-message"></span>
                                </div>
                            </div>
                        </form>



                    </div>
                </div>
            </div>
            <script type="module" src="../../dist/models/login.js"></script>

        </body>

        </html>