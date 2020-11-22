<?php
require_once 'includes/functions.php';
require_once 'includes/classes/form.class.php';
require_once 'includes/classes/login.class.php';

IsAccessAllowed();

$LoginMessage = array();
if(isset($_POST['SubLogin']))
{
    $Login = new Login($GunZ);
    
    $Login->setField('Username', 'Username',$_POST['usernameL']);
    $Login->setField('Password', 'Password',$_POST['passwordL']);
    
    $Login->IsNotEmpty();
    $Login->IsValuesAllowed();
    if($Login->NoErrors())
    {
        //echo 'asd';
        if($Login->Login())
        {
            $UserAID = $Login->getUserAID();
            $Session->SetUserSession($UserAID);
            //$Session->setIsConnected(true);
            //$User->setNewUserData($UserAID);
            $Navigate->newPage('home', SITE_ROOT.'public/');
        }
        
    }
    
    $LoginMessage = $Login->getSystemMessages();
    //print_r($LoginMessage);
}



$Navigate->SetData(array('LoginMessage' => $LoginMessage));