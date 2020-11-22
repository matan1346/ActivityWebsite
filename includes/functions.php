<?php
/*
function printHTML($NavigatePage, $data = array())
{
    $css = $NavigatePage->getCSSFiles();
    $js = $NavigatePage->getJSFiles();
    $Page = $NavigatePage->getHTMLFiles();
    
    $top5players = top5players($GLOBALS['GunZ']);
    $top5clans = top5clans($GLOBALS['GunZ']);
    
    $ArrayData = array('site_url' => SITE_URL,'title' => SITE_TITLE,'css_links' => $css,'js_links' => $js,'menu' => $GLOBALS['MenuPages'],'top_players' => $top5players,'top_clans' => $top5clans);
    
    foreach($data as $key => $value)
        $ArrayData[$key] = $value;
    
    echo $GLOBALS['twig']->render($Page[0], $ArrayData);
}
*/
function ClearFetchKeyNum(&$array)
{
    if(is_array($array))
    {
       for($i = 0,$size = sizeof($array);$i < $size;$i++)
        {
            if(is_array($array[$i]))
            {
                foreach($array[$i] as $k => $v)
                {
                    
                    if(is_numeric($k))
                    {
                        unset($array[$i][$k]);
                    }
                }
            }
        } 
    }             
}

function hex2RGB($hexStr, $returnAsString = false, $seperator = ',')
{
    // Gets a proper hex string
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr);
    $rgbArray = array();
    
    if (strlen($hexStr) == 6)
    { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    }
    elseif(strlen($hexStr) == 3)
    { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    }
    else
    {
        return false; //Invalid hex color code
    }
    // returns the rgb string or the associative array
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray;
}

function IsServerUp($host, $port)
{
    if(empty($host) || empty($port))
		return false;


	$addr = gethostbyname($host);

	$socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	if(!$socket) {
		return false;
	}

	$res = @socket_connect($socket, $addr, $port);
	if(!$res) {
		return false;
	}


	socket_close($socket);
	return true;
}

function IsServerUpStatus($host, $port)
{
    if(IsServerUp($host,$port))
        return 'online';
    return 'offline';
}

function getServerData($GunZ, $server_id)
{
    $_SITE = $GLOBALS['_CONFIG'];
    $ServerData = array();
    $getServer = $GunZ->SelectData('ServerStatus',array('CurrPlayer','MaxPlayer','Port','ServerName','IP'),array('ServerID' => ':server_id'),array(':server_id' => $server_id));
    if(sizeof($getServer) > 0)
    {
        $ServerData = $getServer[0];
        if($_SITE['CheckServerStatus'] && !IsServerUp('localhost', $getServer[0]['Port']))
        {
            $ServerData['CurrPlayer'] = 0;
            $ServerData['Status'] = 'offline';
        }
        else
            $ServerData['Status'] = 'online';
        //$ServerData['Status'] = IsServerUpStatus($getServer[0]['IP'], $getServer[0]['Port']);
    }
    
    $getPlayers = $GunZ->SelectData('Character',array('COUNT(1) AS Players'),array('DeleteFlag' => 0));
    if(sizeof($getPlayers) > 0)
        $ServerData['Players'] = (($getPlayers[0]['Players'] > 0) ? array('Status' => 'online','Amount' => $getPlayers[0]['Players']) : array('Status' => 'offline','Amount' => 0));
        
    $getClans = $GunZ->SelectData('Clan',array('COUNT(1) AS Clans'),array('DeleteFlag' => 0));
    if(sizeof($getClans) > 0)
        $ServerData['Clans'] = (($getClans[0]['Clans'] > 0) ? array('Status' => 'online','Amount' => $getClans[0]['Clans']) : array('Status' => 'offline','Amount' => 0));
    
    return $ServerData;
}

function IsAccessAllowed()
{
    global $isAllowed;
    
    if((!isset($isAllowed) || $isAllowed !== true) && !isset($_GET['allowed']))
    {
        //header('HTTP/1.0 404 Not Found');
        die("Access Denied");
    }
}


function NameToColor($name)
{
    $Colors = $GLOBALS['Shop_nav_bar']['ColorName']['colors'];
    
    $previewName = '';
    $closeSpan = '</span>';
    
    $ColorActiveInName = false;
    
    //$ColorsSize = sizeof($Colors);
    
    for($i = 0,$nameLength = mb_strlen($name);$i < $nameLength;$i++)
    {
        if($name[$i] == '^' && ($i+1) < $nameLength)
        {
            if($ColorActiveInName)
                $previewName .= $closeSpan;
            $Sign = $name[$i+1];
            if(array_key_exists($Sign, $Colors))
                $previewName .= '<span style="color: #'.$Colors[$Sign].'">';
            $ColorActiveInName = true;
            $i++;
        }
        else
        {
            $previewName .= $name[$i];
        }
    }
    if($ColorActiveInName)
        $previewName .= $closeSpan;
    return array('RegularName' => $name,'ColorName' => $previewName);
}

function multiColors(&$value, $path)
{
    
    $NameKeys = explode('/', $path);
    //print_r($NameKeys);
    for($i = 0,$size = sizeof($NameKeys);$i < $size;$i++)
        if(array_key_exists($NameKeys[$i], $value))
            $value = &$value[$NameKeys[$i]];
    if(!is_string($value))
        $value = '';
    $value = NameToColor($value);
}


function multiNameColors(&$value, $key, $path = '')
{
    if(!empty($path))
    {
        //echo $path.' '.'asd<br />';
        
        
        
        $NameKeys = explode(',', $path);
        //print_r($NameKeys);
        for($i = 0,$size = sizeof($NameKeys);$i < $size;$i++)
            multiColors($value, $NameKeys[$i]);
        //print_r($value);
    }
    else if(is_string($value))
        $value = &NameToColor($value);
}

function top5players($GunZ)
{
    $top = 5;
    
    $Players = new Rankings('player', $GunZ, -1, $top);
    
    $getPlayers = $Players->execute()->getData(); 
    
    $num_players = sizeof($getPlayers);
    
    $holdingData = array();
    
    for($i = 0;$i < $num_players;$i++)
        $holdingData[$i] = array('Name' => NameToColor($getPlayers[$i]['Name']),'Level' => $getPlayers[$i]['Level']);
        
    for($i = $num_players;$i < $top;$i++)
        $holdingData[$i] = array('Name' => '--','Level' => '--');
        
    return $holdingData;
}

function top5clans($GunZ)
{
    $top = 5;

    $Clans = new Rankings('clan', $GunZ, -1, $top);
    
    $getClans = $Clans->execute()->getData();
    
    $num_clans = sizeof($getClans);
    
    $holdingData = array();
    
    for($i = 0;$i < $num_clans;$i++)
        $holdingData[$i] = array('Name' => NameToColor($getClans[$i]['Name']),'Point' => $getClans[$i]['Point']);
        
    for($i = $num_clans;$i < $top;$i++)
        $holdingData[$i] = array('Name' => '--','Point' => '--');
        
    return $holdingData;
}

function BestOf($GunZ, $type)
{
    $select = array('Name');
    switch($type)
    {
        case 'EXP':
                $select[] = 'XP';
                $order = array('XP' => 'DESC');
                //$query_str = 'SELECT TOP 1 Name,XP FROM Character WHERE '
                break;
        case 'KDR':
                $select[] = 'KillCount';
                $select[] = 'DeathCount';
                $order = array('KillCount' => 'DESC','DeathCount' => 'ASC');
                break;
        case 'KILL':
                $select[] = 'KillCount';
                $order = array('KillCount' => 'DESC');
                break;
        case 'DEATH':
                $select[] = 'DeathCount';
                $order = array('DeathCount' => 'DESC');
                break;
        case 'PLAYTIME':
                $select[] = 'PlayTime';
                $order = array('PlayTime' => 'DESC');
                break; 
    }
    
    if($Data = $GunZ->SelectData('Character', $select,array(),array(),$order))
    {
        if($type == 'KDR')
        {
            $Data[0]['KDR'] = (($Data[0]['KillCount'] != 0 && $Data[0]['DeathCount'] != 0) ? round(100*$Data[0]['KillCount']/($Data[0]['KillCount']+$Data[0]['DeathCount']), 2) : 0 ).'%';
        }
        array_walk($Data, 'multiNameColors', 'Name');
        return $Data[0];
    }
         
    return array('Name' => array('RegularName' => '','ColorName' => ''),'XP' => 0,'KRD' => 0,'KillCount' => 0,'DeathCount' => 0,'PlayTime' => 0);
    
    //$Data = $GunZ->SelectData('Character', $select,array(),array(),$order);
   
}


function getRankingPage($type, $max_rows, $per_page, $page_number, $where = array())
{
    $max_pages = ceil($max_rows/$per_page);
    if($page_number < 1 || $page_number > $max_pages) 
        $page_number = 1;
    
    
    $start_row = ($page_number - 1) * $per_page;

    $ranking = new Rankings($type, $GLOBALS['GunZ'], $start_row, $per_page, $max_rows, $max_pages);
    
    
    if(sizeof($where) > 0)
    {
        if(array_key_exists('Statement', $where))
            $ranking->setStatements($where['Statement']);
        if(array_key_exists('Bind', $where))
            $ranking->setBindParamss($where['Bind']);
    }
    
    
    $getRankingData = $ranking->execute()->getData();
    return $getRankingData;
}

function getRankingNavigatePages($each_side_pages, $curr_number, $max_pages, $per_page)
{
    $start_page = (($curr_number-$each_side_pages >= 1) ? $curr_number-$each_side_pages : 1);
    $end_page = (($curr_number+$each_side_pages <= $max_pages) ? $curr_number+$each_side_pages : $max_pages);
    
    
    return array('Start_Page_Navigate' => $start_page,'End_Page_Navigate' => $end_page,'Curr_Page_Navigate' => $curr_number,'Per_Page' => $per_page);
}

function UpdateRankPlayers($top = false)
{
    $top = ($top !== false) ? 'TOP '.$top : '';
    
    $query_str = 'UPDATE Character
                    SET Ranking = Char1.NewRank
                    FROM    (
                            SELECT '.$top.' CID
                                , Row_Number() OVER ( ORDER BY XP DESC,Level DESC,KillCount DESC,DeathCount ASC) AS NewRank
                            FROM Character WHERE DeleteFlag = 0
                            ) AS Char1
                        JOIN Character AS Char2
                            ON Char2.CID = Char1.CID';
    $query = $GLOBALS['GunZ']->Query('Update Players Ranking', $query_str);
    $query->execute();
    
    return array('affected' => $query->rowCount());
}

function UpdateRankClans($top = false)
{
    $top = ($top !== false) ? 'TOP '.$top : '';
    
    
    //UPDATE Clan SET Ranking=".$i.", RankIncrease=".($assoc['Ranking']-$i).", LastDayRanking=".$assoc['Ranking']." WHERE CLID=".$assoc['CLID']
    $query_str = 'UPDATE Clan
                    SET Ranking = Clan1.NewRank, RankIncrease = (Clan1.Ranking-Clan1.NewRank), LastDayRanking = Clan1.Ranking
                    FROM    (
                            SELECT '.$top.' CLID,Ranking
                                , Row_Number() OVER ( ORDER BY Point DESC,Wins DESC,Losses ASC) AS NewRank
                            FROM Clan WHERE DeleteFlag = 0
                            ) AS Clan1
                        JOIN Clan AS Clan2
                            ON Clan2.CLID = Clan1.CLID';
    $query = $GLOBALS['GunZ']->Query('Update Clans Ranking', $query_str);
    $query->execute();
    
    return array('affected' => $query->rowCount());
}

function mb_strcasecmp($str1, $str2, $encoding = null) {
    if (null === $encoding) { $encoding = mb_internal_encoding(); }
    return strcmp(mb_strtoupper($str1, $encoding), mb_strtoupper($str2, $encoding));
}


function in_2d_array($needle, $haystack) {
    foreach($haystack as $element) {
        if(in_array($needle, $element))
            return true;
    }
    return false;
}

function getClanMates($GunZ,$clan_id,$clan_user_grade,$page = 1)
{
    $clan_id = intval($clan_id);
    $clan_user_grade = intval($clan_user_grade);
    
    $query_str = 'SELECT COUNT(1) AS Rows FROM ClanMember WHERE CLID = :clan_id';
    $query = $GunZ->prepare($query_str);
    $query->bindValue(':clan_id', $clan_id,PDO::PARAM_INT);
    $query->execute();
    
    $getNumRows = $query->fetch(PDO::FETCH_ASSOC);
    
    
    $per_page = 16;
    
    $max_pages = ceil($getNumRows['Rows']/$per_page);
    
    $page_number = intval($page);
    
    if($page_number < 1 || $page_number > $max_pages)
        $page_number = 1;
    
    $start_from = ($page_number - 1) * $per_page;
    
    //echo 'per_page: '.$per_page.'<br />max_pages: '.$max_pages.'<br />page_number: '.$page_number.'<br />start_from: '.$start_from.'<br />';
    
    $query_str = "SELECT TOP :top_select Chars.Name As CharName,ClanMember.CID,ClanMember.Grade FROM
            ClanMember
             INNER JOIN dbo.Character AS Chars ON Chars.CID = ClanMember.CID
              WHERE ClanMember.CLID = :clan_id ORDER BY ClanMember.Grade ASC,ClanMember.ContPoint DESC";
    $query = $GunZ->prepare($query_str);
    
    
    $query->bindValue(':top_select', $per_page*$page_number, PDO::PARAM_INT);
    $query->bindValue(':clan_id', $clan_id, PDO::PARAM_INT);
    //$query->bindValue(':member_id', $lastRow['CMID'], PDO::PARAM_INT);
    $query->execute();
    
    $MatesData = $query->fetchAll(PDO::FETCH_ASSOC);
    
    $Mates = array();
    $sizeof = sizeof($MatesData);
    
    $endLoop = $sizeof;
    if($sizeof > $per_page)
        $startLoop = (($per_page*$page_number > $sizeof) ? $sizeof-($per_page*$page_number-$sizeof) : $sizeof-($per_page)) ;
    else
        $startLoop = 0;
    
    $myCharacters = $GLOBALS['User']->getUserField('Characters');
    
    for($i = $startLoop;$i < $endLoop;$i++)
    {//#F5FF00
        $isBelongsToUser = ((array_key_exists($MatesData[$i]['CID'], $myCharacters)) ? 'border: 2px solid rgba(255, 255, 0, 0.2);' : '');//((array_key_exists($MatesData[$i]['CID'], $myCharacters)) ? 'color: #F5FF00 !important;' : '');
        $RoleNum = ((isSelectedMateIsAllowed($clan_user_grade, $MatesData[$i]['Grade'])) ? $MatesData[$i]['Grade'] : -1);
        $Role = ((array_key_exists($MatesData[$i]['Grade'], $GLOBALS['Clan_Grades'])) ? $GLOBALS['Clan_Grades'][$MatesData[$i]['Grade']] : 'Unknown');
        $Mates[] = array('Name' => $MatesData[$i]['CharName'],
                        'Role' => $Role,
                        'RoleNum' => $RoleNum,
                        'CID' => $MatesData[$i]['CID'],
                        'colorName' => $isBelongsToUser
        );
    }   
    
    return array('Mates' => $Mates,'Pages' => $max_pages);
}

function arrayDeleteKeys($array)
{
    return array_splice($array,8, sizeof($array)-8);
}


function getClanMatesView($GunZ,$clan_name,$page = 1)
{
    $query_str = 'SELECT COUNT(1) AS Rows FROM ClanMember LEFT JOIN Clan ON ClanMember.CLID = Clan.CLID WHERE Clan.Name = :clan_name';
    $query = $GunZ->prepare($query_str);
    $query->bindValue(':clan_name', $clan_name,PDO::PARAM_STR);
    $query->execute();
    
    $getNumRows = $query->fetch(PDO::FETCH_ASSOC);
    
    
    $per_page = 10;
    
    $max_pages = ceil($getNumRows['Rows']/$per_page);
    
    $page_number = intval($page);
    
    if($page_number < 1 || $page_number > $max_pages)
        $page_number = 1;
    
    $start_from = ($page_number - 1) * $per_page;
    
    //echo 'per_page: '.$per_page.'<br />max_pages: '.$max_pages.'<br />page_number: '.$page_number.'<br />start_from: '.$start_from.'<br />';
    
    $query_str = "SELECT TOP :top_select Clan.Name AS Name,Clan.EmblemUrl AS EmblemUrl,Clan.Ranking AS Rank,Clan.Wins AS Wins,Clan.Losses AS Losses,MasterCID,(SELECT Name FROM dbo.Character WHERE CID = Clan.MasterCID) AS MasterName,Clan.Point AS Points,ClanMember.Grade AS Role,ClanMember.RegDate,ClanMember.ContPoint,Chars.Name AS MemberName FROM dbo.Clan
 INNER JOIN dbo.ClanMember ON ClanMember.CLID = Clan.CLID
  INNER JOIN dbo.Character AS Chars ON Chars.CID = ClanMember.CID
   WHERE Clan.Name = :clan_name ORDER BY ClanMember.Grade ASC,ClanMember.ContPoint DESC";
    $query = $GunZ->prepare($query_str);
    
    
    $query->bindValue(':top_select', $per_page*$page_number, PDO::PARAM_INT);
    $query->bindValue(':clan_name', $clan_name, PDO::PARAM_STR);
    //$query->bindValue(':member_id', $lastRow['CMID'], PDO::PARAM_INT);
    $query->execute();
    
    $MatesData = $query->fetchAll(PDO::FETCH_ASSOC);
    
    $Mates = array();
    $sizeof = sizeof($MatesData);
    
    $endLoop = $sizeof;
    if($sizeof > $per_page)
        $startLoop = (($per_page*$page_number > $sizeof) ? $sizeof-($per_page*$page_number-$sizeof) : $sizeof-($per_page)) ;
    else
        $startLoop = 0;
    
    $count = 0;
    for($i = $startLoop;$i < $endLoop;$i++)
    {//#F5FF00
        $MatesData[$i]['Count'] = $startLoop+(++$count);
        $MatesData[$i]['Role'] = ((array_key_exists($MatesData[$i]['Role'], $GLOBALS['Clan_Grades'])) ? $GLOBALS['Clan_Grades'][$MatesData[$i]['Role']] : 'Unknown'.$MatesData[$i]['Role']);
        $Mates[] = $MatesData[$i];
        //$count++;
    }
    $ClanData = array();
    if($count > 0)
    {
        $ClanData = array('Name' => $Mates[0]['Name'],
                            'EmblemUrl' => $Mates[0]['EmblemUrl'],
                            'MasterName' => $Mates[0]['MasterName'],
                            'Rank' => $Mates[0]['Rank'],
                            'Points' => $Mates[0]['Points'],
                            'Wins' => $Mates[0]['Wins'],
                            'Losses' => $Mates[0]['Losses'],
                            'NumMembers' => $getNumRows['Rows']);
        $Mates = array_map('arrayDeleteKeys',$Mates);
        //echo 'asdas';
    }
    //print_r($ClanData);
    //print_r($Mates);
    
    $each_side_page = 3;
    
    $RankingPage = getRankingNavigatePages($each_side_page, $page_number, $max_pages, $per_page);
    
    $first_top = ($page_number-1)*$per_page;
    
    return array('Mates' => $Mates,'PagesManage' => $RankingPage,'ClanDetails' => $ClanData,'MatesTop'=> array($first_top+1,$first_top+$per_page));
}

function getHighestGradeOfClanWithSameUser($ArrayGrades)
{
   $min = 100;
   foreach($ArrayGrades as $v)
   {
        if($v['Role'] < $min)
            $min = $v['Role'];
   }
   return $min;
}

function getClanChangeRoles($clan_user_role)
{
    $RolesButtonsState = array('Leader' => false,'Administrator' => false,'Member' => false,'Kick' => false);
    switch($clan_user_role)
    {
        case 1://Leader
            $RolesButtonsState = array_fill_keys(array_keys($RolesButtonsState), true);
            break;
        case 2://Admin
            $RolesButtonsState['Kick'] = true;
            break;
    }
    return $RolesButtonsState;
}

function isSelectedMateIsAllowed($player_grade, $mate_grade)
{
    $player_grade = intval($player_grade);
    $mate_grade = intval($mate_grade);
    
    return (($player_grade == 1 && $mate_grade != 1) || ($player_grade == 2 && $mate_grade > 2) || (($player_grade > 9) && $mate_grade == 9));
}