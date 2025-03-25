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
        <title>Fournisseurs</title>
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

                        <!-- Barre de filtres -->
                        <form method="POST" action="index.php?controller=Fournisseur&action=indexListe" class="bg-light p-3 rounded shadow-sm"><?php
                            // Récupération des filtres précédents si existants
                            $nom = isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : "";
                            $numTel = isset($_POST['numTel']) ? htmlspecialchars($_POST['numTel']) : "";
                            $adresse = isset($_POST['adresse']) ? htmlspecialchars($_POST['adresse']) : "";
                            $codePostal = isset($_POST['codePostal']) ? htmlspecialchars($_POST['codePostal']) : "";
                            ?>

                            <div class="row g-3 align-items-center">
                                <div class="col-md-2 text-center d-flex flex-column align-items-start">
                                    <label for="nom" class="fw-bold">Nom :</label>
                                    <input type="text" class="form-control" id="nom" name="nom" value="<?= $nom ?>" placeholder="Ex : Mathias">
                                </div>

                                <div class="col-md-3 text-center d-flex flex-column align-items-start">
                                    <label for="numTel" class="fw-bold">Numéro de téléphone :</label>
                                    <input type="text" class="form-control" id="numTel" name="numTel" value="<?= $numTel ?>" placeholder="Ex : 05 65 18 54 37">
                                </div>

                                <div class="col-md-3 text-center d-flex flex-column align-items-start">
                                    <label for="adresse" class="fw-bold">Adresse :</label>
                                    <input type="text" class="form-control" id="adresse" name="adresse" value="<?= $adresse ?>" placeholder="Ex : Buffières, Belmont sur Rance">
                                </div>

                                <div class="col-md-2 text-center d-flex flex-column align-items-start">
                                    <label for="codePostal" class="fw-bold">Code Postal :</label>
                                    <input type="text" class="form-control" id="codePostal" name="codePostal" value="<?= $codePostal ?>" placeholder="Ex : 12000">
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
                            <?php if (empty($nom) && empty($numTel) && empty($adresse) && empty($codePostal)): ?>
                                <div class="alert alert-warning mt-3" role="alert">
                                    Sélectionnez au moins un filtre pour afficher les résultats.
                                </div>
                            <?php endif; ?>
                        </form>
                        <script>
                            // Fonction pour réinitialiser les filtres
                            function resetFilters() {
                                document.getElementById("nom").value = "";
                                document.getElementById("numTel").value = "";
                                document.getElementById("adresse").value = "";
                                document.getElementById("codePostal").value = "";

                                // Soumettre le formulaire après la réinitialisation
                                document.querySelector("form").submit();
                            }
                        </script>
                        <!-- Tableau des notes de frais -->
                        <div class="mt-4 table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Numéro de téléphone</th>
                                    <th>Adresse</th>
                                    <th>Code postal</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($listeFournisseur)): ?>
                                <?php foreach ($listeFournisseur as $fournisseur): ?>
                                <tr data-bs-toggle="collapse" data-bs-target="#collapse-<?= $fournisseur['nom'] ?>" aria-expanded="false" aria-controls="collapse-<?= $fournisseur['nom'] ?>">
                                    <td><span class="fw-bold text-decoration-underline text-primary"><?= htmlspecialchars($fournisseur['nom']) ?></span></td>
                                    <td><?= htmlspecialchars($fournisseur['numTel']) ?></td>
                                    <td><?= htmlspecialchars($fournisseur['adresse']) ?></td>
                                    <td><?= htmlspecialchars($fournisseur['codePostal']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <!-- Sous-tableau pour afficher les factures fournisseur -->
                                <?php else: ?>
                                <tr>
                                    <td colspan="11" class="text-center text-muted">Aucun fournisseur trouvé</td>
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