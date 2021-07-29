<?php

include_once "Functions/UserFunctions.php";
include_once "Functions/UtilsFunctions.php";

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

/* Contrôle de la validité d'une session

*/
function Session_LifeControl($reset = true)
{
    if(isUserConnected())
    {
        $date_max = date_create_from_format(DB_DATE_FORMAT, $_SESSION["loginDatetime"])->modify('+'.SESSION_LIFE_MINUTES.' minutes');
        $date_now = date_create_from_format(DB_DATE_FORMAT, date(DB_DATE_FORMAT));

        $diff = $date_max->getTimestamp() - $date_now->getTimestamp();

        if($diff > 0)
        {
            if($reset) $_SESSION["loginDatetime"] = date(DB_DATE_FORMAT);
        }
        else
        {
            User_Disconnect("Vous avez été déconnecté pour inactivité");
        }
    }
}
