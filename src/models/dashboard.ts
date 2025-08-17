import { DatabaseService } from '../services/DatabaseService.js';

interface Cargaison {
    id: number;
    numero_cargaison: string;
    type_transport: string;
    lieu_depart: string;
    lieu_arrive: string;
    poids_max: number;
    poids_actuel: number;
    etat: string;
    date_depart: string;
    date_arrivee: string;
    description: string;
}

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
    info_expediteur: any;
    info_destinataire: any;
}

function getEtatColis(colis: Colis): string {
    if (!colis.etat) return '';
    const etat = colis.etat.toLowerCase();
    if (etat.includes('livr')) return 'livré';
    if (etat.includes('attente')) return 'en attente';
    if (etat.includes('probl')) return 'problème';
    return colis.etat;
}

async function loadDashboard() {
    const dbService = DatabaseService.getInstance();
    await dbService.loadDatabase();
    const db = dbService.getDatabase();

    const cargaisons: Cargaison[] = db.cargaisons || [];
    const colis: Colis[] = db.colis || [];

    // Statistiques
    const nbCargaisonsOuvertes = cargaisons.filter(c => c.etat && c.etat.toLowerCase() === 'ouvert').length;
    const nbColisAttente = colis.filter(c => getEtatColis(c) === 'en attente').length;
    const nbColisLivres = colis.filter(c => getEtatColis(c) === 'livré').length;
    const nbColisProblemes = colis.filter(c => getEtatColis(c) === 'problème').length;

    // Injection dans le HTML
    document.getElementById('nb-cargaisons')!.textContent = nbCargaisonsOuvertes.toString();
    document.getElementById('nb-colis-attente')!.textContent = nbColisAttente.toString();
    document.getElementById('nb-colis-livres')!.textContent = nbColisLivres.toString();
    document.getElementById('nb-colis-problemes')!.textContent = nbColisProblemes.toString();

    // Cargaisons récentes
    const recentCargaisonsContainer = document.getElementById('recent-cargaisons');
    if (recentCargaisonsContainer) {
        recentCargaisonsContainer.innerHTML = cargaisons.slice(-3).reverse().map((c) => `
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-ship text-primary text-lg mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">${c.numero_cargaison}</p>
                        <p class="text-sm text-gray-600">${c.lieu_depart} → ${c.lieu_arrive}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        ${c.etat}
                    </span>
                    <p class="text-sm text-gray-500 mt-1">${colis.filter(col => col.cargaison_id === c.id).length} colis</p>
                </div>
            </div>
        `).join('');
    }

    // Alertes dynamiques
    const alertsContainer = document.getElementById('dashboard-alerts');
    if (alertsContainer) {
        const cargaisonsPleines = cargaisons.filter(c => Number(c.poids_actuel) >= Number(c.poids_max));
        const colisProblemes = colis.filter(c => getEtatColis(c) === 'problème');

        let alertsHtml = '';

        cargaisonsPleines.forEach(c => {
            alertsHtml += `
                <div class="flex items-center p-4 bg-red-100 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                    <div>
                        <p class="font-medium text-red-700">Cargaison ${c.numero_cargaison} pleine</p>
                        <p class="text-sm text-red-600">Aucune place disponible pour de nouveaux colis.</p>
                    </div>
                </div>
            `;
        });

        colisProblemes.forEach(c => {
            alertsHtml += `
                <div class="flex items-center p-4 bg-yellow-100 rounded-lg">
                    <i class="fas fa-exclamation-circle text-yellow-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-yellow-800">Colis ${c.numero_colis} en anomalie</p>
                        <p class="text-sm text-yellow-700">Vérifiez ce colis pour plus de détails.</p>
                    </div>
                </div>
            `;
        });

        if (!alertsHtml) {
            alertsHtml = `
                <div class="flex items-center p-4 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                    <div>
                        <p class="font-medium text-green-800">Aucune alerte</p>
                        <p class="text-sm text-green-700">Tout fonctionne normalement.</p>
                    </div>
                </div>
            `;
        }

        alertsContainer.innerHTML = alertsHtml;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadDashboard();
});