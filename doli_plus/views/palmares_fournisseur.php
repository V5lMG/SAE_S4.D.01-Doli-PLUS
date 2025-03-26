<?php
// Démarrer la session si elle n'est pas encore démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tableau pour les labels et les montants HT à passer au JavaScript
$labels = [];
$montantsHT = [];
$backgroundColors = [];
$borderColors = [];

// Couleurs fixes pour les 3 premières positions
$firstColors =  ['gold', 'silver', '#cd7f32']; // Or, Argent, Bronze
$firstBorders = ['rgb(181,145,0)', 'rgb(142,142,142)', 'rgb(163,103,39)']; // Bordures correspondantes

// Autres couleurs fixes
$otherColors = [
    'rgba(75,  192, 192, 0.6)', 'rgba(54,  162, 235, 0.6)', 'rgba(255, 99,  132, 0.6)',
    'rgba(153, 102, 255, 0.6)', 'rgba(255, 206, 86,  0.6)', 'rgba(255, 159, 64,  0.6)',
    'rgba(255, 99,  132, 0.6)', 'rgba(139, 69,  19,  0.6)', 'rgba(0,   255, 0,   0.6)',
    'rgba(255, 105, 180, 0.6)', 'rgba(173, 216, 230, 0.6)', 'rgba(255, 165, 0,   0.6)',
    'rgba(255, 20,  147, 0.6)', 'rgba(123, 104, 238, 0.6)', 'rgba(0,   128, 128, 0.6)',
    'rgba(186, 85,  211, 0.6)', 'rgba(64,  224, 208, 0.6)', 'rgba(102, 205, 170, 0.6)',
    'rgba(255, 69,  0,   0.6)', 'rgba(0,   0,   255, 0.6)', 'rgba(0,   255, 255, 0.6)',
    'rgba(0,   255, 0,   0.6)', 'rgba(139, 0,   139, 0.6)', 'rgba(0,   0,   139, 0.6)',
    'rgba(128, 128, 0,   0.6)', 'rgba(34,  139, 34,  0.6)', 'rgba(255, 255, 0,   0.6)'
];

$otherBorders = [
    'rgba(75,  192, 192, 1)', 'rgba(54,  162, 235, 1)', 'rgba(255, 99,  132, 1)',
    'rgba(153, 102, 255, 1)', 'rgba(255, 206, 86,  1)', 'rgba(255, 159, 64,  1)',
    'rgba(255, 99,  132, 1)', 'rgba(139, 69,  19,  1)', 'rgba(0,   255, 0,   1)',
    'rgba(255, 105, 180, 1)', 'rgba(173, 216, 230, 1)', 'rgba(255, 165, 0,   1)',
    'rgba(255, 20,  147, 1)', 'rgba(123, 104, 238, 1)', 'rgba(0,   128, 128, 1)',
    'rgba(186, 85,  211, 1)', 'rgba(64,  224, 208, 1)', 'rgba(102, 205, 170, 1)',
    'rgba(255, 69,  0,   1)', 'rgba(0,   0,   255, 1)', 'rgba(0,   255, 255, 1)',
    'rgba(0,   255, 0,   1)', 'rgba(139, 0,   139, 1)', 'rgba(0,   0,   139, 1)',
    'rgba(128, 128, 0,   1)', 'rgba(34,  139, 34,  1)', 'rgba(255, 255, 0,   1)'
];

// Initialisation des tableaux finaux
$backgroundColors = [];
$borderColors = [];

// Ajouter les couleurs fixes aux tableaux
for ($i = 0; $i < 3; $i++) {
    $backgroundColors[] = $firstColors[$i];
    $borderColors[] = $firstBorders[$i];
}

// Limite du nombre de couleurs restantes à ajouter (total de 30 couleurs)
$totalColors = $top - 3; // Trois couleurs sont déjà ajoutées

// Préparer les données pour JavaScript
$compteur = 0;
foreach ($listePalmares as $nomFournisseur => $details) {
    if ($compteur < $top) {
        $labels[] = htmlspecialchars($nomFournisseur);  // Nom du fournisseur
        $montantsHT[] = floatval($details['total_ht']);  // Montant HT
        $backgroundColors[] = $otherColors[$compteur];
        $borderColors[] = $otherBorders[$compteur];
        $compteur++;
    }
}
$compteur = 0;
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
                                <?php foreach ($listePalmares as $nomFournisseur  => $details):
                                      if ($compteur < $top) {
                                             $compteur++?>
                                    <tr  class="fournisseur<?=$compteur?>" >
                                        <td><?= htmlspecialchars($nomFournisseur) ?></td>             <!-- Nom du fournisseur -->
                                        <td><?= htmlspecialchars($details['nombre_factures']) ?></td> <!-- Quantité de facture -->
                                        <td><?= htmlspecialchars($details['total_ht']) . ' €' ?></td> <!-- Montant des factures -->
                                    </tr>
                                <?php }
                                      endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Diagramme sectoriel palmarès -->
                    <div class="col-12 col-md-6 text-center">
                        <div class="p-4 border rounded shadow-sm bg-light">
                            <?php if (isset($date_debut) && isset ($date_fin) && !empty($date_debut) && !empty($date_fin)) { ?>
                                <h5>Diagramme sectoriel des notes de frais entre <?= htmlspecialchars((new DateTime($date_debut))->format('d/m/Y')); ?> et <?= htmlspecialchars((new DateTime($date_fin))->format('d/m/Y')); ?> compris </h5>
                            <?php } else { ?>
                                <h5>Diagramme sectoriel de la totalité des notes de frais</h5>
                            <?php } ?>
                            <canvas id="diagramme_sectoriel" width="400" height="400" ></canvas>
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
                                    <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= isset($date_debut) ? htmlspecialchars($date_debut) : '' ?>">
                                </div>
                                <!-- Date fin -->
                                <div class="col-md-3 col-12">
                                    <label for="date_fin">Date fin :</label>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= isset($date_fin) ? htmlspecialchars($date_fin) : '' ?>">
                                </div>
                                <!-- Quantité de fournisseur sélectionné -->
                                <div class="col-md-4 col-8">
                                    <input type="radio" class="form-check-input" name="top" id="top10" value=10 <?= $top === 10 ? 'checked' : '' ?>>
                                    <label for="top10">Top 10</label><br>

                                    <input type="radio" class="form-check-input" name="top" id="top20" value="20" <?= $top === 20 ? 'checked' : '' ?>>
                                    <label for="top20">Top 20</label><br>

                                    <input type="radio" class="form-check-input" name="top" id="top30" value="30" <?= $top === 30 ? 'checked' : '' ?>>
                                    <label for="top30">Top 30</label><br>
                                </div>
                                <div class="col-md-1 col-1">
                                    <label for="invisible"></label> <!-- aligne le bouton de recherche avec les champs "date"-->
                                    <button type="submit" class="btn btn-primary" title="Rechercher">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                                <div class="col-md-1 col-1">
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
    /*--------------------------------- Fonction pour réinitialiser les filtres  -------------------------------------*/
    function resetFilters() {
        let form = document.getElementById("formPalmares");

        document.getElementById("top10").value = false;
        document.getElementById("top20").value = false;
        document.getElementById("top30").value = false;
        document.getElementById("date_debut").value = "";
        document.getElementById("date_fin").value = "";

        // Soumettre le formulaire après la réinitialisation
        form.submit();
    }

    /*--------------------------------------------- Diagramme Sectoriel ----------------------------------------------*/

    // Récupérer les données PHP dans des variables JavaScript
    const labels = <?= json_encode($labels) ?>;
    const montantsHT = <?= json_encode($montantsHT) ?>;
    const backgroundColors = <?= json_encode($backgroundColors) ?>;
    const borderColors = <?= json_encode($borderColors) ?>;

    // Initialiser les données du diagramme sectoriel
    const data = {
        labels: labels,
        datasets: [{
            label: 'Revenus',
            data: montantsHT,
            backgroundColor: backgroundColors,
            borderColor: borderColors,
            borderWidth: 1
        }]
    };

    // Configuration des options du diagramme
    const options = {
        responsive: false,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right',
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        const value = tooltipItem.raw;
                        return `${tooltipItem.label}: ${value} €`;
                    }
                }
            }
        }
    };

    // Initialisation du diagramme avec les données dynamiques
    const ctx = document.getElementById('diagramme_sectoriel').getContext('2d');
    const myBarChart = new Chart(ctx, {
        type: 'pie',
        data: data,
        options: options
    });
</script>
</body>
</html>
