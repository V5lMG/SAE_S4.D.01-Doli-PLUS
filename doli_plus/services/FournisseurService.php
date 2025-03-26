<?php
namespace services;

class FournisseurService
{
    private string $apiUrlFournisseur = "http://dolibarr.iut-rodez.fr/G2024-43-SAE/htdocs/api/index.php/thirdparties";
    private string $apiUrlFacture = "http://dolibarr.iut-rodez.fr/G2024-43-SAE/htdocs/api/index.php/supplierinvoices";

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
        $requeteCurl = curl_init($this->apiUrlFournisseur);
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

                // Ajouter les informations formatées dans le tableau final pour le fournisseur de frais
                $fournisseurFormatees[] = [
                    'nom'           => $fournisseur['name']             ?? 'Inconnu',
                    'numTel'        => $fournisseur['phone']            ?? 'Inconnu',
                    'adresse'       => $fournisseur['address']          ?? 'Inconnu',
                    'codePostal'    => $fournisseur['zip']              ?? 'Inconnu',
                ];
            }

            // Retourner le tableau des notes de frais formatées
            return $fournisseurFormatees;
        }
        return []; // Retourner un tableau vide en cas d'échec
    }

    /**
     * Récupère tous les fournisseurs.
     *
     * @return array Un tableau contenant tous les fournisseurs.
     */
    public function recupererListeCompletePalmares($date_debut = null, $date_fin = null): array
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
        $requeteCurl = curl_init($this->apiUrlFacture);
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

            $palmaresFormatees = [];

            // Convertir les dates de début et de fin en timestamps pour la comparaison
            $dateDebutTimestamp = $date_debut ? strtotime($date_debut) : null;
            $dateFinTimestamp = $date_fin ? strtotime($date_fin) : null;

            // Formater la réponse pour extraire les informations pertinentes
            foreach ($data as $facture) {
                $nomFournisseur = $facture['socnom'];              // Nom du fournisseur
                $totalHT        = floatval($facture['total_ht']);  // Montant total HT
                $timestamp      = $facture['date'];               // Timestamp de la facture
                // Mettre +1 à chaque date car avec le timestamp, il y a un décalage d'un jour
                $dateFacture    = date("d/m/Y", strtotime('+1 day', $timestamp)); // Conversion du timestamp en date

                // Corriger le timestamp en ajoutant 1 jour
                $timestampCorrige = strtotime('+1 day', $timestamp);

                // Filtre par date (comparaison entre timestamps)
                if (($dateDebutTimestamp && $timestampCorrige < $dateDebutTimestamp) ||
                    ($dateFinTimestamp && $timestampCorrige > $dateFinTimestamp)) {
                    continue;
                }

                // Formater la date pour l'affichage
                $dateFacture = date("d/m/Y", $timestampCorrige);

                if (!isset($palmaresFormatees[$nomFournisseur])) {
                    $palmaresFormatees[$nomFournisseur] = [
                        'nombre_factures' => 0,
                        'total_ht' => 0,
                        'dates' => []  // Stocker les dates des factures
                    ];
                }

                // Incrémenter le nombre de factures et le total HT
                foreach ($facture['lines'] as $line) {
                    $palmaresFormatees[$nomFournisseur]['nombre_factures']++;
                }
                $palmaresFormatees[$nomFournisseur]['total_ht'] += $totalHT;
                $palmaresFormatees[$nomFournisseur]['dates'][] = $dateFacture; // Ajouter la date formatée
            }

            // Trier le tableau par montant HT décroissant
            uasort($palmaresFormatees, function ($a, $b) {
                return $b['total_ht'] <=> $a['total_ht'];
            });


            // Retourner le tableau des notes de frais formatées
            return $palmaresFormatees;
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
            if (!empty($numTel) && stripos($fournisseur['nomTel'], $numTel) === false) {
                continue;
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
