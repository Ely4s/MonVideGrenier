<?php

require_once 'Config/Constants.php';
require_once 'Config/DataBase.php';

require_once 'Functions/SessionFunctions.php';
Session_LifeControl();

redirectIfConnectionState("connection.php", false);

require_once 'Controllers/ConversationController.php';

$_ACCESS_DENIED = $_CONVERSATION_VIEW['accessDenied'];
$_CONVERSATION = $_CONVERSATION_VIEW['conversation'];
$_MESSAGE = $_CONVERSATION_VIEW['message'];



?>

<!DOCTYPE>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css">
    <script src="script.js" ></script>
    <link href="FontAwesome/css/all.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <meta name='viewport' content='width=800, user-scalable=no'/>
    <title>
        <?php
        $recipient_username = "";
        if($_CONVERSATION['CONVERSATIONS_id_userSeller'] === $_SESSION['id'])
        {$recipient_username = $_CONVERSATION['CONVERSATIONS_userBuyer']['USERS_username'];}
        else
        {$recipient_username = $_CONVERSATION['CONVERSATIONS_userSeller']['USERS_username'];};
        if ($_ACCESS_DENIED === false) echo $recipient_username.', à propos de "'.HTML_proof($_CONVERSATION['CONVERSATIONS_ad']['ADS_title']).'"' ; else echo "Conversation";
        ?>
    </title>
</head>
<body ontouchstart="">
<div id="wrapper">
    <div id="main">
        <?php include "Sections/NavBar.php";?>
        <?php include "Sections/ErrorsAndSuccess.php";?>
        <?php if($_ACCESS_DENIED === false):?>
        <div class="Grp-OnChld-S">
        <?php include "Sections/Conversation.php";?>
        </div>
        <?php endif;?>
    </div>
    <div id="footer">
        <?php include "Sections/Footer.php";?>
    </div>
</div>
<?php include "script_conversation.php";?>
</body>
</html>