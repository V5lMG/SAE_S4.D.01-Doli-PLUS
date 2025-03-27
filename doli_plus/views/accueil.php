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

                        <!-- Titre -->
                        <div class="row">
                            <div class="col text-center mt-3">
                                <h2 class="mb-4 titre_page"><i class="fa-solid fa-house"></i> Accueil</h2>
                            </div>
                        </div>

                        <!-- Popup de confirmation -->
                        <?php
                        $url = $_SESSION["url_saisie"] ?? '';
                        if ($controller->urlExiste($url)) {
                            // Si l'URL existe déjà, appeler addUrl sans afficher la popup pour remettre l'URL en haut du fichier
                            $controller->addUrl("index.php?controller=Accueil&action=addUrl");
                        } else { ?>
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
                        <?php } ?>
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-8 ">
                                <div class="p-4 border rounded shadow-sm bg-light">
                                    L’organisation a intégré <strong>Doliplus</strong> dans son système d'information afin de gérer efficacement divers processus internes. Doliplus offre une application qui répond aux besoins spécifiques des utilisateurs en matière de gestion des notes de frais, des factures et des fournisseurs, tout en proposant une interface ergonomique.<br><br>

                                    L'accès à l'application est structuré selon les profils des utilisateurs :<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;- <strong>Les administrateurs</strong> bénéficient d'un accès complet à l'intégralité de l'application.<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;- <strong>Les gestionnaires des notes de frais</strong> ont uniquement accès à la gestion des notes de frais de l’entreprise.<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;- <strong>Les gestionnaires des achats</strong> peuvent consulter et gérer uniquement les factures et les fournisseurs.<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;- Enfin, <strong>les autres utilisateurs</strong> n’ont aucun accès à l’application.<br><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>