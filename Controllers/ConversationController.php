<?php

require_once 'Config/DataBase.php';
require_once 'Config/Constants.php';

require_once 'Functions/ConversationFunctions.php';
require_once 'Functions/FromsFieldsFunctions.php';
require_once 'Functions/UtilsFunctions.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

//Créer une conversation
if(end(explode('/',$_SERVER['PHP_SELF'])) === "consulter-une-annonce.php" && isset($_POST['conversationNew-btn']))
{
    // On récupère les valeurs
    $id_buyer = $_SESSION['id'];
    $id_seller = $_USER['USERS_id'];
    $id_ad = $_AD['ADS_id'];

    // Si l'utilisateur ne tente pas de créer une conversation avec lui-même
    if($id_buyer == $id_seller)
    {
        $_SESSION['errors']['Conversation_New'] = "Vous ne pouvez pas communiquer avec cet utilisateur";
    }
    // Sinon on le redirige vers une nouvelle conversation
    else
    {
        redirectTo('conversation.php?id_buyer='.$id_buyer.'&id_seller='.$id_seller.'&id_ad='.$id_ad);
    }
}

//Supprimer une conversation
if(end(explode('/',$_SERVER['PHP_SELF'])) === "mon-compte.php" && isset($_GET['delete']))
{
    //  On récupère l'identifiant de la conversation
    $conversation = Conversation_Get($_GET['id_conversation']);

    //  Si la conversation existe et que l'utilisateur en fait partie, on supprime la conversation
    if($conversation)
    {
        if($conversation['CONVERSATIONS_id_userBuyer'] == $_SESSION['id'] ||
            $conversation['CONVERSATIONS_id_userSeller'] == $_SESSION['id'])
        {
            Conversation_Delete($_GET['id_conversation']);
        }
    }
}

//Afficher une conversation
$_CONVERSATION_VIEW = array();
if(end(explode('/',$_SERVER['PHP_SELF'])) === "conversation.php")
{
    // On stock la conversation pour l'affichage
    $_CONVERSATION_VIEW = Conversation_Action(false);
}

//Afficher la liste des conversation
$_CONVERSATION_LISTVIEW = array();
if(end(explode('/',$_SERVER['PHP_SELF'])) === "mon-compte.php")
{
    // On récupère toutes les informations où l'utilisateur participe
    $convs = Conversation_GetAllConvFromUser($_SESSION['id']);

    $_CONVERSATION_LISTVIEW['conversations'] = $convs;
}