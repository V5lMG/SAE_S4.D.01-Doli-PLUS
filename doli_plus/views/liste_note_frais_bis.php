<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Notes de Frais</title>
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
                <div class="contenu-principal">
                    <div class="container-fluid mt-3">
                        <!-- Barre de filtres -->
                        <form method="GET" action="liste_note_frais_bis.php" class="bg-light p-3 rounded shadow-sm">
                            <!-- Première ligne -->
                            <div class="row g-3 align-items-center">

                                <!-- Employé -->
                                <div class="col-md-2 text-center d-flex flex-column align-items-start">
                                    <label for="employe" class="fw-bold">Employé :</label>
                                    <input type="text" class="form-control" id="employe" name="employe" placeholder="Ex : Dupond Pierre">
                                </div>

                                <!-- Type -->
                                <div class="col-md-2 text-center d-flex flex-column align-items-start">
                                    <label for="type" class="fw-bold">Type de la note :</label>
                                    <input type="text" class="form-control" id="type" name="type" placeholder="Repas, frais d'essence">
                                </div>

                                <!-- Référence -->
                                <div class="col-md-2 text-center d-flex flex-column align-items-start">
                                    <label for="reference" class="fw-bold">Référence de la note :</label>
                                    <input type="text" class="form-control" id="reference" name="reference" placeholder="Ex : PROV1">
                                </div>

                                <!-- Date -->
                                <div class="col-md-2 text-center">
                                    <div class="d-flex flex-column align-items-start">
                                        <label for="date_debut" class="fw-bold">Du :</label>
                                        <input type="date" id="date_debut" class="form-control mb-2" name="date_debut">

                                        <label for="date_fin" class="fw-bold">Au :</label>
                                        <input type="date" id="date_fin" class="form-control" name="date_fin">
                                    </div>
                                </div>

                                <!-- État -->
                                <div class="col-md-2 text-center d-flex flex-column align-items-start">
                                    <label for="etat" class="fw-bold">État de la note :</label>
                                    <select class="form-select" name="etat" id="etat">
                                        <option value="tous">Tous</option>
                                        <option value="brouillon">Brouillon</option>
                                        <option value="valider">Validé</option>
                                        <option value="annuler">Annulé</option>
                                        <option value="approuver">Approuvé</option>
                                        <option value="payer">Payé</option>
                                        <option value="refuser">Refusé</option>
                                    </select>
                                </div>

                                <!-- Boutons -->
                                <div class="col-md-2 d-flex align-self-center bouton-recherche">
                                    <button type="submit" class="btn btn-primary" title="Rechercher">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary ms-2" title="Réinitialiser">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <!-- Tableau des notes de frais -->
                        <div class="mt-4 table-responsive">
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
                </div>
            </div>
        </div>
    </body>
</html>