import { UserRoles, AuthErrorMessages } from '../enums/authEnums.js';

/**
 * Interface pour représenter un utilisateur dans la base de données
 */
export interface User {
  id: number;
  nom: string;
  prenom: string;
  tel: string;
  adresse: string;
  email: string;
  password: string;
  role: string;
  created_at: string;
  updated_at: string;
}

/**
 * Interface pour la structure de la base de données
 */
export interface Database {
  personnes: User[];
  cargaisons: any[];
  colis: any[];
}

/**
 * Résultat de l'authentification
 */
export interface AuthResult {
  success: boolean;
  user?: User;
  message: string;
}

/**
 * Classe pour gérer les opérations de base de données
 * Cette classe simule une base de données en utilisant le fichier db.json
 */
export class DatabaseService {
  private static instance: DatabaseService;
  private database: Database | null = null;

  /**
   * Constructeur privé pour implémenter le pattern Singleton
   */
  private constructor() {}

  /**
   * Obtient l'instance unique de DatabaseService
   * @returns L'instance de DatabaseService
   */
  public static getInstance(): DatabaseService {
    if (!DatabaseService.instance) {
      DatabaseService.instance = new DatabaseService();
    }
    return DatabaseService.instance;
  }

  /**
   * Charge les données de la base de données depuis db.json
   * Dans un environnement réel, ceci serait une requête à une API
   */
 public async loadDatabase(): Promise<void> {
  try {
    const response = await fetch('/db.json');
    if (!response.ok) {
      throw new Error('Impossible de charger la base de données');
    }
    this.database = await response.json();
  } catch (error) {
    console.error('Erreur lors du chargement de la base de données:', error);
    throw error; // Propagez l'erreur pour la gérer ailleurs.
  }
}
  /**
   * Authentifie un utilisateur avec son email et mot de passe
   * @param email L'email de l'utilisateur
   * @param password Le mot de passe de l'utilisateur
   * @returns Le résultat de l'authentification
   */
  public async authenticateUser(email: string, password: string): Promise<AuthResult> {
    // S'assurer que la base de données est chargée
    if (!this.database) {
      await this.loadDatabase();
    }

    // Simulation d'un délai réseau
    await this.simulateNetworkDelay();

    // Rechercher l'utilisateur par email
    const user = this.database!.personnes.find(
      person => person.email.toLowerCase() === email.toLowerCase()
    );

    if (!user) {
      return {
        success: false,
        message: AuthErrorMessages.UTILISATEUR_NON_TROUVE
      };
    }

    // Vérifier le mot de passe
    // ATTENTION: Dans un vrai projet, les mots de passe seraient hachés !
    if (user.password !== password) {
      return {
        success: false,
        message: AuthErrorMessages.LOGIN_INVALIDE
      };
    }

    // Authentification réussie
    return {
      success: true,
      user: user,
      message: "Connexion réussie"
    };
  }

  /**
   * Obtient un utilisateur par son ID
   * @param userId L'ID de l'utilisateur
   * @returns L'utilisateur ou null si non trouvé
   */
  public async getUserById(userId: number): Promise<User | null> {
    if (!this.database) {
      await this.loadDatabase();
    }

    const user = this.database!.personnes.find(person => person.id === userId);
    return user || null;
  }

  /**
   * Obtient un utilisateur par son email
   * @param email L'email de l'utilisateur
   * @returns L'utilisateur ou null si non trouvé
   */
  public async getUserByEmail(email: string): Promise<User | null> {
    if (!this.database) {
      await this.loadDatabase();
    }

    const user = this.database!.personnes.find(
      person => person.email.toLowerCase() === email.toLowerCase()
    );
    return user || null;
  }

  /**
   * Vérifie si un utilisateur a un rôle spécifique
   * @param user L'utilisateur à vérifier
   * @param role Le rôle à vérifier
   * @returns true si l'utilisateur a le rôle, false sinon
   */
  public hasRole(user: User, role: UserRoles): boolean {
    return user.role === role;
  }

  /**
   * Simule un délai réseau pour rendre l'expérience plus réaliste
   * @param minDelay Délai minimum en millisecondes
   * @param maxDelay Délai maximum en millisecondes
   */
  private async simulateNetworkDelay(minDelay: number = 800, maxDelay: number = 1500): Promise<void> {
    const delay = Math.random() * (maxDelay - minDelay) + minDelay;
    return new Promise(resolve => setTimeout(resolve, delay));
  }

  /**
   * Obtient tous les utilisateurs (pour l'administration)
   * @returns La liste de tous les utilisateurs
   */
  public async getAllUsers(): Promise<User[]> {
    if (!this.database) {
      await this.loadDatabase();
    }
    return this.database!.personnes;
  }

  /**
   * Réinitialise la base de données (utile pour les tests)
   */
  public resetDatabase(): void {
    this.database = null;
  }

  public getDatabase(): Database {
    if (!this.database) {
        throw new Error('Database not loaded');
    }
    return this.database;
}

public async getCargaisons(status?: string): Promise<any[]> {
    if (!this.database) {
        await this.loadDatabase();
    }
    
    let cargasons = this.database!.cargaisons;
    if (status) {
        cargasons = cargasons.filter(c => c.etat === status);
    }
    return cargasons;
}

public async getColis(filters?: any): Promise<any[]> {
    if (!this.database) {
        await this.loadDatabase();
    }
    
    let colis = this.database!.colis;
    if (filters) {
        if (filters.code) {
            colis = colis.filter(p => p.numero_colis.includes(filters.code));
        }
        // Ajouter d'autres filtres si nécessaire
    }
    return colis;
}
}