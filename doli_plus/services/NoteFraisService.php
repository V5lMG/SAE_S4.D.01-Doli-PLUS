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
            $nb_note = 0;

            // Formater la réponse pour extraire les informations pertinentes
            foreach ($data as $note) {
                // Formater les dates
                $date_debut = date('d/m/Y', $note['date_debut']);
                $date_fin = date('d/m/Y', $note['date_fin']);

                $lignesTableau = [];
                $nb_note += 1;

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
                    'montant_reclame' => $note['total_ttc'] ?? 0.0,
                    'reste_a_payer' => $note['total_ttc'] ?? 0.0 - $note['total_paid'] ?? 0.0,
                    'totaux' => $totaux,
                    'nombre_note' => $nb_note,
                    'lines' => $lignesTableau   // Contient toutes les lignes formatées
                ];
            }

            // Retourner le tableau des notes de frais formatées
            return $noteFraisFormatees;
        }

        return []; // Retourner un tableau vide en cas d'échec
    }

    /**
     * Filtre une liste de notes de frais en fonction des critères fournis.
     *
     * @param array       $notes       Liste complète des notes de frais.
     * @param string|null $employe     Nom ou identifiant de l'employé associé à la note (filtrage partiel).
     * @param string|null $type        Type de frais (ex: "REPAS", "TRANSPORT"), par défaut 'TOUS' (aucun filtre appliqué).
     * @param string|null $reference   Référence unique de la note de frais (filtrage partiel).
     * @param string|null $date_debut  Date minimale de début au format 'Y-m-d' (ex: '2024-01-01').
     * @param string|null $date_fin    Date maximale de fin au format 'Y-m-d' (ex: '2024-12-31').
     * @param string|null $etat        État de la note de frais (ex: "validé", "en attente"), par défaut 'tous' (aucun filtre appliqué).
     * @param array       $notesFiltrees Référence vers le tableau où seront stockées les notes filtrées.
     *
     * @return array Retourne un tableau contenant les notes filtrées, ou un message d'erreur si aucun filtre n'est utilisé.
     */
    public function filtrerValeurs(array $notes, ?string $employe = null, ?string $type = 'TOUS', ?string $reference = null, ?string $date_debut = null, ?string $date_fin = null, ?string $etat = 'tous'): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['api_token'])) {
            return [];
        }

        if (empty($employe) && $type === 'TOUS' && empty($reference) && empty($date_debut) && empty($date_fin) && $etat === 'tous') {
            return ['message' => 'Sélectionnez au moins un filtre'];
        }

        $notesFiltrees = [];
        $totaux = [
            'nombre_note' => 0,
            'montant_ht_total' => 0,
            'montant_tva_total' => 0,
            'montant_ttc_total' => 0
        ];

        foreach ($notes as $note) {
            // Filtre par employé
            if (!empty($employe) && stripos($note['user_author_infos'], $employe) === false) {
                continue;
            }

            // Filtre par référence
            if (!empty($reference) && stripos($note['ref'], $reference) === false) {
                continue;
            }

            // Gestion des dates
            $noteDateDebut = \DateTime::createFromFormat('d/m/Y', $note['date_debut']);
            $noteDateFin = \DateTime::createFromFormat('d/m/Y', $note['date_fin']);

            if (!empty($date_debut)) {
                $filtreDateDebut = \DateTime::createFromFormat('Y-m-d', $date_debut);
                if ($noteDateDebut < $filtreDateDebut) {
                    continue;
                }
            }

            if (!empty($date_fin)) {
                $filtreDateFin = \DateTime::createFromFormat('Y-m-d', $date_fin);
                if ($noteDateFin > $filtreDateFin) {
                    continue;
                }
            }

            // Filtre par type (au moins une ligne doit correspondre au type sélectionné)
            if ($type !== 'TOUS') {
                $typeMatch = false;
                foreach ($note['lines'] as $line) {
                    if (isset($line['type']) && $line['type'] === $type) {
                        $typeMatch = true;
                        break;
                    }
                }
                if (!$typeMatch) {
                    continue;
                }
            }

            // Filtre par état
            if ($etat !== 'tous' && !empty($etat) && stripos($note['etat'], $etat) === false) {
                continue;
            }

            // Ajout de la note filtrée
            $notesFiltrees[] = $note;

            // Mise à jour des totaux
            $totaux['nombre_note']++;
            $totaux['montant_ht_total'] += $note['montant_ht'];
            $totaux['montant_tva_total'] += $note['montant_tva'];
            $totaux['montant_ttc_total'] += $note['montant_ttc'];
        }

        return ['notes' => $notesFiltrees, 'totaux' => $totaux];
    }

    /**
     * Récupère les statistiques pour l'histogramme (par mois ou par jour)
     * @param string $anneeChoisi
     * @param string|null $moisChoisi
     * @param bool $parMois
     * @param bool $parJour
     * @return array
     */
    public function recupererStatHistogramme(bool $parMois, bool $parJour, string $moisChoisi, string $anneeChoisi): array
    {
        // Démarrer la session si elle n'est pas déjà active
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
            $notesFrais = json_decode($response, true) ?? [];

            // Initialiser les structures de stockage
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
                    $date_frais = $line['date'] ?? null;

                    // Appliquer le filtre de l'année
                    $annee_frais = null;
                    if ($date_frais) {
                        // Convertir la date de frais en objet DateTime
                        $annee_frais = substr($date_frais, 0, 4);
                    }

                    if ($annee_frais && $anneeChoisi) {
                        if ($annee_frais !== $anneeChoisi) {
                            continue; // Ignorer les frais qui ne correspondent pas à l'année choisie
                        }
                    }

                    $mois = (int)date('n', strtotime($date_frais));
                    $jour = (int)date('j', strtotime($date_frais));
                    $montant = $line['total_ttc'] ?? 0;

                    // Par mois
                    if ($parMois) {
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

            // Créer un nouveau tableau avec les clés remplacées par les noms des mois
            $histogrammeAvecMois = [];
            foreach ($histogramme as $numeroMois => $valeurs) {
                $nomMois = $this->moisNoms[$numeroMois]; // Convertir le numéro du mois en nom
                $histogrammeAvecMois[$nomMois] = $valeurs;
            }

            // Formater les montants dans le tableau histogramme
            foreach ($histogrammeAvecMois as $mois => &$data) {
                $data['MontantTotal'] = number_format($data['MontantTotal'], 2, '.', '');
            }

            // Formater les montants dans l'histogrammeJour
            foreach ($histogrammeJour as $jour => &$data) {
                $data['MontantTotal'] = number_format($data['MontantTotal'], 2, '.', '');
            }

            return $parMois ? $histogrammeAvecMois : $histogrammeJour;
        }

        return []; // Retourner un tableau vide en cas d'échec
    }

    /**
     * Récupère les statistiques sectorielles des notes de frais par type
     * @param string|null $date_debut
     * @param string|null $date_fin
     * @return array
     */
    public function recupererStatSectorielle(string $date_debut = null, string $date_fin = null): array
    {
        // Démarrer la session si elle n'est pas déjà active
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
            $notesFrais = json_decode($response, true) ?? [];
            $sectoriel = [];

            // Parcourir toutes les notes de frais récupérées
            foreach ($notesFrais as $note) {
                foreach ($note['lines'] as $line) {
                    $date_frais = $line['date'] ?? null;

                    // Appliquer le filtre de date
                    if ($date_frais && $date_debut && $date_fin) {
                        if ($date_frais < $date_debut || $date_frais > $date_fin) {
                            continue; // Ignorer les frais en dehors de la plage de dates
                        }
                    }

                    // Identifier le type de dépense
                    $type = match($line['type_fees_code'] ?? '') {
                        'EX_KME' => 'Frais Kilométrique',
                        'TF_LUNCH' => 'Repas',
                        'TF_TRIP' => 'Transport',
                        default => 'Autre',
                    };

                    $montant = $line['total_ttc'] ?? 0;

                    // Vérifier si ce type existe déjà dans le tableau
                    if (!isset($sectoriel[$type])) {
                        // Sinon, initialiser le type avec un montant total et un compteur à zéro
                        $sectoriel[$type] = [
                            'MontantTotalType' => 0,
                            'Quantite' => 0
                        ];
                    }

                    // Ajouter le montant et le nombre de notes de frais
                    $sectoriel[$type]['MontantTotalType'] += $montant;
                    $sectoriel[$type]['Quantite']++;
                }
            }

            // Formater les montants
            foreach ($sectoriel as $type => &$data) {
                $data['MontantTotalType'] = number_format($data['MontantTotalType'], 2, '.', '');
            }

            return $sectoriel;
        }

        return []; // Retourner un tableau vide en cas d'échec
    }
}