<?php

require_once 'Config/DataBase.php';
require_once 'Config/Constants.php';

require_once 'Functions/UtilsFunctions.php';
require_once 'Functions/UserFunctions.php';
require_once 'Functions/FormsFunctions.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

//Créer un compte
$_USER_CREATION = array();
if(end(explode('/',$_SERVER['PHP_SELF'])) === "inscription.php" && isset($_POST['signup-btn']))
{
    // On récupère les valeurs dans les champs du formulaire
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $emailPrivacy = getFixedPrivacy($_POST['email-privacy']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $phoneNumberPrivacy = getFixedPrivacy($_POST['phoneNumber-privacy']);
    $city = trim($_POST['city']);
    $cityPrivacy = getFixedPrivacy($_POST['city-privacy']);
    $password = trim($_POST['password']);
    $passwordConf = trim($_POST['passwordConf']);

    //  Si le formulaire est valide, on crée le compte et on redirige l'utilisation sur son compte
    if(isCallBack_fullOf_NO_ERROR(check_IfValid_FormSignupInfo($username, $email, $phoneNumber, $city, $password, $passwordConf)))
    {
        if (User_New($username, $email, $emailPrivacy, $phoneNumber, $phoneNumberPrivacy, $city, $cityPrivacy, $password) === CALLBACK_NO_ERROR)
        {
            User_Connect($username, $password, true);
        }
    }

    //  On remet les valeurs dans les champs du formulaire si l'utilisateur n'a toujours pas été redirgé
    $_USER_CREATION['username'] = $username;
    $_USER_CREATION['email'] = $email;
    $_USER_CREATION['emailPrivacy'] = $emailPrivacy;
    $_USER_CREATION['phoneNumber'] = $phoneNumber;
    $_USER_CREATION['phoneNumberPrivacy'] = $phoneNumberPrivacy;
    $_USER_CREATION['city'] = $city;
    $_USER_CREATION['cityPrivacy'] = $cityPrivacy;
}

//Modifier un compte
$_USER_MODIFICATION = array();
if(end(explode('/',$_SERVER['PHP_SELF'])) === "modifier-mon-compte.php")
{
    //  Si l'utilisateur a demandé la modification de son compte
    if(isset($_POST['userInfoModify-btn']))
    {
        // On récupère les valeurs dans les champs du formulaire
        $id                     = intval(trim($_SESSION['id']));
        $username               = trim($_SESSION['username']);
        $email                  = trim($_POST['email']);
        $emailPrivacy = getFixedPrivacy(trim($_POST['email-privacy']));
        $phoneNumber            = trim($_POST['phoneNumber']);
        $phoneNumberPrivacy = getFixedPrivacy(trim($_POST['phoneNumber-privacy']));
        $city                   = trim($_POST['city']);
        $cityPrivacy = getFixedPrivacy(trim($_POST['city-privacy']));
        $profilPicture          = $_FILES['profilPicture'];
        $password               = trim($_POST['password']);
        $passwordNew            = trim($_POST['passwordNew']);
        $passwordNewConf        = trim($_POST['passwordNewConf']);

        //  On vérifie les champs du formulaire
        $callback_valid = check_IfValid_FormUserInfoModify($id, $username, $email, $emailPrivacy, $phoneNumber, $phoneNumberPrivacy, $city, $cityPrivacy, $profilPicture, $password, $passwordNew, $passwordNewConf);

        //  On modifie le compte de l'utilisateur en fonction du résultat de la vérification
        if(User_Update($id, $email, $emailPrivacy, $phoneNumber, $phoneNumberPrivacy, $city, $cityPrivacy, $profilPicture, $password, $callback_valid) === CALLBACK_NO_ERROR)
        {redirectTo("mon-compte.php");}

        //  On remet les valeurs dans les champs du formulaire si l'utilisateur n'a toujours pas été redirgé
        $_USER_MODIFICATION['username'] = $username;
        $_USER_MODIFICATION['email'] = $email;
        $_USER_MODIFICATION['emailPrivacy'] =  $emailPrivacy;
        $_USER_MODIFICATION['phoneNumber'] = $phoneNumber;
        $_USER_MODIFICATION['phoneNumberPrivacy'] = $phoneNumberPrivacy;
        $_USER_MODIFICATION['city'] = $city;
        $_USER_MODIFICATION['cityPrivacy'] = $cityPrivacy;
    }
    //  Sinon si l'utilisateur a demandé la suppression de son compte
    elseif(isset($_GET['delete']))
    {
        //  On supprime l'utilisateur
        $callback_delete = User_Delete($_SESSION['id']);

        // Si la suppression à reussi, on le déconnecte
        if($callback_delete === CALLBACK_NO_ERROR)
        {
            User_Disconnect($_SESSION['success']['User_Delete']);
        }
    }
}

//Se connecter à un compte
$_USER_CONNECTION = array();
if(end(explode('/',$_SERVER['PHP_SELF'])) === "connection.php" && isset($_POST['login-btn']))
{
    // On récupère les valeurs dans les champs du formulaire
    $username       = trim($_POST['username']);
    $password       = trim($_POST['password']);

    //  On vérifie les champs du formulaire
    $callback = check_IfValid_FormLoginInfo($username, $password);

    // Si le formulaire est valide on connecte l'utilisateur
    if(isCallBack_fullOf_NO_ERROR($callback))
    {
        User_Connect($username, $password);
    }

    //  On remet la valeur dans le champs du formulaire si l'utilisateur n'a toujours pas été redirgé
    $_USER_CONNECTION['username'] = $username;
}

//Se déconnecter d'un compte
if(strpos($_SERVER['PHP_SELF'], "mon-compte.php") && isset($_GET['logout']))
{
    // On deconnecte l'utilisateur
    User_Disconnect();
}

//Voir le profil d'un utilisateur
$_USER_PROFIL = array();
if(end(explode('/',$_SERVER['PHP_SELF'])) === "profil.php")
{
    $user = array();
    $accessDenied = "";

    // Si l'utilisateur existe, on peut accéder à la page
    if(User_Get($_GET['id'], $user, false) == CALLBACK_NO_ERROR)
    {
        $accessDenied = false;
    }
    // Sinon on empêche l'accès à la page
    else
    {
        $accessDenied = true;
        $_SESSION['errors']['User_Profil'] = "Impossible de visualiser le profil de ce vendeur";
    }

    //  On stock les données de l'utilisateur
    $_USER_PROFIL['accessDenied'] = $accessDenied;
    $_USER_PROFIL['user'] = $user;
}
