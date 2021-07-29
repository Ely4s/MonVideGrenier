<?php

require_once 'Config/DataBase.php';
require_once 'Config/Constants.php';

require_once 'Functions/UtilsFunctions.php';
require_once 'Functions/FormsFunctions.php';
require_once 'Functions/AdFunctions.php';
require_once 'Functions/UserFunctions.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

$_AD_CREATION = array();
// Créer une annonce;
if(end(explode('/',$_SERVER['PHP_SELF'])) === "creer-une-annonce.php" && isset($_POST['adNew-btn']))
{
    // On récupère les valeurs dans les champs du formulaire
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $city = trim($_POST['city']);
    $category = trim($_POST['category']);
    $pictureNbr = trim($_POST['pictureNbr']);
    $token = trim($_POST['token']);

    //  On vérifie les champs du formulaire
    $callback_From = check_IfValid_FormAdNew($title, $description, $price, $city, $category, $pictureNbr, $token);

    //  Si il est valide, on crée l'annonce et on redirige l'utilisateur sur son compte
    if(isCallBack_fullOf_NO_ERROR($callback_From))
    {
        $callback_New = Ad_New($_SESSION['id'], $title, $description, $price, $city, $category, $pictureNbr);
        if($callback_New === CALLBACK_NO_ERROR) {redirectTo('mon-compte.php');}
    }
    //  Sinon si l'erreur provient du token on redirige l'utilisateur sur son compte
    elseif($callback_From['token'] === CALLBACK_ERROR)
    {
        $_SESSION['success']['Ad_New'] = "Annonce créée avec succès";
        redirectTo('mon-compte.php');
    }

    //  On remet les valeurs dans les champs du formulaire si l'utilisateur n'a toujours pas été redirgé
    $_AD_CREATION['title'] = $title;
    $_AD_CREATION['description'] = $description;
    $_AD_CREATION['price'] = $price;
    $_AD_CREATION['city'] = $city;
    $_AD_CREATION['category'] = $category;
    $_AD_CREATION['pictureNbr'] = $pictureNbr;
}


// Modifier une annonce;
$_AD_MODIFICATION = array();
if(end(explode('/',$_SERVER['PHP_SELF'])) === "modifier-une-annonce.php")
{
    // On récupère les valeurs dans les champs du formulaire
    $title = "";
    $description = "";
    $price = "";
    $city = "";
    $category = "";
    $pictureNbr = "";
    $accessDenied = "";

    // Si l'utilisateur est bien propriétaire de l'annonce
    if(check_IfMatch_AdIdUserId($_GET['id'], $_SESSION['id'], false) === CALLBACK_NO_ERROR)
    {
        $accessDenied = false;

        //  Si l'utilisateur a demandé la suppression de l'annonce, on la supprime et on le redirige sur son compte
        if(isset($_GET['delete']))
        {
            $callback_delete = Ad_Delete($_GET['id'], true);
            if($callback_delete === CALLBACK_NO_ERROR) {redirectTo("mon-compte.php");}
        }

        //  Si l'utilisateur vient d'arriver sur la page de modification, on remplit les champs de la page avec les informations correspondant à l'annonce
        if(!isset($_POST['adModify-btn']))
        {
            $ad = array();
            Ad_Get($_GET['id'], $ad);

            $title = $ad['ADS_title'];
            $description = $ad['ADS_description'];
            $price = $ad['ADS_price'];
            $city = $ad['ADS_city'];
            $category = $ad['ADS_category'];
            $pictureNbr = $ad['ADSPICTURES_nbr'];
        }
        //  Sinon si l'utilisateur a demandé la modification de son annonce
        else
        {

            //  On récupère les champs du formulaire
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $city = $_POST['city'];
            $category = $_POST['category'];
            $pictureNbr = $_POST['pictureNbr'];

            // On vérifie les champs du formulaire
            $callback_valid = check_IfValid_FormAdUpdate($_GET['id'], $title, $description, $price, $city, $category, $pictureNbr);

            // Si le formulaire est valide, on effectue la modification
            $callback_update = Ad_Update($_GET['id'], $title, $description, $price, $city, $category, $pictureNbr, $callback_valid);

            // Si la modification a réussi on redirige l'utilisateur sur son compte
            if($callback_update === CALLBACK_NO_ERROR)
            {
                redirectTo("mon-compte.php");
            }
        }
    }
    // Sinon on empêche l'utilisateur d'accéder à la page
    else
    {
        $accessDenied = true;
        $_SESSION['errors']['Ad_Update'] = "Impossible de modifier cette annonce";
    }

    //  On remet les valeurs dans les champs du formulaire si l'utilisateur n'a toujours pas été redirgé
    $_AD_MODIFICATION['title'] = $title;
    $_AD_MODIFICATION['description'] = $description;
    $_AD_MODIFICATION['price'] = $price;
    $_AD_MODIFICATION['city'] = $city;
    $_AD_MODIFICATION['category'] = $category;
    $_AD_MODIFICATION['pictureNbr'] = $pictureNbr;
    $_AD_MODIFICATION['accessDenied'] = $accessDenied;
}


// Consulter une annonce;
$_AD_VIEW = array();
if(end(explode('/',$_SERVER['PHP_SELF'])) === "consulter-une-annonce.php")
{
    $ad = array();
    $user = array();
    $accessDenied = "";

    // Si l'annonce existe, on récupère les informations relatives à elle-même et au vendeur
    if(Ad_isExist($_GET['id']) === CALLBACK_NO_ERROR)
    {
        $accessDenied = false;
        Ad_Get($_GET['id'], $ad);
        User_Get($ad['ADS_id_user'], $user, false);
    }
    // Sinon on empêche l'utilisateur d'accéder à la page
    else
    {
        $accessDenied = true;
        $_SESSION['errors']['Ad_View'] = "Impossible de visualiser cette annonce";
    }

    //  On stock les informations relatives à l'annonce et au vendeur pour l'affichage
    $_AD_VIEW['accessDenied'] = $accessDenied;
    $_AD_VIEW['ad'] = $ad;
    $_AD_VIEW['user'] = $user;
}