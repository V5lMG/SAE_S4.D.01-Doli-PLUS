<?php
// La session doit être lancé dans chaque vue
$userName = $_SESSION['user_name'] ?? 'Erreur';
?>

<!-- Barre latérale de la page (affichée sur grands écrans) -->
<div class="sidebar col-12 col-md-2 p-3 d-none d-md-block">
    <div class="liste-bouton">
        <h4 class="mt-3">Notes de frais</h4>
        <button class="btn w-100 bouton-action mt-2 mb-1" onclick="window.location.href='index.php?controller=NoteFrais&action=indexListe'">Liste</button>
        <button class="btn w-100 bouton-action mb-1" onclick="window.location.href='index.php?controller=NoteFrais&action=indexStatistique'">Statistiques</button>

        <hr class="responsive-line">

        <h4 class="mt-1">Achats</h4>
        <button class="btn w-100 bouton-action mt-2 mb-1" onclick="window.location.href='index.php?controller=Fournisseur&action=indexAchat'">Historique des factures</button>
        <button class="btn w-100 bouton-action mb-1" onclick="window.location.href='index.php?controller=Fournisseur&action=indexPalmares'">Palmarès</button>

        <hr class="responsive-line">

        <h4 class="mt-1">URL</h4>
        <button class="btn w-100 bouton-action mt-2" onclick="window.location.href='index.php?controller=Url&action=index'">Configuration URL</button>
    </div>

    <!-- Dropdown utilisateur --> <!-- TODO changer pour fonctionner avec le survole -->
    <div class="dropdown">
        <?php if (isset($error)) : ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- Bouton utilisateur avec le nom dynamique -->
        <button class="btn bouton-action w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo $userName; ?>
        </button>

        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="index.php?controller=Home&action=logout"><i class="fa fa-sign-out-alt"></i> Déconnexion</a></li>
            <li><a class="dropdown-item" href="#"><i class="fa-regular fa-circle-question"></i> Besoin d'aide ?</a></li> <!-- TODO yasmf -->
        </ul>
    </div>
</div>

<!-- Barre latérale mobile (affichée sur petits écrans) -->
<div class="petit-sidebar col-12 d-md-none">
    <nav class="navbar">
        <button class="navbar-toggler navbar-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <button class="btn" onclick="window.location.href='index.php?controller=NoteFrais&action=indexListe'"><span class="text-white">Liste</span></button>
                </li>
                <li class="nav-item">
                    <button class="btn" onclick="window.location.href='index.php?controller=NoteFrais&action=indexStatistique'"><span class="text-white">Statistiques</span></button>
                </li>
                <hr class="responsive-line">
                <li class="nav-item">
                    <button class="btn" onclick="window.location.href='index.php?controller=Fournisseur&action=indexAchat'"><span class="text-white">Historique des factures</span></button>
                </li>
                <li class="nav-item">
                    <button class="btn" onclick="window.location.href='index.php?controller=Fournisseur&action=indexPalmares'"><span class="text-white">Palmarès</span></button>
                </li>
                <hr class="responsive-line">
                <li class="nav-item">
                    <button class="btn" onclick="window.location.href='#'"><span class="text-white">Configuration URL</span></button>
                </li>
            </ul>
        </div>
    </nav>
</div>

<!-- Import Bootstrap JS pour le fonctionnement du menu sur téléphone -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>