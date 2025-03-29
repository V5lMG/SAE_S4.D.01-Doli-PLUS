<?php
namespace services;

use DateTime;
use RuntimeException;

class NoteFraisService
{

    private array $moisNoms = [
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
     * Récupère toutes les notes de frais pour la liste des notes de frais.
     *
     * @return array Un tableau contenant toutes les notes de frais formatées.
             */
    public function recupererListeComplete(): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['api_token'], $_SESSION['url_saisie'])) {
            return [];
        }

        $urlNoteFrais = $_SESSION['url_saisie'] . "/expensereports";

        $requeteCurl = curl_init($urlNoteFrais);
        if ($requeteCurl === false) {
            throw new RuntimeException("Échec de l'initialisation de cURL.");
        }

        curl_setopt($requeteCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($requeteCurl, CURLOPT_HTTPGET, true);
        curl_setopt($requeteCurl, CURLOPT_HTTPHEADER, [
            'DOLAPIKEY: ' . $_SESSION['api_token'],
            'Accept: application/json'
        ]);

        $response = curl_exec($requeteCurl);
        curl_close($requeteCurl);

        if (!is_string($response)) {
            return [];
        }

        /** @var mixed $data */
        $data = json_decode($response, true);
        if (!is_array($data)) {
            return [];
        }

        $noteFraisFormatees = [];
        $nb_note = 0;

        foreach ($data as $note) {
            if (!is_array($note)) {
                continue;
            }

            $date_debut = isset($note['date_debut']) && is_int($note['date_debut'])
                ? date('d/m/Y', $note['date_debut'])
                : 'Inconnu';

            $date_fin = isset($note['date_fin']) && is_int($note['date_fin'])
                ? date('d/m/Y', $note['date_fin'])
                : 'Inconnu';

            $lignesTableau = [];
            $nb_note++;

            $total_ht_global = 0.0;
            $total_tva_global = 0.0;
            $total_ttc_global = 0.0;

            if (isset($note['lines']) && is_array($note['lines'])) {
                foreach ($note['lines'] as $line) {
                    if (!is_array($line)) {
                        continue;
                    }

                    $tva = is_numeric($line['tva_tx'] ?? null) ? (float)$line['tva_tx'] : 0.0;
                    $value_unit_ttc = is_numeric($line['value_unit'] ?? null) ? (float)$line['value_unit'] : 0.0;
                    $value_unit = $value_unit_ttc / (1 + ($tva / 100));
                    $montant_ht = is_numeric($line['total_ht'] ?? null) ? (float)$line['total_ht'] : 0.0;
                    $montant_ttc = is_numeric($line['total_ttc'] ?? null) ? (float)$line['total_ttc'] : 0.0;
                    $quantite = is_numeric($line['qty'] ?? null) ? (float)$line['qty'] : 1.0;

                    $total_ht_global += $montant_ht;
                    $total_tva_global += ($montant_ttc - $montant_ht);
                    $total_ttc_global += $montant_ttc;

                    $type_fees_code = (string)($line['type_fees_code'] ?? '');
                    $type = match ($type_fees_code) {
                        'EX_KME' => 'Frais kilométriques',
                        'TF_LUNCH' => 'Repas',
                        'TF_TRIP' => 'Transport',
                        default => 'Autre',
                    };

                    $date_line = isset($line['date']) && is_string($line['date']) ? strtotime($line['date']) : false;
                    $date_formatted = $date_line ? date("d/m/Y", $date_line) : 'Inconnu';

                    $ligneTableau = [
                        'date' => $date_formatted,
                        'type' => $type,
                        'tva' => number_format($tva, 2, ',', ' ') . ' %',
                        'prix_unitaire_ht' => number_format($value_unit, 2, ',', ' ') . ' €',
                        'prix_unitaire_ttc' => number_format($value_unit_ttc, 2, ',', ' ') . ' €',
                        'quantite' => $quantite,
                        'montant_ht' => number_format($montant_ht, 2, ',', ' ') . ' €',
                        'montant_ttc' => number_format($montant_ttc, 2, ',', ' ') . ' €',
                    ];

                    $lignesTableau[] = $ligneTableau;
                }
            }

            $status_code = (string)($note['status'] ?? '');
            $status = match ($status_code) {
                '0' => 'Brouillon',
                '2' => 'Validé',
                '99' => 'Refusé',
                '4' => 'Annulé',
                '5' => 'Approuvé',
                '6' => 'Payé',
                default => 'Inconnu',
            };

            $totaux = [
                'montant_ht_total' => number_format($total_ht_global, 2, ',', ' ') . ' €',
                'montant_tva_total' => number_format($total_tva_global, 2, ',', ' ') . ' €',
                'montant_ttc_total' => number_format($total_ttc_global, 2, ',', ' ') . ' €',
            ];

            $montant_ttc = is_numeric($note['total_ttc'] ?? null) ? (float)$note['total_ttc'] : 0.0;
            $montant_paid = is_numeric($note['total_paid'] ?? null) ? (float)$note['total_paid'] : 0.0;

            $noteFraisFormatees[] = [
                'ref' => isset($note['ref']) && is_string($note['ref']) ? $note['ref'] : 'Inconnu',
                'user_author_infos' => isset($note['user_author_infos']) && is_string($note['user_author_infos'])
                    ? $note['user_author_infos']
                    : 'Inconnu',
                'date_debut' => $date_debut,
                'date_fin' => $date_fin,
                'montant_ht' => is_numeric($note['total_ht'] ?? null) ? (float)$note['total_ht'] : 0.0,
                'montant_tva' => is_numeric($note['total_tva'] ?? null) ? (float)$note['total_tva'] : 0.0,
                'montant_ttc' => $montant_ttc,
                'etat' => $status,
                'montant_reclame' => $montant_ttc,
                'reste_a_payer' => $montant_ttc - $montant_paid,
                'totaux' => $totaux,
                'nombre_note' => $nb_note,
                'lines' => $lignesTableau
            ];
        }

        return $noteFraisFormatees;
    }




    /**
     * Filtre une liste de notes de frais en fonction des critères fournis.
     *
     * @param array $notes Liste complète des notes de frais.
     * @param string|null $employe Nom ou identifiant de l'employé associé à la note (filtrage partiel).
     * @param string|null $type Type de frais (ex : "REPAS", "TRANSPORT"), par défaut 'TOUS'.
     * @param string|null $reference Référence unique de la note de frais (filtrage partiel).
     * @param string|null $date_debut Date minimale de début au format 'Y-m-d'.
     * @param string|null $date_fin Date maximale de fin au format 'Y-m-d'.
     * @param string|null $etat État de la note de frais (ex : "validé", "en attente"), par défaut 'tous'.
     * @return array Un tableau contenant les notes filtrées et les totaux, ou un message d'erreur.
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
            return [];
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
            $noteDateDebut = DateTime::createFromFormat('d/m/Y', $note['date_debut']);
            $noteDateFin = DateTime::createFromFormat('d/m/Y', $note['date_fin']);

            if (!empty($date_debut)) {
                $filtreDateDebut = DateTime::createFromFormat('Y-m-d', $date_debut);
                if ($noteDateDebut < $filtreDateDebut) {
                    continue;
                }
            }

            if (!empty($date_fin)) {
                $filtreDateFin = DateTime::createFromFormat('Y-m-d', $date_fin);
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

    // Valeur à trier
    // Colonne
    // Direction (ascendant et descendant)
    public function triColonne(array $notes, string $colonne, string $direction = 'asc'): array
    {
        // Vérifier si la colonne spécifiée existe dans les données
        if (empty($notes) || !isset($notes[0][$colonne])) {
            return $notes; // Retourner les données inchangées si la colonne n'existe pas
        }

        // Fonction de comparaison pour le tri
        usort($notes, function ($a, $b) use ($colonne, $direction) {
            $valA = $a[$colonne];
            $valB = $b[$colonne];

            // Gérer les dates au format 'd/m/Y'
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $valA) && preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $valB)) {
                $valA = DateTime::createFromFormat('d/m/Y', $valA);
                $valB = DateTime::createFromFormat('d/m/Y', $valB);
            }

            // Comparaison selon la direction
            if ($direction === 'asc') {
                return $valA <=> $valB;
            } else {
                return $valB <=> $valA;
            }
        });

        return $notes;
    }

    /**
     * Récupère les statistiques pour l'histogramme (par mois ou par jour).
     *
     * @param bool $parMois Détermine si les statistiques sont par mois.
     * @param bool $parJour Détermine si les statistiques sont par jour.
     * @param string $moisChoisi Mois choisi pour l'affichage des statistiques.
     * @param string $anneeChoisi Année choisie pour l'affichage des statistiques.
     * @return array Un tableau contenant les données statistiques par mois ou par jour.
     */
    public function recupererStatHistogramme(bool $parMois, bool $parJour, string $moisChoisi, string $anneeChoisi, bool $comparaison): array
    {
        // Démarrer la session si elle n'est pas déjà active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifier si un token API est disponible
        if (!isset($_SESSION['api_token'])) {
            return [];
        }

        // Récupérer l'URL
        $urlNoteFrais = $_SESSION['url_saisie'] . "/expensereports";

        // Initialiser cURL
        $requeteCurl = curl_init($urlNoteFrais);
        if ($requeteCurl === false) {
            throw new RuntimeException("Échec de l'initialisation de cURL.");
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
        if (!is_string($response)) {
            curl_close($requeteCurl);
            return [];
        }

        $httpCode = curl_getinfo($requeteCurl, CURLINFO_HTTP_CODE);
        curl_close($requeteCurl);

        // Vérifier si la requête a réussi (HTTP 200)
        if ($httpCode === 200) {
            $notesFrais = json_decode($response, true) ?? [];

            // Initialiser les structures de stockage
            $histogrammeActuel = array_fill(1, 12, ['MontantTotal' => 0, 'NombreNotes' => 0]);
            $histogrammeComparaison = array_fill(1, 12, ['MontantTotal' => 0, 'NombreNotes' => 0]);

            // Si parJour est sélectionné, initialiser un tableau pour les jours du mois
            $histogrammeJourActuel = [];
            $histogrammeJourComparaison = [];
            if ($parJour && $moisChoisi) {
                // Initialiser l'histogrammeActuel pour chaque jour du mois choisi (1 à 31)
                for ($i = 1; $i <= 31; $i++) {
                    $histogrammeJourActuel[$i] = ['MontantTotal' => 0, 'NombreNotes' => 0];
                    $histogrammeJourComparaison[$i] = ['MontantTotal' => 0, 'NombreNotes' => 0];
                }
            }

            // Calcul de l'année précédente
            $anneePrecedente = (string)((int)$anneeChoisi - 1);

            // Parcourir toutes les notes de frais récupérées
            foreach ($notesFrais as $note) {
                foreach ($note['lines'] as $line) {
                    $date_frais = $line['date'] ?? null;

                    if (!$date_frais) continue;

                    $annee_frais = substr($date_frais, 0, 4);
                    $mois = (int)date('n', strtotime($date_frais));
                    $jour = (int)date('j', strtotime($date_frais));
                    $montant = $line['total_ht'] ?? 0;

                    // Comparaison avec l'année choisie et l'année précédente
                    if ($annee_frais === $anneeChoisi) {
                        if ($parMois) {
                            $histogrammeActuel[$mois]['MontantTotal'] += $montant;
                            $histogrammeActuel[$mois]['NombreNotes']++;
                        }
                        if ($parJour && $mois === (int)$moisChoisi) {
                            $histogrammeJourActuel[$jour]['MontantTotal'] += $montant;
                            $histogrammeJourActuel[$jour]['NombreNotes']++;
                        }
                    } elseif ($comparaison && $annee_frais === $anneePrecedente) {
                        if ($parMois) {
                            $histogrammeComparaison[$mois]['MontantTotal'] += $montant;
                            $histogrammeComparaison[$mois]['NombreNotes']++;
                        }
                        if ($parJour && $mois === (int)$moisChoisi) {
                            $histogrammeJourComparaison[$jour]['MontantTotal'] += $montant;
                            $histogrammeJourComparaison[$jour]['NombreNotes']++;
                        }
                    }
                }
            }

            // Créer un nouveau tableau avec les clés remplacées par les noms des mois
            $histogrammeAvecMoisActuel = [];
            $histogrammeAvecMoisComparaison = [];
            foreach ($histogrammeActuel as $numeroMois => $valeurs) {
                $nomMois = $this->moisNoms[$numeroMois]; // Convertir le numéro du mois en nom
                $valeurs['MontantTotal'] = number_format($valeurs['MontantTotal'], 2, '.', '');
                $histogrammeAvecMoisActuel[$nomMois] = $valeurs;
            }

            foreach ($histogrammeComparaison as $numeroMois => $valeurs) {
                $nomMois = $this->moisNoms[$numeroMois];
                $valeurs['MontantTotal'] = number_format($valeurs['MontantTotal'], 2, '.', '');
                $histogrammeAvecMoisComparaison[$nomMois] = $valeurs;
            }

            // Retourner les résultats
            return $parMois
                ? ['actuel' => $histogrammeAvecMoisActuel, 'comparaison' => $histogrammeAvecMoisComparaison]
                : ['actuel' => $histogrammeJourActuel, 'comparaison' => $histogrammeJourComparaison];
        }

        return []; // Retourner un tableau vide en cas d'échec
    }

    /**
     * Récupère les statistiques sectorielles des notes de frais par type.
     *
     * @param string|null $date_debut Date de début pour le filtre.
     * @param string|null $date_fin Date de fin pour le filtre.
     * @return array Un tableau contenant les statistiques par type de dépense.
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

        // Récupérer l'URL
        $urlNoteFrais = $_SESSION['url_saisie'] . "/expensereports";

            // Initialiser cURL
            $requeteCurl = curl_init($urlNoteFrais);

        // Vérifier si l'initialisation a réussi
        if ($requeteCurl === false) {
            throw new RuntimeException("Échec de l'initialisation de cURL.");
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

            // Vérifier si la requête a échoué
            if ($response === false) {
                throw new RuntimeException("Erreur cURL : " . curl_error($requeteCurl));
            }

            $httpCode = curl_getinfo($requeteCurl, CURLINFO_HTTP_CODE);
            curl_close($requeteCurl); //Fermer la connexion cURL

            // Vérifier si la requête a réussi (HTTP 200)
            if ($httpCode !== 200) {
                throw new RuntimeException("Erreur API, code HTTP : " . $httpCode);
            } else {
                $notesFrais = json_decode($response, true) ?? [];

                // Vérifier si la réponse est valide
                if (!is_array($notesFrais)) {
                    throw new RuntimeException("Réponse JSON invalide.");
                }

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

                    $montant = $line['total_ht'] ?? 0;

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
            foreach ($sectoriel as &$data) {
                $data['MontantTotalType'] = number_format($data['MontantTotalType'], 2, '.', '');
            }

            return $sectoriel;
        }

        return []; // Retourner un tableau vide en cas d'échec
    }
}