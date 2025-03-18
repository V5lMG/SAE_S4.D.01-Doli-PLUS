<?php
session_start();

require_once "services/NoteFraisService.php";

// Récupérer toutes les notes de frais
// $notesFrais;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Notes de Frais</title>
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
            <div class="container-fluid mt-3">

                <!-- Barre de filtres -->
                <form method="GET" action="liste_note_frais.php" class="bg-light p-3 rounded shadow-sm">
                    <div class="row g-3">
                        <div class="col-md-2 offset-md-1">
                            <input type="text" class="form-control" name="ref" placeholder="Référence">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="utilisateur" placeholder="Utilisateur">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" name="date_debut">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" name="date_fin">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="etat">
                                <option value="">État</option>
                                <option value="Validé">Validé</option>
                                <option value="En attente">En attente</option>
                                <option value="Rejeté">Rejeté</option>
                            </select>
                        </div>
                        <div class="col-md-1 d-flex gap-2">
                            <button type="submit" class="btn btn-primary" title="Rechercher">
                                <i class="fa fa-search"></i>
                            </button>
                            <button type="reset" class="btn btn-outline-secondary" title="Réinitialiser">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Tableau des notes de frais -->
                <div class="mt-4 table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                        <tr>
                            <th>Réf.</th>
                            <th>Utilisateur</th>
                            <th>Type</th>
                            <th>Date début</th>
                            <th>Date fin</th>
                            <th>Montant HT</th>
                            <th>Montant TVA</th>
                            <th>Montant TTC</th>
                            <th>État</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($notesFrais)): ?>
                            <?php foreach ($notesFrais as $note): ?>
                                <tr>
                                    <td><?= htmlspecialchars($note['ref']) ?></td>
                                    <td><?= htmlspecialchars($note['user']['name'] ?? 'Inconnu') ?></td>
                                    <td><?= htmlspecialchars($note['type']) ?></td>
                                    <td><?= htmlspecialchars($note['date_debut']) ?></td>
                                    <td><?= htmlspecialchars($note['date_fin']) ?></td>
                                    <td><?= number_format($note['montant_ht'], 2, ',', ' ') ?> €</td>
                                    <td><?= number_format($note['montant_tva'], 2, ',', ' ') ?> €</td>
                                    <td><?= number_format($note['montant_ttc'], 2, ',', ' ') ?> €</td>
                                    <td>
                                                <span class="badge bg-<?=
                                                ($note['etat'] === 'Validé') ? 'success' :
                                                    (($note['etat'] === 'Rejeté') ? 'danger' : 'warning')
                                                ?>">
                                                    <?= htmlspecialchars($note['etat']) ?>
                                                </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted">Aucune note de frais trouvée</td>
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
