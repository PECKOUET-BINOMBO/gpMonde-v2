import { GetData } from './../services/GetData.js';
import { AuthValidator, LoginFormData } from '../services/authValidator.js';
import { DatabaseService, AuthResult } from '../services/DatabaseService.js';
import { 
  AuthErrorMessages, 
  AuthSuccessMessages, 
  AuthLoadingStates, 
  AuthTimings,
  AuthStatusCodes
} from '../enums/authEnums.js';

/**
 * Classe principale pour gérer la connexion utilisateur
 * Cette classe orchestre toute la logique de connexion
 */
export class LoginManager {
  private getData: GetData;
  private databaseService: DatabaseService;
  private currentStatus: AuthStatusCodes = AuthStatusCodes.IDLE;

  /**
   * Constructeur de LoginManager
   * @param formId L'ID du formulaire de connexion
   */
  constructor(formId: string) {
    this.getData = new GetData(formId);
    this.databaseService = DatabaseService.getInstance();
  }

  /**
   * Initialise le gestionnaire de connexion
   */
  public async initialize(): Promise<void> {
    // Charger la base de données
    await this.databaseService.loadDatabase();
    
    // Configurer les gestionnaires d'événements
    this.setupEventListeners();
  }

  /**
   * Configure tous les gestionnaires d'événements
   */
  private setupEventListeners(): void {
    // Gestionnaire pour la soumission du formulaire
    const form = document.getElementById('loginForm');
    if (form) {
      form.addEventListener('submit', (e) => this.handleFormSubmit(e));
    }

    // Gestionnaire pour le bouton "afficher/masquer" le mot de passe
    this.setupPasswordToggle();
  }

  /**
   * Configure le bouton pour afficher/masquer le mot de passe
   */
  private setupPasswordToggle(): void {
    const passwordIcon = document.getElementById('password-icon');
    const passwordInput = document.getElementById('password') as HTMLInputElement;
    
    if (passwordIcon && passwordInput) {
      passwordIcon.addEventListener('click', () => {
        if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
          passwordIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
          passwordInput.type = 'password';
          passwordIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
      });
    }
  }

  /**
   * Gère la soumission du formulaire de connexion
   * @param event L'événement de soumission
   */
  private async handleFormSubmit(event: Event): Promise<void> {
    event.preventDefault();
    
    if (this.currentStatus === AuthStatusCodes.LOADING) {
      return; // Empêcher les soumissions multiples
    }

    try {
      // Étape 1: Extraire les données du formulaire
      const formData = this.getData.extractData();
      const loginData: LoginFormData = {
        email: formData.email as string | null,
        password: formData.password as string | null
      };

      // Étape 2: Valider les données
      const validationErrors = AuthValidator.validateLogin(loginData);
      this.displayValidationErrors(validationErrors);

      // Si il y a des erreurs de validation, arrêter ici
      if (Object.keys(validationErrors).length > 0) {
        return;
      }

      // Étape 3: Tenter la connexion
      await this.attemptLogin(loginData.email!, loginData.password!);

    } catch (error) {
      console.error('Erreur lors de la connexion:', error);
      this.showGlobalError(AuthErrorMessages.CONNEXION_ERREUR);
      this.setLoadingState(false);
    }
  }

  /**
   * Tente de connecter l'utilisateur
   * @param email L'email de l'utilisateur
   * @param password Le mot de passe de l'utilisateur
   */
  private async attemptLogin(email: string, password: string): Promise<void> {
    // Activer l'état de chargement
    this.setLoadingState(true);
    this.currentStatus = AuthStatusCodes.LOADING;

    try {
      // Authentifier l'utilisateur
      const authResult: AuthResult = await this.databaseService.authenticateUser(email, password);

      if (authResult.success && authResult.user) {
        // Connexion réussie
        this.handleSuccessfulLogin(authResult.user);
      } else {
        // Connexion échouée
        this.handleFailedLogin(authResult.message);
      }
    } catch (error) {
      console.error('Erreur d\'authentification:', error);
      this.handleFailedLogin(AuthErrorMessages.CONNEXION_ERREUR);
    }
  }

  /**
   * Gère une connexion réussie
   * @param user L'utilisateur connecté
   */
  private handleSuccessfulLogin(user: any): void {
    this.currentStatus = AuthStatusCodes.SUCCESS;
    
    // Sauvegarder les informations utilisateur (optionnel)
    this.saveUserSession(user);
    
    // Afficher le message de succès
    this.showSuccessMessage(AuthSuccessMessages.CONNEXION_REUSSIE);
    
    // Rediriger après un délai
    setTimeout(() => {
      this.redirectToDashboard();
    }, AuthTimings.REDIRECT_DELAY);
  }

  /**
   * Gère une connexion échouée
   * @param errorMessage Le message d'erreur
   */
  private handleFailedLogin(errorMessage: string): void {
    this.currentStatus = AuthStatusCodes.ERROR;
    this.setLoadingState(false);
    this.showGlobalError(errorMessage);
  }

  /**
   * Sauvegarde la session utilisateur
   * @param user L'utilisateur à sauvegarder
   */
  private saveUserSession(user: any): void {
    // Sauvegarder dans le localStorage (attention: pas sécurisé pour des données sensibles)
    const userSession = {
      id: user.id,
      email: user.email,
      nom: user.nom,
      prenom: user.prenom,
      role: user.role,
      loginTime: new Date().toISOString()
    };
    
    localStorage.setItem('userSession', JSON.stringify(userSession));
  }

  /**
   * Redirige vers le tableau de bord
   */
  private redirectToDashboard(): void {
    // Dans un vrai projet, vous utiliseriez un routeur
    window.location.href = '/dashboard';
  }

  /**
   * Affiche les erreurs de validation dans le formulaire
   * @param errors Les erreurs de validation
   */
  private displayValidationErrors(errors: Record<string, string>): void {
    // Réinitialiser les erreurs précédentes
    document.querySelectorAll("[id$='-error']").forEach(el => {
      (el as HTMLElement).classList.add('hidden');
    });

    // Afficher les nouvelles erreurs
    for (const [field, message] of Object.entries(errors)) {
      const errorElement = document.getElementById(`${field}-error`);
      if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
      }
    }
  }

  /**
   * Affiche une erreur globale dans le formulaire
   * @param message Le message d'erreur
   */
  private showGlobalError(message: string): void {
    const errorElement = document.getElementById('global-error');
    const errorMessage = document.getElementById('error-message');
    
    if (errorElement && errorMessage) {
      errorMessage.textContent = message;
      errorElement.classList.remove('hidden');
      
      // Masquer l'erreur après un délai
      setTimeout(() => {
        errorElement.classList.add('hidden');
      }, AuthTimings.MESSAGE_DISPLAY_DURATION);
    }
  }

  /**
   * Affiche un message de succès
   * @param message Le message de succès
   */
  private showSuccessMessage(message: string): void {
  const successElement = document.getElementById('global-success');
  const successMessage = document.getElementById('success-message');
  if (successElement && successMessage) {
    successMessage.textContent = message;
    successElement.classList.remove('hidden');
    // Masquer le message après un délai
    setTimeout(() => {
      successElement.classList.add('hidden');
    }, AuthTimings.MESSAGE_DISPLAY_DURATION);
  }
}

  /**
   * Active/désactive l'état de chargement du formulaire
   * @param isLoading État de chargement
   */
  private setLoadingState(isLoading: boolean): void {
    const loginText = document.getElementById('login-text');
    const loginSpinner = document.getElementById('login-spinner');
    const submitButton = document.querySelector('#loginForm button[type="submit"]') as HTMLButtonElement;

    if (loginText && loginSpinner && submitButton) {
      if (isLoading) {
        loginText.textContent = AuthLoadingStates.CONNEXION_EN_COURS;
        loginSpinner.classList.remove('hidden');
        submitButton.disabled = true;
      } else {
        loginText.textContent = AuthLoadingStates.SE_CONNECTER;
        loginSpinner.classList.add('hidden');
        submitButton.disabled = false;
      }
    }
  }

  /**
   * Obtient le statut actuel de l'authentification
   * @returns Le statut actuel
   */
  public getCurrentStatus(): AuthStatusCodes {
    return this.currentStatus;
  }

  /**
   * Réinitialise le formulaire
   */
  public resetForm(): void {
    this.getData.resetForm();
    this.currentStatus = AuthStatusCodes.IDLE;
    this.setLoadingState(false);
    
    // Masquer tous les messages
    document.querySelectorAll("[id$='-error'], #global-error, #global-success").forEach(el => {
      (el as HTMLElement).classList.add('hidden');
    });
  }
}

// Initialisation automatique quand le DOM est chargé
document.addEventListener('DOMContentLoaded', async () => {
  try {
    const loginManager = new LoginManager('loginForm');
    await loginManager.initialize();
    
    // Exposer globalement pour le débogage (optionnel)
    (window as any).loginManager = loginManager;
    
  } catch (error) {
    console.error('Erreur lors de l\'initialisation du gestionnaire de connexion:', error);
  }
});