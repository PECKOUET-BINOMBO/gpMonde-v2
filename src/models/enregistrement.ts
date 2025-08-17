import { GetData } from '../services/GetData.js';
import { DatabaseService } from '../services/DatabaseService.js';

export class EnregistrementColis {
    private formId: string;
    private dbService: DatabaseService;

    constructor(formId: string = 'packageForm') {
        this.formId = formId;
        this.dbService = DatabaseService.getInstance();
        this.initialize();
    }

    private async initialize(): Promise<void> {
        await this.loadCargaisons();
        this.setupFormSubmit();
        this.setupEventListeners();
    }

    private async loadCargaisons(): Promise<void> {
        try {
            const cargaisons = await this.dbService.getCargaisons('ouvert');
            this.renderCargaisons(cargaisons);
        } catch (error) {
            console.error('Erreur de chargement des cargaisons:', error);
            alert('Impossible de charger les cargaisons disponibles');
        }
    }

    private renderCargaisons(cargaisons: any[]): void {
        const container = document.getElementById('cargaisons-disponibles');
        if (!container) return;

        if (cargaisons.length === 0) {
            container.innerHTML = '<p class="text-gray-500 py-4">Aucune cargaison disponible</p>';
            return;
        }

        container.innerHTML = cargaisons.map(c => `
            <div class="border border-gray-200 rounded-lg p-4 mb-3 cursor-pointer hover:shadow-md transition-shadow"
                 onclick="selectCargaison(this, ${c.id})">
                <div class="flex items-start">
                    <input type="radio" name="cargaison_id" value="${c.id}" class="mt-1 mr-3 hidden">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">${c.numero_cargaison}</h4>
                        <div class="grid grid-cols-2 gap-2 mt-2 text-sm text-gray-600">
                            <div><span class="font-medium">Type:</span> ${c.type_transport}</div>
                            <div><span class="font-medium">Poids max:</span> ${c.poids_max} kg</div>
                            <div><span class="font-medium">Poids actuel:</span> ${c.poids_actuel || 0} kg</div>
                            <div><span class="font-medium">Disponible:</span> ${c.poids_max - (c.poids_actuel || 0)} kg</div>
                            <div><span class="font-medium">Départ:</span> ${c.lieu_depart}</div>
                            <div><span class="font-medium">Arrivée:</span> ${c.lieu_arrive}</div>
                            <div class="col-span-2">
                                <span class="font-medium">Dates:</span> 
                                ${new Date(c.date_depart).toLocaleDateString()} - 
                                ${new Date(c.date_arrivee).toLocaleDateString()}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    private setupEventListeners(): void {
        // Calcul du prix automatique
        document.getElementById('poids')?.addEventListener('input', this.calculatePrice.bind(this));
        document.getElementById('type_cargaison')?.addEventListener('change', this.calculatePrice.bind(this));
        
        // Validation du poids par rapport à la cargaison sélectionnée
        document.getElementById('poids')?.addEventListener('input', this.validateWeight.bind(this));
    }

    private validateWeight(): void {
        const poidsInput = document.getElementById('poids') as HTMLInputElement;
        const selectedCargaison = document.querySelector('input[name="cargaison_id"]:checked') as HTMLInputElement;
        
        if (!selectedCargaison || !poidsInput.value) return;

        const poids = parseFloat(poidsInput.value);
        const cargaisonId = parseInt(selectedCargaison.value);
        
        // Récupérer les données de la cargaison depuis le DOM
        const cargaisonElement = selectedCargaison.closest('.border');
        const poidsMaxText = cargaisonElement?.querySelector('.grid .text-sm:nth-child(2)')?.textContent;
        const poidsActuelText = cargaisonElement?.querySelector('.grid .text-sm:nth-child(3)')?.textContent;
        
        if (poidsMaxText && poidsActuelText) {
            const poidsMax = parseFloat(poidsMaxText.replace('Poids max:', '').replace(' kg', ''));
            const poidsActuel = parseFloat(poidsActuelText.replace('Poids actuel:', '').replace(' kg', ''));
            const poidsDisponible = poidsMax - poidsActuel;
            
            if (poids > poidsDisponible) {
                alert(`Le poids saisi (${poids} kg) dépasse la capacité disponible (${poidsDisponible} kg)`);
                poidsInput.value = poidsDisponible.toString();
                this.calculatePrice(); // Recalculer avec le nouveau poids
            }
        }
    }

    private calculatePrice(): void {
        const poids = parseFloat((document.getElementById('poids') as HTMLInputElement)?.value) || 0;
        const typeTransport = (document.getElementById('type_cargaison') as HTMLSelectElement)?.value;

        if (poids > 0 && typeTransport) {
            let prix = 0;
            switch (typeTransport) {
                case 'maritime': prix = poids * 1500; break; // 1500 XAF/kg
                case 'aerien': prix = poids * 5000; break;   // 5000 XAF/kg
                case 'routier': prix = poids * 2500; break; // 2500 XAF/kg
            }
            (document.getElementById('prix_calcule') as HTMLInputElement).value = `${prix.toFixed(0)} XAF`;
        } else {
            (document.getElementById('prix_calcule') as HTMLInputElement).value = '';
        }
    }

    private setupFormSubmit(): void {
        const form = document.getElementById(this.formId);
        form?.addEventListener('submit', async (e) => {
            e.preventDefault();
            await this.handleFormSubmit();
        });
    }

    private async handleFormSubmit(): Promise<void> {
        const formData = new GetData(this.formId).extractData();
        const cargaisonId = (document.querySelector('input[name="cargaison_id"]:checked') as HTMLInputElement)?.value;

        if (!cargaisonId) {
            alert('Veuillez sélectionner une cargaison');
            return;
        }

        // Validation des champs obligatoires
        const requiredFields = [
            'nom', 'prenom', 'telephone', 'adresse',
            'dest_nom', 'dest_prenom', 'dest_telephone', 'dest_adresse',
            'nombre_colis', 'poids', 'type_produit', 'type_cargaison'
        ];

        for (const field of requiredFields) {
            if (!formData[field]) {
                alert(`Le champ ${field.replace('dest_', 'destinataire ')} est obligatoire`);
                return;
            }
        }

        // Validation du poids
        const poids = parseFloat(formData.poids as string);
        if (poids <= 0) {
            alert('Le poids doit être supérieur à 0');
            return;
        }

        // Validation du prix calculé
        const prixText = (document.getElementById('prix_calcule') as HTMLInputElement).value;
        if (!prixText) {
            alert('Le prix n\'a pas été calculé. Vérifiez le poids et le type de transport.');
            return;
        }

        try {
            const numeroColis = 'CG' + Math.random().toString(36).substr(2, 6).toUpperCase();
            const prix = parseFloat(prixText.replace(' XAF', ''));

            const colisData = {
                numero_colis: numeroColis,
                cargaison_id: parseInt(cargaisonId),
                nbr_colis: parseInt(formData.nombre_colis as string),
                poids: poids,
                type_produit: formData.type_produit as string,
                type_transport: formData.type_cargaison as string,
                prix: prix,
                description: (formData.description as string) || '',
                info_expediteur: {
                    nom: formData.nom as string,
                    prenom: formData.prenom as string,
                    adresse: formData.adresse as string,
                    tel: formData.telephone as string,
                    email: (formData.email as string) || ''
                },
                info_destinataire: {
                    nom: formData.dest_nom as string,
                    prenom: formData.dest_prenom as string,
                    adresse: formData.dest_adresse as string,
                    tel: formData.dest_telephone as string,
                    email: (formData.dest_email as string) || ''
                }
            };

            console.log('Données du colis à envoyer:', colisData);

            const response = await fetch('/api/colis', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(colisData)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Erreur serveur');
            }

            const result = await response.json();
            if (result.success) {
                this.showConfirmation(numeroColis);
                console.log('Colis créé avec succès:', result.colis);
            } else {
                alert(result.message || 'Erreur lors de l\'enregistrement');
            }
        } catch (error) {
            let message = 'Une erreur est survenue';
            if (error instanceof Error) {
                message += ': ' + error.message;
            }
            console.error('Erreur:', error);
            alert(message);
        }
    }

    private showConfirmation(trackingCode: string): void {
        const modal = document.getElementById('confirmationModal');
        const codeDisplay = document.getElementById('tracking-code-display');
        
        if (modal && codeDisplay) {
            codeDisplay.textContent = trackingCode;
            modal.classList.remove('hidden');
        }
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    new EnregistrementColis();
});

// Fonctions globales pour le HTML
declare global {
    interface Window {
        selectCargaison: (element: HTMLElement, id: number) => void;
        resetForm: () => void;
        closeConfirmationModal: () => void;
        printReceipt: () => void;
    }
}

window.selectCargaison = function(element: HTMLElement, id: number) {
    document.querySelectorAll('#cargaisons-disponibles .border-primary').forEach(el => {
        el.classList.remove('border-primary', 'bg-blue-50');
    });
    element.classList.add('border-primary', 'bg-blue-50');
    (element.querySelector('input[type="radio"]') as HTMLInputElement).checked = true;
};

window.resetForm = function() {
    const form = document.getElementById('packageForm') as HTMLFormElement;
    form?.reset();
    (document.getElementById('prix_calcule') as HTMLInputElement).value = '';
    document.querySelectorAll('#cargaisons-disponibles .border-primary').forEach(el => {
        el.classList.remove('border-primary', 'bg-blue-50');
    });
};

window.closeConfirmationModal = function() {
    document.getElementById('confirmationModal')?.classList.add('hidden');
    window.resetForm();
};

window.printReceipt = function() {
    const trackingCode = document.getElementById('tracking-code-display')?.textContent;
    if (trackingCode) {
        window.print();
    }
};