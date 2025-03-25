<?php
namespace services;

class FournisseurService
{
    private string $apiUrl = "http://dolibarr.iut-rodez.fr/G2024-43-SAE/htdocs/api/index.php/thirdparties";

    /**
     * Récupère tous les fournisseurs.
     *
     * @return array Un tableau contenant tous les fournisseurs.
     */
    public function recupererListeComplete(): array
    {
        // Démarrer la session si elle n'est pas encore démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifier si un token API est disponible
        if (!isset($_SESSION['api_token'])) {
            return [];
        }

        // Initialiser cURL
        $requeteCurl = curl_init($this->apiUrl);
        curl_setopt($requeteCurl, CURLOPT_VERBOSE, true);
        curl_setopt($requeteCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($requeteCurl, CURLOPT_HTTPGET, true);
        curl_setopt($requeteCurl, CURLOPT_HTTPHEADER, [
            'DOLAPIKEY: ' . $_SESSION['api_token'],
            'Accept: application/json'
        ]);

        // Exécuter la requête
        $response = curl_exec($requeteCurl);
        $httpCode = curl_getinfo($requeteCurl, CURLINFO_HTTP_CODE);
        curl_close($requeteCurl);

        // Vérifier si la requête a réussi (HTTP 200)
        if ($httpCode === 200) {
            $data = json_decode($response, true) ?? [];

            $fournisseurFormatees = [];

            // Formater la réponse pour extraire les informations pertinentes
            foreach ($data as $fournisseur) {

                // Ajouter les informations formatées dans le tableau final pour la fournisseur de frais
                $fournisseurFormatees[] = [
                    'nom' => $fournisseur['name'] ?? 'Inconnu',
                    'numTel' => $fournisseur['phone'] ?? 'Inconnu',
                    'adresse' => $fournisseur['address'] ?? 'Inconnu',
                    'codePostal' => $fournisseur['zip'] ?? 'Inconnu',
                ];
            }

            // Retourner le tableau des notes de frais formatées
            return $fournisseurFormatees;
        }
        return []; // Retourner un tableau vide en cas d'échec
    }

    /**
     * @param array $fournisseurs liste des fournisseurs.
     * @param string|null $nom nom du fournisseur.
     * @param string|null $numTel numéro de téléphone du fournisseur.
     * @param string|null $adresse adresse du fournisseur.
     * @param string|null $codePostal code postal du fournisseur.
     * @return array|string[]
     */
    public function filtrerValeurs(array $fournisseurs, ?string $nom = null, ?string $numTel = null, ?string $adresse = null, ?string $codePostal = null): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['api_token'])) {
            return [];
        }

        if (empty($nom) && empty($numTel) && empty($adresse) && empty($codPostal)) {
            return ['message' => 'Sélectionnez au moins un filtre'];
        }

        $fournisseursFiltrees = [];

        foreach ($fournisseurs as $fournisseur) {
            // Filtre par nom
            if (!empty($nom) && stripos($fournisseur['nom'], $nom) === false) {
                continue;
            }

            // Filtre par numéro de téléphone
            if (!empty($numTel)) {
                if (preg_match('/^[0-9]$/', $numTel) && strpos($fournisseur['numTel'], $numTel) === false) {
                    continue;
                } elseif (!preg_match('/^[0-9]$/', $numTel) && stripos($fournisseur['numTel'], $numTel) === false) {
                    continue;
                }
            }

            // Filtre par adresse
            if (!empty($adresse) && stripos($fournisseur['adresse'], $adresse) === false) {
                continue;
            }

            // Filtre par code postal
            if (!empty($codePostal) && stripos($fournisseur['codePostal'], $codePostal) === false) {
                continue;
            }



            // Ajout de la note filtrée
            $fournisseursFiltrees[] = $fournisseur;

        }

        return $fournisseursFiltrees;
    }

}
