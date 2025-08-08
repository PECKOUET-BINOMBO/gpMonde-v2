<header class="bg-white shadow-sm border-b border-gray-200">
     <div class="flex items-center justify-between px-4 py-4">
         <div class="flex items-center">
             <button class="md:hidden mr-3">
                 <i class="fas fa-bars text-gray-600"></i>
             </button>
             <h2 class="text-xl font-semibold text-gray-900">
                <?= htmlspecialchars($pageTitle) ?>
             </h2>
         </div>
         <div class="flex items-center space-x-4">
             <button class="p-2 text-gray-400 hover:text-gray-600">
                 <i class="fas fa-bell"></i>
             </button>
             <a href="/accueil" class="text-sm text-gray-600 hover:text-primary">
                 Retour au site
             </a>
         </div>
     </div>
 </header>