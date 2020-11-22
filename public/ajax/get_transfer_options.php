<?php

if(isset($_POST['UserID'],$_POST['UserPass']))
{
    require_once '../../includes/config.php';
    require_once '../../includes/globalvars.php';
    require_once '../../includes/functions.php';
    require_once '../../includes/classes/mssql.class.php';
    require_once '../../includes/classes/user.class.php';
    require_once '../../includes/classes/form.class.php';
    require_once '../../includes/classes/login.class.php';
    
    $GunZ = new sqlGunz($_Data['Host'],$_Data['User'],$_Data['Pass'],$_Data['Base']);
    
    
    $flagLogged = false;
    
    $Login = new Login($GunZ);
        
    $Login->setField('Username', 'Username',$_POST['UserID']);
    $Login->setField('Password', 'Password',$_POST['UserPass']);
    
    $Login->IsNotEmpty();
    $Login->IsValuesAllowed();
    if($Login->NoErrors())
    {
        //echo 'asd';
        if($Login->Login())
        {
            $UserAID = $Login->getUserAID();
            $TransferUser = new User($GunZ);
            $TransferUser->setNewUserData($UserAID);
            
            $flagLogged = true;
            $Login->setMessage('User', $TransferUser->getMultiUserFields());
            //$getData = $TransferUser->getMultiUserFields();
            
            //echo '<pre>';
            //print_r($getData);
            //echo '</pre><br /><br />';
            //$Session->SetUserSession($UserAID);
            //$Session->setIsConnected(true);
            //$User->setNewUserData($UserAID);
            //$Navigate->newPage('home', SITE_ROOT.'public/');
        }
        
    }
    $Login->setMessage('Logged', $flagLogged);
    $LoginMessage = $Login->getSystemMessages();
    //echo '<pre>';
    //print_r($LoginMessage);
    //echo '</pre>';
    echo json_encode($LoginMessage);
}
/*
custom transfer..... selectbox.....
*/