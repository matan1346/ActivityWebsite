<?php

if(isset($_POST['UserID'],$_POST['UserPass'],$_POST['nUserID'],$_POST['nUserPass']))
{
    require_once '../../includes/config.php';
    require_once '../../includes/globalvars.php';
    require_once '../../includes/functions.php';
    require_once '../../includes/classes/mssql.class.php';
    require_once '../../includes/classes/user.class.php';
    require_once '../../includes/classes/form.class.php';
    require_once '../../includes/classes/login.class.php';
    require_once '../../includes/classes/transfer.class.php';
    
    /*$_POST['UserID'] = 'matanasus';
    $_POST['UserPass'] = '123123123';
    $_POST['nUserID'] = 'shurp';
    $_POST['nUserPass'] = 'matasadsadsdasdsdnasus';*/
    $GunZ = new sqlGunz($_Data['Host'],$_Data['User'],$_Data['Pass'],$_Data['Base']);
    $GunZ2 = new sqlGunz($_Data['Host'],$_Data['User'],$_Data['Pass'],'GunzDB2');
    $flagTransferd = false;
    $flagLogged = false;
    
    
    $Transfer = new Transfer($GunZ,$GunZ2);
    
    $Transfer->setField('Username', 'Username',$_POST['UserID']);
    $Transfer->setField('Password', 'Password',$_POST['UserPass']);
    $Transfer->setField('dstUserID', 'Username', $_POST['nUserID']);
    $Transfer->setField('dstPassword', 'Password', $_POST['nUserPass']);
    
    
    $Transfer->IsNotEmpty();
    $Transfer->IsValuesAllowed();
    if($Transfer->NoErrors())
    {
        if($Transfer->Login())
        {
            $flagLogged = true;
            $Transfer->setField('srcUserAID','AID',$Transfer->getUserAID());
            
            
            
            /*
            $Transfer->setInsertUserData(true)->setInsertCharactersData(true)->
                        setUpdateCharactersData(true)->setUpdateClanData(true)->
                        setDeleteUserData(true)->setDeleteCharactersData(true)->
                        setDeleteClanAnyWay(true);
            */
            
            $Characters = explode(',',$_POST['Characters']);
            $Transfer->setField('CharactersToTransfer', 'Number', $Characters);
            
            if($Transfer->TransferAccount())
            {
                $flagTransferd = true;
            }
        }
    }
   // $Transfer->setMessage('Characters', $_POST['Characters']);
   //$newArray = strval($_POST['Characters'][0][1]);
   //$Characters = explode(',',$_POST['Characters']);
    //$Transfer->setMessage('Characters', $Characters);
    $Transfer->setMessage('IsLogged', $flagLogged);
    $Transfer->setMessage('IsTransferred', $flagTransferd);
    $TransferMessage = $Transfer->getSystemMessages();

    echo json_encode($TransferMessage);
}

//echo json_encode(array('asd'));