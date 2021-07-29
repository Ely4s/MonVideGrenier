<?php

require_once 'Constants.php';

//Connection à la base de donnée

$conn = new mysqli(DB_HOST,DB_USER,DB_PASS, DB_NAME);
$conn->set_charset("utf8mb4");
date_default_timezone_set('Europe/Paris');