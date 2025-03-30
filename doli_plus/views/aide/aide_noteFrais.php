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
                        <h2 class="mb-4 titre_page"><i class="fa-solid fa-house"></i> Aide - Notes de frais</h2>
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

                            <!-- Section Filtre des notes de frais -->
                            <h3>Filtrage des notes de frais</h3>
                            <p>Vous pouvez filtrer les notes de frais en utilisant les options suivantes :</p>
                            <ul>
                                <li><strong>Employé :</strong> Sélectionnez un employé spécifique.</li>
                                <li><strong>Type de la note :</strong> Choisissez un type de note de frais (par exemple, "Tous").</li>
                                <li><strong>Référence de la note :</strong> Entrez une référence spécifique.</li>
                                <li><strong>Du / Au :</strong> Sélectionnez une plage de dates.</li>
                                <li><strong>État de la note :</strong> Choisissez un état (par exemple, "Tous").</li>
                            </ul>
                            <p></p>

                            <!-- Section Liste note de frais -->
                            <h3>Liste des notes de frais</h3>
                            <p>La liste des notes de frais affiche les informations suivantes :</p>
                            <ul>
                                <li><strong>Ref. :</strong> Référence de la note de frais.</li>
                                <li><strong>Employé :</strong> Nom de l'employé.</li>
                                <li><strong>Date début / Date fin :</strong> Période couverte par la note de frais.</li>
                                <li><strong>Montant HT :</strong> Montant hors taxes.</li>
                                <li><strong>Montant TVA :</strong> Montant de la TVA.</li>
                                <li><strong>Montant TTC :</strong> Montant total toutes taxes comprises.</li>
                                <li><strong>État :</strong> État actuel de la note de frais.</li>
                            </ul>

                            <!-- Section Conseils supplémentaires -->
                            <h3>Conseils supplémentaires :</h3>
                            <ul>
                                <li>Cliquer sur "Afficher toutes les notes de frais" pour voir toutes les notes sans filtre.</li>
                                <li>Sélectionnez au moins un filtre pour afficher les résultats dans la liste des notes de frais.</li>
                                <li>Lorsque au moins une note de frais s'affiche, il est possible de cliquer dessus pour afficher son détail.</li>
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