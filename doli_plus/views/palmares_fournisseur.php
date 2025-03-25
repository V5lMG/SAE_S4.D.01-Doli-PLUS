<?php
// Démarrer la session si elle n'est pas encore démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Palmarès fournisseur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="static/css/styles.css">
    <link rel="stylesheet" href="static/css/sidebar.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">

        <!-- Importer la sidebar et son CSS -->
        <?php include 'static/sidebar.php'; ?>

        <!-- Contenu principal -->
        <div class="contenu-principal">
            <div class="container-fluid">

                <!-- Titre -->
                <div class="row">
                    <div class="col text-center">
                        <h1 class="mb-4">Fournisseurs - Palmarès</h1>
                    </div>
                </div>
                <!-- Liste palmarès et Diagramme sectoriel-->
                <div class="row justify-content-center">
                    <div class="col-12 col-md-6 text-center">
                        <div class="p-4 border rounded shadow-sm bg-light">
                            <table class="table table-bordered mt-2">
                                <thead class="table-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Quantité de facture</th>
                                    <th>Montant HT</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($listePalmares as $nomFournisseur  => $details): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($nomFournisseur) ?></td>             <!-- Nom du fournisseur -->
                                        <td><?= htmlspecialchars($details['nombre_factures']) ?></td> <!-- Quantité de facture -->
                                        <td><?= htmlspecialchars($details['total_ht']) . ' €' ?></td> <!-- Montant des factures -->
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Diagramme sectoriel palmarès -->
                    <div class="col-12 col-md-6 text-center">
                        <div class="p-4 border rounded shadow-sm bg-light">
                            <canvas id="myBarChart"></canvas>
                        </div>
                    </div>
                </div>
                <br>
                <!-- Filtre -->
                <div class="row justify-content-center">
                    <form id="formPalmares" method="POST" action="<?= htmlspecialchars('index.php?controller=Fournisseur&action=indexPalmares'); ?>">
                    <div class="col-12 col-md-6 offset-md-6 text-center">
                        <div class="p-4 border rounded shadow-sm bg-light">
                            <div class="row">
                                <!-- Date début-->
                                <div class="col-md-3 col-12">
                                    <label for="date_debut">Date début :</label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut">
                                </div>
                                <!-- Date fin -->
                                <div class="col-md-3 col-12">
                                    <label for="date_fin">Date fin :</label>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin">
                                </div>
                                <!-- Quantité de fournisseur sélectionné -->
                                <div class="col-md-4 col-12">
                                    <input type="radio" class="form-check-input" name="top" id="top10" value="10">
                                    <label for="top10">Top 10</label><br>

                                    <input type="radio" class="form-check-input" name="top" id="top20" value="20">
                                    <label for="top20">Top 20</label><br>

                                    <input type="radio" class="form-check-input" name="top" id="top30" value="30">
                                    <label for="top30">Top 30</label><br>
                                </div>
                                <div class="col-md-1">
                                    <label for="invisible"></label> <!-- aligne le bouton de recherche avec les champs "date"-->
                                    <button type="submit" class="btn btn-primary" title="Rechercher">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                                <div class="col-md-1">
                                    <label for="invisible"></label> <!-- aligne le bouton de recherche avec les champs "date"-->
                                    <button type="button" class="btn btn-outline-secondary ms-2" title="Réinitialiser" onclick="resetFilters()">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <!-- Jusqu'ici -->

            </div>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configuration des données pour le diagramme en bâtons
    const data = {
        labels: ['Jérome', 'Vincent', 'George', 'Titouan'],
        datasets: [{
            label: 'Revenus',
            data: [5000, 3000, 2000, 1000], // Données factices
            backgroundColor: [
                'rgb(255,215,0)',
                'rgb(192,192,192)',
                'rgb(205,127,50)',
                'rgba(75, 192, 192, 0.6)'
            ],
            borderColor: [
                'rgb(181,145,0)',
                'rgb(142,142,142)',
                'rgb(163,103,39)',
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
                position: 'top', // Place la légende en haut
            }
        }
    };

    // Initialisation du diagramme
    const ctx = document.getElementById('myBarChart').getContext('2d');
    const myBarChart = new Chart(ctx, {
        type: 'pie',
        data: data,
        options: options
    });
</script>
</body>
</html>
