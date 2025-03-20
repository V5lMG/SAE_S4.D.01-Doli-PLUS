<?php
// Démarrer la session si elle n'est pas encore démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Convertir le tableau en JSON
$listeStatJson = json_encode($listStat, true);

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
                                    <h3 class="mb-4">Diagramme de comparaison</h3>
                                    <!-- TODO Mettre le graphique Histogramme ici -->
                                    <canvas id="histogramme" width="400" height="200"></canvas>
                                    <div class="row justify-content-center mt-3">
                                        <div class="col-md-4">
                                            <input type="date" class="form-control" name="date_debut">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="date" class="form-control" name="date_fin">
                                        </div>
                                    </div>
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

            // TODO écrire les données brutes et tout le reste ici
            // Récupération des données PHP encodées en JSON
            // Création des données brutes directement en JavaScript
            const histogrammeLabels = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            const datasetMontant = [3000, 2500, 3200, 2800, 3500, 3100, 2900, 2600, 3400, 3300, 3100, 3600];  // Montant total par mois
            const datasetQuantite = [1500, 1200, 1800, 1400, 2000, 1700, 1300, 1600, 1900, 1800, 1600, 2100];  // Quantité de notes de frais par mois

            // Configuration des données pour le graphique histogramme
            const histogrammeData = {
                labels: histogrammeLabels,
                datasets: [
                    {
                        label: 'Montant total (€)',
                        data: datasetMontant,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Quantité de notes de frais',
                        data: datasetQuantite,
                        backgroundColor: 'rgba(255, 159, 64, 0.6)',
                        borderColor: 'rgba(255, 159, 64, 1)',
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

            /*--------------------------------------------- Diagramme Circulaire -------------------------------------*/
            // Récupération des données PHP encodées en JSON
            const listeStat = <?php echo $listeStatJson; ?>;

            // Extraction des labels (types de frais) et des données associées
            const labels = Object.keys(listeStat); // ["Frais kilométriques", "Repas", "Transport", "Autre"]
            const montantTotal = labels.map(type => listeStat[type]['MontantTotalType']);
            const quantite     = labels.map(type => listeStat[type]['Quantite']);

            // Configuration des données pour le diagramme
            const data = {
                labels: labels, // Type des notes de frais
                datasets: [
                    {
                        label: 'Montant total',
                        data: montantTotal, // Montant total de chaque type de note de frais
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
