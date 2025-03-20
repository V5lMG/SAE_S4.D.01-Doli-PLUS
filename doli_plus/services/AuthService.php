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

                // Vérifier si un message contenant le nom d'utilisateur est présent
                if (isset($responseData['success']['message'])) {
                    // Utilisation d'une expression régulière pour extraire le nom d'utilisateur
                    if (preg_match('/Welcome (\S+) -/', $responseData['success']['message'], $matches)) {
                        $_SESSION['user_name'] = $matches[1];
                    }
                }

                return true;
            }
        }

        return false;  // Retourner false si l'authentification a échoué
    }

    /**
     * Vérifie si l'utilisateur possède bien un token
     */
    public static function checkAuthentication() {
        // Démarre la session si ce n'est pas déjà fait
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifie si le token d'authentification existe dans la session
        if (!isset($_SESSION['api_token'])) {
            header('Location: /doli_plus/index.php?controller=Home&action=index');
            exit();
        }

        // var_dump($_SESSION['api_token']);
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
}
