<?php

require_once 'Config/DataBase.php';
require_once 'Config/Constants.php';

require_once 'Functions/Ad_Functions.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

function isAllSucces($success)
{
    foreach($success as $success_item){if($success_item === false){return false;}}
    return true;
}


function callbackVarDump($callback)
{
    echo '</br>';
    foreach($callback as $i=>$callback_item)
    {
        echo '["'.$i.'"]{'.CALLBACKNAMES_ARRAY[$callback_item].'}'.'</br>';
    }
    echo '</br>';
}

function to_bool($value)
{
    if(intval($value) < 1) return false;
    if(intval($value) >= 1) return true;
}

function getFixedProfilPicture($profilPicture)
{
    if($profilPicture === DB_PROFILPICTURE_DEFAULT) return FILENAME_PROFILPICTURE_DEFAULT;
    else return $profilPicture;
}

function getFixedPrivacy($privacy)
{
    if(trim($privacy) === PRIVACY_PRIVATE) return true; else return false;
}

function get_whichExist($values)
{
    foreach ($values as $value)
    {
        if($value || !empty($value) || isset($value) || $value !== null)
        {
            return $value;
        }
    }
}

function km_Between_Twopoints_Onearth($latitudeFrom, $longitudeFrom, $latitudeTo,  $longitudeTo)
{
    $long1 = deg2rad($longitudeFrom);
    $long2 = deg2rad($longitudeTo);
    $lat1 = deg2rad($latitudeFrom);
    $lat2 = deg2rad($latitudeTo);

    //Haversine Formula
    $dlong = $long2 - $long1;
    $dlati = $lat2 - $lat1;

    $val = pow(sin($dlati/2),2)+cos($lat1)*cos($lat2)*pow(sin($dlong/2),2);

    $res = 2 * asin(sqrt($val));

    $radius = 3958.756;

    return ($res*$radius)*1.609344;
}

function HTML_proof($value)
{
    return htmlspecialchars($value, ENT_QUOTES);
}


function redirectIfValueCompare($redirectUrl, $value1, $value2, $ifEqual)
{
    if(!($value1 === $value2 xor $ifEqual))
    {
        $header_str = 'location: '.$redirectUrl;
        header($header_str);
        exit();
    }
}

function redirectTo($redirectUrl)
{
    $header_str = 'location: '.$redirectUrl;
    header($header_str);
    exit();
}

function redirectIfConnectionState($redirectUrl, $ifConnected)
{
    if(!(isset($_SESSION['id']) xor $ifConnected))
    {
        $header_str = 'location: '.$redirectUrl;
        header($header_str);
        exit();
    }
}

function DateSinceArray_to_DateSinceStr($DateSinceArray, $prefixe, $precision)
{
    if(empty($DateSinceArray))
    {
        return $prefixe."une durée inconnue";
    }

    $DateSinceArray_NotEqualTo_0_indexs = array();

    $atInstant = 1;
    foreach ($DateSinceArray as $i=>$DateSinceElement)
    {
        if(intval($DateSinceElement) !== 0 && $i != count($DateSinceArray)-1 && $atInstant === 1)
        {
            $atInstant = 0;
        }

        if(intval($DateSinceElement) !== 0)
        {
            array_push($DateSinceArray_NotEqualTo_0_indexs, $i);
        }
    }

    if($atInstant)
    {
        $DateSinceStr = "À l'instant";
    }
    else
    {
        $DateSinceStr = $prefixe;
        foreach($DateSinceArray_NotEqualTo_0_indexs as $i=>$index)
        {
            if($i >= $precision) {break;}

            $value = $DateSinceArray[$index];

            $unit_index = 1;
            if($value == 1) $unit_index = 1;
            if($value > 1) $unit_index = 2;

            if(($i+1 === count($DateSinceArray_NotEqualTo_0_indexs) || $i+1 === $precision) && $i !==0) $DateSinceStr = $DateSinceStr." et ";
            elseif (($i !== count($DateSinceArray_NotEqualTo_0_indexs) || $i !== $precision) && $i !==0) $DateSinceStr = $DateSinceStr.", ";
            $DateSinceStr = $DateSinceStr.$value." ".DATE_FORMAT_ARRAY[$index][$unit_index];

        }
    }

    return $DateSinceStr;
}

function DateTimeStr2_to_DateSinceArray($date1, $date2)
{
    $date_before = date_create_from_format(DB_DATE_FORMAT, $date1);
    $date_now = date_create_from_format(DB_DATE_FORMAT, $date2);

    if($date_before && $date_now)
    {
        $date_array = array();

        foreach(DATE_FORMAT_ARRAY as $i=>$format)
        {
            $value = intval(date_diff($date_before,$date_now)->format($format[0]));
            $date_array[$i] = $value;
        }

        return $date_array;
    }
    else
    {
        return array();
    }
}

function getOneThatIAmNot($id1, $id2)
{
    if($id1 != $_SESSION['id']) return $id1;
    if($id2 != $_SESSION['id']) return $id2;
    return "";
}

function getOneThatIAm($id1, $id2)
{
    if($id1 == $_SESSION['id']) return $id1;
    if($id2 == $_SESSION['id']) return $id2;
    return "";
}

function DateTimeStr_to_DateSinceArray($date)
{
    $date_before = date_create_from_format(DB_DATE_FORMAT, $date);
    $date_now = date_create_from_format(DB_DATE_FORMAT, date(DB_DATE_FORMAT));

    if($date_before && $date_now)
    {
        $date_array = array();

        foreach(DATE_FORMAT_ARRAY as $i=>$format)
        {
            $value = intval(date_diff($date_before,$date_now)->format($format[0]));
            $date_array[$i] = $value;
        }

        return $date_array;
    }
    else
    {
        return array();
    }
}

function getAdNbr_FromIdUser($userId)
{
    global $conn;

    $sql = "SELECT ads.* FROM ads, users WHERE USERS_id=? AND USERS_id=ADS_id_user";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $i = 0;

    while($ad = $result->fetch_assoc())
    {
        if(Ad_isDateValid($ad['ADS_creationDatetime']))
        {$i++;}
        else
        {Ad_Delete($ad['ADS_id'], false);}
    }

    return $i;
}

function getAllCityAndPostalCode()
{
    global $conn;

    $sql = "SELECT ville_nom_reel, ville_departement FROM villes_france_free ORDER BY ville_nom_reel";
    $stmt = $conn->prepare($sql);
    if($stmt->execute())
    {
        $result = $stmt->get_result();
        return $result->fetch_all();
    }
    else
    {
        return array();
    }
}

function echoActualUserInfo($infoType)
{
    echo $_SESSION[$infoType];
}

function getActualUserInfo($infoType)
{
    return $_SESSION[$infoType];
}

function echoPhpSelf()
{
    echo $_SERVER['PHP_SELF'];
}

function getOpacityClassIfPrivateInfo($infoType)
{
    if ($_SESSION[$infoType] === true) {return"Txt-Opct-0-5";}
    else return "";
}

function getPrivateIconIfPrivateInfo($infoType)
{
    if ($_SESSION[$infoType] === true) return '<i class="fas fa-eye-slash"></i>';
    else return "";
}


function isUserConnected()
{
    if(isset($_SESSION['id'])) return true;
    else return false;
}

function pictureResize(&$picture, $widthMax, $heightMax)
{
    $width = (int)getimagesize($picture)[0];
    $height = (int)getimagesize($picture)[1];
    $ratio_orig = $width/$height;

    if ($widthMax/$heightMax > $ratio_orig)
    {
        $widthMax = $heightMax*$ratio_orig;
    } else
    {
        $heightMax = $widthMax/$ratio_orig;
    }

    $dst = imagecreatetruecolor($widthMax, $heightMax);
    $src = 0;

    if(exif_imagetype($picture) == 2)
    {
        $src = imagecreatefromjpeg($picture);
    }
    elseif (exif_imagetype($picture) == 3)
    {
        $src = imagecreatefrompng($picture);
    }

    imagecopyresampled($dst, $src, 0, 0, 0, 0, $widthMax, $heightMax, $width, $height);

    if(exif_imagetype($picture) == 2)
    {
        imagejpeg($dst, $picture);
    }
    elseif (exif_imagetype($picture) == 3)
    {
        imagepng($dst, $picture);
    }
}

function pictureFixOrientation(&$picture, $filename)
{
    $exif = exif_read_data($filename);

    if (!empty($exif['Orientation']))
    {
        switch ($exif['Orientation'])
        {
            case 3:
                $picture = imagerotate($picture, 180, 0);
                break;

            case 6:
                $picture = imagerotate($picture, -90, 0);
                break;

            case 8:
                $picture = imagerotate($picture, 90, 0);
                break;
        }
    }
}

function isCallBack_fullOf_NO_ERROR(&$checkCallBack)
{
    $noError = true;
    foreach ($checkCallBack as $itCheckCallBack)
    {
        if($itCheckCallBack !== CALLBACK_NO_ERROR)
        {
            $noError = false;
            break;
        }
    }
    return $noError;
}

function isCallBack_fullOf_SAME(&$checkCallBack)
{
    $noError = true;
    foreach ($checkCallBack as $itCheckCallBack)
    {
        if($itCheckCallBack !== CALLBACK_SAME)
        {
            $noError = false;
            break;
        }
    }
    return $noError;
}

function isCallBack_fullOf_NO_ERROR_OR_EMPTY(&$checkCallBack)
{
    $noError = true;
    foreach ($checkCallBack as $itCheckCallBack)
    {
        if($itCheckCallBack !== CALLBACK_NO_ERROR && $itCheckCallBack !== CALLBACK_EMPTY)
        {
            $noError = false;
            break;
        }
    }
    return $noError;
}

function isCallBack_fullOf_NO_ERROR_OR_SAME(&$checkCallBack)
{
    $noError = true;
    foreach ($checkCallBack as $itCheckCallBack)
    {
        if($itCheckCallBack !== CALLBACK_NO_ERROR && $itCheckCallBack !== CALLBACK_SAME)
        {
            $noError = false;
            break;
        }
    }
    return $noError;
}

function isCallBack_fullOf_EMPTY(&$checkCallBack)
{
    $noError = true;
    foreach ($checkCallBack as $itCheckCallBack)
    {
        if($itCheckCallBack !== CALLBACK_EMPTY)
        {
            $noError = false;
            break;
        }
    }
    return $noError;
}


function isCallBack_fullOf_ERROR(&$checkCallBack)
{
    $noError = true;
    foreach ($checkCallBack as $itCheckCallBack)
    {
        if($itCheckCallBack !== CALLBACK_ERROR)
        {
            $noError = false;
            break;
        }
    }
    return $noError;
}

function echo_S_IfPlurial($value)
{
    if(intval($value) > 1)
    {
        echo HTML_proof("s");
    }
    else
    {
        echo HTML_proof("");
    }
}
