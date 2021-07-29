<?php

include_once 'Config/Constants.php';
include_once 'Config/DataBase.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

function searchAd($userId, $search, $category, $priceMin, $priceMax, $city, $cityKm)
{
    global $conn;

    if($city === "%")
    {
        $sql = "SELECT * FROM ads, users WHERE LOWER(ADS_title) LIKE (?) AND ADS_category LIKE (?) AND (ADS_price BETWEEN ? AND ?) AND ADS_city LIKE ? AND CAST(USERS_id AS CHAR) LIKE (?) AND USERS_id=ADS_id_user ORDER BY ADS_creationDatetime DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssiiss', strtolower($search), $category, $priceMin, $priceMax, $city, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $ads = array();
        while($ad = $result->fetch_assoc())
        {
            $ad_id = $ad['ADS_id'];

            if(!(Ad_isDateValid($ad['ADS_creationDatetime']) === CALLBACK_NO_ERROR))
            {
                Ad_Delete($ad_id);
            }
            else
            {
                $sql = "SELECT ads_pictures.* FROM ads_pictures, ads WHERE ADS_id = ? AND ADSPICTURES_id_ad=ADS_id";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $ad_id);
                $stmt->execute();
                $result_ads_pictures = $stmt->get_result();
                $ad_pictures = $result_ads_pictures->fetch_all();
                foreach ($ad_pictures as $i=>$ad_picture) {$ad['ADSPICTURES_file_name_'.($i+1)] = $ad_picture[1];}
                $ad['ADSPICTURES_nbr'] = count($ad_pictures);

                array_push($ads, $ad);
            }

        }

        return $ads;

    }
    else
    {
        $sql = "SELECT * FROM ads, users WHERE LOWER(ADS_title) LIKE (?) AND ADS_category LIKE (?) AND (ADS_price BETWEEN ? AND ?) AND CAST(USERS_id AS CHAR) LIKE (?) AND USERS_id = ADS_id_user ORDER BY ADS_creationDatetime DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssiis', strtolower($search), $category, $priceMin, $priceMax, $userId);
        $stmt->execute();
        $result_CityNotCheck = $stmt->get_result();
        $stmt->close();

        $sql = "SELECT ville_longitude_deg, ville_latitude_deg, CONCAT(ville_nom_reel,' (',ville_departement,')') AS city FROM villes_france_free WHERE CONCAT(ville_nom_reel,' (',ville_departement,')') LIKE (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $city);
        $stmt->execute();
        $result_CitySelected = $stmt->get_result()->fetch_assoc();
        $result_CitySelected_Lon = $result_CitySelected['ville_longitude_deg'];
        $result_CitySelected_Lat = $result_CitySelected['ville_latitude_deg'];

        $ads = array();
        while($ad = $result_CityNotCheck->fetch_assoc())
        {
            if(!(Ad_isDateValid($ad['ADS_creationDatetime']) === CALLBACK_NO_ERROR))
            {
                Ad_Delete($ad['ADS_id']);
            }
            else
            {
                $sql = "SELECT ville_longitude_deg, ville_latitude_deg, CONCAT(ville_nom_reel,' (',ville_departement,')') AS city FROM villes_france_free WHERE CONCAT(ville_nom_reel,' (',ville_departement,')') LIKE (?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $ad['ADS_city']);
                $stmt->execute();
                $result_City = $stmt->get_result()->fetch_assoc();
                $result_City_Lon = $result_City['ville_longitude_deg'];
                $result_City_Lat = $result_City['ville_latitude_deg'];

                if(round(km_Between_Twopoints_Onearth($result_City_Lon, $result_City_Lat, $result_CitySelected_Lon, $result_CitySelected_Lat)) <= $cityKm)
                {
                    $ad_id = $ad['ADS_id'];

                    $sql = "SELECT ads_pictures.* FROM ads_pictures, ads WHERE ADS_id = ? AND ADSPICTURES_id_ad=ADS_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('i', $ad_id);
                    $stmt->execute();
                    $result_ads_pictures = $stmt->get_result();
                    $ad_pictures = $result_ads_pictures->fetch_all();
                    foreach ($ad_pictures as $i=>$ad_picture) {$ad['ADSPICTURES_file_name_'.($i+1)] = $ad_picture[1];}
                    $ad['ADSPICTURES_nbr'] = count($ad_pictures);

                    array_push($ads, $ad);
                }
            }
        }

        return $ads;
    }
}

function prepare_ForSearch_UserId(&$userId)
{

    if(end(explode('/',$_SERVER['PHP_SELF'])) === "mon-compte.php")
    {
        $userId = strval($_SESSION['id']);
    }
    elseif(end(explode('/',$_SERVER['PHP_SELF'])) === "profil.php")
    {
        $userId = strval($_GET['id']);
    }
    else
    {
        $userId = "%";
    }
}

function prepare_ForSearch_Search(&$search)
{
    if(!isset($_GET['search']))
    {
        $search = "%";
        $_GET['search'] = "";
    }
    else
    {
        if(strlen($_GET['search']) > SEARCH_CHAR_MAX)
        {
            $_GET['search'] = substr($_GET['search'], 0, SEARCH_CHAR_MAX);
            $search = $_GET['search'];
        }

        $search = "%" . implode("%", preg_split('/\s+/', $_GET['search'])) . "%";
    }
}

function prepare_ForSearch_Category(&$category)
{
    if(!isset($_GET['category']))
    {
        $category = "%";
        $_GET['category'] = AD_CATEGORIES[0][1];
    }
    else
    {
        $callback = check_IfValid_Category($_GET['category']);
        unset($_SESSION['errors']['category']);

        if($callback === CALLBACK_NO_ERROR)
        {
            if($_GET['category'] === AD_CATEGORIES[0][1])
            {
                $category = "%";
            }
            else
            {
                $category = $_GET['category'];
            }
        }
        else
        {
            $category = "%";
            $_GET['category'] = AD_CATEGORIES[0][1];
        }
    }
}

function prepare_ForSearch_Price(&$priceMin, &$priceMax)
{
    if(!isset($_GET['priceMin']) || intval(trim($_GET['priceMin'])) < PRICE_INT_MIN || intval(trim($_GET['priceMin'])) > PRICE_INT_MAX || !ctype_digit(trim($_GET['priceMin'])))
    {
        $_GET['priceMin'] = "";
        $priceMin = PRICE_INT_MIN;
    }
    else
    {
        $priceMin = intval($_GET['priceMin']);
    }

    if(!isset($_GET['priceMax']) || intval(trim($_GET['priceMax'])) < PRICE_INT_MIN || intval(trim($_GET['priceMax'])) > PRICE_INT_MAX|| !ctype_digit(trim($_GET['priceMax'])))
    {
        $_GET['priceMax'] = "";
        $priceMax = PRICE_INT_MAX;
    }
    else
    {
        $priceMax = intval($_GET['priceMax']);
    }

    if(!($priceMin < $priceMax))
    {
        $tmp = $priceMin;
        $priceMin = $priceMax;
        $priceMax = $tmp;

        $_GET['priceMin'] = $priceMin;
        $_GET['priceMax'] = $priceMax;
    }
}

function prepare_ForSearch_City(&$city, &$cityKm)
{
    if(!isset($_GET['city']))
    {
        $city = "%";
        $_GET['city'] = "";
    }
    else
    {
        $callback = check_IfValid_City($_GET['city']);
        unset($_SESSION['errors']['city']);

        if($callback === CALLBACK_NO_ERROR)
        {
            $city = $_GET['city'];
        }
        else
        {
            $city = "%";
            $_GET['city'] = "";
        }
    }

    if($city === "%")
    {
        $_GET['cityKm'] = "";
        $cityKm = CITYKM_INT_MAX;
    }
    elseif(!isset($_GET['cityKm']) || !ctype_digit($_GET['cityKm']) || intval($_GET['cityKm']) < CITYKM_INT_MIN || strlen($_GET['cityKm']) < CITYKM_CHAR_MIN)
    {
        $_GET['cityKm'] = "";
        $cityKm = CITYKM_INT_MIN;
    }
    elseif(intval($_GET['cityKm']) > CITYKM_INT_MAX || strlen($_GET['cityKm']) > CITYKM_CHAR_MAX)
    {
        $_GET['cityKm'] = strval(CITYKM_INT_MIN);
        $cityKm = CITYKM_INT_MIN;
    }
    else
    {
        $cityKm = intval($_GET['cityKm']);
    }
}

function prepare_ForSearch_Page(&$page, &$ads, &$ads_nbr, &$ads_index_start, &$ads_index_end, &$page_nbr, &$page_nbr_before, &$page_nbr_after)
{
    $ads_nbr = count($ads);
    $page_nbr = intval(ceil($ads_nbr/PAGE_AD_PER_PAGE));
    if($page_nbr === 0) $page_nbr = 1;

    if(!isset($_GET['page']) || !ctype_digit($_GET['page']))
    {
        $page = 1;
        $_GET['page'] = strval(1);
    }
    elseif(intval($_GET['page']) < 1)
    {
        $page = 1;
        $_GET['page'] = strval(1);
    }
    elseif(intval($_GET['page']) > $page_nbr)
    {
        $page = $page_nbr;
        $_GET['page'] = strval($page_nbr);
    }
    else
    {
        $page = intval($_GET['page']);
    }

    if($page_nbr === 0)
    {
        $page_nbr_before = 0;
        $page_nbr_after = 0;
    }
    else
    {
        if($page - 1 <= 2)
        {
            $page_nbr_before = $page - 1;
        }
        else
        {
            $page_nbr_before = 2;
        }

        if($page_nbr - $page <= 2)
        {
            $page_nbr_after = $page_nbr - $page;
        }
        else
        {
            $page_nbr_after = 2;
        }
    }

    $ads_index_start = PAGE_AD_PER_PAGE * ($page-1);
    $ads_index_end = PAGE_AD_PER_PAGE - 1;

    if($page === 0)
    {
        $page = 1;
        $_GET['page'] = strval(1);
        $page_nbr_before = 0;
        $page_nbr_after = 0;
        $ads_index_start = 0;
        $ads_index_end = 0;
    }
}

function getURL_withSearchOption($page)
{
    $page_UrlParts = explode("/", $_SERVER['PHP_SELF']);
    $page_cleanUrl = $page_UrlParts[count($page_UrlParts)-1];
    $page = "?page=".$page;
    $search = "&search=".$_GET['search'];
    $category = "&category=".$_GET['category'];
    $priceMin = "&priceMin=".$_GET['priceMin'];
    $priceMax = "&priceMax=".$_GET['priceMax'];
    $city = "&city=".$_GET['city'];
    $cityKm = "&cityKm=".$_GET['cityKm'];
    $id = "&id=".$_GET['id'];

    return $page_cleanUrl.$page.$search.$category.$priceMin.$priceMax.$city.$cityKm.$id;
}
