<?php

include_once 'Config/Constants.php';
include_once 'Config/DataBase.php';

include_once 'Functions/Utils_Functions.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

function User_New(&$username, &$email, &$email_privacy, &$phoneNumber, &$phoneNumber_privacy, &$city, &$city_privacy, &$password)
{
    global $conn;

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    $datetime = date(DB_DATE_FORMAT);
    $sql = "INSERT INTO users (USERS_username, USERS_email, USERS_emailPrivacy, USERS_phoneNumber, USERS_phoneNumberPrivacy, USERS_city, USERS_cityPrivacy, USERS_password, USERS_signupDatetime) VALUES (?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssisisiss', $username, $email, boolval($email_privacy), $phoneNumber, boolval($phoneNumber_privacy), $city, boolval($city_privacy), $password_hashed, $datetime);

    if($stmt->execute())
    {
        return CALLBACK_NO_ERROR;
    }
    else
    {
        $_SESSION['errors']['signup'] = "Une erreur est survenue durant l'inscription";
        return CALLBACK_ERROR;
    }
}

function User_Connect($username, $password, $isNewAccount = false)
{
    global $conn;

    $sql = "SELECT * FROM users WHERE USERS_email=? OR USERS_username=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $username);
    if($stmt->execute())
    {
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if(password_verify($password, $user['USERS_password']))
        {
            $_SESSION['id'] = $user['USERS_id'];
            $_SESSION['username'] = $user['USERS_username'];
            $_SESSION['email'] = $user['USERS_email'];
            $_SESSION['phoneNumber'] = $user['USERS_phoneNumber'];
            $_SESSION['city'] = $user['USERS_city'];
            $_SESSION['emailPrivacy'] = getFixedPrivacy($user['USERS_emailPrivacy']);
            $_SESSION['phoneNumberPrivacy'] = getFixedPrivacy($user['USERS_phoneNumberPrivacy']);
            $_SESSION['cityPrivacy'] = getFixedPrivacy($user['USERS_cityPrivacy']);
            $_SESSION['profilPicture'] = getFixedProfilPicture($user['USERS_profilPicturePath']);
            $_SESSION['password'] = $password;
            $_SESSION['signupDatetime'] = $user['USERS_signupDatetime'];
            $_SESSION["loginDatetime"] = date(DB_DATE_FORMAT);

            if($isNewAccount) $_SESSION['success']['login'] = "Vous vous êtes inscrit avec succès !";
            else $_SESSION['success']['login'] = "Vous vous êtes connecté avec succès !";

            header('location: mon-compte.php');
            exit();
        }
        else
        {
            $_SESSION['errors']['login'] = "Pseudo ou mot de passe incorrect";
        }
    }
    else
    {
        $_SESSION['errors']['login'] = "Une erreur est survenue pendant la connection";
        if($isNewAccount) $_SESSION['errors']['login'] .= ", mais votre compte a bien été crée";
    }
    $stmt->close();
}

function User_Update($id, &$email, &$emailPrivacy, &$phoneNumber, &$phoneNumberPrivacy, &$city, &$cityPrivacy, &$profilPicture, &$password, &$callback_canUpdate, $isUserConnected = true)
{
    if(isCallBack_fullOf_NO_ERROR_OR_SAME($callback_canUpdate))
    {
        if($callback_canUpdate['email'] === CALLBACK_NO_ERROR)
        {
            User_UpdateEmail($id,$email);
            if($isUserConnected) $_SESSION['email'] = $email;
        }
        if($callback_canUpdate['emailPrivacy'] === CALLBACK_NO_ERROR)
        {
            if($isUserConnected) $_SESSION['emailPrivacy'] = $emailPrivacy;
            User_UpdateEmailPrivacy($id,$emailPrivacy);
        }
        if($callback_canUpdate['phoneNumber'] === CALLBACK_NO_ERROR)
        {
            if($isUserConnected) $_SESSION['phoneNumber'] = $phoneNumber;
            User_UpdatePhoneNumber($id,$phoneNumber);
        }
        if($callback_canUpdate['phoneNumberPrivacy'] === CALLBACK_NO_ERROR)
        {
            if($isUserConnected) $_SESSION['phoneNumberPrivacy'] = $phoneNumberPrivacy;
            User_UpdatePhoneNumberPrivacy($id,$phoneNumberPrivacy);
        }
        if($callback_canUpdate['city'] === CALLBACK_NO_ERROR)
        {
            if($isUserConnected) $_SESSION['city'] = $city;
            User_UpdateCity($id,$city);
        }
        if($callback_canUpdate['cityPrivacy'] === CALLBACK_NO_ERROR)
        {
            if($isUserConnected) $_SESSION['cityPrivacy'] = $cityPrivacy;
            User_UpdateCityPrivacy($id,$cityPrivacy);
        }
        if($callback_canUpdate['profilPicture'] === CALLBACK_NO_ERROR)
        {
            if($isUserConnected) $_SESSION['profilPicture'] = getFixedProfilPicture($profilPicture);
            User_UpdateProfilPicture($id,$profilPicture);
        }
        if($callback_canUpdate['password'] === CALLBACK_NO_ERROR && $callback_canUpdate['passwordAndConf'])
        {
            if($isUserConnected) $_SESSION['password'] = $password;
            User_UpdatePassword($id,$password);
        }

        if(isCallBack_fullOf_SAME($callback_canUpdate))
        {
            $_SESSION['success']['User_Update'] = "Aucune mofication n'a été apporté à vos informations";
            return CALLBACK_NO_ERROR;
        }
        else
        {
            $_SESSION['success']['User_Update'] = "Vos informations ont été modifié avec succès";
            return CALLBACK_NO_ERROR;
        }
    }
    else
    {
        return CALLBACK_ERROR;
    }
}

function User_isExist($user_id)
{
    global $conn;

    $sql = "SELECT * FROM users WHERE USERS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    if($stmt->execute())
    {
        $result = $stmt->get_result();
        $stmt->close();

        if($result->num_rows === 1)
        {
            return CALLBACK_NO_ERROR;
        }
        else
        {
            return CALLBACK_ERROR;
        }
    }
    else
    {
        $_SESSION['errors']['User_isExist'] = "Une erreur s'est produite pendant la vérification de l'existance de l'utilisateur";
        $stmt->close();
        return CALLBACK_ERROR;
    }
}


function User_Get($id, &$user, $errorOn = true)
{
    global $conn;

    $sql = "SELECT * FROM users WHERE USERS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    if($stmt->execute())
    {
        $result = $stmt->get_result();
        $row_number = $result->num_rows;

        if($row_number === 1)
        {
            $user = $result->fetch_assoc();
            $user['USERS_emailPrivacy'] = getFixedPrivacy($user['USERS_emailPrivacy']);
            $user['USERS_phoneNumberPrivacy'] = getFixedPrivacy($user['USERS_phoneNumberPrivacy']);
            $user['USERS_cityPrivacy'] = getFixedPrivacy($user['USERS_cityPrivacy']);
            $user['USERS_profilPicturePath'] = getFixedProfilPicture($user['USERS_profilPicturePath']);

            return CALLBACK_NO_ERROR;
        }
        else
        {
            $user = array();
            if($errorOn) $_SESSION['errors']['User_Get'] = "L'utilisateur d'identifiant \"".$id."\" n'existe pas";
            return CALLBACK_ERROR;
        }
    }
    else
    {   $user = array();
        if($errorOn) $_SESSION['errors']['User_Get'] = "Une erreur s'est produite pendant la récupération de l'utilisateur";
        return CALLBACK_ERROR;
    }
}


function User_Disconnect($message = "")
{
    session_destroy();
    if(!empty($message))
    {
        if (session_status() == PHP_SESSION_NONE)
        {
            session_start();
        }
        $_SESSION['errors']['User_Disconnect'] = $message;
    }
    header('location: connection.php');
    exit();
}

function User_Delete($id)
{
    global $conn;

    $sql = "SELECT USERS_profilPicturePath FROM users WHERE USERS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $pictureProfil = $stmt->get_result()->fetch_assoc()['USERS_profilPicturePath'];

    $sql = "DELETE FROM users WHERE USERS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    if($stmt->execute())
    {
        if($pictureProfil != DB_PROFILPICTURE_DEFAULT)
        {unlink(FOLDERPATH_PROFILPICTURE.$pictureProfil);}
        $_SESSION['success']['User_Delete'] = "Le compte à été supprimé avec succès";
        return CALLBACK_NO_ERROR;
    }
    else
    {
        $_SESSION['errors']['User_Delete'] = "Une erreur est survenue pendant la suppression du compte";
        return CALLBACK_ERROR;
    }
}

function User_UpdateEmail($id, $email)
{
    global $conn;

    $sql = "UPDATE users SET USERS_email=? WHERE USERS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $email, $id);
    if($stmt->execute())
    {
        $stmt->close();
        return CALLBACK_NO_ERROR;
    }
    else
    {
        $stmt->close();
        $_SESSION['errors']['User_UpdateEmail'] = "Un erreur s'est produite pendant la mise à jour de l'email";
        return CALLBACK_ERROR;
    }
}

function User_UpdateEmailPrivacy($id, $emailPrivacy)
{
    global $conn;

    $sql = "UPDATE users SET USERS_emailPrivacy=? WHERE USERS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $emailPrivacy, $id);
    if($stmt->execute())
    {
        $stmt->close();
        return CALLBACK_NO_ERROR;
    }
    else
    {
        $stmt->close();
        $_SESSION['errors']['User_UpdateEmailPrivacy'] = "Un erreur s'est produite pendant la mise à jour de la confidentialité de l'email";
        return CALLBACK_ERROR;
    }
}

function User_UpdatePhoneNumber($id, $phoneNumber)
{
    global $conn;

    $sql = "UPDATE users SET USERS_phoneNumber=? WHERE USERS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $phoneNumber, $id);
    if($stmt->execute())
    {
        $stmt->close();
        return CALLBACK_NO_ERROR;
    }
    else
    {
        $stmt->close();
        $_SESSION['errors']['User_UpdatePhoneNumber'] = "Un erreur s'est produite pendant la mise à jour du numéro de téléphone";
        return CALLBACK_ERROR;
    }
}

function User_UpdatePhoneNumberPrivacy($id, $phoneNumberPrivacy)
{
    global $conn;

    $sql = "UPDATE users SET USERS_phoneNumberPrivacy=? WHERE USERS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $phoneNumberPrivacy, $id);
    if($stmt->execute())
    {
        $stmt->close();
        return CALLBACK_NO_ERROR;
    }
    else
    {
        $stmt->close();
        $_SESSION['errors']['User_UpdatePhoneNumberPrivacy'] = "Un erreur s'est produite pendant la mise à jour de la confidentialité du numéro de téléphone";
        return CALLBACK_ERROR;
    }
}

function User_UpdateCity($id, $city)
{
    global $conn;

    $sql = "UPDATE users SET USERS_city=? WHERE USERS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $city, $id);
    if($stmt->execute())
    {
        $stmt->close();
        return CALLBACK_NO_ERROR;
    }
    else
    {
        $stmt->close();
        $_SESSION['errors']['User_UpdateCity'] = "Un erreur s'est produite pendant la mise à jour du numéro de la ville";
        return CALLBACK_ERROR;
    }
}

function User_UpdateCityPrivacy($id, $cityPrivacy)
{
    global $conn;

    $sql = "UPDATE users SET USERS_cityPrivacy=? WHERE USERS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $cityPrivacy, $id);
    if($stmt->execute())
    {
        $stmt->close();
        return CALLBACK_NO_ERROR;
    }
    else
    {
        $stmt->close();
        $_SESSION['errors']['User_UpdateCityPrivacy'] = "Un erreur s'est produite pendant la mise à jour de la confidentialité du numéro de la ville";
        return CALLBACK_ERROR;
    }
}

function User_UpdateProfilPicture($id, $profilPicture)
{
    global $conn;

    $conn->autocommit(false);
    $conn->begin_transaction();

    $sql = "SELECT USERS_profilPicturePath FROM users WHERE USERS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $success["User_UpdateProfilPicture__GetNameOld"] = $stmt->execute();
    $result = $stmt->get_result();
    $profilPictureName_Old = $result->fetch_assoc()['USERS_profilPicturePath'];

    $profilPictureName_New = md5(uniqid('',true)) . '.' . pathinfo($profilPicture['name'], PATHINFO_EXTENSION);
    pictureFixOrientation($profilPicture, $profilPicture['tmp_name']);
    pictureResize($profilPicture['tmp_name'], PROFILPICTURE_WIDTH_RESIZE, PROFILPICTURE_HEIGHT_RESIZE);
    $success["User_UpdateProfilPicture_-Upload"] = move_uploaded_file($profilPicture['tmp_name'], FOLDERPATH_PROFILPICTURE.$profilPictureName_New);

    $sql = "UPDATE users SET USERS_profilPicturePath=? WHERE USERS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $profilPictureName_New, $id);
    $success["User_UpdateProfilPicture__UpdatePicture"] = $stmt->execute();

    $noError = true;
    foreach($success as $success_item){if($success_item === false){$noError = false;break;}}

    if($noError)
    {
        $conn->commit();
        if($profilPictureName_Old != DB_PROFILPICTURE_DEFAULT)
        {
            unlink(FOLDERPATH_PROFILPICTURE.$profilPictureName_Old);
        }
        $_SESSION['profilPicture'] = $profilPictureName_New;
    }
    else
    {
        $conn->rollback();
        if($success["User_UpdateProfilPicture__Upload"] === true)
        {
            unlink(FOLDERPATH_PROFILPICTURE.$profilPictureName_New);
        }
        $_SESSION['errors']['User_UpdateProfilPicture'] = "Une erreur est survenue pendant la mise à jour de la photo de profile";
    }

    $conn->autocommit(true);
    $stmt->close();
}

function User_UpdatePassword($id, $password)
{
    global $conn;

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET USERS_password=? WHERE USERS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $password_hashed, $id);
    if($stmt->execute())
    {
        $stmt->close();
        return CALLBACK_NO_ERROR;
    }
    else
    {
        $stmt->close();
        $_SESSION['errors']['User_UpdatePassword'] = "Un erreur s'est produite pendant la mise à jour du mot de passe";
        return CALLBACK_ERROR;
    }
}

