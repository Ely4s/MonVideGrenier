<?php

require_once 'Config/Constants.php';
require_once 'Config/DataBase.php';

require_once 'Functions/SessionFunctions.php';
Session_LifeControl();

redirectIfConnectionState("connection.php", false);

require_once 'Functions/UtilsFunctions.php';
require_once 'Controllers/UserController.php';
require_once 'Controllers/AdController.php';
require_once 'Controllers/SearchController.php';

require_once 'Controllers/ConversationController.php';

$_CONVERSATIONS = $_CONVERSATION_LISTVIEW['conversations'];

?>

<!DOCTYPE>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css">
    <script src="script.js" ></script>
    <title>Mon Compte</title>
    <link href="FontAwesome/css/all.css" rel="stylesheet">
    <meta name='viewport' content='width=800, user-scalable=no'/>
</head>
<body ontouchstart="">
<div id="wrapper">
    <div id="main">
        <?php include "Sections/NavBar.php";?>
        <?php include "Sections/ErrorsAndSuccess.php";?>
        <?php include "Sections/UserCard.php";?>
        <?php include "Sections/UserInfoSelect.php";?>
        <div class="Swtchr-Trgt" data-vld-src-id="1-1">
        <?php include "Sections/SearchEngin.php";?>
        <?php include "Sections/SearchResultInfo.php";?>
        <?php include "Sections/Ads.php";?>
        <?php include "Sections/Pagination.php";?>
        </div>
        <div class="Swtchr-Trgt hidden" data-vld-src-id="1-2">
        <?php include "Sections/ConversationList.php";?>
        </div>
    </div>
    <div id="footer">
        <?php include "Sections/Footer.php";?>
    </div>
</div>
</body>
</html>