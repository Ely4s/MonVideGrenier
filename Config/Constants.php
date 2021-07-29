<?php

//Definition de toutes les constantes

define('DB_HOST','monvidegrcadmin.mysql.db');
define('DB_USER','monvidegrcadmin');
define('DB_PASS','WesternDigital123');
define('DB_NAME','monvidegrcadmin');
define('DB_DATE_FORMAT', 'Y-m-d H:i:s');
define('DB_PROFILPICTURE_DEFAULT', 'default');

define('SESSION_LIFE_MINUTES', 15);

define('MESSAGES_SHOW_MAX', 50);
define('MESSAGES_CHAR_MAX', 1024);

define('SITE_WIDTH', 800);

define('DATE_FORMAT_ARRAY', array
(
    array('%Y', "année", "années"),
    array('%m', "mois", "mois"),
    array('%d', "jour", "jours"),
    array('%H', "heure", "heures"),
    array('%i', "minute", "minutes"),
    array('%s', "seconde", "secondes")
));

define('PROFILPICTURE_WIDTH_RESIZE', 200);
define('PROFILPICTURE_HEIGHT_RESIZE', 200);

define('ADPICTURE_WIDTH_RESIZE', 600);
define('ADPICTURE_HEIGHT_RESIZE', 400);
define('PICTURE_WIDTH_MAX', 5000);
define('PICTURE_HEIGHT_MAX', 5000);
define('PICTURE_SIZE_MIN', 20000); //20 ko
define('PICTURE_SIZE_MAX', 1000000); //1 mo
define('PICTURE_MIME_ALLOWED', array('image/png', 'image/jpeg'));
define('PICTURE_MAGIC_NBR_ALLOWED', array('89504E47', 'FFD8FFE0', 'FFD8FFDB', 'FFD8FFEE'));
define('FOLDERPATH_ADPICTURE','./Data/A-dPictures/');
define('FOLDERPATH_PROFILPICTURE','./Data/ProfilPictures/');
define('FILENAME_PROFILPICTURE_DEFAULT','default.jpg');
define('FILENAME_AD_DEFAULT','default.jpg');

define('AD_VALIDITY_TIME_STR', '2 mois');
define('AD_VALIDITY_TIME', '2 month');
define('TITLE_CHAR_MIN', 10);
define('TITLE_CHAR_MAX', 32);
define('DESCRIPTION_CHAR_MIN', 32);
define('DESCRIPTION_CHAR_MAX', 1024);
define('PRICE_INT_MIN', 0);
define('PRICE_INT_MAX', 1000000000);
define('PRICE_CHAR_MIN', 1);
define('PRICE_CHAR_MAX', 10);
define('CATEGORY_CHAR_MIN', 1);
define('CATEGORY_CHAR_MAX', 42);
define('PICTURE_NBR_MIN', 1);
define('PICTURE_NBR_MAX', 3);

define('SEARCH_CHAR_MIN', 1);
define('SEARCH_CHAR_MAX', 32);

define('CITYKM_CHAR_MIN', 1);
define('CITYKM_CHAR_MAX', 5);
define('CITYKM_INT_MIN', 0);
define('CITYKM_INT_MAX', 10000);


define('AD_CATEGORIES', array(

    array("TOUT", "Toutes les catégories"),
    array("EMPLOI", "Offres d'emploi", "Formations Professionnelles"),
    array("VÉHICULES", "Voitures", "Motos", "Caravaning", "Utilitaires", "Camions", "Nautisme", "Équipement auto", "Équipement moto", "Équipement caravaning", "Équipement nautisme"),
    array("IMMOBILIER", "Ventes", "Locations", "Colocations", "Bureaux & Commerces"),
    array("VACANCES", "Locations & Gîtes", "Chambres d'hôtes", "Campings", "Hôtels"),
    array("LOISIRS", "DVD & Films", "CD & Musique", "Livres", "Animaux", "Vélos", "Sports & Hobbies", "Instruments de musique", "Collection", "Jeux & Jouets", "Vins & Gastronomie"),
    array("MODE", "Vêtements", "Chaussures", "Accessoires & Bagagerie", "Montres & Bijoux", "Équipement bébé", "Vêtements bébé", "Luxe et Tendance"),
    array("MULTIMÉDIA", "Informatique", "Consoles & Jeux vidéo", "Image & Son", "Téléphonie"),
    array("SERVICES", "Prestations de services", "Billetterie", "Événements", "Cours particuliers", "Covoiturage"),
    array("MAISON", "Ameublement", "Électroménager", "Arts de la table", "Décoration", "Linge de maison", "Bricolage", "Jardinage"),
    array("DIVERS", "Autres")
));

define('PAGE_AD_PER_PAGE', 2);

define('USERNAME_CHAR_MIN', 3);
define('USERNAME_CHAR_MAX', 16);
define('EMAIL_CHAR_MIN', 5); //a@b.c
define('EMAIL_CHAR_MAX', 32);
define('PHONENUMBER_CHAR_MIN', 10);
define('PHONENUMBER_CHAR_MAX', 10);
define('CITY_CHAR_MIN', 1);
define('CITY_CHAR_MAX', 64);
define('PASSWORD_CHAR_MIN', 6);
define('PASSWORD_CHAR_MAX', 32);

define('PRIVACY_PRIVATE', "private");

define('CALLBACK_NO_ERROR', 1);
define('CALLBACK_ERROR', 0);
define('CALLBACK_EMPTY', -1);
define('CALLBACK_ALREADY', -2);
define('CALLBACK_SAME', -3);
define('CALLBACK_NOT_SAME', -4);

define('CALLBACKNAMES_ARRAY',
array
(
    '1'=>'CALLBACK_NO_ERROR',
    '0'=>'CALLBACK_ERROR',
    '-1'=>'CALLBACK_EMPTY',
    '-2'=>'CALLBACK_ALREADY',
    '-3'=>'CALLBACK_SAME',
    '-4'=>'CALLBACK_NOT_SAME',
));

