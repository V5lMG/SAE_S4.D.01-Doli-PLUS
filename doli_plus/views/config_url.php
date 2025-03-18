<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Configuration des URLS</title>
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
                    <div class="container-fluid">

                        <!-- Contenu principal a modifier -->
                        <div class="row">
                            <div class="col text-center">
                                <h1 class="mb-4">Configuration des URLS</h1>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-8 text-center">
                                <div class="p-4 border rounded shadow-sm bg-light">
                                    Contenu de la modif des URLS
                                </div>
                            </div>
                        </div>
                        <!-- Jusqu'ici -->

                    </div>
                </div>
            </div>
        </div>
    </body>
</html>