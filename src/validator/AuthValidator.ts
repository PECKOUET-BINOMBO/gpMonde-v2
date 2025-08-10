/**
 * Valide un email selon le format standard
 * @param email L'email à valider
 * @returns true si l'email est valide, false sinon
 */
export function validateEmail(email: string): boolean {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

