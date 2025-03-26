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
                                    <th>État</th>
                                    <th>Fichier joint</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($factures)): ?>
                                    <?php foreach ($factures as $facture): ?>
                                        <tr data-bs-toggle="collapse" data-bs-target="#collapse-<?= $facture['ref'] ?>" aria-expanded="false" aria-controls="collapse-<?= $facture['ref'] ?>">
                                            <td><span class="fw-bold text-decoration-underline text-primary"><?= htmlspecialchars($facture['ref'] ?? '') ?></span></td>
                                            <td><?= htmlspecialchars($facture['date_facture']) ?></td>
                                            <td><?= htmlspecialchars($facture['date_echeance']) ?></td>
                                            <td><?= htmlspecialchars($facture['cond_reglement']) ?></td>
                                            <td><?= htmlspecialchars($facture['mode_reglement']) ?></td>
                                            <td><?= htmlspecialchars($facture['montant_ht']) ?></td>
                                            <td><?= htmlspecialchars($facture['etat']) ?></td>
                                            <td><?php echo "test" ?></td>
                                        </tr>

                                        <!-- Sous-tableau pour afficher les lignes de la facture -->
                                        <tr class="collapse" id="collapse-<?= $facture['ref'] ?>">
                                            <td colspan="8">
                                                <table class="table table-bordered mt-2">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Description</th>
                                                            <th>Réf. produit fournisseur</th>
                                                            <th>TVA</th>
                                                            <th>Prix Unitaire HT</th>
                                                            <th>Prix Unitaire TTC</th>
                                                            <th>Quantité</th>
                                                            <th>Réduction</th>
                                                            <th>Total HT</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (!empty($facture['lignes'])):
                                                            foreach ($facture['lignes'] as $ligne):?>
                                                                <tr>
                                                                    <td><?= htmlspecialchars($ligne['description']) ?></td>
                                                                    <td><?= htmlspecialchars($ligne['ref']) ?></td>
                                                                    <td><?= htmlspecialchars($ligne['tva']) ?> %</td>
                                                                    <td><?= htmlspecialchars($ligne['prix_unitaire_ht']) ?> €</td>
                                                                    <td><?= htmlspecialchars($ligne['prix_unitaire_ttc']) ?> €</td>
                                                                    <td><?= htmlspecialchars($ligne['quantite']) ?></td>
                                                                    <td><?= htmlspecialchars($ligne['reduction']) ?> %</td>
                                                                    <td><?= htmlspecialchars($ligne['total_ht']) ?> €</td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="8" class="text-center text-muted">Aucune ligne de facture</td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </td>
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

                        <!-- JavaScript pour activer le collapse Bootstrap -->
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                let collapsibleRows = document.querySelectorAll("[data-bs-toggle='collapse']");
                                collapsibleRows.forEach(row => {
                                    row.addEventListener("click", function () {
                                        let target = this.getAttribute("data-bs-target");
                                        let element = document.querySelector(target);
                                        if (element) {
                                            element.classList.toggle("show");
                                        }
                                    });
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>