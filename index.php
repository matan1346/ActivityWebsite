<?php
session_start();
session_regenerate_id(true);
require_once './includes/config.php';
require_once './includes/globalvars.php';
require_once './includes/functions.php';
//require_once './includes/classes/lang.class.php';
require_once './includes/classes/mssql.class.php';
require_once './includes/classes/navigate.class.php';
require_once './includes/classes/user.class.php';
require_once './includes/classes/session.class.php';
require_once './includes/classes/rankings.class.php';
require_once './protected/Twig-1.12.3/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('./public/html');
$twig = new Twig_Environment($loader);

$Page = 'home';


if(isset($_GET['page']))
    $Page = $_GET['page'];

$GunZ = new sqlGunz($_Data['Host'],$_Data['User'],$_Data['Pass'],$_Data['Base']);

$isAllowed = true;

/*
//New User Session
*/

$Session = new Session($GunZ);
$Session->SetUserSession();
$User = $Session->getUser();

$Page = $Session->CanAccess($Page);

$Navigate = new Navigate($Page, SITE_ROOT.'public/');

$getPHP = $Navigate->getFilesByType('php', 'public/php/');


foreach($getPHP as $page)
    require_once $page;
$Navigate->NavigateAuto($twig);


$GunZ = NULL;
?>