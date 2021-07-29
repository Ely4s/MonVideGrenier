<?php

require_once 'Config/Constants.php';
require_once 'Config/DataBase.php';

require_once 'Functions/SessionFunctions.php';
Session_LifeControl();

require_once 'Controllers/AdController.php';
require_once 'Functions/UtilsFunctions.php';

$_ACCESS_DENIED = $_AD_VIEW['accessDenied'];
$_AD = $_AD_VIEW['ad'];
$_USER = $_AD_VIEW['user'];

require_once 'Controllers/ConversationController.php';

?>

<!DOCTYPE>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css">
    <script src="script.js" ></script>
    <link href="FontAwesome/css/all.css" rel="stylesheet">
    <meta name='viewport' content='width=800, user-scalable=no'/>
    <title><?php echo HTML_proof($_AD['ADS_title']);?></title>
</head>
<body ontouchstart="">
<div id="wrapper">
    <div id="main">
        <?php include "Sections/NavBar.php";?>
        <?php include "Sections/ErrorsAndSuccess.php";?>
        <?php if($_ACCESS_DENIED === false):?>
        <?php include "Sections/AdView.php";?>
        <?php endif;?>
    </div>
    <div id="footer">
        <?php include "Sections/Footer.php";?>
    </div>
</div>

</body>
</html>