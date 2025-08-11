/**
 * Récupère toutes les données d'un formulaire HTML, avec support complet HTML5.
 * @param formId L'identifiant du formulaire à traiter.
 * @returns Un objet avec clé = nom du champ et valeur typée selon le type du champ.
 */
export function getData(
  formId: string
): Record<
  string,
  string | string[] | number | boolean | File | File[] | Date | null
> {
  const formElement = document.getElementById(formId);

  if (!formElement) {
    throw new Error(`Aucun formulaire trouvé avec l'ID: ${formId}`);
  }

  if (!(formElement instanceof HTMLFormElement)) {
    throw new Error(
      `L'élément avec l'ID ${formId} n'est pas un formulaire valide.`
    );
  }

  const form = formElement;
  const formData: Record<
    string,
    string | string[] | number | boolean | File | File[] | Date | null
  > = {};

  for (const el of Array.from(form.elements)) {
    if (!(el instanceof HTMLElement) || !el.hasAttribute("name")) {
      continue;
    }

    const name = (
      el as HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement
    ).name;

    if (el instanceof HTMLInputElement) {
      switch (el.type) {
        // Cases à cocher (plusieurs valeurs possibles)
        case "checkbox":
          if (formData[name] === undefined) {
            formData[name] = [];
          }
          if (el.checked) {
            (formData[name] as string[]).push(el.value);
          }
          break;

        // Boutons radio (1 seule valeur possible)
        case "radio":
          if (el.checked) {
            formData[name] = el.value;
          }
          break;

        // Fichiers
        case "file":
          if (el.multiple) {
            formData[name] = Array.from(el.files || []);
          } else {
            formData[name] = el.files?.[0] || null;
          }
          break;

        // Nombre
        case "number":
        case "range":
          formData[name] = el.value ? Number(el.value) : null;
          break;

        // Dates
        case "date":
        case "month":
        case "week":
        case "time":
        case "datetime-local":
          formData[name] = el.value ? new Date(el.value) : null;
          break;

        // Couleur
        case "color":
          formData[name] = el.value; // string hex
          break;

        // Texte, email, password, url, search, tel, hidden...
        default:
          formData[name] = el.value;
      }
    } else if (el instanceof HTMLSelectElement) {
      // Sélecteurs
      if (el.multiple) {
        formData[name] = Array.from(el.selectedOptions).map((opt) => opt.value);
      } else {
        formData[name] = el.value;
      }
    } else if (el instanceof HTMLTextAreaElement) {
      // Zone de texte
      formData[name] = el.value;
    }
  }

  return formData;
}
