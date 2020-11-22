<?php
require_once 'includes/functions.php';
IsAccessAllowed();



        
unset($_SESSION['AID']);
unset($_SESSION['RegDate']);
unset($_SESSION['token']);
unset($_SESSION);
session_destroy();
$Session->setIsConnected(false);
$Navigate->newPage('home', SITE_ROOT.'public/');