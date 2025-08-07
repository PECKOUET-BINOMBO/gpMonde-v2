<div class="hidden md:flex md:w-64 md:flex-col">
    <div class="flex flex-col flex-grow pt-5 bg-primary overflow-y-auto">
        <div class="flex items-center flex-shrink-0 px-4">
            <i class="fas fa-shipping-fast text-white text-2xl mr-3"></i>
            <h1 class="text-xl font-bold text-white">CargoTrack</h1>
        </div>
        
        <div class="mt-8 flex-grow flex flex-col">
            <nav class="flex-1 px-2 pb-4 space-y-1">
                <a href="/dashboard" class="<?= $currentPath === '/dashboard' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Tableau de bord
                </a>
                <a href="/cargaisons" class="<?= $currentPath === '/cargaisons' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-boxes mr-3"></i>
                    Cargaisons
                </a>
                <a href="/enregistrement" class="<?= $currentPath === '/enregistrement' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-plus-circle mr-3"></i>
                    Nouveau Colis
                </a>
                <a href="/recherche" class="<?= $currentPath === '/recherche' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-search mr-3"></i>
                    Recherche
                </a>                        
            </nav>
        </div>
        
        <div class="flex-shrink-0 flex border-t border-blue-800 p-4">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-white">Gestionnaire</p>
                    <p class="text-xs text-blue-200">En ligne</p>
                </div>
            </div>
        </div>
    </div>
</div>