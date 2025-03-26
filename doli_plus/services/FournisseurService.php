<?php
namespace services;

class FournisseurService
{
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

        // Récupérer l'URL
        $fourniseurUrl = $_SESSION['url_saisie'] . "/thirdparties";

        // Initialiser cURL
        $requeteCurl = curl_init($fourniseurUrl);
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

            $fourniseurFormatees = [];
            $nb_note = 0;

            // Formater la réponse pour extraire les informations pertinentes
            foreach ($data as $fourniseur) {

                $nb_note += 1;
                $ref =  $fourniseur['ref'] ?? 'Inconnu';
                // Ajouter les informations formatées dans le tableau final pour la note de frais
                $fourniseurFormatees[] = [
                    'ref' => $ref,
                    'nom' => $fourniseur['name'] ?? 'Inconnu',
                    'numTel' => $fourniseur['phone'] ?? 'Inconnu',
                    'adresse' => !empty($fourniseur['address']) ? $fourniseur['address'] : 'Inconnu',
                    'codePostal' => $fourniseur['zip'] ?? 'Inconnu',
                ];
            }


            // Retourner le tableau des notes de frais formatées
            return $fourniseurFormatees;
        }

        return []; // Retourner un tableau vide en cas d'échec
    }

    /**
     * @param array       $fournisseurs liste des fournisseurs.
     * @param string|null $nom nom du fournisseur.
     * @param string|null $numTel numéro de téléphone du fournisseur.
     * @param string|null $adresse adresse du fournisseur.
     * @param string|null $codePostal code postal du fournisseur.
     * @return array|string[]
     */
    public function filtrerValeurs(array $fournisseurs, ?string $nom = null, ?string $numTel = null, ?string $adresse = null, ?string $codePostal = null): array
    {
        // Démarrage de la session si nécessaire
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['api_token'])) {
            return [];
        }

        // Enregistrer les filtres dans la session
        $_SESSION['filters'] = [
            'nom' => $_POST['nom'] ?? "",
            'numTel' => $_POST['numTel'] ?? "",
            'adresse' => $_POST['adresse'] ?? "",
            'codePostal' => $_POST['codePostal'] ?? "",
        ];

        // Vérification si tous les filtres ne sont pas définis
        if (
            (!isset($nom) || $nom == '') &&
            (!isset($adresse) || $adresse == '') &&
            (!isset($codePostal) || $codePostal == '') &&
            (!isset($numTel) || $numTel == '')
        ) {
            return [];
        }

        $fournisseursFiltres = [];

        foreach ($fournisseurs as $fournisseur) {
            // Vérification du nom
            if (isset($nom) && $nom !== '' && stripos($fournisseur['nom'], $nom) === false) {
                continue;
            }

            // Vérification du numéro de téléphone (ne pas filtrer si "Inconnu" ou vide, mais filtrer si on cherche des numéros avec "0")
            if (isset($numTel) && $numTel !== '' && $numTel !== 'Inconnu' && stripos($fournisseur['numTel'], $numTel) === false) {
                continue;
            }

            // Vérification de l'adresse (ne pas filtrer si "Inconnu")
            if (isset($adresse) && $adresse !== '' && $adresse !== 'Inconnu' && stripos($fournisseur['adresse'], $adresse) === false) {
                continue;
            }

            // Vérification du code postal (ne pas filtrer si "Inconnu")
            if (isset($codePostal) && $codePostal !== '' && $codePostal !== 'Inconnu' && stripos($fournisseur['codePostal'], $codePostal) === false) {
                continue;
            }

            // Ajout du fournisseur si tous les filtres sont valides
            $fournisseursFiltres[] = $fournisseur;
        }

        return ['fournisseurs' => $fournisseursFiltres];
    }

    /**
     * Récupère les factures du fournisseur cherché.
     *
     * @return array Un tableau contenant toutes les factures du fournisseur.
     */
    public function factureFournisseur($ref): array
    {
        // Démarrage de la session si nécessaire
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['api_token'])) {
            return [];
        }

        // Récupérer l'URL
        $apiUrlFacture = $_SESSION['url_saisie'] . "/supplierinvoices";

        // Initialiser cURL
        $requeteCurl = curl_init($apiUrlFacture);
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

        if ($httpCode === 200) {
            $data = json_decode($response, true) ?? [];

            // Formater les données
            $factures = [
                'factures' => [],
                'refSupplier' => ''
            ];

            if (!empty($data)) {

                $factures['refSupplier'] = $data[0]['ref_supplier'] ?? 'Inconnu';

                // Filtrer les factures qui correspondent à ref_supplier
                foreach ($data as $facture) {
                    if ($facture['socid'] === $ref) {

                        $status = match($facture['status']) {
                            '0' => 'Brouillon',
                            '1' => 'Impayées',
                            '2' => 'Payé',
                            default => 'Inconnu'
                        };

                        $condReglement = match($facture['cond_reglement_code']) {
                            'RECEP' => 'A réception',
                            '30D' => '30 jours',
                            '30DENDMONTH' => '30 jours en fin de mois',
                            '60D' => '60 jours',
                            '60DENDMONTH' => '60 jours en fin de mois',
                            'PT_ORDER' => 'A commande',
                            'PT_DELIVERY' => 'A livraison',
                            'PT_5050' => '50/50',
                            '10D' => '10 jours',
                            '10DENDMONTH' => '10 jours en fin de mois',
                            '14D' => '14 jours',
                            '14DENDMONTH' => '14 jours en fin de mois',
                            default => 'Inconnu'
                        };

                        $modeReglement = match($facture['mode_reglement_code']) {
                            'CB' => 'Carte bancaire',
                            'CHQ' => 'Chèque',
                            'LIQ' => 'Espèce',
                            'PRE' => 'Ordre de prélèvement',
                            'VIR' => 'Virement bancaire',
                            default => 'Inconnu'
                        };

                        $factures['factures'][] = [
                            'ref' => $facture['ref'] ?? 'Inconnue',
                            'date_facture' => date("d/m/Y", $facture['date']) ?? 'Inconnue',
                            'date_echeance' => date("d/m/Y", $facture['date_echeance']) ?? 'Inconnue',
                            'cond_reglement' => $condReglement,
                            'mode_reglement' => $modeReglement,
                            'montant_ht' => number_format($facture['total_ht'], 2, ',', ' ') . ' €' ?? 'Inconnu',
                            'etat' => $status
                        ];
                    }
                }
            }

            return $factures;
        }
        return []; // Retourner un tableau vide en cas d'échec
    }
}