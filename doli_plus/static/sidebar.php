<?php // if (gestionnaireDeNoteDeFrais) { ?>
    <!-- Barre latérale pour la gestion des notes de frais-->
    <div class="sidebar">
        <h3>Notes de frais</h3>
        <ul>
            <li><a href="gestion_note_frais_list.php">Liste</a></li>
            <li><a href="gestion_note_frais_statistiques.php">Statistiques</a></li>
        </ul>
        <div class="user-dropdown">
            <button class="dropdown-btn">
                Id_User
            </button>
            <ul class="dropdown-content">
                <li><a href="/doli_plus?controller=Disconnect"><i class="fa fa-sign-out-alt"></i> Déconnexion</a></li>
                <li><a href="#"><i class="fa-regular fa-circle-question"></i> Besoin d'aide ?</a></li>
            </ul>
        </div>
    </div>

<?php // } else { ?>
<!-- Barre latérale Pour les factures fournisseurs-->
<!--    <div class="sidebar">-->
<!--        <h3>Notes de frais</h3>-->
<!--        <ul>-->
<!--            <li><a href="facturation_liste.php">Liste</a></li>-->
<!--            <li><a href="facturation_palmares_liste.php">Palmarès - Liste</a></li>-->
<!--            <li><a href="facturation_palmares_graphique.php">Palmarès - Graphique</a></li>-->
<!--        </ul>-->
<!--        <div class="user-dropdown">-->
<!--            <button class="dropdown-btn">-->
<!--                Id_User-->
<!--            </button>-->
<!--            <ul class="dropdown-content">-->
<!--                <li><a href="/doli_plus?controller=Disconnect"><i class="fa fa-sign-out-alt"></i> Déconnexion</a></li>-->
<!--                <li><a href="#"><i class="fa-regular fa-circle-question"></i> Besoin d'aide ?</a></li>-->
<!--            </ul>-->
<!--        </div>-->
<!--    </div>-->
<?php // } ?>