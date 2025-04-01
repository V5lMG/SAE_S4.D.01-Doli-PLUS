<?php
// Démarrer la session si elle n'est pas encore démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$droit = $_SESSION['droit'] ?? 'rien';
if ($droit != 'admin' && $droit != 'note2frais') {
    header('location: index.php?controller=Accueil&action=index');
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
                        <form method="POST" action="index.php?controller=NoteFrais&action=indexListe" class="bg-light p-3 rounded shadow-sm">
                            <?php
                            // Récupération des filtres précédents si existants
                            $employe = isset($_POST['employe']) ? htmlspecialchars($_POST['employe']) : "";
                            $type = isset($_POST['type']) ? htmlspecialchars($_POST['type']) : "TOUS";
                            $reference = isset($_POST['reference']) ? htmlspecialchars($_POST['reference']) : "";
                            $date_debut = isset($_POST['date_debut']) ? htmlspecialchars($_POST['date_debut']) : "";
                            $date_fin = isset($_POST['date_fin']) ? htmlspecialchars($_POST['date_fin']) : "";
                            $etat = isset($_POST['etat']) ? htmlspecialchars($_POST['etat']) : "tous";
                            ?>

                            <div class="row g-3 align-items-center">
                                <div class="col-md-2 text-center d-flex flex-column align-items-start">
                                    <label for="employe" class="fw-bold">Employé :</label>
                                    <input type="text" class="form-control" id="employe" name="employe" value="<?= $employe ?>" placeholder="Ex : Dupond Pierre">
                                </div>

                                <div class="col-md-2 text-center d-flex flex-column align-items-start">
                                    <label for="type" class="fw-bold">Type de la note :</label>
                                    <select class="form-control" id="type" name="type">
                                        <option value="TOUS" <?= ($type == "TOUS") ? "selected" : "" ?>>Tous</option>
                                        <option value="Frais kilométriques" <?= ($type == "Frais kilométriques") ? "selected" : "" ?>>Frais kilométriques</option>
                                        <option value="Autre" <?= ($type == "Autre") ? "selected" : "" ?>>Autre</option>
                                        <option value="Repas" <?= ($type == "Repas") ? "selected" : "" ?>>Repas</option>
                                        <option value="Transport" <?= ($type == "Transport") ? "selected" : "" ?>>Transport</option>
                                    </select>
                                </div>

                                <div class="col-md-2 text-center d-flex flex-column align-items-start">
                                    <label for="reference" class="fw-bold">Référence de la note :</label>
                                    <input type="text" class="form-control" id="reference" name="reference" value="<?= $reference ?>" placeholder="Ex : PROV1">
                                </div>

                                <div class="col-md-2 text-center">
                                    <div class="d-flex flex-column align-items-start">
                                        <label for="date_debut" class="fw-bold">Du :</label>
                                        <input type="date" id="date_debut" class="form-control mb-2" name="date_debut" value="<?= $date_debut ?>">
                                        <label for="date_fin" class="fw-bold">Au :</label>
                                        <input type="date" id="date_fin" class="form-control" name="date_fin" value="<?= $date_fin ?>">
                                    </div>
                                </div>

                                <div class="col-md-2 text-center d-flex flex-column align-items-start">
                                    <label for="etat" class="fw-bold">État de la note :</label>
                                    <select class="form-select" name="etat" id="etat">
                                        <option value="tous" <?= ($etat == "tous") ? "selected" : "" ?>>Tous</option>
                                        <option value="Brouillon" <?= ($etat == "Brouillon") ? "selected" : "" ?>>Brouillon</option>
                                        <option value="Validé" <?= ($etat == "Validé") ? "selected" : "" ?>>Validé</option>
                                        <option value="Annulé" <?= ($etat == "Annulé") ? "selected" : "" ?>>Annulé</option>
                                        <option value="Approuvé" <?= ($etat == "Approuvé") ? "selected" : "" ?>>Approuvé</option>
                                        <option value="Payé" <?= ($etat == "Payé") ? "selected" : "" ?>>Payé</option>
                                        <option value="Refusé" <?= ($etat == "Refusé") ? "selected" : "" ?>>Refusé</option>
                                    </select>
                                </div>

                                <div class="col-md-2 d-flex align-self-center bouton-recherche">
                                    <button type="submit" class="btn btn-primary" title="Rechercher">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary ms-2" title="Réinitialiser" onclick="resetFilters()">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Affichage d'un message d'erreur si aucun filtre n'est sélectionné -->
                            <?php if (empty($employe) && $type == 'TOUS' && empty($reference) && empty($date_debut) && empty($date_fin) && $etat == 'tous'): ?>
                                <div class="alert alert-warning mt-3" role="alert">
                                    Sélectionnez au moins un filtre pour afficher les résultats.
                                    <a href="index.php?controller=NoteFrais&action=indexListe&afficherTous=1">
                                        [Afficher toutes les notes de frais]
                                    </a>
                                </div>
                            <?php endif; ?>
                        </form>

                        <script>
                            // Fonction pour réinitialiser les filtres
                            function resetFilters() {
                                document.getElementById("employe").value = "";
                                document.getElementById("type").value = "TOUS";
                                document.getElementById("reference").value = "";
                                document.getElementById("date_debut").value = "";
                                document.getElementById("date_fin").value = "";
                                document.getElementById("etat").value = "tous";

                                // Soumettre le formulaire après la réinitialisation
                                document.querySelector("form").submit();
                            }
                        </script>

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
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    if (!empty($listeNoteFrais)): ?>
                                    <?php foreach ($listeNoteFrais as $note): ?>
                                        <tr data-bs-toggle="collapse" data-bs-target="#collapse-<?= $note['ref'] ?>" aria-expanded="false" aria-controls="collapse-<?= $note['ref'] ?>">
                                            <td><span class="fw-bold text-decoration-underline text-primary"><?= htmlspecialchars($note['ref']) ?></span></td>
                                            <td><?= htmlspecialchars($note['user_author_infos']) ?></td>
                                            <td><?= htmlspecialchars($note['date_debut']) ?></td>
                                            <td><?= htmlspecialchars($note['date_fin']) ?></td>
                                            <td class="text-end"><?= number_format($note['montant_ht'], 2, ',', ' ') ?> €</td>
                                            <td class="text-end"><?= number_format($note['montant_tva'], 2, ',', ' ') ?> €</td>
                                            <td class="text-end"><?= number_format($note['montant_ttc'], 2, ',', ' ') ?> €</td>
                                            <td><?= htmlspecialchars($note['etat']) ?></td>
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
                                                            <td class="text-end"><?= htmlspecialchars($line['tva']) ?></td> <!-- Tva -->
                                                            <td class="text-end"><?= htmlspecialchars($line['prix_unitaire_ht']) ?></td> <!-- Prix Unitaire HT -->
                                                            <td class="text-end"><?= htmlspecialchars($line['prix_unitaire_ttc']) ?></td> <!-- Prix unitaire TTC -->
                                                            <td class="text-end"><?= htmlspecialchars($line['quantite']) ?></td> <!-- Quantité -->
                                                            <td class="text-end"><?= htmlspecialchars($line['montant_ht']) ?></td> <!-- Montant HT (calculé avec quantité) -->
                                                            <td class="text-end"><?= htmlspecialchars($line['montant_ttc']) ?></td> <!-- Montant TTC (calculé avec quantité) -->
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                        <td colspan="2" class="text-center">
                                            Nombre de notes de frais : <?= htmlspecialchars($totaux['nombre_note']) ?>
                                        </td>
                                        <td colspan="2"></td>
                                        <td class="text-center">
                                            Montant HT total : <?= number_format($totaux['montant_ht_total'], 2, ',', ' ') ?> €
                                        </td>
                                        <td class="text-center">
                                            Montant TVA total : <?= number_format($totaux['montant_tva_total'], 2, ',', ' ') ?> €
                                        </td>
                                        <td class="text-center">
                                            Montant TTC total : <?= number_format($totaux['montant_ttc_total'], 2, ',', ' ') ?> €
                                        </td>
                                        <td></td>
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
