<?php
require_once 'includes/functions.php';

IsAccessAllowed();

if(isset($_GET['name']))
{
    $player = $_GET['name'];
    
    $pageNum = ((isset($_GET['page_num'])) ? $_GET['page_num'] : 1);
    
    $clanData = getClanMatesView($GunZ,$_GET['name'],$pageNum);
    
    if(sizeof($clanData) > 0)
    {
        $Emblem = $clanData['ClanDetails']['EmblemUrl'];
        if(is_null($Emblem) || empty($Emblem) || !file_exists($GlobalPaths['BASE_CLAN_EMBLEM_DIR'].$Emblem))
            $clanData['ClanDetails']['EmblemUrl'] = $GlobalPaths['DEFAULT_CLAN_NO_EMBLEM'];
        
        
        multiNameColors($clanData, 1, 'ClanDetails/Name,ClanDetails/MasterName');
        array_walk($clanData['Mates'], 'multiNameColors', 'MemberName');
        
        $Navigate->SetData(array('title' => SITE_TITLE.' - Clans - '.$clanData['ClanDetails']['Name']['RegularName'].'`s Profile - Showing Members Top '.$clanData['MatesTop'][0].'-'.$clanData['MatesTop'][1],'top_mates' => $clanData['MatesTop'][0].'-'.$clanData['MatesTop'][1]));
    }
    $Navigate->SetData($clanData['PagesManage']);
    $Navigate->SetData(array('MatesData' => $clanData['Mates'],'ClanDetails' => $clanData['ClanDetails'],'not_exists' => $player));
}
