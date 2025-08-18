import { DatabaseService } from '../services/DatabaseService.js';

interface Cargaison {
    id: number;
    numero_cargaison: string;
    type_transport: string;
    lieu_depart: string;
    lieu_arrive: string;
    distance: number | string;
    poids_max: number;
    poids_actuel: number;
    etat: string;
    date_depart: string;
    date_arrivee: string;
    description: string;
}

interface Colis {
    id: number;
    cargaison_id: number;
}

let allCargaisons: Cargaison[] = [];
let allColis: Colis[] = [];

let currentPage = 1;
const itemsPerPage = 5;

function getTypeIcon(type: string) {
    switch (type.toLowerCase()) {
        case 'maritime': return `<i class="fas fa-ship text-primary mr-2"></i>`;
        case 'aérien':
        case 'aerien': return `<i class="fas fa-plane text-secondary mr-2"></i>`;
        case 'routier': return `<i class="fas fa-truck text-accent mr-2"></i>`;
        default: return '';
    }
}

function getEtatBadge(etat: string) {
    if (etat.toLowerCase() === 'ouvert') {
        return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Ouvert</span>`;
    }
    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Fermé</span>`;
}

function getAvancementBadge(cargaison: Cargaison) {
    // Simple logique : si poids_actuel == 0 => "En attente", si == poids_max => "Arrivé", sinon "En cours"
    if (cargaison.poids_actuel === 0) {
        return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">En attente</span>`;
    }
    if (cargaison.poids_actuel >= cargaison.poids_max) {
        return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Arrivé</span>`;
    }
    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">En cours</span>`;
}

function renderCargaisons(cargaisons: Cargaison[]) {
    const tbody = document.getElementById('cargaisons-tbody');
    if (!tbody) return;
    if (cargaisons.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center text-gray-500 py-6">Aucune cargaison trouvée</td></tr>`;
        updatePagination(0, 0, 0);
        return;
    }

    // Pagination
    const totalItems = cargaisons.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    if (currentPage > totalPages) currentPage = totalPages || 1;
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageItems = cargaisons.slice(start, end);

    tbody.innerHTML = pageItems.map(c => {
        const nbColis = allColis.filter(colis => colis.cargaison_id === c.id).length;
        const percent = Math.round((c.poids_actuel / c.poids_max) * 100);
        return `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${c.numero_cargaison}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        ${getTypeIcon(c.type_transport)}
                        <span class="text-sm text-gray-900">${c.type_transport.charAt(0).toUpperCase() + c.type_transport.slice(1)}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${c.lieu_depart} → ${c.lieu_arrive}</div>
                    <div class="text-sm text-gray-500">${c.distance || ''}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${c.poids_actuel}/${c.poids_max} kg</div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-primary h-2 rounded-full" style="width: ${percent}%;"></div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${nbColis}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${getEtatBadge(c.etat)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${getAvancementBadge(c)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <button onclick="viewCargaison('${c.numero_cargaison}')" class="text-primary hover:text-blue-800">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editCargaison('${c.numero_cargaison}')" class="text-secondary hover:text-orange-600">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="closeCargaison('${c.numero_cargaison}')" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-lock"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');

    updatePagination(start + 1, Math.min(end, totalItems), totalItems, totalPages);
}

function updatePagination(start: number, end: number, total: number, totalPages?: number) {
    const info = document.getElementById('paginationInfo');
    if (info) {
        info.innerHTML = `Affichage de <span class="font-medium">${start}</span> à <span class="font-medium">${end}</span> sur <span class="font-medium">${total}</span> résultats`;
    }

    // Pagination boutons
    const prevBtn = document.getElementById('prevPage');
    const nextBtn = document.getElementById('nextPage');
    const prevBtnMobile = document.getElementById('prevPageMobile');
    const nextBtnMobile = document.getElementById('nextPageMobile');
    const pagesSpan = document.getElementById('paginationPages');

    if (pagesSpan && totalPages) {
        pagesSpan.textContent = `${currentPage} / ${totalPages}`;
    }

    // Desktop
    if (prevBtn && nextBtn && totalPages) {
        prevBtn.onclick = (e) => {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                renderCargaisons(getFilteredCargaisons());
            }
        };
        nextBtn.onclick = (e) => {
            e.preventDefault();
            if (currentPage < totalPages) {
                currentPage++;
                renderCargaisons(getFilteredCargaisons());
            }
        };
    }
    // Mobile
    if (prevBtnMobile && nextBtnMobile && totalPages) {
        prevBtnMobile.onclick = (e) => {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                renderCargaisons(getFilteredCargaisons());
            }
        };
        nextBtnMobile.onclick = (e) => {
            e.preventDefault();
            if (currentPage < totalPages) {
                currentPage++;
                renderCargaisons(getFilteredCargaisons());
            }
        };
    }
}

// Utilitaire pour récupérer la liste filtrée
function getFilteredCargaisons(): Cargaison[] {
    let filtered = allCargaisons;
    if (typeSelect && typeSelect.value) {
        filtered = filtered.filter(c => normalize(c.type_transport) === normalize(typeSelect.value));
    }
    if (etatSelect && etatSelect.value) {
        filtered = filtered.filter(c => c.etat.toLowerCase() === etatSelect.value.toLowerCase());
    }
    if (departInput && departInput.value) {
        filtered = filtered.filter(c => c.lieu_depart.toLowerCase().includes(departInput.value.toLowerCase()));
    }
    if (arriveeInput && arriveeInput.value) {
        filtered = filtered.filter(c => c.lieu_arrive.toLowerCase().includes(arriveeInput.value.toLowerCase()));
    }
    return filtered;
}

// Dans applyFilters, au lieu de renderCargaisons(filtered), fais :
function applyFilters() {
    currentPage = 1;
    renderCargaisons(getFilteredCargaisons());
}

async function loadCargaisons() {
    const dbService = DatabaseService.getInstance();
    await dbService.loadDatabase();
    const db = dbService.getDatabase();
    allCargaisons = db.cargaisons || [];
    allColis = db.colis || [];
    renderCargaisons(allCargaisons);
}

let typeSelect: HTMLSelectElement;
let etatSelect: HTMLSelectElement;
let departInput: HTMLInputElement;
let arriveeInput: HTMLInputElement;

function normalize(str: string): string {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
}

document.addEventListener('DOMContentLoaded', () => {
    loadCargaisons();

    // Filtres dynamiques
    typeSelect = document.querySelector('select[placeholder="Type"]') as HTMLSelectElement
        || document.querySelector('select:nth-of-type(1)') as HTMLSelectElement;
    etatSelect = document.querySelector('select[name="etat"]') as HTMLSelectElement
        || document.querySelector('select:nth-of-type(2)') as HTMLSelectElement;
    departInput = document.querySelector('input[placeholder="Ville de départ"]') as HTMLInputElement;
    arriveeInput = document.querySelector('input[placeholder="Ville d\'arriver"]') as HTMLInputElement;

    // Appliquer les filtres
    typeSelect?.addEventListener('change', applyFilters);
    etatSelect?.addEventListener('change', applyFilters);
    departInput?.addEventListener('input', applyFilters);
    arriveeInput?.addEventListener('input', applyFilters);

    // Boutons
    document.querySelector('button:contains("Réinitialiser")')?.addEventListener('click', (e) => {
        e.preventDefault();
        typeSelect.value = '';
        etatSelect.value = '';
        departInput.value = '';
        arriveeInput.value = '';
        renderCargaisons(allCargaisons);
    });
    document.querySelector('button:contains("Appliquer")')?.addEventListener('click', (e) => {
        e.preventDefault();
        applyFilters();
    });
});

// Fonctions d'action (affichent juste un message)
(window as any).viewCargaison = (code: string) => alert('Affichage des détails de la cargaison: ' + code);
(window as any).editCargaison = (code: string) => alert('Modification de la cargaison: ' + code);
(window as any).closeCargaison = (code: string) => alert('Cargaison ' + code + ' fermée');
(window as any).reopenCargaison = (code: string) => alert('Cargaison ' + code + ' rouverte');