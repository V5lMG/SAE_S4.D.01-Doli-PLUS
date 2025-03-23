<?php
namespace services;

class AuthService
{

    /**
     * Authentifie l'utilisateur et récupère le token.
     * Cette méthode envoie une requête à l'API Dolibarr avec les informations d'identification
     * et récupère un token d'authentification en cas de succès.
     *
     * @param string $username Le nom d'utilisateur.
     * @param string $password Le mot de passe.
     * @param string $url L'URL de l'API Dolibarr.
     *
     * @return bool Retourne `true` si l'authentification a réussi, sinon `false`.
     */
    public function authentification(string $username, string $password, string $url): bool
    {
        // Démarrer la session si elle n'est pas encore démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Construire l'URL de l'API Dolibarr pour l'authentification avec les identifiants
        $urlContruite = $url . "/login?login=" . urlencode($username) . "&password=" . urlencode($password);

        // Initialiser cURL pour l'authentification
        $requeteCurl = curl_init($urlContruite);
        curl_setopt($requeteCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($requeteCurl, CURLOPT_HTTPGET, true); // Utiliser la méthode GET

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

        // Retourner false si l'authentification a échoué
        return false;
    }

    /**
     * Enregistre l'URL dans le fichier `url.conf`, ou la place en haut si elle y est déjà.
     * Cette méthode permet d'ajouter une URL dans un fichier de configuration, et de la
     * positionner en haut si elle existe déjà dans le fichier.
     *
     * @param string $url L'URL à enregistrer.
     * @return void
     */
    public static function setUrlFichier(string $url): void
    {
        // Définir le chemin du fichier
        $filePath = 'static/config/url.conf';

        // Lire le contenu du fichier (si le fichier n'existe pas, on crée un tableau vide)
        $lines = file_exists($filePath) ? file($filePath) : [];

        // Vérifier si l'URL existe déjà dans le fichier
        if (($key = array_search($url, $lines)) !== false) {
            unset($lines[$key]);
        }

        // Ajouter l'URL en haut du tableau
        array_unshift($lines, $url);

        // Écrire de nouveau tout le contenu dans le fichier (EOL = end of line)
        file_put_contents($filePath, implode(PHP_EOL, $lines) . PHP_EOL);
    }

    /**
     * Récupère toutes les URLs du fichier `url.conf`.
     * Cette méthode lit les URLs stockées dans le fichier de configuration et les retourne sous forme de tableau.
     *
     * @return array Retourne un tableau contenant toutes les URLs.
     */
    public static function getUrlFichier(): array
    {
        // Définir le chemin du fichier
        $filePath = 'static/config/url.conf';

        // Vérifier si le fichier existe
        if (!file_exists($filePath)) {
            return []; // Retourne un tableau vide si le fichier n'existe pas
        }

        // Lire le contenu du fichier ligne par ligne
        return file($filePath, FILE_SKIP_EMPTY_LINES);
    }

    /**
     * Vérifie si l'utilisateur possède bien un token.
     * Cette méthode vérifie que le token d'authentification est présent dans la session,
     * sinon elle redirige l'utilisateur vers la page d'accueil.
     *
     * @return void
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
     * Déconnecte l'utilisateur en supprimant son token de session.
     * Cette méthode efface le token et le nom d'utilisateur de la session afin de déconnecter l'utilisateur.
     *
     * @return void
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
     * Enregistre l'URL saisie dans la session.
     * Cette méthode permet de stocker une URL dans la session pour une utilisation future.
     *
     * @param string $url L'URL à enregistrer.
     * @return void
     */
    public function urlSession(string $url) : void
    {
        // Démarrer la session si elle n'est pas encore démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['url_saisie'] = $url;
    }
}
