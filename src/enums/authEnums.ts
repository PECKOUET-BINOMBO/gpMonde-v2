/**
 * Énumérations pour les messages d'authentification
 */

/**
 * Messages d'erreur pour l'authentification
 */
export enum AuthErrorMessages {
  CHAMP_VIDE = "Le champ ne doit pas être vide",
  EMAIL_INVALIDE = "Email invalide",
  EMAIL_OBLIGATOIRE = "L'adresse e-mail est obligatoire",
  EMAIL_TROP_LONG = "L'adresse e-mail ne doit pas dépasser 255 caractères",
  EMAIL_FORMAT_INVALIDE = "Le format de l'adresse e-mail est invalide",
  EMAIL_TEMPORAIRE_INTERDIT = "Les adresses e-mail temporaires ne sont pas autorisées",
  
  PASSWORD_OBLIGATOIRE = "Le mot de passe est obligatoire",
  PASSWORD_TROP_COURT = "Le mot de passe doit contenir au moins 8 caractères",
  PASSWORD_TROP_LONG = "Le mot de passe est trop long",
  PASSWORD_COMPLEXITE = "Le mot de passe doit contenir au moins 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial",
  
  LOGIN_INVALIDE = "Identifiant ou mot de passe invalide",
  CONNEXION_ERREUR = "Une erreur est survenue lors de la connexion",
  UTILISATEUR_NON_TROUVE = "Aucun utilisateur trouvé avec ces identifiants"
}

/**
 * Messages de succès pour l'authentification
 */
export enum AuthSuccessMessages {
  CONNEXION_REUSSIE = "Connexion réussie",
  DECONNEXION_REUSSIE = "Déconnexion réussie",
  REDIRECTION_EN_COURS = "Redirection vers le tableau de bord..."
}

/**
 * États de chargement pour l'interface utilisateur
 */
export enum AuthLoadingStates {
  CONNEXION_EN_COURS = "Connexion en cours...",
  SE_CONNECTER = "Se connecter",
  VERIFICATION_EN_COURS = "Vérification des identifiants...",
  REDIRECTION = "Redirection..."
}

/**
 * Types d'utilisateurs dans le système
 */
export enum UserRoles {
  GESTIONNAIRE = "gestionnaire",
  ADMIN = "admin",
  UTILISATEUR = "utilisateur"
}

/**
 * Codes de statut pour les opérations d'authentification
 */
export enum AuthStatusCodes {
  SUCCESS = "SUCCESS",
  ERROR = "ERROR",
  LOADING = "LOADING",
  IDLE = "IDLE"
}

/**
 * Durées en millisecondes pour les animations et timeouts
 */
export enum AuthTimings {
  MESSAGE_DISPLAY_DURATION = 5000, // 5 secondes
  REDIRECT_DELAY = 2000, // 2 secondes
  LOADING_MIN_DURATION = 1000 // 1 seconde minimum pour le loading
}