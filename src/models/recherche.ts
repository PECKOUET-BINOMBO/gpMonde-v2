import { DatabaseService } from '../services/DatabaseService.js';

interface Colis {
    id: number;
    numero_colis: string;
    cargaison_id: number;
    nbr_colis: number;
    poids: number;
    type_produit: string;
    type_transport: string;
    prix: number;
    description: string;
    etat: string;
    info_expediteur: {
        nom: string;
        prenom: string;
        adresse: string;
        tel: string;
        email: string;
    };
    info_destinataire: {
        nom: string;
        prenom: string;
        adresse: string;
        tel: string;
        email: string;
    };
}

interface Cargaison {
    id: number;
    numero_cargaison: string;
    type_transport: string;
    lieu_depart: string;
    lieu_arrive: string;
}

let allColis: Colis[] = [];
let allCargaisons: Cargaison[] = [];
let currentPackage: Colis | null = null;
let pendingStatusChange: { code: string, label: string } | null = null;

async function loadData() {
    const dbService = DatabaseService.getInstance();
    await dbService.loadDatabase();
    const db = dbService.getDatabase();
    allColis = db.colis || [];
    allCargaisons = db.cargaisons || [];
}

function renderResults(colisList: Colis[]) {
    const tbody = document.querySelector('#search-results tbody');
    if (!tbody) return;
    if (colisList.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-gray-500 py-6">Aucun résultat</td></tr>`;
        return;
    }
    tbody.innerHTML = colisList.map(colis => {
        const exp = colis.info_expediteur;
        const dest = colis.info_destinataire;
        const cargaison = allCargaisons.find(c => c.id === colis.cargaison_id);
        return `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${colis.numero_colis}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${exp.nom} ${exp.prenom}</div>
                    <div class="text-sm text-gray-500">${exp.tel}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${dest.nom} ${dest.prenom}</div>
                    <div class="text-sm text-gray-500">${dest.tel}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${cargaison ? cargaison.numero_cargaison : ''}</div>
                    <div class="text-sm text-gray-500">${cargaison ? cargaison.type_transport : ''}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${colis.poids} kg</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                        colis.etat.toLowerCase().includes('attente') ? 'bg-blue-100 text-blue-800'
                        : colis.etat.toLowerCase().includes('livr') ? 'bg-green-100 text-green-800'
                        : colis.etat.toLowerCase().includes('perdu') ? 'bg-red-100 text-red-800'
                        : 'bg-yellow-100 text-yellow-800'
                    }">
                        ${colis.etat}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <button data-action="view" data-code="${colis.numero_colis}" class="text-primary hover:text-blue-800" title="Voir détails">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button data-action="status" data-code="${colis.numero_colis}" data-status="recupere" class="text-accent hover:text-green-600" title="Marquer récupéré">
                            <i class="fas fa-check"></i>
                        </button>
                        <button data-action="status" data-code="${colis.numero_colis}" data-status="perdu" class="text-red-600 hover:text-red-800" title="Marquer perdu">
                            <i class="fas fa-exclamation-triangle"></i>
                        </button>
                        <button data-action="archive" data-code="${colis.numero_colis}" class="text-gray-600 hover:text-gray-800" title="Archiver">
                            <i class="fas fa-archive"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function filterColis() {
    const code = (document.getElementById('search-code') as HTMLInputElement).value.trim().toUpperCase();
    const cargo = (document.getElementById('search-cargo') as HTMLInputElement).value.trim().toUpperCase();
    const client = (document.getElementById('search-client') as HTMLInputElement).value.trim().toLowerCase();

    let filtered = allColis;
    if (code) {
        filtered = filtered.filter(c => c.numero_colis.toUpperCase().includes(code));
    }
    if (cargo) {
        filtered = filtered.filter(c => {
            const carg = allCargaisons.find(cg => cg.id === c.cargaison_id);
            return carg && carg.numero_cargaison.toUpperCase().includes(cargo);
        });
    }
    if (client) {
        filtered = filtered.filter(c =>
            c.info_expediteur.nom.toLowerCase().includes(client) ||
            c.info_expediteur.prenom.toLowerCase().includes(client) ||
            c.info_destinataire.nom.toLowerCase().includes(client) ||
            c.info_destinataire.prenom.toLowerCase().includes(client)
        );
    }
    renderResults(filtered);
}

function clearSearch() {
    (document.getElementById('search-code') as HTMLInputElement).value = '';
    (document.getElementById('search-cargo') as HTMLInputElement).value = '';
    (document.getElementById('search-client') as HTMLInputElement).value = '';
    renderResults(allColis);
}

function openPackageDetails(code: string) {
    currentPackage = allColis.find(c => c.numero_colis === code) || null;
    if (!currentPackage) return;
    const exp = currentPackage.info_expediteur;
    const dest = currentPackage.info_destinataire;
    const cargaison = allCargaisons.find(c => c.id === currentPackage!.cargaison_id);

    // Remplir les champs du modal
    (document.getElementById('detail-code') as HTMLElement).textContent = currentPackage.numero_colis;
    (document.getElementById('detail-weight') as HTMLElement).textContent = `${currentPackage.poids} kg`;
    (document.getElementById('detail-product-type') as HTMLElement).textContent = currentPackage.type_produit;
    (document.getElementById('detail-value') as HTMLElement).textContent = currentPackage.prix + ' XAF';
    (document.getElementById('detail-price') as HTMLElement).textContent = currentPackage.prix + ' XAF';

    (document.getElementById('detail-sender-name') as HTMLElement).textContent = `${exp.nom} ${exp.prenom}`;
    (document.getElementById('detail-sender-phone') as HTMLElement).textContent = exp.tel;
    (document.getElementById('detail-sender-email') as HTMLElement).textContent = exp.email;

    (document.getElementById('detail-recipient-name') as HTMLElement).textContent = `${dest.nom} ${dest.prenom}`;
    (document.getElementById('detail-recipient-phone') as HTMLElement).textContent = dest.tel;
    (document.getElementById('detail-recipient-email') as HTMLElement).textContent = dest.email;

    (document.getElementById('detail-cargo-code') as HTMLElement).textContent = cargaison ? cargaison.numero_cargaison : '';
    (document.getElementById('detail-cargo-type') as HTMLElement).textContent = cargaison ? cargaison.type_transport : '';
    (document.getElementById('detail-cargo-route') as HTMLElement).textContent = cargaison ? `${cargaison.lieu_depart} → ${cargaison.lieu_arrive}` : '';

    (document.getElementById('detail-status-badge') as HTMLElement).textContent = currentPackage.etat;
    (document.getElementById('detail-status-description') as HTMLElement).textContent = `Le colis est actuellement : ${currentPackage.etat}`;

    (document.getElementById('packageDetailsModal') as HTMLElement).classList.remove('hidden');
}

function closePackageDetailsModal() {
    (document.getElementById('packageDetailsModal') as HTMLElement).classList.add('hidden');
    currentPackage = null;
}

async function changePackageStatus(code: string, status: string) {
    const colis = allColis.find(c => c.numero_colis === code);
    if (!colis) return;
    let label = '';
    if (status === 'recupere') label = 'Récupéré';
    else if (status === 'perdu') label = 'Perdu';
    else if (status === 'archive') label = 'Archivé';
    else if (status === 'en_attente') label = 'En attente';
    else if (status === 'en_cours') label = 'En cours de transport';
    else if (status === 'arrive') label = 'Livré';
    else label = status;

    showActionConfirmModal(
        `Êtes-vous sûr de vouloir marquer ce colis comme "${label}" ?`,
        async () => {
            try {
                const response = await fetch('/api/colis/update-status', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ numero_colis: code, etat: label })
                });
                const result = await response.json();
                if (result.success) {
                    colis.etat = label;
                    renderResults(allColis);
                    if (currentPackage && currentPackage.numero_colis === code) {
                        openPackageDetails(code);
                    }
                    showSuccessToast(`Le colis a bien été marqué comme "${label}".`);
                } else {
                    showSuccessToast(result.message || "Erreur lors de la mise à jour");
                }
            } catch (err) {
                showSuccessToast("Erreur serveur lors de la mise à jour");
            }
        }
    );
}

function archivePackage(code: string) {
    changePackageStatus(code, 'archive');
}

async function updatePackageStatus() {
    const newStatus = (document.getElementById('new-status') as HTMLSelectElement).value;
    if (newStatus && currentPackage) {
        let label = '';
        if (newStatus === 'recupere') label = 'Récupéré';
        else if (newStatus === 'perdu') label = 'Perdu';
        else if (newStatus === 'archive') label = 'Archivé';
        else if (newStatus === 'en_attente') label = 'En attente';
        else if (newStatus === 'en_cours') label = 'En cours de transport';
        else if (newStatus === 'arrive') label = 'Livré';
        else label = newStatus;

        showActionConfirmModal(
            `Confirmer le changement d'état du colis en "${label}" ?`,
            async () => {
                try {
                    const response = await fetch('/api/colis/update-status', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ numero_colis: currentPackage.numero_colis, etat: label })
                    });
                    const result = await response.json();
                    if (result.success) {
                        if (currentPackage) {
                            currentPackage.etat = label;
                            renderResults(allColis);
                            openPackageDetails(currentPackage.numero_colis);
                        } else {
                            renderResults(allColis);
                        }
                        showSuccessToast(`L'état du colis a bien été mis à jour en "${label}".`);
                    } else {
                        showSuccessToast(result.message || "Erreur lors de la mise à jour");
                    }
                } catch (err) {
                    showSuccessToast("Erreur serveur lors de la mise à jour");
                }
            }
        );
    } else {
        showSuccessToast('Veuillez sélectionner un nouvel état');
    }
}

function showActionConfirmModal(message: string, onConfirm: () => void) {
    const modal = document.getElementById('actionConfirmModal');
    const msg = document.getElementById('actionConfirmMessage');
    const yesBtn = document.getElementById('actionConfirmYes');
    const noBtn = document.getElementById('actionConfirmNo');
    if (modal && msg && yesBtn && noBtn) {
        msg.textContent = message;
        modal.classList.remove('hidden');
        // Nettoyer les anciens listeners
        yesBtn.onclick = null;
        noBtn.onclick = null;
        yesBtn.onclick = () => {
            modal.classList.add('hidden');
            onConfirm();
        };
        noBtn.onclick = () => {
            modal.classList.add('hidden');
        };
    }
}

function closeActionConfirmModal() {
    const modal = document.getElementById('actionConfirmModal');
    if (modal) modal.classList.add('hidden');
}
(window as any).closeActionConfirmModal = closeActionConfirmModal;

function showSuccessToast(message: string) {
    const toast = document.getElementById('successToast');
    if (toast) {
        toast.textContent = message;
        toast.classList.remove('hidden');
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3500);
    }
}

// Expose pour le HTML
(window as any).clearSearch = clearSearch;
(window as any).viewPackageDetails = openPackageDetails;
(window as any).closePackageDetailsModal = closePackageDetailsModal;
(window as any).changePackageStatus = changePackageStatus;
(window as any).archivePackage = archivePackage;
(window as any).updatePackageStatus = updatePackageStatus;

document.addEventListener('DOMContentLoaded', async () => {
    await loadData();
    renderResults(allColis);

    // Recherche en temps réel
    document.getElementById('search-code')?.addEventListener('keypress', function(e) {
        if ((e as KeyboardEvent).key === 'Enter') {
            filterColis();
        }
    });
    document.getElementById('search-code')?.addEventListener('input', filterColis);
    document.getElementById('search-cargo')?.addEventListener('input', filterColis);
    document.getElementById('search-client')?.addEventListener('input', filterColis);

    (document.getElementById('search-results') as HTMLElement).addEventListener('click', function(e) {
        const target = e.target as HTMLElement;
        const btn = target.closest('button[data-action]');
        if (!btn) return;
        const action = btn.getAttribute('data-action');
        const code = btn.getAttribute('data-code');
        if (!code) return;
        if (action === 'view') openPackageDetails(code);
        else if (action === 'status') {
            const status = btn.getAttribute('data-status')!;
            changePackageStatus(code, status);
        }
        else if (action === 'archive') archivePackage(code);
    });
});