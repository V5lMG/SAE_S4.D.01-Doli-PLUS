/**
 * Récupère les notes de frais pour les statistiques
 * @param string|null $date_debut
 * @param string|null $date_fin
 * @param bool $parMois
 * @param bool $parJour
 * @param string $moisChoisi
 * @param string $anneeChoisi
 * @return array
 */
public function recupererStat(string  $date_debut = null,
                              string  $date_fin = null,
                              bool $parMois,
                              bool $parJour,
                              string $moisChoisi,
                              string $anneeChoisi) : array
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