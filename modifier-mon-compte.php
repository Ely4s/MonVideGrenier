<?php

require_once 'Config/DataBase.php';
require_once 'Config/Constants.php';

require_once 'Functions/SessionFunctions.php';
Session_LifeControl();

require_once 'Controllers/UserController.php';
require_once 'Functions/UtilsFunctions.php';

redirectIfConnectionState("connection.php", false);

$_USER = $_USER_MODIFICATION;

?>

<!DOCTYPE>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css">
    <script src="script.js" ></script>
    <link href="FontAwesome/css/all.css" rel="stylesheet">
    <meta name='viewport' content='width=<?php echo HTML_proof(SITE_WIDTH)?>, user-scalable=no'/>
    <title>Modifier mes informations</title>
</head>
<body ontouchstart="">
<div id="wrapper">
    <div id="main">
        <?php include "Sections/NavBar.php";?>
        <?php include "Sections/ErrorsAndSuccess.php";?>
        <?php include "Sections/UserUpdate.php"?>
    </div>
    <div id="footer">
        <?php include "Sections/Footer.php";?>
    </div>
</div>
</body>
</html>