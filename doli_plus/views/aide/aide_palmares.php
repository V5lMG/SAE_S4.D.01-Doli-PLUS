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
                        <h2 class="mb-4 titre_page"><i class="fa-solid fa-house"></i> Aide - Palmarès fournisseurs</h2>
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
                                        <li><strong>Liste des notes de frais :</strong> Consultez ici les listes des notes de frais.</li>
                                        <li><strong>Statistiques :</strong> Visualisez les statistiques relatives aux notes de frais.</li>
                                        <li><strong>Historique des factures d'un fournisseur :</strong> Consultez l'historique des factures d'un fournisseur.</li>
                                        <li><strong>Palmarès :</strong> <u>Vous êtes ici !</u> Accédez au palmarès des fournisseurs ayant le plus grand chiffre d'affaires.</li>
                                    </ul>
                                </li>
                            </ul>

                            <!-- Section Palmarès tableau -->
                            <h3>Palmarès des fournisseurs</h3>
                            <p>Cette section affiche le classement des fournisseurs en fonction de leur chiffre d'affaires (quantité de factures, montant total).</p>
                            <ul>
                                <li><strong>Classement : </strong> Position du fournisseur (Top 1, Top 2, etc.).</li>
                                <li><strong>Nom :</strong> Nom du fournisseur.</li>
                                <li><strong>Quantité de facture :</strong> Nombre total de factures émises par le fournisseur.</li>
                                <li><strong>Montant HT :</strong> Montant total hors taxes des factures du fournisseur.</li>
                            </ul>

                            <!-- Section Palmarès diagramme sectoriel -->
                            <h3>Diagramme sectoriel des factures de chaque fournisseur</h3>
                            <p>Ce graphique circulaire présente la répartition des montants des notes de frais par fournisseur. Chaque part du gâteau représente la part de chaque fournisseur dans le total des notes de frais. Passez la souris sur une part pour voir le nom du fournisseur et le montant exact.</p>

                            <!-- Section Conseils supplémentaires -->
                            <h3>Conseils supplémentaires :</h3>
                            <ul>
                                <li>Vous pouvez filtrer les résultats par plage de dates en utilisant les champs "Date de début" et "Date de fin".</li>
                                <li>Vous pouvez également choisir d'afficher le Top 10, Top 20 ou Top 30 des fournisseurs.</li>
                                <li>Cliquez sur le bouton de recherche (loupe) pour appliquer les filtres.</li>
                                <li>Cliquez sur le bouton de suppression (croix) pour réinitialiser les filtres.</li>
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