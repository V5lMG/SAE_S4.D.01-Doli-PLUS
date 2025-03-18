<?php
session_start();

$listeStat;

// Convertir le tableau en JSON
$listeStatJson = json_encode($listeStat);
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

            <!-- Importer la sidebar et son css -->
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
                            <div class="p-4 border rounded shadow-sm bg-light">
                                <canvas id="diagramme_sectoriel" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- Jusqu'ici -->
                </div>
            </div>
        </div>
    </div>
        <script>
            // Récupération des données PHP encodées en JSON
            const listeStat = <?php echo $listeStatJson; ?>;

            // Extraction des labels (types de frais) et des données associées
            const labels = Object.keys(listeStat); // ["ExpLabelKm", "Lunch", "Transportation", "Other"]
            const montantTotal = labels.map(type => listeStat[type]['MontantTotalType']); // [45, 117.05, 85.53, 40]
            const quantite = labels.map(type => listeStat[type]['Quantite']); // [2, 2, 2, 1]

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
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    },
                    {
                        label: 'Nombre de notes de frais',
                        data: quantite,        // Nombre de notes de frais
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }
                ]
            };

            // Configuration des options du diagramme
            const options = {
                responsive: true,
                maintainAspectRatio: false, // Ajuste le diagramme selon le conteneur
                plugins: {
                    legend: {
                        position: 'right', // Place la légende en bas
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
