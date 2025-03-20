<?php
namespace services;

class AuthService
{
    private string $apiUrl = "http://dolibarr.iut-rodez.fr/G2024-43-SAE/htdocs/api/index.php/login";  // URL d'authentification

    /**
     * Authentifie l'utilisateur et récupère le token
     */
    public function authentification(string $username, string $password): bool
    {
        // Démarrer la session si elle n'est pas encore démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Construire l'URL de l'API Dolibarr pour l'authentification avec les identifiants
        $url = $this->apiUrl . "?login=" . urlencode($username) . "&password=" . urlencode($password);

        // Initialiser cURL pour l'authentification
        $requeteCurl = curl_init($url);
        curl_setopt($requeteCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($requeteCurl, CURLOPT_HTTPGET, true);          // Utiliser la méthode GET

        // Exécuter la requête et récupérer la réponse
        $response = curl_exec($requeteCurl);
        $httpCode = curl_getinfo($requeteCurl, CURLINFO_HTTP_CODE);
        curl_close($requeteCurl);

        // Vérifier si la connexion est réussie (code HTTP 200)
        if ($httpCode === 200) {
            $responseData = json_decode($response, true);

            // Vérifier si un token est renvoyé
            if (isset($responseData['success']['token'])) {
                // Stocker le token dans la session pour des appels futurs
                $_SESSION['api_token'] = $responseData['success']['token'];
                return true;
            }
        }

        return false;  // Retourner false si l'authentification a échoué
    }

    /**
     * Déconnecte l'utilisateur en supprimant son token de session
     */
    public function deconnexion(): void
    {
        // Démarrer la session si elle n'est pas encore démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Supprimer le token de session et le nom d'utilisateur
        unset($_SESSION['api_token']);
        unset($_SESSION['user_name']);
    }

    /**
     * Renvoie le nom et prénom de l'utilisateur connecté
     */
    public function renvoieUser(): ?string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifier si un token est stocké
        if (!isset($_SESSION['api_token'])) {
            return null;
        }

        // Initialisation de cURL pour récupérer les informations utilisateur
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'DOLAPIKEY: ' . $_SESSION['api_token'] // Envoi du token dans l'en-tête
        ]);

        // Exécuter la requête et récupérer la réponse
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Vérifier si la requête a réussi
        if ($httpCode === 200) {
            $userData = json_decode($response, true);

            // Vérifier si les informations de l'utilisateur sont présentes
            if (!empty($userData) && isset($userData[0]['firstname'], $userData[0]['lastname'])) {
                return $userData[0]['firstname'] . ' ' . $userData[0]['lastname'];
            }
        }

        return null; // Retourner null en cas d'erreur
    }
}
