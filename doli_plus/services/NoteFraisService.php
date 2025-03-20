<?php
namespace services;

class NoteFraisService
{
    private string $apiUrl = "http://dolibarr.iut-rodez.fr/G2024-43-SAE/htdocs/api/index.php/expensereports";

    private $moisNoms = [
        1 => 'Janvier',
        2 => 'Février',
        3 => 'Mars',
        4 => 'Avril',
        5 => 'Mai',
        6 => 'Juin',
        7 => 'Juillet',
        8 => 'Août',
        9 => 'Septembre',
        10 => 'Octobre',
        11 => 'Novembre',
        12 => 'Décembre'
    ];

    /**
     * Récupère toutes les notes de frais pour la liste des notes de frais
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

            $noteFraisFormatees = [];

            // Formater la réponse pour extraire les informations pertinentes
            foreach ($data as $note) {
                // Formater les dates
                $date_debut = date('d/m/Y', $note['date_debut']);
                $date_fin = date('d/m/Y', $note['date_fin']);

                $lignesTableau = [];

                // Formater chaque ligne
                foreach ($note['lines'] as $line) {
                    // Calculer les montants pour chaque ligne
                    $tva = $line['tva_tx'];
                    $value_unit = $line['value_unit'];
                    $value_unit_ttc = $line['value_unit'] / (1 + ($tva/100));
                    $montant_ht = $line['total_ht'] ?? 0.0;
                    $montant_ttc = $line['total_ttc'] ?? 0.0;

                    // Mettre à jour les totaux globaux
                    $total_ht_global += $montant_ht;
                    $total_tva_global += $montant_ttc - $montant_ht; // TVA = TTC - HT
                    $total_ttc_global += $montant_ttc;

                    // Formater le type_fees_code pour l'affichage
                    $type = match($line['type_fees_code'] ?? '') {
                        'EX_KME' => 'Frais kilométriques',
                        'TF_LUNCH' => 'Repas',
                        'TF_TRIP' => 'Transport',
                        default => 'Autre',
                    };


                    // Créer la ligne sous forme de tableau pour cette ligne spécifique
                    $ligneTableau = [
                        'date' => date("d/m/Y", strtotime($line['date'])),
                        'type' => $type,
                        'tva' => number_format($tva, 2, ',') . ' %',  // Formater la TVA
                        'prix_unitaire_ht' => number_format($value_unit, 2, ',', ' ') . ' €',  // Formater le prix unitaire HT
                        'prix_unitaire_ttc' => number_format($value_unit_ttc, 2, ',', ' ') . ' €',  // Formater le prix unitaire TTC
                        'quantite' => $line['qty'],
                        'montant_ht' => number_format($montant_ht, 2, ',', ' ') . ' €',  // Formater le montant HT
                        'montant_ttc' => number_format($montant_ttc, 2, ',', ' ') . ' €',  // Formater le montant TTC
                    ];

                    // Ajouter cette ligne formatée dans le tableau des lignes
                    $lignesTableau[] = $ligneTableau;
                }

                $status = match ($note['status']) {
                    '0' => 'Brouillon',
                    '2' => 'Validé',
                    '99' => 'Refusé',
                    '4' => 'Annulé',
                    '5' => 'Approuvé',
                    '6' => 'Payé',
                    default => 'Inconnu'
                };

                // Ajouter les totaux globaux
                $totaux = [
                    'montant_ht_total' => number_format($total_ht_global, 2, ',', ' ') . ' €',
                    'montant_tva_total' => number_format($total_tva_global, 2, ',', ' ') . ' €',
                    'montant_ttc_total' => number_format($total_ttc_global, 2, ',', ' ') . ' €'
                ];

                // Ajouter les informations formatées dans le tableau final pour la note de frais
                $noteFraisFormatees[] = [
                    'ref' => $note['ref'] ?? 'Inconnu',
                    'user_author_infos' => $note['user_author_infos'] ?? 'Inconnu',
                    'date_debut' => $date_debut,
                    'date_fin' => $date_fin,
                    'montant_ht' => $note['total_ht'] ?? 0,
                    'montant_tva' => $note['total_tva'] ?? 0,
                    'montant_ttc' => $note['total_ttc'] ?? 0,
                    'etat' => $status,
                    'deja_regle' => $note['paid'] ?? '0.0',
                    'montant_reclame' => $note['total_ttc'] ?? 0.0,
                    'reste_a_payer' => $note['total_ttc'] ?? 0.0 - $note['total_paid'] ?? 0.0,
                    'totaux' => $totaux,
                    'lines' => $lignesTableau   // Contient toutes les lignes formatées
                ];
            }

            // Retourner le tableau des notes de frais formatées
            return $noteFraisFormatees;
        }

        return []; // Retourner un tableau vide en cas d'échec
    }

    public function recupererNotesDeFraisFluteJean(): array
    {
        // Démarrer la session si elle n'est pas encore démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifier si un token API est disponible
        if (!isset($_SESSION['api_token'])) {
            return [];
        }

        // Timestamp Unix pour 18/02/2025
        $timestampDateDebut = 1741647600; // Date: 18/02/2025
        $timestampDateFin = 1741734000;   // Date: 19/02/2025

        // Construire l'URL avec le filtre sur la date de début
        $url = $this->apiUrl . "?sortfield=t.rowid&user_ids=1&sqlfilters=(t.date_debut>='" . $timestampDateDebut . "')%20and%20(t.date_debut<'". $timestampDateFin . "')";

        // Initialiser cURL pour récupérer les notes de frais filtrées
        $requeteCurl = curl_init($url);  // URL avec les paramètres de recherche, tri, et filtre
        curl_setopt($requeteCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($requeteCurl, CURLOPT_HTTPGET, true);
        curl_setopt($requeteCurl, CURLOPT_HTTPHEADER, [
            'DOLAPIKEY: ' . $_SESSION['api_token'],
            'Accept: application/json'
        ]);

        // Exécuter la requête
        $response = curl_exec($requeteCurl);
        $httpCode = curl_getinfo($requeteCurl, CURLINFO_HTTP_CODE);
        var_dump($response.$httpCode);
        curl_close($requeteCurl);

        // Vérifier si la requête a réussi (HTTP 200)
        if ($httpCode === 200) {
            $data = json_decode($response, true) ?? [];

            $noteFraisFormatees = [];

            // Formater la réponse pour extraire les informations pertinentes
            foreach ($data as $note) {
                // Formater les dates
                $date_debut = date('d/m/Y', strtotime($note['date_debut']));
                $date_fin = date('d/m/Y', strtotime($note['date_fin']));

                // Ajouter les informations formatées dans le tableau final pour la note de frais
                $noteFraisFormatees[] = [
                    'ref' => $note['ref'] ?? 'Inconnu',
                    'user_author_infos' => $note['user_author_infos'] ?? 'Inconnu',
                    'date_debut' => $date_debut,
                    'date_fin' => $date_fin,
                ];
            }

            // Retourner le tableau des notes de frais formatées
            return $noteFraisFormatees;
        }

        return []; // Retourner un tableau vide en cas d'échec
    }

    /**
     * Récupère les notes de frais pour les statistiques
     * @param string|null $date_debut
     * @param string|null $date_fin
     * @param bool $parMois
     * @param bool $parJour
     * @param string $moisChoisi
     * @return array
     */
    public function recupererStat(string  $date_debut = null,
                                  string  $date_fin = null,
                                  bool $parMois,
                                  bool $parJour,
                                  string  $moisChoisi): array
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
            // Décoder la réponse JSON en tableau associatif
            $notesFrais = json_decode($response, true) ?? [];

            // Initialiser les tableaux pour stocker les valeurs pour le diagramme
            $sectoriel = [];
            $histogramme = array_fill(1, 12, ['MontantTotal' => 0, 'NombreNotes' => 0]);

            // Si parJour est sélectionné, initialiser un tableau pour les jours du mois
            $histogrammeJour = [];
            if ($parJour && $moisChoisi) {
                // Initialiser l'histogramme pour chaque jour du mois choisi (1 à 31)
                for ($i = 1; $i <= 31; $i++) {
                    $histogrammeJour[$i] = ['MontantTotal' => 0, 'NombreNotes' => 0];
                }
            }

            // Parcourir toutes les notes de frais récupérées
            foreach ($notesFrais as $note) {
                foreach ($note['lines'] as $line) {
                    // Récupérer la date de la ligne de frais
                    $date_frais = $line['date'] ?? null;

                    // Vérifier s'il y a un filtre sur les dates et appliquer le filtre si nécessaire
                    if ($date_frais && $date_debut && $date_fin) {
                        if ($date_frais < $date_debut || $date_frais > $date_fin) {
                            continue; // Ignorer les frais en dehors de la plage de dates
                        }
                    }

                    // Formater le type_fees_code pour l'affichage
                    $type = match($line['type_fees_code'] ?? '') {
                        'EX_KME' => 'Frais Kilométrique',
                        'TF_LUNCH' => 'Repas',
                        'TF_TRIP' => 'Transport',
                        default => 'Autre',
                    };

                    $montant = $line['total_ttc'] ?? 0;
                    $mois = (int)date('n', strtotime($date_frais));
                    $jour = (int)date('j', strtotime($date_frais));

                    // Vérifier si ce type de note de frais est déjà enregistré
                    if (!isset($sectoriel[$type])) {
                        // Sinon, initialiser le type avec un montant total et un compteur à zéro
                        $sectoriel[$type] = [
                            'MontantTotalType' => 0,
                            'Quantite' => 0
                        ];
                    }

                    // Ajouter le montant de la note de frais au total du type
                    $sectoriel[$type]['MontantTotalType'] += $montant;
                    // Incrémenter le nombre de notes de frais de ce type
                    $sectoriel[$type]['Quantite']++;

                    // Calculer pour l'histogramme
                    if ($parMois) {
                        // Remplir l'histogramme par mois (1 à 12)
                        $histogramme[$mois]['MontantTotal'] += $montant;
                        $histogramme[$mois]['NombreNotes']++;
                    }

                    if ($parJour && $mois === (int)$moisChoisi) {
                        // Remplir l'histogramme pour les jours du mois sélectionné
                        $histogrammeJour[$jour]['MontantTotal'] += $montant;
                        $histogrammeJour[$jour]['NombreNotes']++;
                    }
                }
            }
            // Retourner un tableau contenant deux sous-tableaux, selon le graphique à afficher
            // return [$sectoriel, $histogramme]
            // Créer un nouveau tableau avec les clés remplacées par les noms des mois
            $histogrammeAvecMois = [];
            foreach ($histogramme as $numeroMois => $valeurs) {
                $nomMois = $this->moisNoms[$numeroMois]; // Convertir le numéro du mois en nom
                $histogrammeAvecMois[$nomMois] = $valeurs;
            }

            // Formater les montants dans le tableau sectoriel
            foreach ($sectoriel as $type => &$data) {
                $data['MontantTotalType'] = number_format($data['MontantTotalType'], 2, '.', '');
            }

            // Formater les montants dans le tableau histogramme
            foreach ($histogrammeAvecMois as $mois => &$data) {
                $data['MontantTotal'] = number_format($data['MontantTotal'], 2, '.', '');
            }

            // Formater les montants dans l'histogrammeJour
            foreach ($histogrammeJour as $jour => &$data) {
                $data['MontantTotal'] = number_format($data['MontantTotal'], 2, '.', '');
            }

            return ['sectoriel' => $sectoriel, 'histogramme' => $parMois ? $histogrammeAvecMois : $histogrammeJour];
        }

        return []; // Retourner un tableau vide en cas d'échec
    }
}