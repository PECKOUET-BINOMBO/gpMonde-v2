import { AuthErrorMessages } from "../enums/authEnums.js";

/**
 * Définit la forme des données attendues pour le formulaire de connexion.
 */
export type LoginFormData = {
  email: string | null;
  password: string | null;
};

/**
 * Classe pour valider les données d'authentification
 * Cette classe utilise les énumérations pour centraliser les messages d'erreur
 */
export class AuthValidator {
  
  /**
   * Valide les données de connexion utilisateur.
   * @param data Données issues de FormDataExtractor.
   * @returns Un objet avec clé = nom du champ et valeur = message d'erreur.
   */
  public static validateLogin(data: LoginFormData): Record<string, string> {
    const errors: Record<string, string> = {};

    // Validation de l'email
    this.validateEmail(data.email, errors);
    
    // Validation du mot de passe
    this.validatePassword(data.password, errors);

    return errors;
  }

  /**
   * Valide le champ email
   * @param email L'email à valider
   * @param errors L'objet d'erreurs à remplir
   */
  private static validateEmail(email: string | null, errors: Record<string, string>): void {
    if (!email || email.trim() === "") {
      errors.email = AuthErrorMessages.EMAIL_OBLIGATOIRE;
      return;
    }

    const trimmedEmail = email.trim();

    // Vérification de la taille maximale
    if (trimmedEmail.length > 255) {
      errors.email = AuthErrorMessages.EMAIL_TROP_LONG;
      return;
    }

    // Vérification du format email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(trimmedEmail)) {
      errors.email = AuthErrorMessages.EMAIL_FORMAT_INVALIDE;
      return;
    }

    // Vérification des domaines temporaires (optionnel)
    const tempDomains = ["yopmail.com", "mailinator.com", "10minutemail.com"];
    const domain = trimmedEmail.split("@")[1]?.toLowerCase();
    if (tempDomains.includes(domain)) {
      errors.email = AuthErrorMessages.EMAIL_TEMPORAIRE_INTERDIT;
    }
  }

  /**
   * Valide le champ mot de passe
   * @param password Le mot de passe à valider
   * @param errors L'objet d'erreurs à remplir
   */
  private static validatePassword(password: string | null, errors: Record<string, string>): void {
    if (!password || password.trim() === "") {
      errors.password = AuthErrorMessages.PASSWORD_OBLIGATOIRE;
      return;
    }

    // Vérification de la taille minimale
    if (password.length < 8) {
      errors.password = AuthErrorMessages.PASSWORD_TROP_COURT;
      return;
    }

    // Vérification de la taille maximale (sécurité)
    if (password.length > 128) {
      errors.password = AuthErrorMessages.PASSWORD_TROP_LONG;
      return;
    }

    // Règles de complexité (optionnel - commenté pour simplifier)
    // const complexityRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/;
    // if (!complexityRegex.test(password)) {
    //   errors.password = AuthErrorMessages.PASSWORD_COMPLEXITE;
    // }
  }

  /**
   * Vérifie si un email a un format valide (méthode utilitaire)
   * @param email L'email à vérifier
   * @returns true si l'email est valide, false sinon
   */
  public static isValidEmail(email: string): boolean {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email.trim());
  }

  /**
   * Vérifie si un mot de passe respecte les critères minimaux
   * @param password Le mot de passe à vérifier
   * @returns true si le mot de passe est valide, false sinon
   */
  public static isValidPassword(password: string): boolean {
    return password.length >= 8 && password.length <= 128;
  }
}

/**
 * Fonction de compatibilité pour maintenir l'API existante
 * @deprecated Utilisez AuthValidator.validateLogin() à la place
 */
export function validateLogin(data: LoginFormData): Record<string, string> {
  return AuthValidator.validateLogin(data);
}
