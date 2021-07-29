<?php

include_once 'Config/Constants.php';
include_once 'Config/DataBase.php';

include_once 'Functions/UtilsFunctions.php';
include_once 'Functions/ConversationFunctions.php';
include_once 'Functions/AdFunctions.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

/* Vérifier si des identifiants sont valides

*/
function check_IfValid_Credentials($username, $password, $errorOn = true)
{
    global $conn;

    if(empty($username) || empty($password))
    {
        if($errorOn) $_SESSION['errors']['password'] = "Mot de passe requis";
        return CALLBACK_EMPTY;
    }
    else
    {
        $sql = "SELECT * FROM users WHERE USERS_email=? OR USERS_username=? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $username, $username);
        if($stmt->execute())
        {
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if(!password_verify($password, $user['USERS_password']))
            {
                if($errorOn) $_SESSION['errors']['password'] = "Mot de passe incorrect";
                return CALLBACK_ERROR;
            }
            else
            {
                return CALLBACK_NO_ERROR;
            }
        }
        else
        {
            if($errorOn) $_SESSION['errors']['password'] = "Une erreur est survenue pendant la verification du mot de passe";
            return CALLBACK_ERROR;
        }
    }
}

/* Vérifier si un identifiant ou un email est valide

*/
function check_IfValid_UsernameOrEmail(&$username, $errorOn = true)
{
    if(empty($username))
    {
        if($errorOn) $_SESSION['errors']['username'] = 'Pseudo ou email requis';
        return CALLBACK_EMPTY;
    }
    else
    {
        return CALLBACK_NO_ERROR;
    }
}

/* Vérifier si un identifiant est valide

*/
function check_IfValid_Username(&$username, $isNew, $errorOn = true)
{
    global $conn;

    if(empty($username))
    {
        if($errorOn) $_SESSION['errors']['username'] = 'Pseudo requis';
        return CALLBACK_EMPTY;
    }

    if(strlen($username) > USERNAME_CHAR_MAX)
    {
        if($errorOn) $_SESSION['errors']['username'] = 'Pseudo trop long';
        return CALLBACK_ERROR;
    }

    if(strlen($username) < USERNAME_CHAR_MIN)
    {
        if($errorOn) $_SESSION['errors']['username'] = 'Pseudo trop court';
        return CALLBACK_ERROR;
    }

    $isOk = 1;
    for($i = 0; $i < strlen($username) && $isOk === 1; $i++)
    {
        if(!ctype_alnum($username[$i]))
        {
            $isOk = 0;
        }
    }
    if($isOk === 0)
    {
        if($errorOn) $_SESSION['errors']['username'] = 'Pseudo invalide';
        return CALLBACK_ERROR;
    }

    if($isNew)
    {
        $sql = "SELECT * FROM users WHERE USERS_username=? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s',$username);
        $stmt->execute();
        $result = $stmt->get_result();
        $userCount = (int)$result->num_rows;
        $stmt->close();

        if($userCount > 0)
        {
            if($errorOn) $_SESSION['errors']['username'] = 'Pseudo déjà existant';
            return CALLBACK_ALREADY;
        }
    }
    else
    {
        return CALLBACK_NO_ERROR;
    }

    return CALLBACK_NO_ERROR;
}

/* Vérifier si un email est valide

*/
function check_IfValid_Email(&$email, $isNew, $errorOn = true)
{
    global $conn;

    if(empty($email))
    {
        if($errorOn) $_SESSION['errors']['email'] = 'Email requis';
        return CALLBACK_EMPTY;
    }

    if(strlen($email) > EMAIL_CHAR_MAX)
    {
        if($errorOn) $_SESSION['errors']['email'] = 'Email trop long';
        return CALLBACK_ERROR;
    }

    if(!filter_var($email,FILTER_VALIDATE_EMAIL))
    {
        if($errorOn) $_SESSION['errors']['email'] = 'Email invalide';
        return CALLBACK_ERROR;
    }

    if($isNew)
    {
        $sql = "SELECT * FROM users WHERE USERS_email=? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s',$email);
        $stmt->execute();
        $result = $stmt->get_result();
        $userCount = (int)$result->num_rows;
        $stmt->close();

        if($userCount > 0)
        {
            if($errorOn) $_SESSION['errors']['email'] = 'Email déjè existant';
            return CALLBACK_ALREADY;
        }
    }
    else
    {
        return CALLBACK_NO_ERROR;
    }


    return CALLBACK_NO_ERROR;
}

/* Vérifier si un numéro de téléphone est valide

    Le numéro ne peut pas être surtaxé

*/
function check_IfValid_PhoneNumber(&$phoneNumber, $errorOn = true)
{
    if(empty($phoneNumber))
    {
        if($errorOn) $_SESSION['errors']['phoneNumber'] = 'Numéro de téléphone requis';
        return CALLBACK_EMPTY;
    }
    elseif(strlen($phoneNumber) > PHONENUMBER_CHAR_MAX || strlen($phoneNumber) < PHONENUMBER_CHAR_MIN || !preg_match("/0[0-7, 9][0-9]{8}/", $phoneNumber))
    {
        if($errorOn) $_SESSION['errors']['phoneNumber'] = 'Numéro de téléphone invalide';
        return CALLBACK_ERROR;
    }

    return CALLBACK_NO_ERROR;
}

/* Vérifier si une ville est valide

    La ville doit exister dans la base de donnée

*/
function check_IfValid_City(&$city, $errorOn = true)
{
    global $conn;

    if(empty($city))
    {
        if($errorOn) $_SESSION['errors']['city'] = 'Ville requise';
        return CALLBACK_EMPTY;
    }
    elseif(strlen($city) > CITY_CHAR_MAX)
    {
        if($errorOn) $_SESSION['errors']['city'] = 'Nom de ville trop long';
        return CALLBACK_ERROR;
    }
    else
    {
        $sql = "SELECT ville_nom_reel FROM villes_france_free WHERE LOWER(CONCAT(ville_nom_reel,' (',ville_departement,')'))=? ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s',$city);
        if($stmt->execute())
        {
            $result = $stmt->get_result();
            $city_count = (int)$result->num_rows;
            $stmt->close();

            if($city_count === 0)
            {
                if($errorOn) $_SESSION['errors']['city'] = 'Nom de ville incorrect';
                return CALLBACK_ERROR;
            }

            return CALLBACK_NO_ERROR;
        }
        else
        {
            if($errorOn) $_SESSION['errors']['city'] = 'Une erreur est survenue pendant la vérification de la ville';
            return CALLBACK_ERROR;
        }
    }
}

/* Vérifier si un mot de passe est valide

*/
function check_IfValid_Password(&$password, $errorOn = true)
{
    if(empty($password))
    {
        if($errorOn) $_SESSION['errors']['password'] = 'Mot de passe requis';
        return CALLBACK_EMPTY;
    }
    else
    {
        return CALLBACK_NO_ERROR;
    }
}

/* Vérifier si un mot de passe et sa confirmation est valide

*/
function check_IfValid_PasswordAndConf(&$passwordNew, &$passwordConf, $isNew, $errorOn = true)
{

    if(empty($passwordNew) && empty($passwordConf))
    {
        if($isNew && $errorOn)  $_SESSION['errors']['passwordAndConf'] = 'Nouveau mot de passe et confirmation requise';
        elseif($errorOn)        $_SESSION['errors']['passwordAndConf'] = 'Mot de passe et confirmation requise';

        return CALLBACK_EMPTY;
    }
    elseif(empty($passwordNew))
    {
        if($isNew && $errorOn)  $_SESSION['errors']['passwordAndConf'] = 'Nouveau mot de passe requis';
        elseif($errorOn)        $_SESSION['errors']['passwordAndConf'] = 'Mot de passe requis';

        return CALLBACK_ERROR;
    }
    elseif(empty($passwordConf))
    {
        if($isNew && $errorOn)  $_SESSION['errors']['passwordAndConf'] = 'Confirmation du nouveau mot de passe requises';
        elseif($errorOn)        $_SESSION['errors']['passwordAndConf'] = 'Confirmation du mot de passe requise';

        return CALLBACK_ERROR;
    }
    elseif(strlen($passwordNew) < PASSWORD_CHAR_MIN)
    {
        if($isNew && $errorOn)  $_SESSION['errors']['passwordAndConf'] = 'Nouveau mot de passe trop court';
        elseif($errorOn)        $_SESSION['errors']['passwordAndConf'] = 'Mot de passe trop court';

        return CALLBACK_ERROR;
    }
    elseif(strlen($passwordNew) > PASSWORD_CHAR_MAX)
    {
        if($isNew && $errorOn)  $_SESSION['errors']['passwordAndConf'] = 'Nouveau mot de passe trop long';
        elseif($errorOn)        $_SESSION['errors']['passwordAndConf'] = 'Mot de passe trop long';

        return CALLBACK_ERROR;
    }
    elseif(empty($passwordConf))
    {
        if($isNew && $errorOn)  $_SESSION['errors']['passwordAndConf'] = 'Confirmation du nouveau mot de passe requise';
        elseif($errorOn)        $_SESSION['errors']['passwordAndConf'] = 'Confirmation du mot de passe requise';

        return CALLBACK_ERROR;
    }
    elseif($passwordNew !== $passwordConf)
    {
        if($isNew && $errorOn)  $_SESSION['errors']['passwordAndConf'] = 'Nouveau mot de passe non identiques à la confirmation';
        elseif($errorOn)        $_SESSION['errors']['passwordAndConf'] = 'Mots de passe non identiques';

        return CALLBACK_ERROR;
    }
    else
    {
        return CALLBACK_NO_ERROR;
    }
}

/* Vérifier si un photo de profil est valide

    On vérifie qu'il s'agit bien en interne d'une image PNG ou JPEG
    Elle ne peut pas être plus grande , ni plus lourd que les valeurs définies

*/
function check_IfValid_ProfilPicture(&$pictureProfil, $errorOn = true)
{
    $pictureProfil_file = $pictureProfil['tmp_name'];

    if (empty($pictureProfil_file) || !is_uploaded_file($pictureProfil_file)) {
        if($errorOn) $_SESSION['errors']["profilPicture"] = "Photo de profil requise";

        return CALLBACK_EMPTY;
    }
    if (!getimagesize($pictureProfil_file) || (exif_imagetype($pictureProfil_file) != 2 && exif_imagetype($pictureProfil_file) != 3) || !in_array(getimagesize($pictureProfil_file)['mime'], PICTURE_MIME_ALLOWED)) {
        if($errorOn) $_SESSION['errors']['profilPicture'] = "La photo de profil doit être de type JPEG ou PNG";
        return CALLBACK_ERROR;
    }
    if (getimagesize($pictureProfil_file)[0] > PICTURE_WIDTH_MAX || getimagesize($pictureProfil_file)[0] > PICTURE_HEIGHT_MAX) {
        if($errorOn) $_SESSION['errors']['profilPicture'] = "La photo de profil ne peut pas faire plus de " . PICTURE_WIDTH_MAX . "px de largeur et " . PICTURE_HEIGHT_MAX . "px de hauteur";
        return CALLBACK_ERROR;
    }
    if (filesize($pictureProfil_file) > PICTURE_SIZE_MAX) {
        if($errorOn) $_SESSION['errors']['profilPicture'] = "La photo de profil ne peut pas faire plus de " . (PICTURE_SIZE_MAX / 1000) . "Ko";
        return CALLBACK_ERROR;
    }

    return CALLBACK_NO_ERROR;
}

/* Vérifier si un titre est valide

*/
function check_IfValid_Title(&$title, $errorOn = true)
{
    if (empty($title))
    {
        if($errorOn) $_SESSION['errors']['title'] = "Titre requis";
        return CALLBACK_EMPTY;
    }

    if (strlen($title) < TITLE_CHAR_MIN)
    {
        if($errorOn) $_SESSION['errors']['title'] = "Titre trop court";
        return CALLBACK_ERROR;
    }

    if (strlen($title) > TITLE_CHAR_MAX)
    {
        if($errorOn) $_SESSION['errors']['title'] = "Titre trop long";
        return CALLBACK_ERROR;
    }

    return CALLBACK_NO_ERROR;
}

/* Vérifier si une description est valide

*/
function check_IfValid_Description(&$description, $errorOn = true)
{
    if (empty($description))
    {
        if($errorOn) $_SESSION['errors']['description'] = "Description requise";
        return CALLBACK_EMPTY;
    }

    if (strlen($description) < DESCRIPTION_CHAR_MIN)
    {
        if($errorOn) $_SESSION['errors']['description'] = "Description trop courte";
        return CALLBACK_ERROR;
    }

    if (strlen($description) > DESCRIPTION_CHAR_MAX)
    {
        if($errorOn) $_SESSION['errors']['description'] = "Description trop longue";
        return CALLBACK_ERROR;
    }

    return CALLBACK_NO_ERROR;
}

/* Vérifier si une catégorie est valide

*/
function check_IfValid_Category(&$category, $errorOn = true)
{
    if (empty($category) || AD_CATEGORIES[0][1] === $category)
    {
        if($errorOn) $_SESSION['errors']['category'] = "Catégorie requise";
        return CALLBACK_EMPTY;
    }

    if (strlen($category) > CATEGORY_CHAR_MAX)
    {
        if($errorOn) $_SESSION['errors']['category'] = "Catégorie trop longues";
        return CALLBACK_ERROR;
    }

    $categoryExist = false;
    for($i = 1; $i < count(AD_CATEGORIES); $i++)
    {
        for($j = 1; $j < count(AD_CATEGORIES[$i]); $j++)
        {
            if($category === AD_CATEGORIES[$i][$j])
            {
                $categoryExist = true;
                break 2;
            }
        }
    }
    if(!$categoryExist)
    {
        if($errorOn) $_SESSION['errors']['category'] = "Catégorie inconnue";
        return CALLBACK_ERROR;
    }

    return CALLBACK_NO_ERROR;
}

/* Vérifier si prix est valide

*/
function check_IfValid_Price(&$price, $errorOn = true)
{
    if(empty($price))
    {
        if($errorOn) $_SESSION['errors']['price'] = "Prix requis";
        return CALLBACK_EMPTY;
    }

    if(intval($price) > PRICE_INT_MAX || strlen($price) > PRICE_CHAR_MAX)
    {
        if($errorOn) $_SESSION['errors']['price'] = "Prix trop élevé ";
        return CALLBACK_ERROR;
    }

    if(intval($price) < PRICE_INT_MIN || strlen($price) < PRICE_CHAR_MIN)
    {
        if($errorOn) $_SESSION['errors']['price'] = "Prix trop bas";
        return CALLBACK_ERROR;
    }

    if(!ctype_digit($price))
    {

        if(is_float($price + 0))
        {
            if($errorOn) $_SESSION['errors']['price'] = "Le prix doit être rond";
        }
        else
        {
            if($errorOn) $_SESSION['errors']['price'] = "Prix non valide";
        }

        return CALLBACK_ERROR;
    }

    return CALLBACK_NO_ERROR;
}

/* Vérifier si les images uploadées sont valides

    On vérifie qu'il s'agit bien en interne d'images PNG ou JPEG
    Elles ne peuvent pas être plus grandes , ni plus lourdes que les valeurs définies

*/
function check_IfValid_Pictures(&$pictureNbr, $errorOn = true)
{
    if ($pictureNbr < PICTURE_NBR_MIN)
    {
        if($errorOn) if($errorOn) $_SESSION['errors']['picture'] = "Veuillez joindre au moins" . PICTURE_NBR_MIN . "photo";
        return CALLBACK_ERROR;
    }

    if ($pictureNbr > PICTURE_NBR_MAX)
    {
        if($errorOn) if($errorOn) $_SESSION['errors']['picture'] = "Veuillez joindre au maximim" . PICTURE_NBR_MAX . " photos";
        return CALLBACK_ERROR;
    }

    $callback = array();

    for ($i = 1; $i <= $pictureNbr; $i++)
    {
        $pictureId = 'picture-' . $i;
        $picture = $_FILES[$pictureId]['tmp_name'];

        if (empty($picture) || !is_uploaded_file($picture)) {
            if($errorOn) if($errorOn) $_SESSION['errors'][$pictureId] = "Photo " . $i . " requise";
            $callback[$pictureId] = CALLBACK_EMPTY;
            goto end;
        }

        if (!getimagesize($picture) || (exif_imagetype($picture) != 2 && exif_imagetype($picture) != 3) || !in_array(getimagesize($picture)['mime'], PICTURE_MIME_ALLOWED)) {
            if($errorOn) if($errorOn) $_SESSION['errors'][$pictureId] = "Le fichier " . $i . " doit être une photo de type JPEG ou PNG";
            $callback[$pictureId] = CALLBACK_ERROR;
            goto end;
        }

        if (getimagesize($picture)[0] > PICTURE_WIDTH_MAX || getimagesize($picture)[0] > PICTURE_HEIGHT_MAX) {
            if($errorOn) if($errorOn) $_SESSION['errors'][$pictureId] = "La photo " . $i . " ne peut pas faire plus de " . PICTURE_WIDTH_MAX . "px de largeur et " . PICTURE_HEIGHT_MAX . "px de hauteur";
            $callback[$pictureId] = CALLBACK_ERROR;
            goto end;
        }

        if (filesize($picture) > PICTURE_SIZE_MAX) {
            if($errorOn) if($errorOn) $_SESSION['errors'][$pictureId] = "La photo " . $i . " ne peut pas faire plus de " . (PICTURE_SIZE_MAX / 1000) . "Ko";
            $callback[$pictureId] = CALLBACK_ERROR;
            goto end;
        }

        $callback[$pictureId] = CALLBACK_NO_ERROR;

        end:
    }

    if(isCallBack_fullOf_EMPTY($callback))
    {
        return CALLBACK_EMPTY;
    }
    if(isCallBack_fullOf_NO_ERROR($callback))
    {
        return CALLBACK_NO_ERROR;
    }
    else
    {
        return CALLBACK_ERROR;
    }
}

/* Vérifier si une annonce existe

*/
function check_IfExist_AdId($id, $errorOn = true)
{
    global $conn;

    $sql = "SELECT * FROM ads WHERE ADS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1 && Ad_isDateValid($result->fetch_assoc()['ADS_creationDatetime']) === CALLBACK_NO_ERROR)
    {
        return CALLBACK_NO_ERROR;
    }
    elseif($result->num_rows === 1 && !(Ad_isDateValid($result->fetch_assoc()['ADS_creationDatetime']) === CALLBACK_NO_ERROR))
    {
        Ad_Delete($result->fetch_assoc()['ADS_id']);
        return CALLBACK_ERROR;
    }
    else
    {
        if($errorOn) $_SESSION['errors']['NotExist_AdId'] = "L'annonce d'identifiant \"".$id."\" n'existe pas";
        return CALLBACK_ERROR;
    }
}

/* Vérifier si un utilisateur existe

*/
function check_IfExist_UserId($id, $errorOn = true)
{
    global $conn;

    $sql = "SELECT * FROM users WHERE USERS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row_nbr = $result->num_rows;

    if($row_nbr == 1)
    {
        return CALLBACK_NO_ERROR;
    }
    else
    {
        if($errorOn) $_SESSION['errors']['NotExist_AdId'] = "L'utilisateur d'identifiant \"".$id."\" n'existe pas";
        return CALLBACK_ERROR;
    }
}

/* Vérifier si une annonce appartient à un utilisateur

*/
function check_IfMatch_AdIdUserId($ad_id, $user_id, $errorOn = true)
{
    global $conn;

    if(check_IfExist_UserId($user_id,$errorOn) === CALLBACK_NO_ERROR && check_IfExist_AdId($ad_id,$errorOn) === CALLBACK_NO_ERROR)
    {
        $sql = "SELECT ads.* FROM ads, users WHERE ADS_id=? AND USERS_id=? AND ADS_id_user=USERS_id";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', intval($ad_id), intval($user_id));
        $stmt->execute();
        $result = $stmt->get_result();
        $row_nbr = $result->num_rows;

        if($row_nbr == 0)
        {
            if($errorOn) $_SESSION['errors']['NotMatch_AdIdUserId'] = "L'annonce n'est pas associé à cette utilisateur";
            return CALLBACK_ERROR;
        }
        else
        {
            return CALLBACK_NO_ERROR;
        }
    }
    else
    {
        return CALLBACK_ERROR;
    }

}

/* Vérifier si un utilisateur est propriétaire d'annonce

*/
function check_IfMatch_UserIdAdId($user_id, $ad_id, $errorOn = true)
{
    $callback = check_IfMatch_AdIdUserId($ad_id, $user_id, $errorOn);
    if($callback === CALLBACK_ERROR && $_SESSION['errors']['NotMatch_AdIdUserId'])
    {
        unset($_SESSION['errors']['NotMatch_AdIdUserId']);
        $_SESSION['errors']['NotMatch_UserIdAdId'] = "L'utilisateur n'est pas associé à cette annonce";
    }
    return $callback;
}

/* Vérifier si une photo n'a pas été uploadée

*/
function check_IfEmpty_Picture(&$picture)
{
    if(!(empty($picture) || !is_uploaded_file($picture['tmp_name'])))
    {
        return CALLBACK_ERROR;
    }
    else
    {
        return CALLBACK_NO_ERROR;
    }
}

/* Vérifier si aucune photo n'a été uploadée

*/
function check_IfEmpty_UploadedPictures(&$pictureNbr)
{
    for($i = 1; $i <= $pictureNbr; $i++)
    {
        $picture = $_FILES['picture-'.$i];

        if(!(empty($picture) || !is_uploaded_file($picture['tmp_name'])))
        {
            return CALLBACK_ERROR;
        }
    }
    return CALLBACK_NO_ERROR;
}


/* Vérifier si une valeur est vide

*/
function check_IfEmpty($value)
{
    if(empty($value)) return CALLBACK_NO_ERROR;
    else return CALLBACK_ERROR;
}

/* Vérifier si une valeur est égale à une autre

*/
function check_IfEqual($value1, $value2)
{
    if($value1 == $value2) return CALLBACK_NO_ERROR;
    else return CALLBACK_ERROR;
}

/* Vérifier les similitudes d'une annonce

*/
function check_IfSame_AdValues(&$adOld, &$title, &$description, &$price, &$city, &$category)
{
    $callback = array();

    $callback['title'] = check_IfEqual($adOld['ADS_title'], $title);
    $callback['description'] = check_IfEqual($adOld['ADS_description'], $description);
    $callback['price'] = check_IfEqual($adOld['ADS_price'], $price);
    $callback['city'] = check_IfEqual($adOld['ADS_city'], $city);
    $callback['category'] = check_IfEqual($adOld['ADS_category'], $category);
    $callback['picture'] = CALLBACK_ERROR;

    return $callback;
}

/* Vérifier quel sont les champs vides d'une annonce

*/
function check_IfEmpty_AdValues(&$title, &$description, &$price, &$city, &$category, &$pictureNbr)
{
    $callback = array();

    $callback['title'] = check_IfEmpty($title);
    $callback['description'] = check_IfEmpty($description);
    $callback['price'] = check_IfEmpty($price);
    $callback['city'] = check_IfEmpty($city);
    $callback['category'] = check_IfEmpty($category);
    $callback['picture'] = check_IfEmpty_UploadedPictures($pictureNbr);

    return $callback;
}

/* Vérifier les similitudes d'un compte

*/
function check_IfSame_UserValues(&$userOld, &$email, &$emailPrivacy, &$phoneNumber, &$phoneNumberPrivacy, &$city, &$cityPrivacy, &$password, &$passwordNew, &$passwordNewConf)
{
    $callback = array();

    $callback['email'] = check_IfEqual($userOld['USERS_email'], $email);
    $callback['emailPrivacy'] = check_IfEqual($userOld['USERS_emailPrivacy'], $emailPrivacy);
    $callback['phoneNumber'] = check_IfEqual($userOld['USERS_phoneNumber'], $phoneNumber);
    $callback['phoneNumberPrivacy'] = check_IfEqual($userOld['USERS_phoneNumberPrivacy'], $phoneNumberPrivacy);

    $callback['city'] = check_IfEqual($userOld['USERS_city'], $city);
    $callback['cityPrivacy'] = check_IfEqual($userOld['USERS_cityPrivacy'], $cityPrivacy);
    $callback['profilPicture'] = CALLBACK_ERROR;

    $callback['password'] = password_verify($password, $userOld['USERS_password']);
    $callback['passwordNew'] = password_verify($passwordNew, $userOld['USERS_password']);
    $callback['passwordAndConf'] = password_verify($passwordNewConf, $userOld['USERS_password']);

    if(!($callback['password'] === true &&
         $callback['passwordNew'] === true &&
         $callback['passwordAndConf'] === true))
    {
        $callback['password'] = CALLBACK_ERROR;
        $callback['passwordAndConf'] = CALLBACK_ERROR;
    }
    else
    {
        $callback['password'] = CALLBACK_NO_ERROR;
        $callback['passwordAndConf'] = CALLBACK_NO_ERROR;
    }

    unset($callback['passwordNew']);

    return $callback;
}

/* Vérifier quel sont les champs vides d'un compte

*/
function check_IfEmpty_UserValues(&$email, &$phoneNumber, &$city, &$profilPicture, &$password, &$passwordNew, &$passwordNewConf)
{
    $callback = array();

    $callback['email'] = check_IfEmpty($email);
    $callback['emailPrivacy'] = CALLBACK_ERROR;
    $callback['phoneNumber'] = check_IfEmpty($phoneNumber);
    $callback['phoneNumberPrivacy'] = CALLBACK_ERROR;
    $callback['city'] = check_IfEmpty($city);
    $callback['cityPrivacy'] = CALLBACK_ERROR;
    $callback['profilPicture'] = check_IfEmpty_Picture($profilPicture);
    $callback['password'] = check_IfEmpty($password);
    $callback['passwordNew'] = check_IfEmpty($passwordNew);
    $callback['passwordNewConf'] = check_IfEmpty($passwordNewConf);

    if(!($callback['password'] === CALLBACK_NO_ERROR &&
        $callback['passwordNew'] === CALLBACK_NO_ERROR &&
        $callback['passwordNewConf'] === CALLBACK_NO_ERROR))
    {
        $callback['password'] = CALLBACK_ERROR;
        $callback['passwordAndConf'] = CALLBACK_ERROR;
    }
    else
    {
        $callback['password'] = CALLBACK_NO_ERROR;
        $callback['passwordAndConf'] = CALLBACK_NO_ERROR;
    }

    unset($callback['passwordNew']);
    unset($callback['passwordNewConf']);

    return $callback;
}

/* Vérifier si un message est valide

*/
function check_IfValid_Message($message)
{
    if(empty($message))
    {
        return CALLBACK_EMPTY;
    }

    if(strlen($message) > MESSAGES_CHAR_MAX)
    {
        return CALLBACK_ERROR;
    }

    return CALLBACK_NO_ERROR;
}

/* Vérifier si une conversation est valide

*/
function check_IfValid_Conversation($id_ad, $id_buyer, $id_seller)
{
    if(empty($id_ad) || empty($id_buyer) || empty($id_seller))
    {
        return CALLBACK_EMPTY;
    }

    if(!(Ad_isExist($id_ad) === CALLBACK_NO_ERROR &&
        User_isExist($id_buyer) === CALLBACK_NO_ERROR &&
        User_isExist($id_seller) === CALLBACK_NO_ERROR))
    {
        return CALLBACK_ERROR;
    }

    if(!(check_IfMatch_UserIdAdId($id_seller, $id_ad, false) === CALLBACK_NO_ERROR))
    {
        return CALLBACK_ERROR;
    }

    if(!(Conversation_isExist($id_buyer, $id_seller, $id_ad) === CALLBACK_NO_ERROR))
    {
       if($id_seller == $_SESSION['id'] || $id_buyer != $_SESSION['id'])
       {
           return CALLBACK_ERROR;
       }
    }

    if(Conversation_isExist($id_buyer, $id_seller, $id_ad) === CALLBACK_NO_ERROR)
    {
        if(!($id_seller == $_SESSION['id'] || $id_buyer == $_SESSION['id']))
        {
            return CALLBACK_ERROR;
        }
    }

    return CALLBACK_NO_ERROR;
}

/* Vérifier si un token est valide

    Un token est valide lorsqu'il n'existe pas déjà

*/
function check_IfValid_Token($token)
{
    global $conn;

    if(!$token || empty($token))
    {
        return CALLBACK_ERROR;
    }
    else
    {
        $sql = "SELECT * FROM tokens WHERE id=? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s',$token);
        $stmt->execute();
        $result = $stmt->get_result();
        $row_nbr = (int)$result->num_rows;
        $stmt->close();

        if($row_nbr > 0)
        {
            return CALLBACK_ERROR;
        }
        else
        {
            $sql = "INSERT INTO tokens (id) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $token);
            $stmt->execute();
            $stmt->close();

            return CALLBACK_NO_ERROR;
        }
    }
}