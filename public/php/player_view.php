<?php
require_once 'includes/functions.php';

IsAccessAllowed();

if(isset($_GET['name']))
{
    $player = $_GET['name'];
    //$q = 'SELECT Character.Name AS Name,Character.Level AS Level,Character.XP AS XP,Character.KillCount AS KillCount,Character.DeathCount AS DeathCount,Character.Ranking AS Rank,Character.RegDate AS RegDate,Character.LastTime AS LastTime,ISNULL(Clan.Name, "--") AS "ClanName",ISNULL(ClanMember.Grade, "--") AS "ClanGrade" FROM dbo.Character INNER JOIN dbo.ClanMember ON ClanMember.CID = Character.CID INNER JOIN dbo.Clan ON Clan.CLID = ClanMember.CLID WHERE Character.Name = :playername';
    //$q = 'SELECT Character.Name AS Name,Character.Level AS Level,Character.Ranking AS Rank,ISNULL(Clan.Name, "--") AS "ClanName",ISNULL(ClanMember.Grade, "--") AS "ClanGrade" FROM dbo.Character INNER JOIN dbo.ClanMember ON ClanMember.CID = Character.CID LEFT JOIN dbo.Clan ON Clan.CLID = ClanMember.CLID WHERE Character.Name = :playername';
    $q = 'SELECT Character.Name AS Name,Character.Level AS Level,Character.Ranking AS Rank,Character.XP AS XP,Character.KillCount AS KillCount,Character.DeathCount AS DeathCount,Character.RegDate AS RegDate,Character.LastTime AS LastTime,ISNULL(Clan.Name, "--") AS "ClanName" FROM dbo.Character LEFT JOIN dbo.ClanMember ON ClanMember.CID = Character.CID LEFT JOIN dbo.Clan ON Clan.CLID = ClanMember.CLID WHERE Character.Name = :playername';
    $query = $GunZ->prepare($q);
    $query->bindParam(':playername', $player, PDO::PARAM_STR);
    $query->execute();
    
    $playerData = array();
    
    if($a = $query->fetch(PDO::FETCH_ASSOC))
    {
        $a['Rating'] = (($a['KillCount'] != 0 && $a['DeathCount'] != 0) ? round(100*$a['KillCount']/($a['KillCount']+$a['DeathCount']), 2) : 0 ).'%';
        
        
        if($a['ClanName'] !== '--')
        {
            //$a['ClanGrade'] = $Clan_Grades[intval($a['ClanGrade'])];
            /*
            <a class="clan_link" href="clan/{{player['ClanName']}}">{{player['ClanName']}}</a> 
                        {% if player['ClanGrade'] != '' %}
                        (<span class="ClanGradeLink ClanGrade{{player['ClanGrade']}}">{{player['ClanGrade']}}</span>)
                        {% endif %}
            */
        }
        
        $a['Name'] = NameToColor($a['Name']);
        $a['ClanName'] = NameToColor($a['ClanName']);
        
        $playerData = $a;
        $Navigate->SetData(array('title' => SITE_TITLE.' - Players - '.$playerData['Name']['RegularName'].'`s Profile'));
    }
    //array_walk($playerData, 'multiNameColors', 'Name,ClanName');
    $Navigate->SetData(array('player' => $playerData,'not_exists' => $player));
}
