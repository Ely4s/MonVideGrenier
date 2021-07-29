<?php

require_once 'Config/Constants.php';
require_once 'Config/DataBase.php';

require_once 'Functions/SessionFunctions.php';
Session_LifeControl();

require_once 'Controllers/AdController.php';
require_once 'Functions/UtilsFunctions.php';

redirectIfConnectionState("connection.php", false);

$_AD = $_AD_CREATION;

?>

<!DOCTYPE>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css">
    <script src="script.js" ></script>
    <link href="FontAwesome/css/all.css" rel="stylesheet">
    <meta name='viewport' content='width=800, user-scalable=no'/>
    <title>Créer une annonce</title>
</head>
<body ontouchstart="">
<div id="wrapper">
    <div id="main">
        <?php include "Sections/NavBar.php";?>
        <?php include "Sections/ErrorsAndSuccess.php";?>
        <?php include "Sections/AdNew.php";?>
    </div>
    <div id="footer">
        <?php include "Sections/Footer.php";?>
    </div>
</div>
</body>
</html>