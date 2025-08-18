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

// Variables pour la carte d'édition
let editCargoMap: any;
let editDepartureMarker: any = null;
let editArrivalMarker: any = null;
let editRouteLine: any = null;
let editCurrentSelection = 'departure'; // 'departure' or 'arrival'
let editSearchTimeout: any = null;

// Variables pour la carte de visualisation
let viewCargoMap: any;
let viewDepartureMarker: any = null;
let viewArrivalMarker: any = null;
let viewRouteLine: any = null;


function closeViewCargoModal() {
    const modal = document.getElementById('viewCargoModal');
    if (modal) modal.classList.add('hidden');
}

function closeEditCargoModal() {
    const modal = document.getElementById('editCargoModal');
    if (modal) modal.classList.add('hidden');
}

function showModal(message: string, iconHtml: string = '<i class="fas fa-info-circle"></i>') {
    const modal = document.getElementById('actionModal') as HTMLDivElement;
    const msg = document.getElementById('actionModalMessage') as HTMLDivElement;
    const icon = document.getElementById('actionModalIcon') as HTMLDivElement;
    const closeBtn = document.getElementById('actionModalClose') as HTMLButtonElement;
    if (!modal || !msg || !icon || !closeBtn) return;

    msg.innerHTML = message;
    icon.innerHTML = iconHtml;
    modal.classList.remove('hidden');

    closeBtn.onclick = () => {
        modal.classList.add('hidden');
    };
}

function showConfirmModal(
    message: string,
    iconHtml: string,
    onConfirm: () => void
) {
    const modal = document.getElementById('confirmModal') as HTMLDivElement;
    const msg = document.getElementById('confirmModalMessage') as HTMLDivElement;
    const icon = document.getElementById('confirmModalIcon') as HTMLDivElement;
    const btnOk = document.getElementById('confirmModalOk') as HTMLButtonElement;
    const btnCancel = document.getElementById('confirmModalCancel') as HTMLButtonElement;
    if (!modal || !msg || !icon || !btnOk || !btnCancel) return;

    msg.innerHTML = message;
    icon.innerHTML = iconHtml;
    modal.classList.remove('hidden');

    // Nettoyer les anciens listeners
    btnOk.onclick = null;
    btnCancel.onclick = null;

    btnOk.onclick = () => {
        modal.classList.add('hidden');
        onConfirm();
    };
    btnCancel.onclick = () => {
        modal.classList.add('hidden');
    };
}

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
                        ${c.etat.toLowerCase() === 'ouvert'
                            ? `<button onclick="closeCargaison('${c.numero_cargaison}')" class="text-red-600 hover:text-red-800" title="Fermer">
                                <i class="fas fa-lock"></i>
                               </button>`
                            : `<button onclick="reopenCargaison('${c.numero_cargaison}')" class="text-green-600 hover:text-green-800" title="Ouvrir">
                                <i class="fas fa-unlock"></i>
                               </button>`
                        }
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

// Ouvrir une cargaison
function reopenCargaison(numero: string): void {
    const cargaison = allCargaisons.find(c => c.numero_cargaison === numero);
    if (!cargaison) {
        showModal("Cargaison introuvable", '<i class="fas fa-exclamation-triangle text-red-500"></i>');
        return;
    }
    showConfirmModal(
        "Voulez-vous vraiment ouvrir cette cargaison ?",
        '<i class="fas fa-unlock text-green-600"></i>',
        () => {
            fetch('/api/cargaison/open', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: cargaison.id })
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    showModal("Cargaison ouverte avec succès !", '<i class="fas fa-unlock text-green-600"></i>');
                    loadCargaisons().then(() => renderCargaisons(getFilteredCargaisons()));
                } else {
                    showModal("Erreur lors de l'ouverture", '<i class="fas fa-exclamation-triangle text-red-500"></i>');
                }
            });
        }
    );
}

// Fermer une cargaison
function closeCargaison(numero: string): void {
    const cargaison = allCargaisons.find(c => c.numero_cargaison === numero);
    if (!cargaison) {
        showModal("Cargaison introuvable", '<i class="fas fa-exclamation-triangle text-red-500"></i>');
        return;
    }
    showConfirmModal(
        "Voulez-vous vraiment fermer cette cargaison ?",
        '<i class="fas fa-lock text-red-600"></i>',
        () => {
            fetch('/api/cargaison/close', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: cargaison.id })
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    showModal("Cargaison fermée avec succès !", '<i class="fas fa-lock text-red-600"></i>');
                    loadCargaisons().then(() => renderCargaisons(getFilteredCargaisons()));
                } else {
                    showModal("Erreur lors de la fermeture", '<i class="fas fa-exclamation-triangle text-red-500"></i>');
                }
            });
        }
    );
}

// Pour que les boutons HTML puissent appeler ces fonctions :
(window as unknown as { closeCargaison: typeof closeCargaison, reopenCargaison: typeof reopenCargaison }).closeCargaison = closeCargaison;
(window as unknown as { closeCargaison: typeof closeCargaison, reopenCargaison: typeof reopenCargaison }).reopenCargaison = reopenCargaison;

// Fonctions d'action (affichent juste un message)
(window as any).viewCargaison = (code: string) => alert('Affichage des détails de la cargaison: ' + code);
(window as any).editCargaison = (code: string) => alert('Modification de la cargaison: ' + code);

function viewCargaison(numero: string): void {
    const cargaison = allCargaisons.find(c => c.numero_cargaison === numero);
    if (!cargaison) {
        showModal("Cargaison introuvable", '<i class="fas fa-exclamation-triangle text-red-500"></i>');
        return;
    }
    const content = `
        <div class="space-y-4">
            <div>
                <p><span class="font-semibold">Code :</span> ${cargaison.numero_cargaison}</p>
                <p><span class="font-semibold">Type :</span> ${cargaison.type_transport}</p>
                <p><span class="font-semibold">État :</span> ${cargaison.etat}</p>
                <p><span class="font-semibold">Poids max :</span> ${cargaison.poids_max} kg</p>
                <p><span class="font-semibold">Poids actuel :</span> ${cargaison.poids_actuel} kg</p>
                <p><span class="font-semibold">Description :</span> ${cargaison.description || '-'}</p>
            </div>
            <div>
                <p><span class="font-semibold">Départ :</span> ${cargaison.lieu_depart}</p>
                <p><span class="font-semibold">Arrivée :</span> ${cargaison.lieu_arrive}</p>
                <p><span class="font-semibold">Distance :</span> ${cargaison.distance || '-'}</p>
                <p><span class="font-semibold">Date départ :</span> ${cargaison.date_depart ? new Date(cargaison.date_depart).toLocaleString() : '-'}</p>
                <p><span class="font-semibold">Date arrivée :</span> ${cargaison.date_arrivee ? new Date(cargaison.date_arrivee).toLocaleString() : '-'}</p>
            </div>
        </div>
    `;
    const modal = document.getElementById('viewCargoModal');
    const contentDiv = document.getElementById('viewCargoContent');
    if (modal && contentDiv) {
        contentDiv.innerHTML = content;
        modal.classList.remove('hidden');
        
        // Initialiser la carte de visualisation
        setTimeout(() => {
            initViewCargoMap();
            
            // Afficher le trajet si les coordonnées sont disponibles
            const depLat = parseFloat((cargaison as any).latitude_depart);
            const depLng = parseFloat((cargaison as any).longitude_depart);
            const arrLat = parseFloat((cargaison as any).latitude_arrivee);
            const arrLng = parseFloat((cargaison as any).longitude_arrivee);

            if (!isNaN(depLat) && !isNaN(depLng)) {
                addViewDepartureMarker(depLat, depLng);
            }
            if (!isNaN(arrLat) && !isNaN(arrLng)) {
                addViewArrivalMarker(arrLat, arrLng);
            }
            
            // Afficher la ligne de trajet si les deux points sont disponibles
            if (!isNaN(depLat) && !isNaN(depLng) && !isNaN(arrLat) && !isNaN(arrLng)) {
                addViewRouteLine();
            }
        }, 100);
    }
}
(window as any).viewCargaison = viewCargaison;

function editCargaison(numero: string): void {
    const cargaison = allCargaisons.find(c => c.numero_cargaison === numero);
    if (!cargaison) {
        showModal("Cargaison introuvable", '<i class="fas fa-exclamation-triangle text-red-500"></i>');
        return;
    }

    // Pré-remplir les champs du formulaire
    const form = document.getElementById('editCargoForm') as HTMLFormElement;
    if (!form) return;

    // Remplir les champs
    (document.getElementById('editTransportType') as HTMLSelectElement).value = cargaison.type_transport;
    (document.getElementById('editDeparturePlace') as HTMLInputElement).value = cargaison.lieu_depart;
    (document.getElementById('editArrivalPlace') as HTMLInputElement).value = cargaison.lieu_arrive;
    (document.getElementById('editDistance') as HTMLInputElement).value = cargaison.distance?.toString() || '';
    (document.getElementById('editMaxWeight') as HTMLInputElement).value = cargaison.poids_max.toString();
    (document.getElementById('editDescription') as HTMLTextAreaElement).value = cargaison.description || '';

    // Formater les dates pour les champs datetime-local
    if (cargaison.date_depart) {
        const dateDepart = new Date(cargaison.date_depart);
        (document.getElementById('editDateDepart') as HTMLInputElement).value = dateDepart.toISOString().slice(0, 16);
    }
    if (cargaison.date_arrivee) {
        const dateArrivee = new Date(cargaison.date_arrivee);
        (document.getElementById('editDateArrivee') as HTMLInputElement).value = dateArrivee.toISOString().slice(0, 16);
    }

    // Remplir les coordonnées cachées
    (document.getElementById('editDepartureLat') as HTMLInputElement).value = (cargaison as any).latitude_depart || '';
    (document.getElementById('editDepartureLng') as HTMLInputElement).value = (cargaison as any).longitude_depart || '';
    (document.getElementById('editArrivalLat') as HTMLInputElement).value = (cargaison as any).latitude_arrivee || '';
    (document.getElementById('editArrivalLng') as HTMLInputElement).value = (cargaison as any).longitude_arrivee || '';

    // Afficher la modal
    const modal = document.getElementById('editCargoModal');
    if (modal) modal.classList.remove('hidden');

    // Initialiser la carte
    setTimeout(() => {
        initEditCargoMap();
        
        // Pré-remplir la carte avec les coordonnées existantes
        const depLat = parseFloat((cargaison as any).latitude_depart);
        const depLng = parseFloat((cargaison as any).longitude_depart);
        const arrLat = parseFloat((cargaison as any).latitude_arrivee);
        const arrLng = parseFloat((cargaison as any).longitude_arrivee);

        if (!isNaN(depLat) && !isNaN(depLng)) {
            updateEditDeparture(depLat, depLng);
        }
        if (!isNaN(arrLat) && !isNaN(arrLng)) {
            updateEditArrival(arrLat, arrLng);
        }
    }, 100);

    // Gestion de la soumission du formulaire
    form.onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const updatedCargo = {
            id: cargaison.id,
            type_transport: formData.get('type_transport'),
            lieu_depart: formData.get('lieu_depart'),
            lieu_arrive: formData.get('lieu_arrive'),
            distance: formData.get('distance'),
            latitude_depart: formData.get('latitude_depart'),
            longitude_depart: formData.get('longitude_depart'),
            latitude_arrivee: formData.get('latitude_arrivee'),
            longitude_arrivee: formData.get('longitude_arrivee'),
            poids_max: Number(formData.get('poids_max')),
            date_depart: formData.get('date_depart'),
            date_arrivee: formData.get('date_arrivee'),
            description: formData.get('description')
        };

        // Appel API pour modifier la cargaison
        const response = await fetch('/api/cargaison/update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(updatedCargo)
        });
        const result = await response.json();
        if (result.success) {
            closeEditCargoModal();
            showModal("Cargaison modifiée avec succès !", '<i class="fas fa-check-circle text-green-600"></i>');
            await loadCargaisons();
            renderCargaisons(getFilteredCargaisons());
        } else {
            showModal(result.message || "Erreur lors de la modification", '<i class="fas fa-exclamation-triangle text-red-500"></i>');
        }
    };
}
(window as any).editCargaison = editCargaison;


// Fonctions pour la carte d'édition
function initEditCargoMap() {
    if (editCargoMap) {
        editCargoMap.remove();
    }
    
    // Créer la carte centrée sur l'Europe
    editCargoMap = (window as any).L.map('editCargoMap').setView([46.603354, 1.888334], 5);

    // Ajouter la couche OpenStreetMap
    (window as any).L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(editCargoMap);

     // Réinitialise les marqueurs et la ligne
    editDepartureMarker = null;
    editArrivalMarker = null;
    editRouteLine = null;

    // Gestion des clics et autocomplete
    editCurrentSelection = 'departure';
    setupEditAutocomplete();

    // Gérer le clic sur la carte
    editCargoMap.on('click', function(e: any) {
        const { lat, lng } = e.latlng;

        // Mettre à jour les coordonnées sélectionnées
        (document.getElementById('editSelectedLat') as HTMLInputElement).value = lat.toFixed(6);
        (document.getElementById('editSelectedLng') as HTMLInputElement).value = lng.toFixed(6);

        // Mettre à jour le marqueur approprié
        if (editCurrentSelection === 'departure') {
            updateEditDeparture(lat, lng);
            // Géocodage inverse pour obtenir le nom de la ville
            editReverseGeocode(lat, lng, 'departure');
        } else {
            updateEditArrival(lat, lng);
            // Géocodage inverse pour obtenir le nom de la ville
            editReverseGeocode(lat, lng, 'arrival');
        }
    });

    // Gestion des champs de saisie pour l'autocomplétion
    setupEditAutocomplete();
}

function updateEditDeparture(lat: number, lng: number) {
    // Mettre à jour les champs cachés
    (document.getElementById('editDepartureLat') as HTMLInputElement).value = lat.toString();
    (document.getElementById('editDepartureLng') as HTMLInputElement).value = lng.toString();

    // Mettre à jour ou créer le marqueur
    if (editDepartureMarker) {
        editDepartureMarker.setLatLng([lat, lng]);
    } else {
        editDepartureMarker = (window as any).L.marker([lat, lng], {
            icon: (window as any).L.divIcon({
                className: 'custom-marker',
                html: `<div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white shadow-lg border-2 border-white">
                         <i class="fas fa-play text-xs"></i>
                       </div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            })
        }).addTo(editCargoMap);

        // Ajouter un popup
        editDepartureMarker.bindPopup("Point de départ");
    }

    // Mettre à jour la ligne de route si nécessaire
    updateEditRouteLine();
}

function updateEditArrival(lat: number, lng: number) {
    // Mettre à jour les champs cachés
    (document.getElementById('editArrivalLat') as HTMLInputElement).value = lat.toString();
    (document.getElementById('editArrivalLng') as HTMLInputElement).value = lng.toString();

    // Mettre à jour ou créer le marqueur
    if (editArrivalMarker) {
        editArrivalMarker.setLatLng([lat, lng]);
    } else {
        editArrivalMarker = (window as any).L.marker([lat, lng], {
            icon: (window as any).L.divIcon({
                className: 'custom-marker',
                html: `<div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white shadow-lg border-2 border-white">
                         <i class="fas fa-flag text-xs"></i>
                       </div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            })
        }).addTo(editCargoMap);

        // Ajouter un popup
        editArrivalMarker.bindPopup("Point d'arrivée");
    }

    // Mettre à jour la ligne de route si nécessaire
    updateEditRouteLine();
}

function updateEditRouteLine() {
    // Supprimer l'ancienne ligne si elle existe
    if (editRouteLine) {
        editCargoMap.removeLayer(editRouteLine);
    }

    // Dessiner une nouvelle ligne si les deux points sont définis
    if (editDepartureMarker && editArrivalMarker) {
        const departureLatLng = editDepartureMarker.getLatLng();
        const arrivalLatLng = editArrivalMarker.getLatLng();

        editRouteLine = (window as any).L.polyline([departureLatLng, arrivalLatLng], {
            color: '#3b82f6',
            weight: 4,
            dashArray: '10, 5',
            opacity: 0.8
        }).addTo(editCargoMap);

        // Ajuster la vue pour voir toute la route
        editCargoMap.fitBounds([departureLatLng, arrivalLatLng], {
            padding: [50, 50]
        });

        // Calculer et afficher la distance
        calculateEditDistance();
    }
}

function calculateEditDistance() {
    if (editDepartureMarker && editArrivalMarker) {
        const departureLatLng = editDepartureMarker.getLatLng();
        const arrivalLatLng = editArrivalMarker.getLatLng();

        // Calculer la distance en km (formule de Haversine)
        const R = 6371; // Rayon de la Terre en km
        const dLat = (arrivalLatLng.lat - departureLatLng.lat) * Math.PI / 180;
        const dLng = (arrivalLatLng.lng - departureLatLng.lng) * Math.PI / 180;
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(departureLatLng.lat * Math.PI / 180) * Math.cos(arrivalLatLng.lat * Math.PI / 180) *
            Math.sin(dLng / 2) * Math.sin(dLng / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const distance = R * c;

        (document.getElementById('editDistance') as HTMLInputElement).value = Math.round(distance) + ' km';
    }
}

function setupEditAutocomplete() {
    const departureInput = document.getElementById('editDeparturePlace') as HTMLInputElement;
    const arrivalInput = document.getElementById('editArrivalPlace') as HTMLInputElement;

    if (departureInput) {
        departureInput.addEventListener('focus', () => {
            editCurrentSelection = 'departure';
        });

        departureInput.addEventListener('input', (e) => {
            const query = (e.target as HTMLInputElement).value;
            if (query.length > 2) {
                clearTimeout(editSearchTimeout);
                editSearchTimeout = setTimeout(() => {
                    editSearchPlace(query, 'departure');
                }, 300);
            } else {
                editHideSuggestions('departure');
            }
        });
    }

    if (arrivalInput) {
        arrivalInput.addEventListener('focus', () => {
            editCurrentSelection = 'arrival';
        });

        arrivalInput.addEventListener('input', (e) => {
            const query = (e.target as HTMLInputElement).value;
            if (query.length > 2) {
                clearTimeout(editSearchTimeout);
                editSearchTimeout = setTimeout(() => {
                    editSearchPlace(query, 'arrival');
                }, 300);
            } else {
                editHideSuggestions('arrival');
            }
        });
    }
}

async function editSearchPlace(query: string, type: 'departure' | 'arrival') {
    try {
        const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&addressdetails=1`);
        const results = await response.json();
        
        editShowSuggestions(results, type);
    } catch (error) {
        console.error('Erreur lors de la recherche:', error);
    }
}

function editShowSuggestions(results: any[], type: 'departure' | 'arrival') {
    const suggestionsId = type === 'departure' ? 'editDepartureSuggestions' : 'editArrivalSuggestions';
    const suggestionsDiv = document.getElementById(suggestionsId);
    
    if (!suggestionsDiv) return;

    if (results.length === 0) {
        suggestionsDiv.classList.add('hidden');
        return;
    }

    suggestionsDiv.innerHTML = results.map(result => `
        <div class="p-2 hover:bg-gray-100 cursor-pointer border-b border-gray-200 last:border-b-0" 
             onclick="editSelectPlace('${result.display_name}', ${result.lat}, ${result.lon}, '${type}')">
            <div class="font-medium text-sm">${result.display_name}</div>
        </div>
    `).join('');

    suggestionsDiv.classList.remove('hidden');
}

function editSelectPlace(displayName: string, lat: number, lng: number, type: 'departure' | 'arrival') {
    const inputId = type === 'departure' ? 'editDeparturePlace' : 'editArrivalPlace';
    const statusId = type === 'departure' ? 'editDepartureStatus' : 'editArrivalStatus';
    
    (document.getElementById(inputId) as HTMLInputElement).value = displayName;
    (document.getElementById(statusId) as HTMLElement).innerHTML = '<span class="text-green-600"><i class="fas fa-check"></i> Lieu sélectionné</span>';
    
    if (type === 'departure') {
        updateEditDeparture(lat, lng);
    } else {
        updateEditArrival(lat, lng);
    }
    
    editHideSuggestions(type);
}

function editHideSuggestions(type: 'departure' | 'arrival') {
    const suggestionsId = type === 'departure' ? 'editDepartureSuggestions' : 'editArrivalSuggestions';
    const suggestionsDiv = document.getElementById(suggestionsId);
    if (suggestionsDiv) {
        suggestionsDiv.classList.add('hidden');
    }
}

async function editReverseGeocode(lat: number, lng: number, type: 'departure' | 'arrival') {
    try {
        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`);
        const result = await response.json();
        
        if (result && result.display_name) {
            const inputId = type === 'departure' ? 'editDeparturePlace' : 'editArrivalPlace';
            const statusId = type === 'departure' ? 'editDepartureStatus' : 'editArrivalStatus';
            
            (document.getElementById(inputId) as HTMLInputElement).value = result.display_name;
            (document.getElementById(statusId) as HTMLElement).innerHTML = '<span class="text-green-600"><i class="fas fa-map-marker-alt"></i> Position sélectionnée sur la carte</span>';
        }
    } catch (error) {
        console.error('Erreur lors du géocodage inverse:', error);
    }
}

// Rendre les fonctions globales pour les onclick
(window as any).editSelectPlace = editSelectPlace;


// Fonctions pour la carte de visualisation
function initViewCargoMap() {
    if (viewCargoMap) {
        viewCargoMap.remove();
    }
    
    // Créer la carte centrée sur l'Europe
    viewCargoMap = (window as any).L.map('viewCargoMap').setView([46.603354, 1.888334], 5);

    // Ajouter la couche OpenStreetMap
    (window as any).L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(viewCargoMap);
}

function addViewDepartureMarker(lat: number, lng: number) {
    viewDepartureMarker = (window as any).L.marker([lat, lng], {
        icon: (window as any).L.divIcon({
            className: 'custom-marker',
            html: `<div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white shadow-lg border-2 border-white">
                     <i class="fas fa-play text-xs"></i>
                   </div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        })
    }).addTo(viewCargoMap);

    // Ajouter un popup
    viewDepartureMarker.bindPopup("Point de départ");
}

function addViewArrivalMarker(lat: number, lng: number) {
    viewArrivalMarker = (window as any).L.marker([lat, lng], {
        icon: (window as any).L.divIcon({
            className: 'custom-marker',
            html: `<div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white shadow-lg border-2 border-white">
                     <i class="fas fa-flag text-xs"></i>
                   </div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        })
    }).addTo(viewCargoMap);

    // Ajouter un popup
    viewArrivalMarker.bindPopup("Point d'arrivée");
}

function addViewRouteLine() {
    // Supprimer l'ancienne ligne si elle existe
    if (viewRouteLine) {
        viewCargoMap.removeLayer(viewRouteLine);
    }

    // Dessiner une nouvelle ligne si les deux points sont définis
    if (viewDepartureMarker && viewArrivalMarker) {
        const departureLatLng = viewDepartureMarker.getLatLng();
        const arrivalLatLng = viewArrivalMarker.getLatLng();

        viewRouteLine = (window as any).L.polyline([departureLatLng, arrivalLatLng], {
            color: '#3b82f6',
            weight: 4,
            dashArray: '10, 5',
            opacity: 0.8
        }).addTo(viewCargoMap);

        // Ajuster la vue pour voir toute la route
        viewCargoMap.fitBounds([departureLatLng, arrivalLatLng], {
            padding: [50, 50]
        });
    }
}

// Fermer les modals (ajout des fonctions globales)
(window as any).closeViewCargoModal = closeViewCargoModal;
(window as any).closeEditCargoModal = closeEditCargoModal;

