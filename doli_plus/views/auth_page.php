<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>DoliPlus - Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="static/css/auth.css">
</head>
<body class="body bodylogin">

<div class="login_center center">
    <div class="login_vertical_align">
        <form id="login" name="login" method="post" action="../index.php">
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
                                <input type="text" id="username" maxlength="255" placeholder="Identifiant" name="username" class="flat input-icon-user minwidth150" required />
                            </div>
                        </div>
                        <div class="trinputlogin">
                            <div class="center tdinputlogin">
                                <span class="fa fa-key"></span>
                                <input type="password" id="password" maxlength="128" placeholder="Mot de passe" name="password" class="flat input-icon-password minwidth150" required />
                            </div>
                        </div>
                    </div>
                </div>
                <div id="login_line2">
                    <div id="login-submit-wrapper">
                        <input hidden name="action" value="login"/>
                        <input hidden name="controller" value="Home"/>
                        <input id="button-connection" type="submit" class="button" value="Se connecter"/>
                    </div>
                    <br>
                </div>
                <br><div class="center" style="margin-top: 5px;"><a class="alogin" href="/user/passwordforgotten.php">Mot de passe oublié ?</a></div>

                <!-- Message d'erreur en cas d'échec de la connexion -->
                <?php if (HttpHelper::getParam('error') === 'true') : ?>
                    <div class="alert alert-danger mt-3" role="alert">
                        Identifiants incorrects. Veuillez réessayer.
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
</body>
</html>
