
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="robots" content="noindex,follow">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Dolibarr Development Team">
<meta name="anti-csrf-newtoken" content="6c64e17cb360c88f00ecc484a323f7ea">
<meta name="anti-csrf-currenttoken" content="">
<link rel="shortcut icon" type="image/x-icon" href="/G2024-43-SAE/htdocs/theme/dolibarr_256x256_color.png"/>
<link rel="manifest" href="/G2024-43-SAE/htdocs/theme/eldy/manifest.json.php" />
<title>Identifiant @ 17.0.4</title>

<!-- Includes CSS for font awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Includes CSS for Dolibarr theme -->
<link rel="stylesheet" type="text/css" href="cssTest.css">



</head>

<!-- BEGIN PHP TEMPLATE LOGIN.TPL.PHP -->
	<body class="body bodylogin">
	
<script>
$(document).ready(function () {
	/* Set focus on correct field */
	$('#username').focus(); 		// Warning to use this only on visible element
});
</script>

<div class="login_center center" style="background-size: cover; background-position: center center; background-attachment: fixed; background-repeat: no-repeat; background: linear-gradient(4deg, rgb(240,240,240) 52%, rgb(60,70,100) 52.1%);">
<div class="login_vertical_align">


<form id="login" name="login" method="post" action="/G2024-43-SAE/htdocs/index.php?mainmenu=home">

<input type="hidden" name="token" value="6c64e17cb360c88f00ecc484a323f7ea" />
<input type="hidden" name="actionlogin" value="login">
<input type="hidden" name="loginfunction" value="loginfunction" />
<input type="hidden" name="backtopage" value="" />
<!-- Add fields to store and send local user information. This fields are filled by the core/js/dst.js -->




<!-- Title with version -->
<div class="login_table_title center" title="Dolibarr 17.0.4">
<a class="login_table_title" href="https://www.dolibarr.org" target="_blank" rel="noopener noreferrer external">Doli-plus 1.0.0</a></div>



<div class="login_table">

<div id="login_line1">

<div id="login_left">
<img alt="" src="/G2024-43-SAE/htdocs/theme/dolibarr_logo.svg" id="img_logo" />
</div>

<br>

<div id="login_right">

<div class="tagtable left centpercent" title="Saisir les informations de connexion">

<!-- Login -->
<div class="trinputlogin">
<div class="tagtd nowraponall center valignmiddle tdinputlogin">
<!-- <span class="span-icon-user">-->
<span class="fa fa-user"></span>
<input type="text" id="username" maxlength="255" placeholder="Identifiant" name="username" class="flat input-icon-user minwidth150" value="" tabindex="1" autofocus="autofocus" />
</div>
</div>

<!-- Password -->
<div class="trinputlogin">
<div class="tagtd nowraponall center valignmiddle tdinputlogin">
<!--<span class="span-icon-password">-->
<span class="fa fa-key"></span>
<input type="password" id="password" maxlength="128" placeholder="Mot de passe" name="password" class="flat input-icon-password minwidth150" value="" tabindex="2" autocomplete="off" />
</div></div>


</div>

</div> <!-- end div login_right -->

</div> <!-- end div login_line1 -->


<div id="login_line2" style="clear: both">

<!-- Button Connection -->
<br>
<div id="login-submit-wrapper">
<input type="submit" class="button" value="&nbsp; Se connecter &nbsp;" tabindex="5" />
</div>

<br><div class="center" style="margin-top: 5px;"><a class="alogin" href="/G2024-43-SAE/htdocs/support/index.php" target="_blank" rel="noopener noreferrer">Besoin d'assistance ou aide ?</a></div>
</div> <!-- end login line 2 -->

</div> <!-- end login table -->


</form>




<!-- authentication mode = dolibarr -->
<!-- cookie name used for this session = DOLSESSID_e5c0ce835608c3843ce85b1962f01f6c9c214f8f -->
<!-- urlfrom in this session =  -->

<!-- Common footer is not used for login page, this is same than footer but inside login tpl -->



</div>
</div><!-- end of center -->


</body>
</html>
<!-- END PHP TEMPLATE -->
