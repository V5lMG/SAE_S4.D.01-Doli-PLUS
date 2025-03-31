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
                        <h2 class="mb-4 titre_page"><i class="fa-solid fa-house"></i> Aide - DEFAAUT</h2>
                    </div>
                </div>

                <!-- Information de la page -->
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 ">
                        <div class="p-4 border rounded shadow-sm bg-light">

                            <!-- Section Informative -->
                            <h2>Comment pouvons-nous vous aider ?</h2>
                            <p> Nous sommes là pour vous accompagner dans l'utilisation de notre plateforme. Si vous rencontrez des difficultés ou si vous avez des questions, n'hésitez pas à consulter les différentes sections d'aide ci-dessous.</p>
                            <p> Notre équipe est également disponible pour vous assister par email à l'adresse <a href="mailto:support@exemple.com">support@exemple.com</a> ou par téléphone au 01 02 03 04 05.</p>

                            <!-- Section Navigation -->
                            <h3>Navigation :</h3>
                            <ul>
                                <li>
                                    <strong>Menu de gauche (sidebar) :</strong> Vous trouverez ici les différentes sections de l'application :
                                    <ul>
                                        <li><strong>Accueil :</strong> Cette page vous donne une vue d'ensemble de l'application et de ses fonctionnalités.</li>
                                        <li><strong>Liste des notes de frais :</strong> Consultez ici les listes des notes de frais.</li>
                                        <li><strong>Statistiques :</strong> Visualisez les statistiques relatives aux notes de frais.</li>
                                        <li><strong>Historique des factures d'un fournisseur :</strong> Consultez l'historique des factures d'un fournisseur.</li>
                                        <li><strong>Palmarès :</strong> Accédez au palmarès des fournisseurs ayant le plus grand chiffre d'affaires.</li>
                                    </ul>
                                </li>
                            </ul>

                            <!-- Section Aide disponibles -->
                            <h3>Pages d'aide disponibles</h3>
                            <ul>
                                <li><a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" target="_blank">Aide sur la page d'accueil</a></li>
                                <li><a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" target="_blank">Aide sur la gestion des notes de frais</a></li>
                                <li><a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" target="_blank">Aide sur la gestion des fournisseurs</a></li>
                                <li><a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" target="_blank">Aide sur l'historique des factures</a></li>
                                <li><a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" target="_blank">Aide sur le palmarès</a></li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>