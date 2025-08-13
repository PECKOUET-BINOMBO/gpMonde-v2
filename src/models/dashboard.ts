import { AuthSuccessMessages, AuthTimings } from '../enums/authEnums.js';


/**
 * Interface pour représenter une session utilisateur
 */
interface UserSession {
  id: number;
  email: string;
  nom: string;
  prenom: string;
  role: string;
  loginTime: string;
}

/**
 * Classe pour gérer le tableau de bord
 * Cette classe gère l'affichage du tableau de bord et les messages de succès
 */
export class DashboardManager {
  private userSession: UserSession | null = null;

  /**
   * Constructeur de DashboardManager
   */
  constructor() {
    this.loadUserSession();
  }

  /**
   * Initialise le gestionnaire de tableau de bord
   */
  public initialize(): void {
    // Vérifier si l'utilisateur est connecté
    if (!this.userSession) {
      this.redirectToLogin();
      return;
    }

    // Configurer le tableau de bord
    this.setupDashboard();
    
    // Afficher le message de succès de connexion
    this.showLoginSuccessMessage();
  }

  /**
   * Charge la session utilisateur depuis le localStorage
   */
  private loadUserSession(): void {
    try {
      const sessionData = localStorage.getItem('userSession');
      if (sessionData) {
        this.userSession = JSON.parse(sessionData);
      }
    } catch (error) {
      console.error('Erreur lors du chargement de la session:', error);
      this.userSession = null;
    }
  }

  /**
   * Configure le tableau de bord avec les informations utilisateur
   */
  private setupDashboard(): void {
    if (!this.userSession) return;

    // Mettre à jour le nom d'utilisateur dans l'interface
    this.updateUserInfo();
    
    // Configurer les gestionnaires d'événements
    this.setupEventListeners();
  }

  /**
   * Met à jour les informations utilisateur dans l'interface
   */
  private updateUserInfo(): void {
    if (!this.userSession) return;

    // Mettre à jour le nom d'utilisateur
    const userNameElement = document.getElementById('user-name');
    if (userNameElement) {
      userNameElement.textContent = `${this.userSession.prenom} ${this.userSession.nom}`;
    }

    // Mettre à jour l'email
    const userEmailElement = document.getElementById('user-email');
    if (userEmailElement) {
      userEmailElement.textContent = this.userSession.email;
    }

    // Mettre à jour le rôle
    const userRoleElement = document.getElementById('user-role');
    if (userRoleElement) {
      userRoleElement.textContent = this.userSession.role;
    }

    // Mettre à jour l'heure de connexion
    const loginTimeElement = document.getElementById('login-time');
    if (loginTimeElement) {
      const loginDate = new Date(this.userSession.loginTime);
      loginTimeElement.textContent = loginDate.toLocaleString('fr-FR');
    }
  }

  /**
   * Configure les gestionnaires d'événements
   */
  private setupEventListeners(): void {
    // Gestionnaire pour le bouton de déconnexion
    const logoutButton = document.getElementById('logout-button');
    if (logoutButton) {
      logoutButton.addEventListener('click', () => this.handleLogout());
    }

    // Gestionnaire pour fermer les messages
    const closeButtons = document.querySelectorAll('.close-message');
    closeButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        const messageElement = (e.target as HTMLElement).closest('.message');
        if (messageElement) {
          messageElement.classList.add('hidden');
        }
      });
    });
  }

  /**
   * Affiche le message de succès de connexion
   */
  private showLoginSuccessMessage(): void {
    // Créer l'élément de message de succès
    const successMessage = this.createSuccessMessage(
      AuthSuccessMessages.CONNEXION_REUSSIE,
      'Bienvenue sur votre tableau de bord !'
    );

    // Insérer le message au début du contenu principal
    const mainContent = document.getElementById('main-content');
    if (mainContent) {
      mainContent.insertBefore(successMessage, mainContent.firstChild);
    }

    // Programmer la disparition automatique du message
    setTimeout(() => {
      this.hideMessage(successMessage);
    }, AuthTimings.MESSAGE_DISPLAY_DURATION);
  }

  /**
   * Crée un élément de message de succès
   * @param title Le titre du message
   * @param description La description du message
   * @returns L'élément HTML du message
   */
  private createSuccessMessage(title: string, description: string): HTMLElement {
    const messageElement = document.createElement('div');
    messageElement.className = 'message bg-green-50 border border-green-200 rounded-lg p-4 mb-6 animate-fade-in';
    messageElement.innerHTML = `
      <div class="flex items-start">
        <div class="flex-shrink-0">
          <i class="fas fa-check-circle text-green-500 text-xl"></i>
        </div>
        <div class="ml-3 flex-1">
          <h3 class="text-sm font-medium text-green-800">${title}</h3>
          <p class="mt-1 text-sm text-green-700">${description}</p>
        </div>
        <div class="ml-auto pl-3">
          <button class="close-message inline-flex text-green-400 hover:text-green-600 focus:outline-none">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
    `;

    return messageElement;
  }

  /**
   * Masque un message avec une animation
   * @param messageElement L'élément de message à masquer
   */
  private hideMessage(messageElement: HTMLElement): void {
    messageElement.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
    messageElement.style.opacity = '0';
    messageElement.style.transform = 'translateY(-10px)';
    
    setTimeout(() => {
      if (messageElement.parentNode) {
        messageElement.parentNode.removeChild(messageElement);
      }
    }, 500);
  }

  /**
   * Gère la déconnexion de l'utilisateur
   */
  private handleLogout(): void {
    // Supprimer la session
    localStorage.removeItem('userSession');
    
    // Afficher un message de déconnexion
    this.showLogoutMessage();
    
    // Rediriger vers la page de connexion après un délai
    setTimeout(() => {
      this.redirectToLogin();
    }, 2000);
  }

  /**
   * Affiche un message de déconnexion
   */
  private showLogoutMessage(): void {
    const logoutMessage = this.createInfoMessage(
      'Déconnexion en cours...',
      'Vous allez être redirigé vers la page de connexion.'
    );

    const mainContent = document.getElementById('main-content');
    if (mainContent) {
      mainContent.insertBefore(logoutMessage, mainContent.firstChild);
    }
  }

  /**
   * Crée un élément de message d'information
   * @param title Le titre du message
   * @param description La description du message
   * @returns L'élément HTML du message
   */
  private createInfoMessage(title: string, description: string): HTMLElement {
    const messageElement = document.createElement('div');
    messageElement.className = 'message bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 animate-fade-in';
    messageElement.innerHTML = `
      <div class="flex items-start">
        <div class="flex-shrink-0">
          <i class="fas fa-info-circle text-blue-500 text-xl"></i>
        </div>
        <div class="ml-3 flex-1">
          <h3 class="text-sm font-medium text-blue-800">${title}</h3>
          <p class="mt-1 text-sm text-blue-700">${description}</p>
        </div>
      </div>
    `;

    return messageElement;
  }

  /**
   * Redirige vers la page de connexion
   */
  private redirectToLogin(): void {
    window.location.href = '/';
  }

  /**
   * Affiche un message d'erreur personnalisé
   * @param title Le titre de l'erreur
   * @param description La description de l'erreur
   */
  public showErrorMessage(title: string, description: string): void {
    const errorMessage = this.createErrorMessage(title, description);
    
    const mainContent = document.getElementById('main-content');
    if (mainContent) {
      mainContent.insertBefore(errorMessage, mainContent.firstChild);
    }

    // Programmer la disparition automatique du message
    setTimeout(() => {
      this.hideMessage(errorMessage);
    }, AuthTimings.MESSAGE_DISPLAY_DURATION);
  }

  /**
   * Crée un élément de message d'erreur
   * @param title Le titre du message
   * @param description La description du message
   * @returns L'élément HTML du message
   */
  private createErrorMessage(title: string, description: string): HTMLElement {
    const messageElement = document.createElement('div');
    messageElement.className = 'message bg-red-50 border border-red-200 rounded-lg p-4 mb-6 animate-fade-in';
    messageElement.innerHTML = `
      <div class="flex items-start">
        <div class="flex-shrink-0">
          <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
        </div>
        <div class="ml-3 flex-1">
          <h3 class="text-sm font-medium text-red-800">${title}</h3>
          <p class="mt-1 text-sm text-red-700">${description}</p>
        </div>
        <div class="ml-auto pl-3">
          <button class="close-message inline-flex text-red-400 hover:text-red-600 focus:outline-none">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
    `;

    return messageElement;
  }

  /**
   * Obtient les informations de la session utilisateur
   * @returns Les informations de session ou null
   */
  public getUserSession(): UserSession | null {
    return this.userSession;
  }

  /**
   * Vérifie si l'utilisateur est connecté
   * @returns true si connecté, false sinon
   */
  public isLoggedIn(): boolean {
    return this.userSession !== null;
  }
}

// Initialisation automatique quand le DOM est chargé
document.addEventListener('DOMContentLoaded', () => {
  try {
    const dashboardManager = new DashboardManager();
    dashboardManager.initialize();
    
    // Exposer globalement pour le débogage (optionnel)
    (window as any).dashboardManager = dashboardManager;
    
  } catch (error) {
    console.error('Erreur lors de l\'initialisation du tableau de bord:', error);
  }
});