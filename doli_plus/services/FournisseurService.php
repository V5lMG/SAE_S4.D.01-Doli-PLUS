<?php
namespace services;

use RuntimeException;

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

        // On vérifie si "curl_init" a bien réussi avant d'utiliser "$requeteCurl"
        if ($requeteCurl === false) {
            throw new RuntimeException('Échec de l’initialisation de cURL');
        }

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

            // Sécurité (PHPStan) : évite une erreur si $data n'est pas un tableau
            if (is_array($data)) {
                // Formater la réponse pour extraire les informations pertinentes
                foreach ($data as $fourniseur) {
                    // Sécurité (PHPStan) : évite une erreur si $fournisseur n'est pas un tableau
                    if (is_array($fourniseur)) {
                        $ref = $fourniseur['ref'] ?? 'Inconnu';
                        // Ajouter les informations formatées dans le tableau final pour la note de frais
                        $fourniseurFormatees[] = [
                            'ref' => $ref,
                            'nom' => $fourniseur['name'] ?? 'Inconnu',
                            'numTel' => $fourniseur['phone'] ?? 'Inconnu',
                            'adresse' => !empty($fourniseur['address']) ? $fourniseur['address'] : 'Inconnu',
                            'codePostal' => $fourniseur['zip'] ?? 'Inconnu',
                        ];
                    }
                }
            }

            // Retourner le tableau des notes de frais formatées
            return $fourniseurFormatees;
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

        // Récupérer l'URL
        $urlFacture = $_SESSION['url_saisie'] . "/supplierinvoices";

        // Initialiser cURL
        $requeteCurl = curl_init($urlFacture);

        // On vérifie si "curl_init" a bien réussi avant d'utiliser "$requeteCurl".
        if ($requeteCurl === false) {
            throw new RuntimeException('Échec de l’initialisation de cURL');
        }

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

            // Conditions "is_array" afin de régler les erreurs PHPStan
            if (is_array($data)) {
                // Formater la réponse pour extraire les informations pertinentes
                foreach ($data as $facture) {
                    // Sécurité (PHPStan) : évite une erreur si $facture n'est pas un tableau
                    if (is_array($facture)) {
                        $nomFournisseur = $facture['socnom'];       // Nom du fournisseur
                        $totalHT = floatval($facture['total_ht']);  // Montant total HT
                        $timestamp = $facture['date'];              // Timestamp de la facture

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
                        foreach ($facture['lines'] as $ignored) {
                            $palmaresFormatees[$nomFournisseur]['nombre_factures']++;
                        }
                        $palmaresFormatees[$nomFournisseur]['total_ht'] += $totalHT;
                        $palmaresFormatees[$nomFournisseur]['dates'][] = $dateFacture; // Ajouter la date formatée
                    }
                }
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

        // On vérifie si "curl_init" a bien réussi avant d'utiliser "$requeteCurl".
        if ($requeteCurl === false) {
            throw new RuntimeException('Échec de l’initialisation de cURL');
        }

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

            if (!is_array($data)) {
                $data = []; // Sécurité (PHPStan) : évite une erreur si $data n'est pas un tableau
            }

            // Formater les données
            $factures = [
                'factures' => [],
                'refSupplier' => ''
            ];

            if (!empty($data)) {
                $factures['refSupplier'] = $data[0]['ref_supplier'] ?? 'Inconnu';

                foreach ($data as $facture) {
                    if (!is_array($facture)) {
                        continue; // Sécurité (PHPStan) : évite une erreur si $facture n'est pas un tableau
                    }
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

                        $lignes = [];
                        foreach ($facture['lines'] as $ligne) {
                            // Sécurité PHPStan : évité une erreur si "$ligne" n'est pas un tableau
                            if (!is_array($ligne)) {
                                continue;
                            }
                            $lignes[] = [
                                'description' => isset($ligne['description']) && trim($ligne['description']) !== '' ? $ligne['description'] : 'Aucune',
                                'ref' => isset($ligne['ref_supplier']) && trim($ligne['ref_supplier']) !== '' ? $ligne['ref_supplier'] : 'Inconnu',
                                'tva' => isset($ligne['tva_tx']) ? number_format((float) $ligne['tva_tx'], 2) : 'Inconnu',
                                'prix_unitaire_ht' => isset($ligne['pu_ht']) ? number_format((float) $ligne['pu_ht'], 2, ',', ' ') : 'Inconnu',
                                'prix_unitaire_ttc' => isset($ligne['pu_ttc']) ? number_format((float) $ligne['pu_ttc'], 2, ',', ' ') : 'Inconnu',
                                'quantite' => $ligne['qty'] ?? 0,
                                'reduction' => $ligne['remise_percent'] ?? 'Inconnu',
                                'total_ht' => isset($ligne['total_ht']) ? number_format((float) $ligne['total_ht'], 2, ',', ' ') : 'Inconnu',
                            ];
                        }

                        // Récupération des fichiers joints de la facture
                        $fichiersJoints = $this->recupererFichiersJoints($facture['id']);

                        $factures['factures'][] = [
                            'ref' => $facture['ref'] ?? 'Inconnue',
                            'date_facture' => isset($facture['date']) ? date("d/m/Y", (int) $facture['date']) : 'Inconnue',
                            'date_echeance' => isset($facture['date_echeance']) ? date("d/m/Y", (int) $facture['date_echeance']) : 'Inconnue',
                            'cond_reglement' => $condReglement,
                            'mode_reglement' => $modeReglement,
                            'montant_ht' => number_format($facture['total_ht'], 2, ',', ' ') . ' €',
                            'etat' => $status,
                            'fichiers_joints' => $fichiersJoints,
                            'lignes' => $lignes
                        ];
                    }
                }
            }
            return $factures;
        }

        return []; // retourne un tableau vide en cas d'erreur
    }

    /**
     * Récupère les fichiers joints d'une facture via l'API
     */
    public function recupererFichiersJoints(int $factureId): array
    {
        // Récupérer l'URL
        $url = $_SESSION['url_saisie'] . "/documents?modulepart=supplier_invoice&id=";
        $urlDocuments = $url . $factureId;

        $requeteCurl = curl_init($urlDocuments);

        // On vérifie si "curl_init" a bien réussi avant d'utiliser "$requeteCurl".
        if ($requeteCurl === false) {
            throw new RuntimeException('Échec de l’initialisation de cURL');
        }

        curl_setopt($requeteCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($requeteCurl, CURLOPT_HTTPGET, true);
        curl_setopt($requeteCurl, CURLOPT_HTTPHEADER, [
            'DOLAPIKEY: ' . $_SESSION['api_token'],
            "Accept: application/json"
        ]);

        // Exécuter la requête
        $response = curl_exec($requeteCurl);
        $httpCode = curl_getinfo($requeteCurl, CURLINFO_HTTP_CODE);
        curl_close($requeteCurl);

        if ($httpCode === 200) {
            $documents = json_decode($response, true);

            // Vérifier si la réponse est valide
            if (!is_array($documents)) {
                $documents = [];
            }

            $liensDocuments = [];
            foreach ($documents as $document) {
                if (!is_array($document) || !isset($document['fullname'])) {
                    continue; // Passer les entrées invalides
                }

                $partiesUrl = explode('/', $document['fullname']);
                $url = implode('/', array_slice($partiesUrl, 8, 4));

                $liensDocuments[] = [
                    'nom'  => $document['name'] ?? '',
                    'url'  => $url
                ];
            }
            return $liensDocuments;
        }
        return [];
    }
    /**
     * Redirige directement vers l'URL du fichier pour le téléchargement
     */
    public function telechargerFichierApi(string $fichierUrl): void
    {
        $urlComplet = "http://dolibarr.iut-rodez.fr/G2024-43-SAE/documents/fournisseur/facture/" . $fichierUrl;

        // Redirection directe vers l'URL du fichier
        header("Location: " . $urlComplet);
    }
}