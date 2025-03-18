<?php
namespace services;

class NoteFraisService
{
    private string $apiUrl = "http://dolibarr.iut-rodez.fr/G2024-43-SAE/htdocs/api/index.php/expensereports";

    /**
     * Récupère toutes les notes de frais pour la liste des notes de frais
     */
    public function recupererListeComplete(): array
    {
        session_start();
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
            return json_decode($response, true) ?? [];
        }

        // Formater la réponse
        // Colone : Rèf ; Utilisateur ; Type ; Date début ;

        return []; // Retourner un tableau vide en cas d'échec
    }

    /**
     * Récupère toutes les notes de frais pour les statistiques
     */
    public function recupererStat(): array
    {
        session_start();
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
            // Décoder la réponse JSON en tableau associatif
            $notesFrais = json_decode($response, true) ?? [];

            // Initialiser un tableau pour stocker les statistiques par type de note de frais
            $statistiques = [];

            // Parcourir toutes les notes de frais récupérées
            foreach ($notesFrais as $note) {
                foreach ($note['lines'] as $line) {
                    // Formater le type_fees_code pour l'affichage
                    $type = match($line['type_fees_code'] ?? '') {
                        'EX_KME' => 'Frais Kilométrique',
                        'TF_LUNCH' => 'Repas',
                        'TF_TRIP' => 'Transport',
                        default => 'Autre',
                    };
                    $montant = $line['total_ttc'] ?? 0;
                    // Vérifier si ce type de note de frais est déjà enregistré
                    if (!isset($statistiques[$type])) {
                        // Sinon, initialiser le type avec un montant total et un compteur à zéro
                        $statistiques[$type] = [
                            'MontantTotalType' => 0,
                            'Quantite' => 0
                        ];
                    }

                    // Ajouter le montant de la note de frais au total du type
                    $statistiques[$type]['MontantTotalType'] += $montant;
                    // Incrémenter le nombre de notes de frais de ce type
                    $statistiques[$type]['Quantite']++;
                }
            }

            // Retourner le tableau formaté contenant les données prêtes à être utilisées pour un diagramme
            return $statistiques;
        }

        return []; // Retourner un tableau vide en cas d'échec
    }
}
