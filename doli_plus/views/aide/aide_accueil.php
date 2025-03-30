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
                        <h2 class="mb-4 titre_page"><i class="fa-solid fa-house"></i> Aide - Accueil</h2>
                    </div>
                </div>

                <!-- Information de la page -->
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 ">
                        <div class="p-4 border rounded shadow-sm bg-light">

                            <!-- Section Navigation -->
                            <h3>Navigation :</h3>
                            <ul>
                                <li>
                                    <strong>Menu de gauche (sidebar) :</strong> Vous trouverez ici les différentes sections de l'application :
                                    <ul>
                                        <li><strong>Accueil :</strong> <u>Vous êtes ici !</u> Cette page vous donne une vue d'ensemble de l'application et de ses fonctionnalités.</li>
                                        <li><strong>Liste des notes de frais :</strong> Consultez ici les listes des notes de frais.</li>
                                        <li><strong>Statistiques :</strong> Visualisez les statistiques relatives aux notes de frais.</li>
                                        <li><strong>Historique des factures d'un fournisseur :</strong> Consultez l'historique des factures d'un fournisseur.</li>
                                        <li><strong>Palmarès :</strong> Accédez au palmarès des fournisseurs ayant le plus grand chiffre d'affaires.</li>
                                    </ul>
                                </li>
                            </ul>

                            <!-- Section Profils des utilisateurs -->
                            <h3>Profils d'utilisateurs :</h3>

                            <p>L'accès à l'application est limité en fonction de votre profil :</p>
                            <ul>
                                <li><strong>Administrateurs :</strong> Accès complet à toutes les fonctionnalités de l'application.</li>
                                <li><strong>Gestionnaires des notes de frais :</strong> Accès à la gestion des notes de frais uniquement.</li>
                                <li><strong>Gestionnaires des achats :</strong> Accès à la consultation et à la gestion des factures et des fournisseurs uniquement.</li>
                                <li><strong>Autres utilisateurs :</strong> Aucun accès aux fonctionnalités.</li>
                            </ul>

                            <!-- Section Nom utilisateur et Déconnexion -->
                            <h3>Nom utilisateur, Déconnexion :</h3>
                            <p>Votre nom d'utilisateur est affiché en bas à gauche de l'écran. Lors du clique sur celui-ci, il est possible de se déconnecter de l'application.</p>

                            <!-- Section Conseils supplémentaires -->
                            <h3>Conseils supplémentaires :</h3>
                            <ul>
                                <li>N'hésitez pas à explorer les différentes sections de l'application pour vous familiariser avec ses fonctionnalités.</li>
                                <li>Si vous avez des questions, il y a l'aide associé à chaque page en bas à gauche de l'écran.</li>
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