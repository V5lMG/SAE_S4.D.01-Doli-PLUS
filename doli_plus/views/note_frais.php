<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Notes de Frais</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <!--<link rel="stylesheet" href="static/css/styles.css"> A rajouté si on modifie le styles.css -->
        <link rel="stylesheet" href="static/css/sidebar.css">

    </head>
    <body>
    <!-- Barre latérale de la page -->
    <div class="d-none d-md-block">
        <?php include 'static/sidebar.php'; ?>
    </div>
        <!-- Contenu principal -->
    <div class="container">

        <!-- Barre de filtres -->
        <form method="GET" action="notes_frais.php" class="bg-light p-3 rounded shadow-sm">
            <div class="row g-3">

                <!-- Référence -->
                <div class="col-md-2 offset-md-1">
                    <input type="text" class="form-control" name="ref" placeholder="Référence">
                </div>

                <!-- Utilisateur -->
                <div class="col-md-2">
                        <input type="text" class="form-control" name="ref" placeholder="Utilisateur">
                </div>

                <!-- Date début -->
                <div class="col-md-2">
                    <input type="date" class="form-control" name="date_debut">
                </div>

                <!-- Date fin -->
                <div class="col-md-2">
                    <input type="date" class="form-control" name="date_fin">
                </div>

                <!-- État -->
                <div class="col-md-2">
                    <select class="form-select" name="etat">
                        <option value="">État</option>
                        <option value="Validé">Validé</option>
                        <option value="En attente">En attente</option>
                        <option value="Rejeté">Rejeté</option>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="col-md-1 d-flex gap-2">
                    <button type="submit" class="btn btn-primary" title="Rechercher">
                        <i class="fa fa-search"></i>
                    </button>
                    <button type="reset" class="btn btn-outline-secondary" title="Réinitialiser">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        </form>

        <!-- Tableau des notes de frais -->
        <div class="offset-md-1 mt-4">
            <table class="table table-bordered">
                <thead class="table-light">
                <tr>
                    <th>Réf.</th>
                    <th>Utilisateur</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Montant HT</th>
                    <th>Montant TVA</th>
                    <th>Montant TTC</th>
                    <th>État</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="8" class="text-center text-muted">Aucun enregistrement trouvé</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    </body>
</html>