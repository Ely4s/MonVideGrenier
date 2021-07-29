<?php

include_once 'Config/Constants.php';
include_once 'Config/DataBase.php';

include_once 'Functions/UtilsFunctions.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

/*Crée une annonce

    On crée l'annonce,
    redimensionne les photos,
    et on corrige la rotation des photos (fix d'un problème provenant des exifs des photos prises depuis un smarphone)

*/
function Ad_New(&$_id_user, &$title, &$description, &$price, &$city, &$category, &$pictureNbr)
{
    global $conn;

    $conn->autocommit(false);
    $conn->begin_transaction();

    $success = array();

    $datetime = date(DB_DATE_FORMAT);
    $sql = "INSERT INTO ads (ADS_category, ADS_title, ADS_description, ADS_price, ADS_city, ADS_creationDatetime, ADS_id_user) VALUES (?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssissi', $category, $title, $description, $price, $city, $datetime, $_id_user);
    $success['Ad_New__NewAdInfo'] = $stmt->execute();
    $adId = $stmt->insert_id;

    $picture_NewName_all = array();

    if($pictureNbr == 0)
    {
        $picture_DefaultName = FILENAME_AD_DEFAULT;
        $sql = "INSERT INTO ads_pictures (ADSPICTURES_file_name, ADSPICTURES_id_ad) VALUES (?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $picture_DefaultName, $adId);
        $success['Ad_New__NewAdPictures_Default'] = $stmt->execute();
    }
    else
    {
        for($i = 1; $i <= $pictureNbr; $i++)
        {
            $pictureId = 'picture-'.$i;
            $picture = $_FILES[$pictureId];

            $picture_NewName = md5(uniqid('',true)) . '.' . pathinfo($picture['name'], PATHINFO_EXTENSION);
            array_push($picture_NewName_all, $picture_NewName);
            pictureFixOrientation($picture, $picture['tmp_name']);
            pictureResize($picture['tmp_name'], ADPICTURE_WIDTH_RESIZE, ADPICTURE_HEIGHT_RESIZE);
            $success['Ad_New__UploadAdPictures_'.$i] = move_uploaded_file($picture['tmp_name'], FOLDERPATH_ADPICTURE.$picture_NewName);

            $sql = "INSERT INTO ads_pictures (ADSPICTURES_file_name, ADSPICTURES_id_ad) VALUES (?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $picture_NewName, $adId);
            $success['Ad_New__NewAdPictures_'.$i] = $stmt->execute();
        }
    }

    $noError = isAllSucces($success);

    if($noError)
    {
        $_SESSION['success']['Ad_New'] = "Annonce créée avec succès";
        $conn->commit();
        $conn->autocommit(true);
        $stmt->close();

        return CALLBACK_NO_ERROR;
    }
    else
    {
        $_SESSION['errors']['Ad_New'] = "Une erreur s'est produite pendant la creation de l'annonce";
        foreach($picture_NewName_all as $picture_NewName) {unlink(FOLDERPATH_ADPICTURE.$picture_NewName);}
        $conn->rollback();
        $conn->autocommit(true);
        $stmt->close();

        return CALLBACK_ERROR;
    }
}

/*Supprimer une annonce

    On supprime l'annonce,
    et toutes les photos lui étant attachés

*/
function Ad_Delete($id, $errorOn = false)
{
    global $conn;

    $conn->autocommit(false);
    $conn->begin_transaction();

    $success = array();

    $sql = "SELECT ADSPICTURES_file_name FROM ads_pictures WHERE ADSPICTURES_id_ad=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $success['Ad_Delete__GetAdPictures'] = $stmt->execute();
    $result = $stmt->get_result();
    $pictures = array();
    while($picture = $result->fetch_assoc())
    {array_push($pictures, $picture['ADSPICTURES_file_name']);}

    $sql = "DELETE FROM ads WHERE ADS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $success['Ad_Delete__DeleteAd'] = $stmt->execute();

    if(isAllSucces($success))
    {
        foreach($pictures as $picture)
        {
            if($picture != FILENAME_AD_DEFAULT)
            {unlink(FOLDERPATH_ADPICTURE.$picture);}
        }
        if($errorOn) $_SESSION['success']['Ad_Delete'] = "L'annonce a été supprimé avec succès";
        $conn->commit();
        $conn->autocommit(true);
        $stmt->close();
        return CALLBACK_NO_ERROR;
    }
    else
    {
        if($errorOn) $_SESSION['errors']['Ad_Delete'] = "Une erreur s'est produite pendant la supression de l'annonce";
        $conn->rollback();
        $conn->autocommit(true);
        $stmt->close();
        return CALLBACK_ERROR;
    }
}

/*Mettre à jour une annonce

    On met à jour uniquement les valeurs nouvelles ou non vides

*/
function Ad_Update($id, &$title, &$description, &$price, &$city, &$category, &$pictureNbr, &$callback_canUpdate)
{
    if(isCallBack_fullOf_NO_ERROR_OR_SAME($callback_canUpdate))
    {
        if($callback_canUpdate['title'] === CALLBACK_NO_ERROR)
        {
            Ad_UpdateTitle($id,$title);
        }
        if($callback_canUpdate['description'] === CALLBACK_NO_ERROR)
        {
            Ad_UpdateDescription($id,$description);
        }
        if($callback_canUpdate['price'] === CALLBACK_NO_ERROR)
        {
            Ad_UpdatePrice($id,$price);
        }
        if($callback_canUpdate['city'] === CALLBACK_NO_ERROR)
        {
            Ad_UpdateCity($id,$city);
        }
        if($callback_canUpdate['category'] === CALLBACK_NO_ERROR)
        {
            Ad_UpdateCategory($id,$category);
        }
        if($callback_canUpdate['pictures'] === CALLBACK_NO_ERROR)
        {
            Ad_UpdatePictures($id,$pictureNbr);
        }

        if(isCallBack_fullOf_SAME($callback_canUpdate))
        {
            $_SESSION['success']['Ad_Update'] = "Aucune mofication n'a été apporté à l'annonce";
            return CALLBACK_NO_ERROR;
        }
        else
        {
            $_SESSION['success']['Ad_Update'] = "L'annonce a été modifié avec succès";
            return CALLBACK_NO_ERROR;
        }
    }
    else
    {
        return CALLBACK_ERROR;
    }
}

/* Récuperer une annonce

 */
function Ad_Get($ad_id, &$ad)
{
    global $conn;

    $success = array();

    $sql = "SELECT * FROM ads WHERE ADS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $ad_id);
    $success['Ad_Get__GetAdInfo'] = $stmt->execute();
    $result = $stmt->get_result();
    $success['Ad_Get__AdExist'] = $result->num_rows === 1;
    $stmt->close();

    $ad = $result->fetch_assoc();

    if(!(Ad_isDateValid($ad['ADS_creationDatetime']) === CALLBACK_NO_ERROR))
    {
        Ad_Delete($ad['ADS_id']);
        $ad = array();
        return CALLBACK_ERROR;
    }

    $sql = "SELECT ads_pictures.* FROM ads_pictures, ads WHERE ADS_id = ? AND ADSPICTURES_id_ad=ADS_id";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $ad_id);
    $success['Ad_Get__GetAdPictures'] = $stmt->execute();
    $result_ads_pictures = $stmt->get_result();
    $ad_pictures = $result_ads_pictures->fetch_all();
    foreach ($ad_pictures as $i=>$ad_picture) {$ad['ADSPICTURES_file_name_'.($i+1)] = $ad_picture[1];}
    $ad['ADSPICTURES_nbr'] = count($ad_pictures);

    $noError = isAllSucces($success);

    if($noError)
    {
        return CALLBACK_NO_ERROR;
    }
    else
    {
        $_SESSION['errors']['Ad_Get'] = "Une erreur s'est produite pendant la récupération de l'annonce";
        $ad = array();
        return CALLBACK_ERROR;
    }
}

/* Vérifier qu'une annonce existe

 */
function Ad_isExist($ad_id)
{
    global $conn;

    $sql = "SELECT * FROM ads WHERE ADS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $ad_id);
    if($stmt->execute())
    {
        $result = $stmt->get_result();
        $stmt->close();

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
            return CALLBACK_ERROR;
        }
    }
    else
    {
        $_SESSION['errors']['Ad_isExist'] = "Une erreur s'est produite pendant la vérification de l'existance de l'annonce";
        $stmt->close();
        return CALLBACK_ERROR;
    }
}

/* Mise à jour du titre

 */
function Ad_UpdateTitle($id, $title)
{
    global $conn;

    $sql = "UPDATE ads SET ADS_title=? WHERE ADS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $title, $id);
    if($stmt->execute())
    {
        $stmt->close();
        return CALLBACK_NO_ERROR;
    }
    else
    {
        $stmt->close();
        $_SESSION['errors']['Ad_UpdateTitle'] = "Un erreur s'est produite pendant la mise à jour du titre";
        return CALLBACK_ERROR;
    }
}

/* Mise à jour de la description

 */
function Ad_UpdateDescription($id, $description)
{
    global $conn;

    $sql = "UPDATE ads SET ADS_description=? WHERE ADS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $description, $id);
    if($stmt->execute())
    {
        $stmt->close();
        return CALLBACK_NO_ERROR;
    }
    else
    {
        $stmt->close();
        $_SESSION['errors']['Ad_UpdateDescription'] = "Un erreur s'est produite pendant la mise à jour de la description";
        return CALLBACK_ERROR;
    }
}

/* Mise à jour du prix

 */
function Ad_UpdatePrice($id, $price)
{
    global $conn;

    $sql = "UPDATE ads SET ADS_price=? WHERE ADS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $price, $id);
    if($stmt->execute())
    {
        $stmt->close();
        return CALLBACK_NO_ERROR;
    }
    else
    {
        $stmt->close();
        $_SESSION['errors']['Ad_UpdateDescription'] = "Un erreur s'est produite pendant la mise à jour du prix";
        return CALLBACK_ERROR;
    }
}

/* Mise à jour de la ville

 */
function Ad_UpdateCity($id, $city)
{
    global $conn;

    $sql = "UPDATE ads SET ADS_city=? WHERE ADS_id=?";
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
        $_SESSION['errors']['Ad_UpdateDescription'] = "Un erreur s'est produite pendant la mise à jour de la ville";
        return CALLBACK_ERROR;
    }
}

/* Mise à jour de la catégorie

 */
function Ad_UpdateCategory($id, $category)
{
    global $conn;

    $sql = "UPDATE ads SET ADS_category=? WHERE ADS_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $category, $id);
    if($stmt->execute())
    {
        $stmt->close();
        return CALLBACK_NO_ERROR;
    }
    else
    {
        $stmt->close();
        $_SESSION['errors']['Ad_UpdateDescription'] = "Un erreur s'est produite pendant la mise à jour de la catégorie";
        return CALLBACK_ERROR;
    }
}

/* Mise à jour des photos

    On supprime les anciennes
    et on crée les nouvelles photos après redimension et fix de l'orientation

 */
function Ad_UpdatePictures($id, $adPictureNbr_New)
{
    global $conn;

    $conn->autocommit(false);
    $conn->begin_transaction();

    $success = array();

    //recupereation des nom des anciennes photo
    $sql = "SELECT ads_pictures.* FROM ads_pictures, ads WHERE ADSPICTURES_id_ad=? AND ADS_id=ADSPICTURES_id_ad";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $success['Ad_UpdatePictures__GetOldAdPictures'] = $stmt->execute();
    $result_AdPictures_Old = $stmt->get_result();
    $adPicturesNbr_Old = $result_AdPictures_Old->num_rows;
    $adPictureName_Old_Array = array();
    for ($i = 0; $i < $adPicturesNbr_Old; $i++)
    {
        array_push($adPictureName_Old_Array, $result_AdPictures_Old->fetch_assoc()['ADSPICTURES_file_name']);
    }

    //suppression des anciennes photo dans la bdd
    $sql = "DELETE FROM ads_pictures WHERE ADSPICTURES_id_ad=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $success['Ad_UpdatePictures__RemoveOldAdPictures'] = $stmt->execute();

    $adPictureName_New_Array = array();
    for ($i = 1; $i <= $adPictureNbr_New; $i++)
    {
        $pictureId = 'picture-' . $i;
        $picture = $_FILES[$pictureId];

        $adPictureName_New = md5(uniqid('', true)) . '.' . pathinfo($picture['name'], PATHINFO_EXTENSION);
        array_push($adPictureName_New_Array, $adPictureName_New);
        pictureFixOrientation($picture, $picture['tmp_name']);
        pictureResize($picture['tmp_name'], ADPICTURE_WIDTH_RESIZE, ADPICTURE_HEIGHT_RESIZE);
        $success['Ad_UpdatePictures__UploadNewPicture_' . $i] = move_uploaded_file($picture['tmp_name'], FOLDERPATH_ADPICTURE.$adPictureName_New);

        $sql = "INSERT INTO ads_pictures (ADSPICTURES_file_name, ADSPICTURES_id_ad) VALUES (?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $adPictureName_New, $id);
        $success['Ad_UpdatePictures__CreateNewPicture_'.$i] = $stmt->execute();
    }

        $noError = isAllSucces($success);

        if($noError)
        {
            foreach($adPictureName_Old_Array as $adPictureName_Old)
            {
                if($adPictureName_Old != FILENAME_AD_DEFAULT)
                {
                    unlink(FOLDERPATH_ADPICTURE.$adPictureName_Old);
                }
            }
            $conn->commit();
            $conn->autocommit(true);
            $stmt->close();

            return CALLBACK_NO_ERROR;
        }
        else
        {
            $_SESSION['errors']['Ad_UpdatePictures'] = "Une erreur est survenue pendant la modification de l'annonce";
            foreach ($adPictureName_New_Array as $adPictureName_New) {unlink(FOLDERPATH_ADPICTURE . $adPictureName_New);}
            $conn->rollback();
            $conn->autocommit(true);
            $stmt->close();

            return CALLBACK_ERROR;
        }
}

/* Vérifier qu'une annonce n'a pas atteint sa durée de vie maximum

 */
function Ad_isDateValid($date)
{
    if(!$date)
    {
        return CALLBACK_ERROR;
    }
    else
    {
        $date_end = date_create_from_format(DB_DATE_FORMAT, $date)->modify('+'.AD_VALIDITY_TIME);
        $date_now = date_create_from_format(DB_DATE_FORMAT, date(DB_DATE_FORMAT));

        if($date_end->getTimestamp() < $date_now->getTimestamp())
        {
            return CALLBACK_ERROR;
        }
        else
        {
            return CALLBACK_NO_ERROR;
        }
    }
}