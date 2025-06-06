<?php
// Démarrer la session si elle n'est pas encore démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$droit = $_SESSION['droit'] ?? 'rien';
if ($droit != 'admin' && $droit != 'facture') {
    header('location: index.php?controller=Accueil&action=index');
}

// Récupération des filtres précédents si existants ou dans la session
$nom = $_POST['nom'] ?? $_SESSION['filters']['nom'] ?? '';
$numTel = $_POST['numTel'] ?? $_SESSION['filters']['numTel'] ?? '';
$adresse = $_POST['adresse'] ?? $_SESSION['filters']['adresse'] ?? '';
$codePostal = $_POST['codePostal'] ?? $_SESSION['filters']['codePostal'] ?? '';

// Sécuriser les valeurs pour éviter les attaques XSS
$nom = htmlspecialchars($nom);
$numTel = htmlspecialchars($numTel);
$adresse = htmlspecialchars($adresse);
$codePostal = htmlspecialchars($codePostal);
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
                        <form method="POST" action="index.php?controller=Fournisseur&action=indexListe" class="bg-light p-3 rounded shadow-sm">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-2 text-center d-flex flex-column align-items-start">
                                    <label for="nom" class="fw-bold">Nom :</label>
                                    <input type="text" class="form-control" id="nom" name="nom" value="<?= $nom ?>" placeholder="Ex : Edf">
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

                        <!-- Tableau des fournisseurs -->
                        <div class="mt-4 table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                <tr>
                                    <th class="col-3">Nom</th>
                                    <th class="col-2">Numéro de téléphone</th>
                                    <th class="col-4">Adresse</th>
                                    <th class="col-2">Code postal</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    if (isset($listeFournisseur['fournisseurs']) && is_array($listeFournisseur['fournisseurs']) && count($listeFournisseur['fournisseurs']) > 0) : ?>

                                        <?php
                                        $listeFournisseur = $listeFournisseur['fournisseurs']; // Accéder à la clé correcte

                                        foreach ($listeFournisseur as $fournisseur):
                                            ?>
                                            <tr>
                                                <td>
                                                    <a href="index.php?controller=Fournisseur&action=indexFactures&nomFournisseur=<?= urlencode($fournisseur['nom']) ?>&refFournisseur=<?= urlencode($fournisseur['ref']) ?>">
                                                        <?= htmlspecialchars($fournisseur['nom'] ?? '') ?>
                                                    </a>
                                                </td>
                                                <td><?= htmlspecialchars($fournisseur['numTel'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($fournisseur['adresse'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($fournisseur['codePostal'] ?? '') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Aucun fournisseur trouvé</td>
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