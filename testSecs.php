<?php

require_once './includes/config.php';
require_once './includes/classes/mssql.class.php';



$nowTime = time();


//$past = strtotime('2012-07-13 12:41:00.000');

$minutes = 2;

$timeToAdd = $nowTime+(60*$minutes);

//echo 'For GradeChange: '.(($nowTime-$past)+$timeToAdd).'<br />';




echo 'Before: '.date('d/m/y H:i:s', $nowTime).' - '.$nowTime;
echo '<br />After: '.date('d/m/y H:i:s', $timeToAdd).' - '.$timeToAdd;

/*
$GunZ = new sqlGunz($_Data['Host'],$_Data['User'],$_Data['Pass'],$_Data['Base']);

$GunZ->DeleteData('GradeChange',array('AID' => 7));

$GunZ->UpdateData('Account',array('UGradeID' => 200),array('AID' => 7));

$GunZ->InsertData('GradeChange',array('AID' => 7,'Grade' => 200,'FinalGrade' => 255,'ExpDate' => $timeToAdd));
*/

/*
$GunZ->DeleteData('NameChange',array('CID' => 311));

$GunZ->UpdateData('Character',array('Name' => "'sad^2dasdds'"),array('CID' => 311));

$GunZ->InsertData('NameChange',array('CID' => 311,'Name' => "'sad^2dasdds'",'FinalName' => "'saddasdds'",'ExpDate' => $timeToAdd));
*/
