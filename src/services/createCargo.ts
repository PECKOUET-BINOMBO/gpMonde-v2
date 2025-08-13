import { GetData } from './GetData.js';

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('newCargoForm') as HTMLFormElement;
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const getData = new GetData('newCargoForm');
    const data = getData.extractData();

    const payload = {
      numero_cargaison: 'CARG' + Math.floor(Math.random() * 1000000),
      type_transport: data.type_transport,
      lieu_depart: data.lieu_depart,
      lieu_arrive: data.lieu_arrive,
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
      const result = await response.json();

      if (result.success) {
        alert('Cargaison créée !');
        form.reset();
      } else {
        alert('Erreur lors de la création');
      }
    } catch (err) {
      alert('Erreur serveur');
    }
  });
});
