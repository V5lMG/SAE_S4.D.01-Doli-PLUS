<?php
namespace services;

class NoteFraisService
{
    private string $apiUrl = "http://dolibarr.iut-rodez.fr/G2024-43-SAE/htdocs/api/index.php/expensereports";

    /**
     * Récupère toutes les notes de frais
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

                // Montants totaux
                $montant_ht_total = array_sum(array_column($data, 'montant_ht'));
                $montant_tva_total = array_sum(array_column($data, 'montant_tva'));
                $montant_ttc_total = array_sum(array_column($data, 'montant_ttc'));

                $lignesTableau = [];

                // Formater chaque ligne
                foreach ($note['lines'] as $line) {
                    // Calculer les montants pour chaque ligne
                    $tva = $line['tva_tx'];
                    $value_unit = $line['value_unit'];
                    $value_unit_ttc = $line['value_unit'] / (1 + ($tva/100));
                    $montant_ht = $line['total_ht'] ?? 0.0;
                    $montant_ttc = $line['total_ttc'] ?? 0.0;

                    // Formater le type_fees_code pour l'affichage
                    $type = match($line['type_fees_code'] ?? '') {
                        'EX_KME' => 'Frais Kilométrique',
                        'TF_LUNCH' => 'Repas',
                        'TF_TRIP' => 'Transport',
                        default => 'Autre',
                    };

                    // Créer la ligne sous forme de tableau pour cette ligne spécifique
                    $ligneTableau = [
                        'date' => $line['date'],
                        'type' => $type,
                        'description' => $line['type_fees_libelle'] ?? 'Non spécifiée',
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

                // Ajouter les informations formatées dans le tableau final pour la note de frais
                $noteFraisFormatees[] = [
                    'ref' => $note['ref'] ?? 'Inconnu',
                    'user_author_infos' => $note['user_author_infos'] ?? 'Inconnu',
                    'date_debut' => $date_debut,
                    'date_fin' => $date_fin,
                    'montant_ht' => $montant_ht_total,
                    'montant_tva' => $montant_tva_total,
                    'montant_ttc' => $montant_ttc_total,
                    'etat' => $note['status'] === '1' ? 'Validé' : 'Non validé', // Utilisation du statut de la note
                    'deja_regle' => $note['paid'] === '1' ? 'Oui' : 'Non', // Indication du paiement
                    'montant_reclame' => (float)($note['total_ttc'] ?? 0.0),
                    'reste_a_payer' => (float)($note['total_ttc'] ?? 0.0) - (float)($note['total_paid'] ?? 0.0),
                    'lines' => $lignesTableau, // Contient toutes les lignes formatées
                ];
            }

            var_dump($data);
            // Retourner le tableau des notes de frais formatées
            return $noteFraisFormatees;
        }

        return []; // Retourner un tableau vide en cas d'échec
    }
}