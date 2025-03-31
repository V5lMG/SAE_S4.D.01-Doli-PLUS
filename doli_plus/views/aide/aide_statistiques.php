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
                        <h2 class="mb-4 titre_page"><i class="fa-solid fa-house"></i> Aide - Statistiques fournisseurs</h2>
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
                                        <li><strong>Statistiques :</strong> <u>Vous êtes ici !</u> Visualisez les statistiques relatives aux notes de frais.</li>
                                        <li><strong>Historique des factures d'un fournisseur :</strong> Consultez l'historique des factures d'un fournisseur.</li>
                                        <li><strong>Palmarès :</strong> Accédez au palmarès des fournisseurs ayant le plus grand chiffre d'affaires.</li>
                                    </ul>
                                </li>
                            </ul>

                            <!-- Section Graphique en bâton-->
                            <h3>Évolution des notes de frais sur l'année</h3>
                            <p>Le graphique en bâton vous permet de visualiser la tendance des dépenses au fil des mois. Il est possible de sélectionner les informations que l'on souhaite visualiser. Vous pouvez donc choisir l'année à représenter ou bien le mois d'une année choisie.</p>
                            <div class="col-md-6 offset-md-1">
                                <label for="annee">Par mois (sur un an) :</label>
                                <select id="annee" class="form-select">
                                    <option value="">--Sélectionner une année--</option>
                                </select>
                            </div>
                            <br>
                            <div class="col-md-6 offset-md-1">
                                <label for="mois">Par jour (sur un mois) :</label>
                                <select id="mois" class="form-select">
                                    <option value="">--Sélectionner un mois--</option>
                                </select>
                            </div>
                            <br>
                            <div class="col-md-6 offset-md-1">
                                <label><input type="checkbox" class="form-check-input"> Comparaison avec l'année précédente</label>
                            </div>
                            <br>

                            <!-- Section Diagramme sectoriel -->
                            <h3>Diagramme sectoriel de la totalité des notes de frais par catégorie</h3>
                            <p>Le graphique circulaire présente la répartition de vos dépenses par catégorie : </p>
                                <ul>
                                    <li> Frais kilométrique </li>
                                    <li> Repas</li>
                                    <li> Transport</li>
                                    <li> Autres</li>
                                </ul>
                            Moyen de filtrer les notes de frais :
                            <div class="col-md-6 offset-md-1">
                                <label for="dateDebut">Date de début :</label>
                                <input type="date" id="dateDebut" class="form-control">
                            </div>
                            <br>
                            <div class="col-md-6 offset-md-1">
                                <label for="dateFin">Date de fin :</label>
                                <input type="date" id="dateFin" class="form-control" >
                            </div>
                            <br>

                            <!-- Section Conseils supplémentaires -->
                            <h3>Conseils supplémentaires :</h3>
                            <ul>
                                <li>Passez votre souris sur les points du graphique linéaire ou les parts du diagramme circulaire pour afficher des informations détaillées.</li>
                                <li>Utilisez les filtres de période pour analyser vos dépenses sur des périodes spécifiques (par exemple, un mois ou une année).</li>
                                <li>Comparez vos dépenses avec l'année précédente pour identifier les tendances et les variations.</li>
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