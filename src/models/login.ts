import { getData } from "../services/forms/getData.js";
import { validateLogin } from "../services/forms/authValidator.js";

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("loginForm");

  if (form) {
    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      
      // Récupérer les données du formulaire
      const formData = getData("loginForm");
      
      // Valider les données
      const errors = validateLogin({
        email: formData.email as string | null,
        password: formData.password as string | null
      });

      // Afficher les erreurs de validation
      displayValidationErrors(errors);

      // Si pas d'erreurs, procéder à la connexion
      if (Object.keys(errors).length === 0) {
        try {
          // Afficher le spinner de chargement
          toggleLoading(true);

          // Vérifier les identifiants
          const isAuthenticated = await authenticateUser(
            formData.email as string,
            formData.password as string
          );

          if (isAuthenticated) {
            // Redirection après connexion réussie
            window.location.href = "/dashboard";
          } else {
            // Afficher une erreur globale si l'authentification échoue
            showGlobalError("Identifiants incorrects. Veuillez réessayer.");
          }
        } catch (error) {
          showGlobalError("Une erreur est survenue lors de la connexion.");
        } finally {
          // Masquer le spinner de chargement
          toggleLoading(false);
        }
      }
    });

    // Gestionnaire pour le bouton "afficher/masquer" le mot de passe
    const passwordIcon = document.getElementById("password-icon");
    const passwordInput = document.getElementById("password") as HTMLInputElement;
    
    if (passwordIcon && passwordInput) {
      passwordIcon.addEventListener("click", () => {
        if (passwordInput.type === "password") {
          passwordInput.type = "text";
          passwordIcon.classList.replace("fa-eye", "fa-eye-slash");
        } else {
          passwordInput.type = "password";
          passwordIcon.classList.replace("fa-eye-slash", "fa-eye");
        }
      });
    }
  }
});

/**
 * Affiche les erreurs de validation dans le formulaire
 */
function displayValidationErrors(errors: Record<string, string>) {
  // Réinitialiser les erreurs précédentes
  document.querySelectorAll("[id$='-error']").forEach(el => {
    (el as HTMLElement).classList.add("hidden");
  });

  // Afficher les nouvelles erreurs
  for (const [field, message] of Object.entries(errors)) {
    const errorElement = document.getElementById(`${field}-error`);
    if (errorElement) {
      errorElement.textContent = message;
      errorElement.classList.remove("hidden");
    }
  }
}

/**
 * Affiche une erreur globale dans le formulaire
 */
function showGlobalError(message: string) {
  const errorElement = document.getElementById("global-error");
  const errorMessage = document.getElementById("error-message");
  
  if (errorElement && errorMessage) {
    errorMessage.textContent = message;
    errorElement.classList.remove("hidden");
  }
}

/**
 * Active/désactive l'état de chargement du formulaire
 */
function toggleLoading(isLoading: boolean) {
  const loginText = document.getElementById("login-text");
  const loginSpinner = document.getElementById("login-spinner");
  const submitButton = document.querySelector("#loginForm button[type='submit']");

  if (loginText && loginSpinner && submitButton) {
    if (isLoading) {
      loginText.textContent = "Connexion en cours...";
      loginSpinner.classList.remove("hidden");
      (submitButton as HTMLButtonElement).disabled = true;
    } else {
      loginText.textContent = "Se connecter";
      loginSpinner.classList.add("hidden");
      (submitButton as HTMLButtonElement).disabled = false;
    }
  }
}

/**
 * Simule l'authentification en vérifiant les identifiants
 */
async function authenticateUser(email: string, password: string): Promise<boolean> {
  // Dans une application réelle, vous feriez une requête à votre API ici
  // Pour cet exemple, nous allons simuler une vérification avec les données de db.json
  
  // Simulation d'un délai réseau
  await new Promise(resolve => setTimeout(resolve, 1000));
  
  // Vérification des identifiants (simplifiée pour l'exemple)
  const user = {
    email: "john.doe@example.com",
    password: "john1234"
  };

  return email === user.email && password === user.password;
}