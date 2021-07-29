<?php

require_once 'Config/Constants.php';
require_once 'Config/DataBase.php';

require_once 'Functions/SessionFunctions.php';
Session_LifeControl();

require_once 'Controllers/UserController.php';
require_once 'Functions/UtilsFunctions.php';
require_once 'Controllers/AdController.php';
require_once 'Controllers/SearchController.php';

?>

<!DOCTYPE>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css">
    <script src="script.js" ></script>
    <link href="FontAwesome/css/all.css" rel="stylesheet">
    <title>Accueil</title>
    <meta name='viewport' content='width=800, user-scalable=no'/>
</head>
<body ontouchstart="">
<div id="wrapper">
    <div id="main">
        <?php include "Sections/NavBar.php";?>
        <?php include "Sections/SearchEngin.php";?>
        <?php include "Sections/SearchResultInfo.php";?>
        <?php include "Sections/Ads.php";?>
        <?php include "Sections/Pagination.php";?>
    </div>
    <div id="footer">
        <?php include "Sections/Footer.php";?>
    </div>
</div>
</body>
</html>