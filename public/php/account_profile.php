<?php
require_once 'includes/functions.php';

IsAccessAllowed();


$isAvailable = false;

if($Session->IsConnected())
{
    $isAvailable = true;
    
    $ProfileData = $User->getMultiUserFields('UserID','Name','Age','Email','Characters','ClanLeaders',$DataBase_Fields['Coins'],'UGradeID');
    
    $ProfileData['Coins'] = $ProfileData[$DataBase_Fields['Coins']];
    //echo 'asd';
    //print_r($ProfileData);
    
    $ProfileData['UGradeID'] = ((array_key_exists($ProfileData['UGradeID'], $User_Status)) ? $User_Status[$ProfileData['UGradeID']] : array('GradeName' => 'Unknown','GradeColor' => 'black'));
    $Navigate->setData(array('ProfileData' => $ProfileData,'title' => SITE_TITLE.' - My Profile'));
}
$Navigate->setData(array('IsConnected' => $isAvailable));