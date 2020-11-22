<?php
require_once '../../../includes/globalvars.php';
require_once '../../../includes/config.php';
require_once '../../../includes/classes/mssql.class.php';
require_once '../../../includes/functions.php';

define('CRON_NAME', 'player_rank');



$GunZ = new sqlGunz($_Data['Host'],$_Data['User'],$_Data['Pass'],$_Data['Base']);

//Starting log
	$file = fopen(CRON_LOGS_DIR.CRON_NAME.'.txt', "a+");
	fwrite($file, date('d/m/y H:m:s')."\r\n");

//Updating clans
	$startTime = microtime(true);
	fwrite($file, LOG_SPACE."Updating Players:\r\n");



    $getResults = UpdateRankPlayers();

    if($getResults['affected'] > 0)
    {
        fwrite($file, LOG_SPACE."Found ".$getResults['affected']." rows.\r\n");
        fwrite($file, LOG_SPACE."Execution Time: ".(microtime(true)-$startTime)."\r\n".LOG_SPACE."Done.\r\n\r\n");
    }
    else
    {
        fwrite($file, LOG_SPACE."Found 0 rows, thus leaving.\r\n\r\n");
    }

//Finish Logs
	fwrite($file, "---------------------------------------------------------------------------\r\n");
	fclose($file);
	echo "OK";