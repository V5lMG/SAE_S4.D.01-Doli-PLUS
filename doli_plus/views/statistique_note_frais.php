<?php
// Démarrer la session si elle n'est pas encore démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$filtreJour = isset($_POST['filtreJour']) ? $_POST['filtreJour'] : 'mois'; // 'mois' par défaut

// Convertir le tableau en JSON
$listeStatSectorielle = isset($_SESSION['listSectoriel']) ? json_encode($_SESSION['listSectoriel']) : '[]';
$listeStatHistogramme = isset($_SESSION['listHistogramme']) ? json_encode($_SESSION['listHistogramme']) : '[]';

// Décoder le JSON pour pouvoir accéder aux données comme tableau PHP
$listeStatHistogrammeDecoded = json_decode($listeStatHistogramme, true);

// Vérifier si les données ont bien été décodées
if (isset($listeStatHistogrammeDecoded['actuel'])) {
    $listeStatHistogrammeActuel = $listeStatHistogrammeDecoded['actuel'];
} else {
    $listeStatHistogrammeActuel = [];
}

if (isset($listeStatHistogrammeDecoded['comparaison'])) {
    $listeStatHistogrammeComparaison = $listeStatHistogrammeDecoded['comparaison'];
} else {
    $listeStatHistogrammeComparaison = [];
}

// Tableau des mois
$mois = [
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
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Statistiques des notes de frais</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="static/css/styles.css">
        <link rel="stylesheet" href="static/css/sidebar.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">

                <!-- Importer la sidebar -->
                <?php include 'static/sidebar.php'; ?>

                <!-- Contenu principal -->
                <div class="contenu-principal mt-4">
                    <div class="container-fluid">

                        <!-- Titre -->
                        <div class="row">
                            <div class="col text-center">
                                <h2 class="mb-4 titre_page"><span class="fas fa-wallet beige"></span> Statistiques</h2>
                            </div>
                        </div>
                        <br>
                        <!-- Information de la page -->
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-6 text-center">
                                <!-- Histogramme Baton -->
                                <div class="p-4 border rounded shadow-sm bg-light">
                                    <?php
                                    // Variables pour le titre
                                    $titre = "Evolution des notes de frais";

                                    if ($filtreJour === 'mois') {
                                        $titre .= " sur l'année " . (isset($anneeChoisi) ? $anneeChoisi : date("Y"));
                                    } elseif ($filtreJour === 'jour') {
                                        $titre .= " pour le mois de " . (isset($moisChoisi) ? $mois[$moisChoisi]: 'Sélectionner un mois') . " de l'année " . (isset($anneeChoisi) ? $anneeChoisi : date("Y"));
                                    }

                                    // Vérification si la comparaison est activée
                                    if (isset($_POST['comparaison']) && $_POST['comparaison'] == 'on') {
                                        $titre .= " (Comparaison avec l'année précédente)";
                                    }
                                    ?>
                                    <h5><?= $titre ?></h5>
                                    <br>
                                    <canvas id="histogramme" width="400" height="200"></canvas>
                                    <form id="formHistogramme" method="POST" action="<?= htmlspecialchars('index.php?controller=NoteFrais&action=indexStatistique'); ?>">
                                        <!-- Bouton radio -->
                                        <div class="row justify-content-center mt-3">
                                            <div class="col-md-6">
                                                <label for="parMois">Par mois (sur un an)</label>
                                                <input type="radio" class="form-check-input" name="filtreJour" id="parMois" value="mois" <?= $filtreJour === 'mois' ? 'checked' : '' ?>>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="parJour">Par jour (sur un mois)</label>
                                                <input type="radio" class="form-check-input" name="filtreJour" id="parJour" value="jour" <?= $filtreJour === 'jour' ? 'checked' : '' ?>>
                                            </div>
                                        </div>
                                        <!-- Liste déroulante des années et des mois -->
                                        <div class="row justify-content-center mt-3">
                                            <div class="col-md-6" >
                                                <label for="annee_filtre">Veuillez sélectionner <u>l'année</u> à afficher :</label>
                                                <select class="form-select" id="annee_filtre" name="annee_filtre">
                                                    <option value="" >--Sélectionner une année--</option>
                                                    <?php
                                                    $currentYear = date("Y");
                                                    for ($year = $currentYear; $year >= 1900; $year--) {?>
                                                        <option value="<?= $year ?>" <?= $year == $anneeChoisi ? 'selected' : '' ?> > <?= $year ?> </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6" id="mois_div">
                                                <label for="mois_filtre">Veuillez sélectionner <u>le mois</u> à afficher :</label>
                                                <select class="form-select" name="mois_filtre" id="mois_filtre">
                                                    <option value="" >--Sélectionner un mois--</option>
                                                    <?php
                                                    // Boucle pour afficher les mois dans la liste déroulante
                                                    foreach ($mois as $moisNum => $moisOption) { ?>
                                                        <option value="<?= $moisNum ?>" <?= $moisNum == $moisChoisi ? 'selected' : '' ?> > <?= $moisOption ?> </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center mt-3">
                                            <div class="col-md-6 col-12">
                                                <label class="form-check-label">Comparaison avec l'année précédente : </label>
                                                <input class="form-check-input" type="checkbox" id="comparaison" name="comparaison" <?= $_POST['comparaison'] == 'on' ? 'checked' : ''; ?>>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center mt-3">
                                            <div class="col-md-1 col-12">
                                                <input type="hidden" name="histogramme" value="true"/>
                                                <input type="hidden" name="date_debut" id="date_debut" value="<?= $date_debut?>"/>
                                                <input type="hidden" name="date_fin" id="date_fin" value="<?= $date_fin?>"/>
                                                <input type="hidden" name="listeStatSectorielle" value="<?= $listSectorielle ?>"/>
                                                <button type="submit" class="btn btn-primary" title="Rechercher">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                            <div class="col-md-1 col-12 mt-md-0 mt-3">
                                                <button type="reset" class="btn btn-outline-secondary" title="RéinitialiserHistogramme" onclick="resetFiltersHistogramme()">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 text-center mt-md-0 mt-4">
                                <!-- Diagramme Circulaire -->
                                <div class="p-4 border rounded shadow-sm bg-light">
                                    <?php if (isset($date_debut) && isset ($date_fin) && !empty($date_debut) && !empty($date_fin)) { ?>
                                        <h5>Diagramme sectoriel des notes de frais entre <?= htmlspecialchars((new DateTime($date_debut))->format('d/m/Y')); ?> et <?= htmlspecialchars((new DateTime($date_fin))->format('d/m/Y')); ?> compris </h5>
                                    <?php } else { ?>
                                        <h5>Diagramme sectoriel de la totalité des notes de frais</h5>
                                    <?php } ?>
                                    <br>
                                    <canvas id="diagramme_sectoriel" width="400" height="200"></canvas>
                                    <form id="formSectoriel" method="POST" action="<?= htmlspecialchars('index.php?controller=NoteFrais&action=indexStatistique'); ?>">
                                        <div class="row justify-content-center mt-3">
                                            <div class="col-md-4 col-12">
                                                <label for="date_debut">Date début :</label>
                                                <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= isset($date_debut) ? htmlspecialchars($date_debut) : '' ?>">
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <label for="date_fin">Date fin :</label>
                                                <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= isset($date_fin) ? htmlspecialchars($date_fin) : '' ?>">
                                            </div>
                                            <div class="col-md-1 col-12 mt-md-0 mt-3">
                                                <label for="invisible"></label> <!-- aligne le bouton de recherche avec les champs "date"-->
                                                <input type="hidden" name="sectoriel" value="true"/>
                                                <input type="hidden" name="comparaison" id="comparaison" value="<?= $_POST['comparaison']?>"/>
                                                <input type="hidden" name="filtreJour" id="parMois" value="<?= $filtreJour ?>"/>
                                                <input type="hidden" name="annee_filtre" id="annee_filtre" value="<?= $anneeChoisi?>"/>
                                                <input type="hidden" name="mois_filtre" id="mois_filtre" value="<?= $moisChoisi?>"/>
                                                <input type="hidden" name="listeStatHistogramme" value="<?= $listHistogramme?>"/>
                                                <button type="submit" class="btn btn-primary" title="Rechercher">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                            <div class="col-md-1 col-12 mt-md-0 mt-3">
                                                <label for="invisible"></label> <!-- aligne le bouton de recherche avec les champs "date"-->
                                                <button type="reset" class="btn btn-outline-secondary" title="RéinitialiserSectoriel" onclick="resetFiltersSectoriel()">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            /*------------------------------Fonction pour réinitialiser les filtres  ---------------------------------*/
            function resetFiltersHistogramme() {
                document.getElementById("parMois").checked = true;
                document.getElementById("parJour").checked = false;
                document.getElementById("annee_filtre").value = "";
                document.getElementById("mois_filtre").value = "";
                document.getElementById("comparaison").checked = false;

                // Soumettre le formulaire après la réinitialisation
                document.getElementById("formHistogramme").submit();
            }

            function resetFiltersSectoriel() {
                /*
                  En ce qui concerne la réinitialisation du formulaire du diagramme sectoriel,
                  on doit récupèrer le formulaire directement, car si l'on récupère seulement
                  les champs avec leur ID, des conflits ont lieu avec les "input" de type
                  "hidden" du formulaire de l'histogramme.
                 */

                let form = document.getElementById("formSectoriel");

                // Réinitialiser uniquement les champs de ce formulaire
                form.querySelector("#date_debut").value = "";
                form.querySelector("#date_fin").value = "";

                form.submit();
            }

            /*---------------------------------------- Graphique Histogramme/Courbe ----------------------------------*/
            // Récupération des données PHP encodées en JSON
            const listeStatHistogrammeActuel = <?php echo json_encode($listeStatHistogrammeActuel); ?>;
            const listeStatHistogrammeComparaison = <?php echo json_encode($listeStatHistogrammeComparaison); ?>;

            const histogrammeLabels = Object.keys(listeStatHistogrammeActuel);
            const montantTotalActuel = histogrammeLabels.map(mois => listeStatHistogrammeActuel[mois]['MontantTotal']); // Montant total par mois
            const nombreNotesActuel  = histogrammeLabels.map(mois => listeStatHistogrammeActuel[mois]['NombreNotes']);  // Quantité de notes de frais par mois

            // Données de comparaison
            const montantTotalComparaison = histogrammeLabels.map(mois => {
                // Si les données de comparaison existent, les récupérer, sinon mettre 0
                return listeStatHistogrammeComparaison[mois] ? listeStatHistogrammeComparaison[mois]['MontantTotal'] : 0;
            });

            const nombreNotesComparaison = histogrammeLabels.map(mois => {
                // Si les données de comparaison existent, les récupérer, sinon mettre 0
                return listeStatHistogrammeComparaison[mois] ? listeStatHistogrammeComparaison[mois]['NombreNotes'] : 0;
            });

            const comparaisonCheckbox = document.getElementById('comparaison').checked;

            // Configuration des données pour le graphique histogramme
            const histogrammeData = {
                labels: histogrammeLabels,
                datasets: [
                    {
                        label: 'Montant total (€)',
                        data: montantTotalActuel,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    ...(comparaisonCheckbox ? [{
                        label: 'Montant total (Comparaison)',
                        data: montantTotalComparaison,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)', // Couleur pour les données de comparaison
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }] : [] )
                ]
            };

            // Configuration des options du graphique
            const histogrammeOptions = {
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        enabled: true,  // Activer les tooltips
                        callbacks: {
                            // Ajouter un tooltip personnalisé
                            label: function(tooltipItem) {
                                // L'index du mois
                                const index = tooltipItem.dataIndex;

                                // Nombre de notes de frais correspondant à ce mois
                                const nombreDeNotesActuel = nombreNotesActuel[index];
                                const nombreDeNotesComparaison = nombreNotesComparaison[index];

                                // Affichage lors du passage de la souris
                                if (tooltipItem.datasetIndex === 0) {
                                    return 'Montant total : ' + tooltipItem.raw + '€ | ' + nombreDeNotesActuel + ' notes de frais (Actuel)';
                                } else {
                                    return 'Montant total : ' + tooltipItem.raw + '€ | ' + nombreDeNotesComparaison + ' notes de frais (Comparaison)';
                                }
                            }
                        }
                    }
                }
            };

            // Initialisation du graphique histogramme (barres)
            const ctxHistogramme = document.getElementById('histogramme').getContext('2d');
            const histogramme = new Chart(ctxHistogramme, {
                type: 'bar',
                data: histogrammeData,
                options: histogrammeOptions
            });

            /*----------------------Fonction pour afficher ou masquer la liste déroulante des mois----------------------*/

            document.addEventListener("DOMContentLoaded", function () {
                const parMoisRadio = document.getElementById("parMois");
                const parJourRadio = document.getElementById("parJour");
                const moisDiv      = document.getElementById("mois_div");

                // Vérifier si "parMois" est sélectionné par défaut
                if (parMoisRadio.checked) {
                    moisDiv.style.display = "none";
                } else {
                    moisDiv.style.display = "block";
                }

                // Si l'option "Par mois" est sélectionnée, alors on cache la liste déroulante
                parMoisRadio.addEventListener("change", function () {
                    if (parMoisRadio.checked) {
                        moisDiv.style.display = "none";
                    }
                });

                // Si l'option "Par jour" est sélectionnée, alors on affiche la liste déroulante
                parJourRadio.addEventListener("change", function () {
                    if (parJourRadio.checked) {
                        moisDiv.style.display = "block";
                    }
                });
            });

            /*--------------------------------------------- Diagramme Circulaire -------------------------------------*/

            // Récupération des données PHP encodées en JSON
            const listeStatSectorielle = <?php echo $listeStatSectorielle; ?>;

            // Extraction des labels (types de frais) et des données associées
            const labels       = Object.keys(listeStatSectorielle); // ["Frais kilométriques", "Repas", "Transport", "Autre"]
            const montantTotalSectoriel = labels.map(type => listeStatSectorielle[type]['MontantTotalType']);
            const quantite     = labels.map(type => listeStatSectorielle[type]['Quantite']);

            // Configuration des données pour le diagramme
            const data = {
                labels: labels, // Type des notes de frais
                datasets: [
                    {
                        label: 'Montant total',
                        data: montantTotalSectoriel, // Montant total de chaque type de note de frais
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)'
                        ],
                    },
                    {
                        label: 'Nombre de notes de frais',
                        data: quantite,        // Nombre de notes de frais par type
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)'
                        ],
                    }
                ]
            };

            // Configuration des options du diagramme
            const options = {
                responsive: false,
                maintainAspectRatio: false, // Ajuste le diagramme selon le conteneur
                plugins: {
                    legend: {
                        position: 'right',  // Place la légende à droite
                    }
                }
            };

            // Initialisation du diagramme
            const ctx = document.getElementById('diagramme_sectoriel').getContext('2d');
            const diagramme_sectoriel = new Chart(ctx, {
                type: 'pie',
                data: data,
                options: options
            });
        </script>
    </body>
</html>
