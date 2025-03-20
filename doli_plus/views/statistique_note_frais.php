<?php
// Démarrer la session si elle n'est pas encore démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Convertir le tableau en JSON
$listeStatSectorielle = json_encode($listStat['sectoriel'], true);
$listeStatHistogramme = json_encode($listStat['histogramme'], true);
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
                <div class="contenu-principal">
                    <div class="container-fluid">

                        <!-- Contenu principal a modifier -->
                        <!-- Titre -->
                        <div class="row">
                            <div class="col text-center">
                                <h1 class="mb-4">Statistiques</h1>
                            </div>
                        </div>
                        <!-- Information de la page -->
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-6 text-center">
                                <!-- Histogramme Courbe ou Baton -->
                                <div class="p-4 border rounded shadow-sm bg-light">
                                    <canvas id="histogramme" width="400" height="200"></canvas>
                                    <form method="POST" action="<?= htmlspecialchars('index.php?controller=NoteFrais&action=indexStatistique'); ?>">
                                        <div class="row justify-content-center mt-3">
                                            <div class="col-md-3">
                                                <label for="parMois">Par mois (sur un an)</label>
                                                <!-- j'ai toucher aux deux isset -->
                                                <input type="radio" class="form-check-input" name="filtreJour" id="parMois" value="mois" <?= isset($parMois) && $parMois ? 'checked' : '' ?>>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="parJour">Par jour (sur un mois)</label>
                                                <input type="radio" class="form-check-input" name="filtreJour" id="parJour" value="jour" <?= isset($parJour) && $parJour ? 'checked' : '' ?>>
                                            </div>
                                            <div class="col-md-6" id="mois_div">
                                                <label for="mois_filtre">Veuillez sélectionner le mois à afficher :</label>
                                                <select class="form-select" name="mois_filtre" id="mois_filtre">
                                                    <option value="" >--Sélectionner un mois--</option>
                                                    <?php
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
                                                    // Boucle pour afficher les mois dans la liste déroulante
                                                    foreach ($mois as $moisNum => $moisOption) { ?>
                                                        <option value="<?= $moisNum ?>" <?= $moisNum == $moisChoisi ? 'selected' : '' ?> > <?= $moisOption ?> </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select><br><br>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center mt-3">
                                            <div class="col-md-1 col-12">
                                                <button type="submit" class="btn btn-primary" title="Rechercher">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                            <div class="col-md-1 col-12">
                                                <button type="reset" class="btn btn-outline-secondary" title="Réinitialiser">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 text-center">
                                <!-- Diagramme Circulaire -->
                                <div class="p-4 border rounded shadow-sm bg-light">
                                    <canvas id="diagramme_sectoriel" width="400" height="200"></canvas>
                                    <form method="POST" action="<?= htmlspecialchars('index.php?controller=NoteFrais&action=indexStatistique'); ?>">
                                        <div class="row justify-content-center mt-3">
                                            <div class="col-md-4 col-12">
                                                <label for="date_debut">Date début :</label>
                                                <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= isset($date_debut) ? htmlspecialchars($date_debut) : '' ?>">
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <label for="date_fin">Date fin :</label>
                                                <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= isset($date_fin) ? htmlspecialchars($date_fin) : '' ?>">
                                            </div>
                                            <div class="col-md-1 col-12">
                                                <label for="invisible"></label> <!-- aligne le bouton de recherche avec les champs "date"-->
                                                <button type="submit" class="btn btn-primary" title="Rechercher">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                            <div class="col-md-1 col-12">
                                                <label for="invisible"></label> <!-- aligne le bouton de recherche avec les champs "date"-->
                                                <button type="reset" class="btn btn-outline-secondary" title="Réinitialiser" onclick="window.location.href='index.php?controller=NoteFrais&action=indexStatistique&reinitialiser=<?php echo $reintialiser = true ?>'">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Jusqu'ici -->
                    </div>
                </div>
            </div>
        </div>
        <script>
            /*---------------------------------------- Graphique Histogramme/Courbe ----------------------------------*/
            // Récupération des données PHP encodées en JSON
            const listeStatHistogramme = <?php echo $listeStatHistogramme; ?>;

            const histogrammeLabels = Object.keys(listeStatHistogramme);
            const montantTotal = histogrammeLabels.map(mois => listeStatHistogramme[mois]['MontantTotal']); // Montant total par mois
            const nombreNotes  = histogrammeLabels.map(mois => listeStatHistogramme[mois]['NombreNotes']);  // Quantité de notes de frais par mois

            // Configuration des données pour le graphique histogramme
            const histogrammeData = {
                labels: histogrammeLabels,
                datasets: [
                    {
                        label: 'Montant total (€)',
                        data: montantTotal,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }
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
                                const nombreDeNotes = nombreNotes[index];

                                // Affichage lors du passage de la souris
                                return 'Montant total : ' + tooltipItem.raw + '€ | ' + nombreDeNotes + ' notes de frais';
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
