<?php require_once 'Functions/UtilsFunctions.php';?>

<div class="Overscroll_Hider"></div>

<div id="SECTION_NavBar">
    <div class="NvBr-OvrFlw"></div>
    <div class="NvBr">
        <div class="NvBr-L">
            <a class="NvBr-Elmnt NvBr-Elmnt-StNm Txt-IneritStl-Rcrsv active" href="index.php">MonVideGrenier</a>
            <a class="NvBr-Elmnt Txt-Inln" href="creer-une-annonce.php">Créer une annonce&nbsp;&nbsp;<i class="fas fa-edit Txt-Clr-Spe-F Txt-Sz-1-50"></i></a>
            <a class="NvBr-Elmnt Txt-Inln" href="index.php">Voir les annonces&nbsp;&nbsp;<i class="far fa-clone Txt-Clr-Spe-F Txt-Sz-1-50"></i></a>
        </div>
        <div class="NvBr-R">
            <?php if(!isUserConnected()):?>
            <a class="NvBr-Elmnt Txt-Inln" href="inscription.php">Inscription&nbsp;<i class="fas fa-user-plus"></i></a>
            <a class="NvBr-Elmnt Txt-Inln" href="connection.php">Connection&nbsp;<i class="fas fa-sign-in-alt"></i></a>
            <?php else:?>
            <a class="NvBr-Elmnt Txt-Inln" href="mon-compte.php">Mon Compte&nbsp;<i class="fas fa-user"></i></a>
            <?php endif;?>
        </div>
    </div>
</div>