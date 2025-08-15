import { GetData } from './GetData.js';

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('newCargoForm') as HTMLFormElement;
  if (!form) return;

  const modal = document.getElementById('newCargoModal');
  const statusMessage = document.createElement('div');
  statusMessage.className = 'mt-4 text-center';
  form.appendChild(statusMessage);

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    // Reset status message
    statusMessage.textContent = '';
    statusMessage.className = 'mt-4 text-center';

    const getData = new GetData('newCargoForm');
    const data = getData.extractData();

    // Validation simple
    if (!data.lieu_depart || !data.lieu_arrive || !data.date_depart || !data.date_arrivee) {
      statusMessage.textContent = 'Veuillez remplir tous les champs obligatoires';
      statusMessage.className += ' text-red-600';
      return;
    }

    const payload = {
      numero_cargaison: 'CARG' + Math.floor(Math.random() * 1000000),
      type_transport: data.type_transport,
      lieu_depart: data.lieu_depart,
      lieu_arrive: data.lieu_arrive,
      distance: data.distance,
      latitude_depart: data.latitude_depart,
      longitude_depart: data.longitude_depart,
      latitude_arrivee: data.latitude_arrivee,
      longitude_arrivee: data.longitude_arrivee,
      poids_max: data.poids_max,
      date_depart: data.date_depart,
      date_arrivee: data.date_arrivee,
      description: data.description || ''
    };

    try {
      const response = await fetch('/api/cargaison', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });

      if (!response.ok) {
        const result = await response.json();
        throw new Error(result.message || 'Erreur lors de la création de la cargaison');
      }

      const result = await response.json();
      
      // createCargo.ts
if (result.success) {
    // Stocker le message dans localStorage pour l'afficher sur le dashboard
    localStorage.setItem('cargoCreationSuccess', 'Cargaison créée avec succès !');
    
    // Fermer le modal et recharger
    setTimeout(() => {
        if (modal) modal.classList.add('hidden');
        form.reset();
        window.location.href = '/dashboard'; // Rediriger vers le dashboard
    }, 500);
} else {
    throw new Error(result.message || 'Erreur lors de la création');
}
    } catch (err) {
      let errorMsg = 'Erreur serveur';
      if (err instanceof Error) {
        errorMsg = err.message;
      }
      statusMessage.textContent = errorMsg;
      statusMessage.className += ' text-red-600';
      console.error('Erreur:', err);
    }
  });
});