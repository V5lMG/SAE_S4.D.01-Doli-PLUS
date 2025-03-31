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
                        <h2 class="mb-4 titre_page"><i class="fa-solid fa-house"></i> Aide - Fournisseurs</h2>
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
                                        <li><strong>Accueil :</strong> Cette page vous donne une vue d'ensemble de l'application et de ses fonctionnalités.</li>
                                        <li><strong>Liste des notes de frais :</strong>  Consultez ici les listes des notes de frais.</li>
                                        <li><strong>Statistiques :</strong> Visualisez les statistiques relatives aux notes de frais.</li>
                                        <li><strong>Historique des factures d'un fournisseur :</strong> <u> Vous êtes ici !</u> Consultez l'historique des factures d'un fournisseur.</li>
                                        <li><strong>Palmarès :</strong> Accédez au palmarès des fournisseurs ayant le plus grand chiffre d'affaires.</li>
                                    </ul>
                                </li>
                            </ul>

                            <!-- Section Recherche de fournisseur -->
                            <h3>Recherche de fournisseur</h3>
                            <p> Vous pouvez rechercher un fournisseur en utilisant les options suivantes :</p>
                            <ul>
                                <li><strong>Nom :</strong> Entrez le nom recherché (exemple : mathias).</li>
                                <li><strong>Numéro de téléphone :</strong> Indiquez le numéro de téléphone (exemple : 05 65 18 54 37).</li>
                                <li><strong>Adresse :</strong> Précisez l'adresse (exemple : Buffleres, Belmont sur Rance).</li>
                                <li><strong>Code Postal :</strong> Saisissez le code postal (exemple : 12000).</li>
                            </ul>

                            <!-- Section Conseils supplémentaires -->
                            <h3>Conseils supplémentaires :</h3>
                            <ul>
                                <li>Sélectionnez au moins un filtre pour afficher les résultats dans la liste des fournisseurs.</li>
                                <li>Lorsque au moins un fournisseur s'affiche, il est possible de cliquer sur son nom pour être envoyé sur la page concernant les détails de ses factures.</li>
                                <li>Cliquez sur le bouton de recherche (loupe) pour appliquer les filtres de recherche.</li>
                                <li>Cliquez sur le bouton de suppression (croix) pour réinitialiser les filtres de recherche.</li>
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