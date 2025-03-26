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
        <title>Factures</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="static/css/styles.css">
        <link rel="stylesheet" href="static/css/sidebar.css">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">

                <!-- Importer la sidebar et son css -->
                <?php include 'static/sidebar.php'; ?>

                <!-- Contenu principal -->
                <div class="contenu-principal">
                    <div class="container-fluid mt-3">

                        <!-- header de la page -->
                        <div class="bg-light p-3 rounded shadow-sm">
                            <div class="row align-items-center">
                                <!-- Colonne du bouton retour -->
                                <div class="col-auto">
                                    <form method="POST" action="index.php?controller=Fournisseur&action=index">
                                        <button type="submit" class="btn btn-danger" title="Retour">
                                            <i class="fa-solid fa-arrow-left"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Colonne du titre (centrée sur mobile, alignée à droite sur grand écran) -->
                                <div class="col text-center text-md-center">
                                    <h2 class="mb-0 titre_page">
                                        <i class="fas fa-file-invoice"></i> Factures du fournisseur : <?= htmlspecialchars($nomFournisseur) ?> (Ref: <?= htmlspecialchars($refFournisseur) ?>)
                                    </h2>
                                </div>
                            </div>
                        </div>

                        <!-- Tableau des factures fournisseur -->
                        <div class="mt-4 table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                <tr>
                                    <th>Réf</th>
                                    <th>Date facture</th>
                                    <th>Date échéance</th>
                                    <th>Condition de règlement</th>
                                    <th>Mode de règlement</th>
                                    <th>Montant HT</th>
                                    <th>Etat</th>
                                    <th>Fichier join</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($factures)) :
                                    foreach ($factures as $facture): ?>
                                            <tr>
                                               <td><?= htmlspecialchars($facture['ref'] ?? '') ?></td>
                                               <td><?= htmlspecialchars($facture['date_facture'] ?? '') ?></td>
                                               <td><?= htmlspecialchars($facture['date_echeance'] ?? '') ?></td>
                                               <td><?= htmlspecialchars($facture['cond_reglement'] ?? '') ?></td>
                                               <td><?= htmlspecialchars($facture['mode_reglement'] ?? '') ?></td>
                                               <td><?= htmlspecialchars($facture['montant_ht'] ?? '') ?></td>
                                               <td><?= htmlspecialchars($facture['etat'] ?? '') ?></td>
                                               <td><?php echo "test"?></td>
                                            </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Aucune facture trouvée</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>