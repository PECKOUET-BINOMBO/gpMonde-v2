/**
 * Classe pour extraire les données d'un formulaire HTML
 */
export class GetData {
  private formElement: HTMLFormElement;

  /**
   * Constructeur de la classe GetData
   * @param formId L'identifiant du formulaire à traiter
   * @throws Error si le formulaire n'est pas trouvé ou n'est pas valide
   */
  constructor(formId: string) {
    const element = document.getElementById(formId);

    if (!element) {
      throw new Error(`Aucun formulaire trouvé avec l'ID: ${formId}`);
    }

    if (!(element instanceof HTMLFormElement)) {
      throw new Error(
        `L'élément avec l'ID ${formId} n'est pas un formulaire valide.`
      );
    }

    this.formElement = element;
  }

  /**
   * Extrait toutes les données du formulaire
   * @returns Un objet avec clé = nom du champ et valeur typée selon le type du champ
   */
  public extractData(): Record<
    string,
    string | string[] | number | boolean | File | File[] | Date | null
  > {
    const formData: Record<
      string,
      string | string[] | number | boolean | File | File[] | Date | null
    > = {};

    // Parcourir tous les éléments du formulaire
    for (const element of Array.from(this.formElement.elements)) {
      if (!(element instanceof HTMLElement) || !element.hasAttribute("name")) {
        continue;
      }

      const name = (
        element as HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement
      ).name;

      // Traiter selon le type d'élément
      if (element instanceof HTMLInputElement) {
        this.processInputElement(element, name, formData);
      } else if (element instanceof HTMLSelectElement) {
        this.processSelectElement(element, name, formData);
      } else if (element instanceof HTMLTextAreaElement) {
        this.processTextAreaElement(element, name, formData);
      }
    }

    return formData;
  }

  /**
   * Traite les éléments input selon leur type
   * @param element L'élément input à traiter
   * @param name Le nom du champ
   * @param formData L'objet de données à remplir
   */
  private processInputElement(
    element: HTMLInputElement,
    name: string,
    formData: Record<string, any>
  ): void {
    switch (element.type) {
      case "checkbox":
        this.processCheckboxInput(element, name, formData);
        break;

      case "radio":
        this.processRadioInput(element, name, formData);
        break;

      case "file":
        this.processFileInput(element, name, formData);
        break;

      case "number":
      case "range":
        this.processNumberInput(element, name, formData);
        break;

      case "date":
      case "month":
      case "week":
      case "time":
      case "datetime-local":
        this.processDateInput(element, name, formData);
        break;

      case "color":
        formData[name] = element.value;
        break;

      default:
        // Texte, email, password, url, search, tel, hidden...
        formData[name] = element.value;
    }
  }

  /**
   * Traite les cases à cocher (plusieurs valeurs possibles)
   */
  private processCheckboxInput(
    element: HTMLInputElement,
    name: string,
    formData: Record<string, any>
  ): void {
    if (formData[name] === undefined) {
      formData[name] = [];
    }
    if (element.checked) {
      (formData[name] as string[]).push(element.value);
    }
  }

  /**
   * Traite les boutons radio (1 seule valeur possible)
   */
  private processRadioInput(
    element: HTMLInputElement,
    name: string,
    formData: Record<string, any>
  ): void {
    if (element.checked) {
      formData[name] = element.value;
    }
  }

  /**
   * Traite les champs de fichiers
   */
  private processFileInput(
    element: HTMLInputElement,
    name: string,
    formData: Record<string, any>
  ): void {
    if (element.multiple) {
      formData[name] = Array.from(element.files || []);
    } else {
      formData[name] = element.files?.[0] || null;
    }
  }

  /**
   * Traite les champs numériques
   */
  private processNumberInput(
    element: HTMLInputElement,
    name: string,
    formData: Record<string, any>
  ): void {
    formData[name] = element.value ? Number(element.value) : null;
  }

  /**
   * Traite les champs de date
   */
  private processDateInput(
    element: HTMLInputElement,
    name: string,
    formData: Record<string, any>
  ): void {
    formData[name] = element.value ? new Date(element.value) : null;
  }

  /**
   * Traite les éléments select
   */
  private processSelectElement(
    element: HTMLSelectElement,
    name: string,
    formData: Record<string, any>
  ): void {
    if (element.multiple) {
      formData[name] = Array.from(element.selectedOptions).map((opt) => opt.value);
    } else {
      formData[name] = element.value;
    }
  }

  /**
   * Traite les zones de texte
   */
  private processTextAreaElement(
    element: HTMLTextAreaElement,
    name: string,
    formData: Record<string, any>
  ): void {
    formData[name] = element.value;
  }

  /**
   * Méthode utilitaire pour obtenir la valeur d'un champ spécifique
   * @param fieldName Le nom du champ
   * @returns La valeur du champ ou null si non trouvé
   */
  public getFieldValue(fieldName: string): any {
    const data = this.extractData();
    return data[fieldName] || null;
  }

  /**
   * Méthode utilitaire pour vérifier si le formulaire est valide (HTML5)
   * @returns true si le formulaire est valide, false sinon
   */
  public isFormValid(): boolean {
    return this.formElement.checkValidity();
  }

  /**
   * Méthode utilitaire pour réinitialiser le formulaire
   */
  public resetForm(): void {
    this.formElement.reset();
  }
}
