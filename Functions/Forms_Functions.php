<?php

include_once 'Config/Constants.php';
include_once 'Config/DataBase.php';

include_once 'Functions/Utils_Functions.php';
include_once 'Functions/FromsFields_Functions.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

function check_IfValid_FormAdNew(&$title, &$description, &$price, &$city, &$category, &$pictureNbr, &$token)
{
    $callback = array();

    $callback['title'] = check_IfValid_Title($title);
    $callback['description'] = check_IfValid_Description($description);
    $callback['price'] = check_IfValid_Price($price);
    $callback['city'] = check_IfValid_City($city);
    $callback['category'] = check_IfValid_Category($category);
    $callback['pictures'] = check_IfValid_Pictures($pictureNbr);
    if($callback['pictures'] == CALLBACK_EMPTY)
    {
        for($i = 1; $i <= $pictureNbr; $i++)
        {unset($_SESSION['errors']['picture-'.$i]);}
        $callback['pictures'] = CALLBACK_NO_ERROR;
        $pictureNbr = 0;
    }

    $callback['token'] = check_IfValid_Token($token);

    return $callback;
}

function check_IfValid_FormAdUpdate($id, &$title, &$description, &$price, &$city, &$category, &$pictureNbr)
{
    $callback_valid = array();

    $callback_valid['title'] = check_IfValid_Title($title);
    $callback_valid['description'] = check_IfValid_Description($description);
    $callback_valid['price'] = check_IfValid_Price($price);
    $callback_valid['city'] = check_IfValid_City($city);
    $callback_valid['category'] = check_IfValid_Category($category);
    $callback_valid['pictures'] = check_IfValid_Pictures($pictureNbr);

    if($callback_valid['pictures'] === CALLBACK_EMPTY)
    {for($i = 1; $i <= $pictureNbr; $i++) {unset($_SESSION['errors']['picture-'.$i]);}}

    $adOld = array();
    Ad_Get($id, $adOld);

    $callback_empty = check_IfEmpty_AdValues($title, $description, $price, $city, $category, $pictureNbr);
    $callback_same = check_IfSame_AdValues($adOld, $title, $description, $price, $city, $category);

    $callback_canUpdate = array();

    for($i = 0; $i < count($callback_valid); $i++)
    {
        $callback_valid_item = $callback_valid[array_keys($callback_valid)[$i]];
        $callback_empty_item = $callback_empty[array_keys($callback_empty)[$i]];
        $callback_same_item = $callback_same[array_keys($callback_same)[$i]];

        if($callback_empty_item === CALLBACK_NO_ERROR || $callback_same_item == CALLBACK_NO_ERROR)
        {
            $callback_canUpdate[array_keys($callback_valid)[$i]] = CALLBACK_SAME;
            unset($_SESSION['errors'][array_keys($callback_valid)[$i]]);
        }
        elseif($callback_valid_item === CALLBACK_ERROR)
        {
            $callback_canUpdate[array_keys($callback_valid)[$i]] = CALLBACK_ERROR;
        }
        else
        {
            $callback_canUpdate[array_keys($callback_valid)[$i]] = CALLBACK_NO_ERROR;
        }
    }

    return $callback_canUpdate;
}

function check_IfValid_FormSignupInfo(&$username, &$email, &$phoneNumber, &$city, &$password, &$passwordConf)
{
    $callback = array();

    $callback['username'] = check_IfValid_Username($username, true);
    $callback['email'] = check_IfValid_Email($email, true);
    $callback['phoneNumber'] = check_IfValid_PhoneNumber($phoneNumber);
    $callback['city'] = check_IfValid_City($city);
    $callback['passwordAndConf'] = check_IfValid_PasswordAndConf($password, $passwordConf, false, true);

    return $callback;
}

function check_IfValid_FormLoginInfo($username, $password)
{
    $callback = array();

    $callback['username'] = check_IfValid_UsernameOrEmail($username);
    $callback['password'] = check_IfValid_Password($password);

    return $callback;
}

function check_IfValid_FormUserInfoModify(&$id, &$username, &$email, &$emailPrivacy, &$phoneNumber, &$phoneNumberPrivacy, &$city, &$cityPrivacy, &$profilPicture, &$password, &$passwordNew, &$passwordNewConf)
{
    $callback_valid = array();

    $callback_valid['email'] = check_IfValid_Email($email, true);
    $callback_valid['emailPrivacy'] = CALLBACK_NO_ERROR;
    $callback_valid['phoneNumber'] = check_IfValid_PhoneNumber($phoneNumber);
    $callback_valid['phoneNumberPrivacy'] = CALLBACK_NO_ERROR;
    $callback_valid['city'] = check_IfValid_City($city);
    $callback_valid['cityPrivacy'] = CALLBACK_NO_ERROR;
    $callback_valid['profilPicture'] = check_IfValid_ProfilPicture($profilPicture);
    $callback_valid['password'] = check_IfValid_Credentials($username, $password);
    $callback_valid['passwordAndConf'] = check_IfValid_PasswordAndConf($passwordNew, $passwordNewConf, true);

    $userOld = array();
    User_Get($id, $userOld);

    $callback_empty = check_IfEmpty_UserValues($email, $phoneNumber, $city, $profilPicture, $password, $passwordNew, $passwordNewConf);
    $callback_same = check_IfSame_UserValues($userOld, $email, $emailPrivacy, $phoneNumber, $phoneNumberPrivacy, $city, $cityPrivacy, $password, $passwordNew, $passwordNewConf);

    $callback_canUpdate = array();

    for($i = 0; $i < count($callback_valid); $i++)
    {
        $callback_valid_item = $callback_valid[array_keys($callback_valid)[$i]];
        $callback_empty_item = $callback_empty[array_keys($callback_empty)[$i]];
        $callback_same_item = $callback_same[array_keys($callback_same)[$i]];

        if($callback_empty_item === CALLBACK_NO_ERROR || $callback_same_item == CALLBACK_NO_ERROR)
        {
            $callback_canUpdate[array_keys($callback_valid)[$i]] = CALLBACK_SAME;
            unset($_SESSION['errors'][array_keys($callback_valid)[$i]]);
        }
        elseif($callback_valid_item === CALLBACK_ERROR)
        {
            $callback_canUpdate[array_keys($callback_valid)[$i]] = CALLBACK_ERROR;
        }
        else
        {
            $callback_canUpdate[array_keys($callback_valid)[$i]] = CALLBACK_NO_ERROR;
        }
    }

    return $callback_canUpdate;
}