<?php

include_once 'Config/Constants.php';
include_once 'Config/DataBase.php';

include_once 'Functions/FromsFields_Functions.php';
include_once 'Functions/Utils_Functions.php';
include_once 'Functions/User_Functions.php';
include_once 'Functions/Ad_Functions.php';

include_once 'Functions/Session_Functions.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

function Conversation_New($id_buyer, $id_seller, $id_ad)
{
    global $conn;

    $datetime = date(DB_DATE_FORMAT);
    $sql = "INSERT INTO conversations (CONVERSATIONS_id_userBuyer, CONVERSATIONS_id_userSeller, CONVERSATIONS_id_ad, CONVERSATIONS_creationDatetime) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiis', $id_buyer, $id_seller, $id_ad, $datetime);
    if($stmt->execute())
    {
        return CALLBACK_NO_ERROR;
    }
    else
    {
        return CALLBACK_ERROR;
    }
}

function Conversation_isExist($id_buyer, $id_seller, $id_ad)
{
    global $conn;

    $sql = "SELECT * FROM conversations WHERE CONVERSATIONS_id_ad=? AND CONVERSATIONS_id_userBuyer=? AND CONVERSATIONS_id_userSeller=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iii', $id_ad, $id_buyer, $id_seller);
    $stmt->execute();
    $result = $stmt->get_result();
    $row_nbr = $result->num_rows;

    if($row_nbr === 0)
    {
        return CALLBACK_ERROR;
    }
    else
    {
        return CALLBACK_NO_ERROR;
    }
}

function Conversation_GetIdFromInfo($id_buyer, $id_seller, $id_ad)
{
    global $conn;

    $sql = "SELECT * FROM conversations WHERE CONVERSATIONS_id_ad=? AND CONVERSATIONS_id_userBuyer=? AND CONVERSATIONS_id_userSeller=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iii', $id_ad, $id_buyer, $id_seller);
    $stmt->execute();
    $result = $stmt->get_result();
    $row_nbr = $result->num_rows;

    if($row_nbr === 0)
    {
        return 0;
    }
    else
    {
        return $result->fetch_assoc()['CONVERSATIONS_id'];
    }
}

function Conversation_Get($id)
{
    global $conn;

    $sql = "SELECT * FROM conversations WHERE CONVERSATIONS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row_nbr = $result->num_rows;

    if($row_nbr === 0)
    {
        return array();
    }
    else
    {
        $conversation = $result->fetch_assoc();
        $limit = MESSAGES_SHOW_MAX;

        $sql = "SELECT * FROM (SELECT * FROM messages WHERE MESSAGES_id_conversation=? ORDER BY MESSAGES_postDatetime DESC LIMIT ? ) X ORDER BY MESSAGES_postDatetime ASC";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param('ii', $conversation['CONVERSATIONS_id'], $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $row_nbr = $result->num_rows;

        $conversation['CONVERSATIONS_messageNbr'] = $row_nbr;

        $i = 1;
        while($message = $result->fetch_assoc())
        {$conversation['CONVERSATIONS_message_'.$i] = $message;$i += 1;}

        $ad = array();
        Ad_Get($conversation['CONVERSATIONS_id_ad'], $ad);
        $conversation['CONVERSATIONS_ad'] = $ad;

        $userBuyer = array();
        User_Get($conversation['CONVERSATIONS_id_userBuyer'], $userBuyer);
        $conversation['CONVERSATIONS_userBuyer'] = $userBuyer;

        $userSeller = array();
        User_Get($conversation['CONVERSATIONS_id_userSeller'], $userSeller);
        $conversation['CONVERSATIONS_userSeller'] = $userSeller;

        return $conversation;
    }
}

function Conversation_Delete($id)
{
    global $conn;

    $sql = "DELETE FROM conversations WHERE CONVERSATIONS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    if($stmt->execute())
    {
        $_SESSION['success']['Conversation_Delete'] = "La conversation a été supprimé avec succès";
        return CALLBACK_NO_ERROR;
    }
    else
    {
        $_SESSION['errors']['Conversation_Delete'] = "Une erreur est survenue pendant la suppression de la conversation";
        return CALLBACK_ERROR;
    }
}



function Conversation_NewMessage($message, $id_conversation, $id_user)
{
    global $conn;

    $datetime = date(DB_DATE_FORMAT);
    $sql = "INSERT INTO messages (MESSAGES_content, MESSAGES_id_conversation, MESSAGES_id_user, MESSAGES_postDatetime) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('siis', $message, $id_conversation, $id_user, $datetime);
    if($stmt->execute())
    {
        return CALLBACK_NO_ERROR;
    }
    else
    {
        return CALLBACK_ERROR;
    }
}


function Conversation_GetAllConvFromUser($id)
{
    global $conn;

    $convs = array();

    $sql = "SELECT CONVERSATIONS_id FROM conversations WHERE CONVERSATIONS_id_userBuyer=? OR CONVERSATIONS_id_userSeller=? ORDER BY CONVERSATIONS_creationDatetime DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $id, $id);
    if($stmt->execute())
    {
        $result = $stmt->get_result();
        $convs_id = $result->fetch_all();

        foreach($convs_id as $conv_id)
        {
            array_push($convs, Conversation_Get($conv_id[0]));
        }
        $stmt->close();
    }

    return $convs;
}

function message_action($auto = true)
{
    if($auto)
    {
        Session_LifeControl(false);
    }

    $conversation = array();
    $accessDenied = true;
    $id_ad = $_GET['id_ad'];
    $id_buyer = $_GET['id_buyer'];
    $id_seller = $_GET['id_seller'];
    $message = $_POST['message'];
    $token = $_POST['token'];
    if(check_IfValid_Conversation($id_ad, $id_buyer, $id_seller) === CALLBACK_NO_ERROR)
    {
        $accessDenied = false;

        $conversation_id = "";

        if(isset($_POST['messageNew-btn']))
        {
            $callback_token = check_IfValid_Token($token);

            if(check_IfValid_Message($message) === CALLBACK_NO_ERROR && $callback_token === CALLBACK_NO_ERROR)
            {
                if(!(Conversation_isExist($id_buyer, $id_seller, $id_ad) === CALLBACK_NO_ERROR))
                {$callback_Exist = Conversation_New($id_buyer,$id_seller,$id_ad);}
                else
                {$callback_Exist = CALLBACK_NO_ERROR;}

                if($callback_Exist === CALLBACK_NO_ERROR)
                {
                    $conversation_id = Conversation_GetIdFromInfo($id_buyer, $id_seller, $id_ad);
                    if(Conversation_NewMessage($message, $conversation_id, $_SESSION['id']) === CALLBACK_NO_ERROR)
                    {
                        $message = "";
                    }
                }
            }
            else
            {
                $ad = array();
                Ad_Get($id_ad, $ad);
                $conversation['CONVERSATIONS_ad'] = $ad;

                $userBuyer = array();
                User_Get($id_buyer, $userBuyer);
                $conversation['CONVERSATIONS_userBuyer'] = $userBuyer;

                $userSeller = array();
                User_Get($id_seller, $userSeller);
                $conversation['CONVERSATIONS_userSeller'] = $userSeller;
                $conversation_id = Conversation_GetIdFromInfo($id_buyer, $id_seller, $id_ad);

                if(!$auto && $callback_token === CALLBACK_NO_ERROR)
                {
                    $_SESSION['errors']['Message_New'] = "Message trop long (Les emojis comptent pour 4 caratères)";
                }
            }
        }
        else
        {
            if(!(Conversation_isExist($id_buyer, $id_seller, $id_ad) === CALLBACK_NO_ERROR))
            {
                $ad = array();
                Ad_Get($id_ad, $ad);
                $conversation['CONVERSATIONS_ad'] = $ad;

                $userBuyer = array();
                User_Get($id_buyer, $userBuyer);
                $conversation['CONVERSATIONS_userBuyer'] = $userBuyer;

                $userSeller = array();
                User_Get($id_seller, $userSeller);
                $conversation['CONVERSATIONS_userSeller'] = $userSeller;

            }
            else
            {
                $conversation_id = Conversation_GetIdFromInfo($id_buyer, $id_seller, $id_ad);
            }
        }

        if(!empty($conversation_id))
        {
            $conversation = Conversation_Get($conversation_id);
        }

    }
    else
    {
        $accessDenied = true;
        unset($_SESSION['errors']);
        if(!$auto) $_SESSION['errors']['Conversation_View'] = "Impossible d'acceder à cette conversation";
    }

    if($auto)
    {
        return json_encode($conversation);
    }
    else
    {
        $_CONVERSATION_VIEW['message'] = $message;
        $_CONVERSATION_VIEW['conversation'] = $conversation;
        $_CONVERSATION_VIEW['accessDenied'] = $accessDenied;

        return $_CONVERSATION_VIEW;
    }
}




