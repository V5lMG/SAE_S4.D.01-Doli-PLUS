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
                    <!-- Première ligne -->
                    <div class="row g-3 align-items-center">

                        <!-- Employé -->
                        <div class="col-md-2 text-center d-flex flex-column align-items-start">
                            <label for="employe" class="fw-bold">Employé :</label>
                            <input type="text" class="form-control" id="employe" name="employe" placeholder="Ex : Dupond Pierre">
                        </div>

                        <!-- Type -->
                        <div class="col-md-2 text-center d-flex flex-column align-items-start">
                            <label for="type" class="fw-bold">Type de la note :</label>
                            <select class="form-control" id="type" name="type">
                                <option value="TOUS">Tous</option>
                                <option value="EX_KME"  > Frais kilométrique</option>
                                <option value="Tf_OTHER"> Autre</option>
                                <option value="TF_LUNCH"> Repas</option>
                                <option value="TF_TRIP" > Transport</option>
                            </select>
                        </div>

                        <!-- Référence -->
                        <div class="col-md-2 text-center d-flex flex-column align-items-start">
                            <label for="reference" class="fw-bold">Référence de la note :</label>
                            <input type="text" class="form-control" id="reference" name="reference" placeholder="Ex : PROV1">
                        </div>

                        <!-- Date -->
                        <div class="col-md-2 text-center">
                            <div class="d-flex flex-column align-items-start">
                                <label for="date_debut" class="fw-bold">Du :</label>
                                <input type="date" id="date_debut" class="form-control mb-2" name="date_debut">

                                <label for="date_fin" class="fw-bold">Au :</label>
                                <input type="date" id="date_fin" class="form-control" name="date_fin">
                            </div>
                        </div>

                        <!-- État -->
                        <div class="col-md-2 text-center d-flex flex-column align-items-start">
                            <label for="etat" class="fw-bold">État de la note :</label>
                            <select class="form-select" name="etat" id="etat">
                                <option value="tous">Tous</option>
                                <option value="brouillon">Brouillon</option>
                                <option value="valider">Validé</option>
                                <option value="annuler">Annulé</option>
                                <option value="approuver">Approuvé</option>
                                <option value="payer">Payé</option>
                                <option value="refuser">Refusé</option>
                            </select>
                        </div>

                        <!-- Boutons -->
                        <div class="col-md-2 d-flex align-self-center bouton-recherche">
                            <button type="submit" class="btn btn-primary" title="Rechercher">
                                <i class="fa fa-search"></i>
                            </button>
                            <button type="reset" class="btn btn-outline-secondary ms-2" title="Réinitialiser">
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
                            <th>Employé</th>
                            <th>Date début</th>
                            <th>Date fin</th>
                            <th>Montant HT</th>
                            <th>Montant TVA</th>
                            <th>Montant TTC</th>
                            <th>État</th>
                            <th>Déjà réglé</th>
                            <th>Montant réclamé</th>
                            <th>Reste à payer</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($listeNoteFrais)): ?>
                            <?php foreach ($listeNoteFrais as $note): ?>
                                <tr data-bs-toggle="collapse" data-bs-target="#collapse-<?= $note['ref'] ?>" aria-expanded="false" aria-controls="collapse-<?= $note['ref'] ?>">
                                    <td><?= htmlspecialchars($note['ref']) ?></td>
                                    <td><?= htmlspecialchars($note['user_author_infos']) ?></td>
                                    <td><?= htmlspecialchars($note['date_debut']) ?></td>
                                    <td><?= htmlspecialchars($note['date_fin']) ?></td>
                                    <td><?= number_format($note['montant_ht'], 2, ',', ' ') ?> €</td>
                                    <td><?= number_format($note['montant_tva'], 2, ',', ' ') ?> €</td>
                                    <td><?= number_format($note['montant_ttc'], 2, ',', ' ') ?> €</td>
                                    <td><?= htmlspecialchars($note['etat']) ?></td>
                                    <td><?= number_format($note['montant_deja_regle'], 2, ',', ' ') ?> €</td>
                                    <td><?= number_format($note['montant_reclame'], 2, ',', ' ') ?> €</td>
                                    <td><?= number_format($note['reste_a_payer'], 2, ',', ' ') ?> €</td>
                                </tr>
                                <!-- Sous-tableau pour afficher les lignes de la note de frais -->
                                <tr class="collapse" id="collapse-<?= $note['ref'] ?>">
                                    <td colspan="11">
                                        <table class="table table-bordered mt-2">
                                            <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>TVA</th>
                                                <th>Prix unitaire HT</th>
                                                <th>Prix unitaire TTC</th>
                                                <th>Quantité</th>
                                                <th>Montant HT</th>
                                                <th>Montant TTC</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($note['lines'] as $line): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($line['date']) ?></td> <!-- Date -->
                                                    <td><?= htmlspecialchars($line['type']) ?></td> <!-- Type -->
                                                    <td><?= htmlspecialchars($line['tva']) ?></td> <!-- Tva -->
                                                    <td><?= htmlspecialchars($line['prix_unitaire_ht']) ?></td> <!-- Prix Unitaire HT -->
                                                    <td><?= htmlspecialchars($line['prix_unitaire_ttc']) ?></td> <!-- Prix unitaire TTC -->
                                                    <td><?= htmlspecialchars($line['quantite']) ?></td> <!-- Quantité -->
                                                    <td><?= htmlspecialchars($line['montant_ht']) ?></td> <!-- Montant HT (calculé avec quantité) -->
                                                    <td><?= htmlspecialchars($line['montant_ttc']) ?></td> <!-- Montant TTC (calculé avec quantité) -->
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center text-muted">Aucune note de frais trouvée</td>
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
