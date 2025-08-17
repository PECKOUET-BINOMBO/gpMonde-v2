import { GetData } from "./GetData.js";

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("newCargoForm") as HTMLFormElement;
  if (!form) return;

  const modal = document.getElementById("newCargoModal");
  const statusMessage = document.createElement("div");
  statusMessage.className = "mt-4 text-center";
  form.appendChild(statusMessage);

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    // Reset status message
    statusMessage.textContent = "";
    statusMessage.className = "mt-4 text-center";

    const getData = new GetData("newCargoForm");
    const data = getData.extractData();

    // Validation simple
    if (
      !data.lieu_depart ||
      !data.lieu_arrive ||
      !data.date_depart ||
      !data.date_arrivee
    ) {
      statusMessage.textContent =
        "Veuillez remplir tous les champs obligatoires";
      statusMessage.className += " text-red-600";
      return;
    }

    const payload = {
      numero_cargaison: "CARG" + Math.floor(Math.random() * 1000000),
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
      description: data.description || "",
    };

    try {
      const response = await fetch("/api/cargaison", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      });

      if (!response.ok) {
        const result = await response.json();
        throw new Error(
          result.message || "Erreur lors de la création de la cargaison"
        );
      }

      const result = await response.json();

      // createCargo.ts
      if (result.success) {
        // Stocker le message dans localStorage pour l'afficher sur le dashboard
        localStorage.setItem(
          "cargoCreationSuccess",
          "Cargaison créée avec succès !"
        );

        // Fermer le modal et recharger
        setTimeout(() => {
          if (modal) modal.classList.add("hidden");
          form.reset();
          window.location.href = "/dashboard"; // Rediriger vers le dashboard
        }, 500);
      } else {
        throw new Error(result.message || "Erreur lors de la création");
      }
    } catch (err) {
      let errorMsg = "Erreur serveur";
      if (err instanceof Error) {
        errorMsg = err.message;
      }
      statusMessage.textContent = errorMsg;
      statusMessage.className += " text-red-600";
      console.error("Erreur:", err);
    }
  });

  async function loadAvailableCargaisons() {
    try {
        const response = await fetch('/api/cargaisons?status=open');
        const cargasons = await response.json();
        
        const container = document.getElementById('cargaisons-disponibles');
        if (!container) return;
        
        container.innerHTML = '';
        
        cargasons.forEach((cargo: any) => {
            const cargoElement = document.createElement('div');
            cargoElement.className = 'p-4 border border-gray-200 rounded-lg hover:border-primary cursor-pointer transition-colors';
            cargoElement.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="radio" name="cargaison" value="${cargo.id}" class="mr-3">
                        <div>
                            <p class="font-medium text-gray-900">${cargo.numero_cargaison} - ${cargo.type_transport}</p>
                            <p class="text-sm text-gray-600">${cargo.lieu_depart} → ${cargo.lieu_arrive} | Capacité restante: ${cargo.poids_max - cargo.poids_actuel}kg</p>
                        </div>
                    </div>
                    <span class="text-sm ${cargo.etat === 'ouvert' ? 'text-green-600' : 'text-red-600'} font-medium">${cargo.etat}</span>
                </div>
            `;
            cargoElement.addEventListener('click', () => selectCargaison(cargoElement, cargo.id));
            container.appendChild(cargoElement);
        });
    } catch (error) {
        console.error('Erreur lors du chargement des cargaisons:', error);
    }
}

});

function selectCargaison(cargoElement: HTMLDivElement, id: any): any {
  throw new Error("Function not implemented.");
}
