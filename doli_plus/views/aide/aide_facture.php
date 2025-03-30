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
                        <h2 class="mb-4 titre_page"><i class="fa-solid fa-house"></i> Aide - Factures</h2>
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
                                        <li><strong>Liste des notes de frais :</strong> <u> Vous êtes ici !</u> Consultez ici les listes des notes de frais.</li>
                                        <li><strong>Statistiques :</strong> Visualisez les statistiques relatives aux notes de frais.</li>
                                        <li><strong>Historique des factures d'un fournisseur :</strong> Consultez l'historique des factures d'un fournisseur.</li>
                                        <li><strong>Palmarès :</strong> Accédez au palmarès des fournisseurs ayant le plus grand chiffre d'affaires.</li>
                                    </ul>
                                </li>
                            </ul>

                            <!-- Section Liste note de frais -->
                            <h3>Liste des factures</h3>
                            <p>La liste des factures affiche les informations suivantes :</p>
                            <ul>
                                <li><strong>Réf. :</strong> Référence de la facture.</li>
                                <li><strong>Date facture :</strong> Date de la facturation.</li>
                                <li><strong>Date échéance :</strong> Date de l'échéance de paiement de la facture.</li>
                                <li><strong>Condition de règlement :</strong> Condition de règlement </li>
                                <li><strong>Mode de règlement :</strong> Mode de paiement de la facture</li>
                                <li><strong>Montant HT :</strong> Montant hors taxe.</li>
                                <li><strong>État :</strong> État actuel de la facture.</li>
                                <li><strong>Fichier joint :</strong> Fichier(s) joint à la facture.</li>
                            </ul>

                            <!-- Section Conseils supplémentaires -->
                            <h3>Conseils supplémentaires :</h3>
                            <ul>
                                <li>Lorsque au moins une facture s'affiche, il est possible de cliquer dessus pour afficher son détail.</li>
                                <li>Cliquez sur la flèche rouge en haut à gauche pour revenir à la recherche de fournisseur.</li>
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