/**
 * Définit la forme des données attendues pour le formulaire de connexion.
 */
export type LoginFormData = {
  email: string | null;
  password: string | null;
};

/**
 * Valide les données de connexion utilisateur.
 * @param data Données issues de getData().
 * @returns Un objet avec clé = nom du champ et valeur = message d'erreur.
 */
export function validateLogin(data: LoginFormData): Record<string, string> {
  const errors: Record<string, string> = {};

  // ----------- Email -----------
  if (!data.email || data.email.trim() === "") {
    errors.email = "L'adresse e-mail est obligatoire.";
  } else {
    const email = data.email.trim();

    // Taille max
    if (email.length > 255) {
      errors.email = "L'adresse e-mail ne doit pas dépasser 255 caractères.";
    }

    // Format email basique
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      errors.email = "Le format de l'adresse e-mail est invalide.";
    }

    // Optionnel : bloquer les emails jetables
    const tempDomains = ["yopmail.com", "mailinator.com", "10minutemail.com"];
    const domain = email.split("@")[1]?.toLowerCase();
    if (tempDomains.includes(domain)) {
      errors.email = "Les adresses e-mail temporaires ne sont pas autorisées.";
    }
  }

  // ----------- Mot de passe -----------
  if (!data.password || data.password.trim() === "") {
    errors.password = "Le mot de passe est obligatoire.";
  } else {
    const password = data.password;

    // Taille minimale
    if (password.length < 8) {
      errors.password = "Le mot de passe doit contenir au moins 8 caractères.";
    }

    // Taille max (sécurité)
    if (password.length > 128) {
      errors.password = "Le mot de passe est trop long.";
    }

    // Optionnel : règles de complexité
    // const complexityRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/;
    // if (!complexityRegex.test(password)) {
    //   errors.password =
    //     "Le mot de passe doit contenir au moins 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial.";
    // }
  }

  return errors;
}