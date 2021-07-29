<?php

require_once 'Config/Constants.php';
require_once 'Config/DataBase.php';

require_once 'Functions/SessionFunctions.php';
Session_LifeControl();

require_once 'Controllers/AdController.php';
require_once 'Functions/UtilsFunctions.php';

redirectIfConnectionState("connection.php", false);

$_AD = $_AD_MODIFICATION;

?>

<!DOCTYPE>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css">
    <script src="script.js" ></script>
    <link href="FontAwesome/css/all.css" rel="stylesheet">
    <meta name='viewport' content='width=800, user-scalable=no'/>
    <title><?php if($_AD['accessDenied'] === false) echo 'Modifier l\'annonce "'.HTML_proof($_AD['title'].'"'); else echo "Modifier une annonce"?></title>
</head>
<body ontouchstart="">
<div id="wrapper">
    <div id="main">
        <?php include "Sections/NavBar.php";?>
        <?php include "Sections/ErrorsAndSuccess.php";?>
        <?php if($_AD['accessDenied'] === false):?>
        <?php include "Sections/AdUpdate.php";?>
        <?php endif;?>
    </div>
    <div id="footer">
        <?php include "Sections/Footer.php";?>
    </div>
</div>
</body>
</html>