<?php
namespace services;

class AuthService
{

    /**
     * Authentifie l'utilisateur et récupère le token
     */
    public function authentification(string $username, string $password, string $url): bool
    {
        // Démarrer la session si elle n'est pas encore démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // enregistrer l'url dans le fichier de config
        self::setUrlFichier($url);

        // Construire l'URL de l'API Dolibarr pour l'authentification avec les identifiants
        $urlContruite = $url . "/login?login=" . urlencode($username) . "&password=" . urlencode($password);

        // Initialiser cURL pour l'authentification
        $requeteCurl = curl_init($urlContruite);
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


    // http://dolibarr.iut-rodez.fr/G2024-43-SAE/htdocs/api/index.php
    /**
     * Enregistre l'URL dans le fichier url.conf, ou la place en haut si elle y est déjà.
     * @param string $url L'URL à enregistrer
     * @return void
     */
    public static function setUrlFichier(string $url): void
    {
        // Définir le chemin du fichier
        $filePath = 'static/config/url.conf';

        // Lire le contenu du fichier (si le fichier n'existe pas, on crée un tableau vide)
        $lines = file_exists($filePath) ? file($filePath, FILE_IGNORE_NEW_LINES) : [];

        // Vérifier si l'URL existe déjà dans le fichier
        if (($key = array_search($url, $lines)) !== false) {
            // Si l'URL existe déjà, on la supprime de sa position actuelle
            unset($lines[$key]);
        }

        // Ajouter l'URL en haut du tableau
        array_unshift($lines, $url);

        // Écrire de nouveau tout le contenu dans le fichier
        file_put_contents($filePath, implode(PHP_EOL, $lines) . PHP_EOL);
    }

    /**
     * Récupère toutes les URLs du fichier url.conf
     * @return string Retourne toutes les URLs sous forme de chaîne de caractères, séparées par des retours à la ligne
     */
    public static function getUrlFichier(): string
    {
        // Définir le chemin du fichier
        $filePath = 'static/config/url.conf';

        // Vérifier si le fichier existe
        if (!file_exists($filePath)) {
            return '';
        }

        // Lire le contenu du fichier et ignorer les nouvelles lignes
        $lines = file($filePath, FILE_IGNORE_NEW_LINES);

        // Retourner toutes les URLs sous forme de chaîne, séparées par des retours à la ligne
        return implode(PHP_EOL, $lines);
    }

    /**
     * Vérifie si l'utilisateur possède bien un token
     */
    public static function checkAuthentication(): void
    {
        // Démarre la session si ce n'est pas déjà fait
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifie si le token d'authentification existe dans la session
        if (!isset($_SESSION['api_token'])) {
            header('Location: /doli_plus/index.php?controller=Home&action=index');
            exit();
        }
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
