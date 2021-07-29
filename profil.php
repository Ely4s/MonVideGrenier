<?php

require_once 'Config/Constants.php';
require_once 'Config/DataBase.php';

require_once 'Functions/SessionFunctions.php';
Session_LifeControl();

require_once 'Functions/UtilsFunctions.php';
require_once 'Controllers/AdController.php';
require_once 'Controllers/UserController.php';
require_once 'Controllers/SearchController.php';

redirectIfConnectionState("connection.php", false);

$_USER = $_USER_PROFIL['user'];
$_ACCESS_DENIED = $_USER_PROFIL['accessDenied'];

?>
<!DOCTYPE>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css">
    <script src="script.js" ></script>
    <title><?php if($_ACCESS_DENIED === false) echo 'Profil de '.HTML_proof($_USER['USERS_username']); else echo "Profil"?></title>
    <link href="FontAwesome/css/all.css" rel="stylesheet">
    <meta name='viewport' content='width=800, user-scalable=no'/>
</head>
<body ontouchstart="">
<div id="wrapper">
    <div id="main">
        <?php include "Sections/NavBar.php";?>
        <?php include "Sections/ErrorsAndSuccess.php";?>
        <?php if($_ACCESS_DENIED === false):?>
        <?php include "Sections/SellerCard.php";?>
        <div class="Swtchr-Trgt" data-vld-src-id="1-1">
            <?php include "Sections/SearchEngin.php";?>
            <?php include "Sections/SearchResultInfo.php";?>
            <?php include "Sections/Ads.php";?>
            <?php include "Sections/Pagination.php";?>
        </div>
        <?php endif;?>
    </div>
    <div id="footer">
        <?php include "Sections/Footer.php";?>
    </div>
</div>
</body>
</html>