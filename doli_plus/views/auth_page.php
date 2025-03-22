<?php
// Démarrer la session si elle n'est pas encore démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>DoliPlus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="static/css/auth.css">
</head>
<body>
    <!-- Ligne de fond -->
    <div class="login_center center" style="background-size: cover; background: linear-gradient(4deg, rgb(60,70,100) 52%, rgb(240,240,240) 52.1%) no-repeat fixed center center;">
        <div class="login_vertical_align">
            <form id="login" name="login" method="post" action="index.php?controller=Home&action=login">
                <div class="login_table">
                    <div id="login_line1">
                        <div id="login_left">
                            <img alt="" src="static/img/doliplus_logo.png" id="img_logo" />
                        </div>
                    </div>
                    <div id="login_right">
                        <div class="tagtable left centpercent">
                            <div class="trinputlogin">
                                <div class="center tdinputlogin">
                                    <span class="fa fa-user"></span>
                                    <input type="text" id="username" maxlength="255" placeholder="Identifiant" name="username" class="flat minwidth150" required />
                                </div>
                            </div>
                            <div class="trinputlogin">
                                <div class="center tdinputlogin">
                                    <span class="fa fa-key"></span>
                                    <input type="password" id="password" maxlength="128" placeholder="Mot de passe" name="password" class="flat minwidth150" required />
                                </div>
                            </div>
                            <!-- URL input with dropdown using datalist -->
                            <div class="trinputlogin">
                                <div class="center tdinputlogin d-flex align-items-center">
                                    <i class="fa-solid fa-link me-2"></i>
                                    <input type="url" id="url" maxlength="255" name="url" class="flat minwidth150" placeholder="URL" list="urlList" required/>
                                    <!-- Bouton déroulant placé à droite de l'input -->
                                    <div class="dropdown ms-2">
                                        <button class="btn bouton-url dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li><a class="dropdown-item" href="#">Option 1</a></li>
                                            <li><a class="dropdown-item" href="#">Option 2</a></li>
                                            <li><a class="dropdown-item" href="#">Option 3</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="login_line2">
                        <div id="login-submit-wrapper">
                            <input type="submit" class="button" value="Se connecter" />
                        </div>
                        <br>
                    </div>
                    <br><div class="center" style="margin-top: 5px;"><a class="alogin" href="http://dolibarr.iut-rodez.fr/G2024-43-SAE/htdocs/user/passwordforgotten.php" target="_blank">Mot de passe oubli&eacute; ?</a></div>
                </div>
            </form>
            <!-- Message d'erreur de connexion -->
            <?php if (isset($error)) { ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php } ?>
            <!-- SubTitle with version -->
            <div class="login_table_title center" title="DoliPlus 1.0.0">
                <a class="login_table_title" href="https://www.dolibarr.org" target="_blank" rel="noopener noreferrer external">DoliPlus 1.0.0</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sélectionner les éléments nécessaires
        const inputUrl = document.getElementById('url');
        const dropdownItems = document.querySelectorAll('.dropdown-menu .dropdown-item');

        // Ajouter un écouteur d'événement sur chaque élément du menu déroulant
        dropdownItems.forEach(item => {
            item.addEventListener('click', function(event) {
                event.preventDefault();  // Empêche le comportement par défaut du lien
                inputUrl.value = this.textContent;  // Affecte la valeur sélectionnée à l'input URL
            });
        });
    </script>
    </body>
</html>