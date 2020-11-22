<?php
require_once 'includes/functions.php';
require_once 'includes/classes/form.class.php';
require_once 'includes/classes/login.class.php';
require_once 'includes/classes/transfer.class.php';

IsAccessAllowed();

if(isset($_POST['SubTransfer']))
{
    $GunZ2 = new sqlGunz($_Data['Host'],$_Data['User'],$_Data['Pass'],'GunzDB2');

    $Transfer = new Transfer($GunZ,$GunZ2);
    
    die($_POST['Characters']);
    
    $Transfer->setField('srcUserID','Username','shurp1');
    $Transfer->setField('srcUserAID','AID',1);
    $Transfer->setField('srcUserPassword','Password','123123123');
    
    $Transfer->setField('dstUserID','Username','matanasus');
    $Transfer->setField('dstUserPassword','Password','123123123');
    
    /*
    $Transfer->setInsertUserData(true)->setInsertCharactersData(true)->
                setUpdateCharactersData(true)->setUpdateClanData(true)->
                setDeleteUserData(true)->setDeleteCharactersData(true)->
                setDeleteClanAnyWay(true);
    */
    
    $Transfer->TransferAccount();
    
    //print_r($Transfer->getSystemMessages());
}


$Navigate->SetData(array('TransferAllowed' => true,'confirmbox' => false));