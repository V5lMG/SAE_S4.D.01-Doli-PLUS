<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Statistiques des notes de frais</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="static/css/styles.css">
        <link rel="stylesheet" href="static/css/sidebar.css">
    </head>
    <body>

        <!-- Barre latérale de la page  -->
        <?php include '../vuErwan/sidebar.php'; ?>

        <!-- Contenu principal -->
        <style>
            .chart-container {
                width: 100%;
                max-width: 500px; /* Taille maximale du conteneur */
                margin: auto;
                padding: 20px;
                height: 400px; /* Hauteur fixe du conteneur */
                position: relative;
            }

            .chart-title {
                text-align: center;
                font-size: 18px;
                margin-bottom: 20px;
            }

            canvas {
                display: block;
                width: 100%; /* Garde le diagramme responsive */
                height: 400px; /* Fixe la hauteur du canvas */
            }

            .stats {
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                margin-top: 20px;
                text-align: center;
            }

            .main-content {
                padding: 20px;
            }
        </style>

        <div class="main-content">
            <section class="stats">
                <h2>Statistiques</h2>
                <div class="chart-container">
                    <h2 class="chart-title">Répartition des Revenus par Catégorie</h2>
                    <canvas id="myPieChart"></canvas>
                </div>
            </section>
        </div>

        <script>
            // Configuration des données pour le diagramme
            const data = {
                labels: ['Services', 'Produits', 'Abonnements', 'Publicité'],
                datasets: [{
                    label: 'Revenus',
                    data: [5000, 3000, 2000, 1000], // Données factices
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
                }]
            };

            // Configuration des options du diagramme
            const options = {
                responsive: true,
                maintainAspectRatio: false, // Ajuste le diagramme selon le conteneur
                plugins: {
                    legend: {
                        position: 'bottom', // Place la légende en bas
                    }
                }
            };

            // Initialisation du diagramme
            const ctx = document.getElementById('myPieChart').getContext('2d');
            const myPieChart = new Chart(ctx, {
                type: 'pie',
                data: data,
                options: options
            });
        </script>
    </body>
</html>
