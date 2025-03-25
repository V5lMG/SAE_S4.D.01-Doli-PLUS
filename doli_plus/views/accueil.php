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
        <title>Tableau de bord</title>
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
                <div class="contenu-principal mt-4">
                    <div class="container-fluid">

                        <!-- Contenu principal a modifier -->
                        <div class="row">
                            <div class="col text-center">
                                <h1 class="mb-4">Tableau de bord Admin</h1>
                            </div>
                        </div>

                        <!-- Popup de confirmation -->
                        <?php
                        $url = $_SESSION["url_saisie"] ?? '';
                        if ($controller->urlExiste($url)) {
                            // Si l'URL existe déjà, appeler addUrl sans afficher la popup pour remettre l'URl en haut du fichier
                            header("Location: index.php?controller=Accueil&action=urlEnHaut");
                            exit();
                        } else {?>
                            <!-- Popup de confirmation -->
                            <div id="urlPopup" class="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Nouvelle URL détectée</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            L'URL suivante n'a jamais été enregistrée : <strong><?= htmlspecialchars($url) ?></strong><br>
                                            Souhaitez-vous l'enregistrer ?
                                        </div>
                                        <div class="modal-footer">
                                            <form action="index.php?controller=Accueil&action=addUrl" method="POST">
                                                <input type="hidden" name="new_url" value="<?= htmlspecialchars($url) ?>">
                                                <button type="submit" class="btn btn-primary">Oui, enregistrer</button>
                                            </form>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non, merci</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    var urlPopup = document.getElementById('urlPopup');
                                    if (urlPopup) {
                                        var myModal = new bootstrap.Modal(urlPopup, { keyboard: false });
                                        myModal.show();
                                    }
                                });
                            </script>
                            <?php
                        }
                        ?>
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-8 text-center">
                                <div class="p-4 border rounded shadow-sm bg-light">
                                    Contenu de l'accueil
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>