/**
 * Configure la fonctionnalité de déconnexion
 */
export function setupLogout() {
  const logoutBtn = document.getElementById('logout-btn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', (e) => {
      e.preventDefault();
      // Supprimer la session utilisateur
      localStorage.removeItem('userSession');
      // Rediriger vers la page de connexion
      window.location.href = '/';
    });
  }
}

/**
 * Vérifie si un utilisateur est connecté
 * @returns L'utilisateur connecté ou null
 */
export function getCurrentUser(): { role: string } | null {
  const userSession = localStorage.getItem('userSession');
  return userSession ? JSON.parse(userSession) : null;
}

/**
 * Met à jour l'interface en fonction de l'état de connexion
 */
export function updateAuthUI() {
  const user = getCurrentUser();
  const authLink = document.getElementById('auth-link');
  
  if (authLink) {
    if (user && user.role === 'gestionnaire') {
      authLink.textContent = 'Espace gestionnaire';
      authLink.setAttribute('href', '/dashboard');
    } else {
      authLink.textContent = 'Se connecter';
      authLink.setAttribute('href', '/login');
    }
  }
}

// Initialiser lors du chargement de la page
document.addEventListener('DOMContentLoaded', () => {
  setupLogout();
  updateAuthUI();
});