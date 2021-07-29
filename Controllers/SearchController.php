<?php

require_once 'Config/DataBase.php';
require_once 'Config/Constants.php';

require_once 'Functions/UtilsFunctions.php';
require_once 'Functions/SearchFunctions.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

$userId = null;
$search = null;
$category = null;
$priceMin = null;
$priceMax = null;
$city = null;
$cityKm = null;
$ads = null;
$page = null;
$page_nbr = null;
$ads_nbr = null;
$ads_index_start = null;
$ads_index_end = null;
$page_nbr_before = null;
$page_nbr_after = null;

//Effectuer une recherche
if(end(explode('/',$_SERVER['PHP_SELF'])) === "mon-compte.php" || end(explode('/',$_SERVER['PHP_SELF'])) === "index.php" || end(explode('/',$_SERVER['PHP_SELF'])) === "profil.php")
{
    // On récupère les valeurs dans les champs du formulaire
    $_GET['search'] = trim($_GET['search']);
    $_GET['category'] = trim($_GET['category']);
    $_GET['priceMin'] = trim($_GET['priceMin']);
    $_GET['priceMax'] = trim($_GET['priceMax']);
    $_GET['city'] = trim($_GET['city']);
    $_GET['cityKm'] = trim($_GET['cityKm']);
    $_GET['page'] = trim($_GET['page']);

    // On prépare les paramètres de la recherche
    prepare_ForSearch_UserId($userId);
    prepare_ForSearch_Search($search);
    prepare_ForSearch_Category($category);
    prepare_ForSearch_Price($priceMin, $priceMax);
    prepare_ForSearch_City($city, $cityKm);

    // On récupère toutes les annonces correspondant à la recherche
    $ads = searchAd($userId, $search, $category, $priceMin, $priceMax, $city, $cityKm);

    // On applique la pagination
    prepare_ForSearch_Page($page, $ads, $ads_nbr, $ads_index_start, $ads_index_end, $page_nbr, $page_nbr_before, $page_nbr_after);
}