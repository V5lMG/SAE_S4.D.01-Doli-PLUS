<div class="sidebar container">

    <h3 class="mt-4">Notes de frais</h3>
    <ul>
        <!-- faire en sorte que l'on clique sur les boutons et non sur le texte -->
        <!-- TODO faire avec yasmf-->
        <li><a href="index.php?controller=NoteFrais&action=index">Liste</a></li>
        <li><a href="#">Statistiques</a></li>
    </ul>

    <hr class="responsive-line">

    <h3>Achats</h3>
    <ul>
        <li><a href="#">Historique des factures</a></li>
        <li><a href="#">Palmarès</a></li>
    </ul>

    <hr class="responsive-line">

    <h3>URL</h3>
    <ul>
        <li><a href="#">Configuration URL</a></li>
    </ul>


    <div class="user-dropdown">
        <?php if (isset($error)) : ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <button class="dropdown-btn">
            Id_User
        </button>

        <ul class="dropdown-content">
            <li><a href="index.php?controller=Home&action=logout"><i class="fa fa-sign-out-alt"></i> Déconnexion</a></li>
            <!-- TODO faire avec yasmf-->
            <li><a href="#"><i class="fa-regular fa-circle-question"></i> Besoin d'aide ?</a></li>
        </ul>
    </div>
</div>