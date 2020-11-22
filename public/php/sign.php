<?php
require_once 'includes/functions.php';
IsAccessAllowed();

if(isset($_GET['name']))
{
    require_once 'includes/classes/signature.class.php';
    
    $player = $_GET['name'];
    
    $q = 'SELECT Character.Name AS Name,Character.Level AS Level,Character.Ranking AS Rank,ISNULL(Clan.Name, "No Clan") AS "ClanName",ISNULL(Clan.EmblemUrl, 0) AS "Emblem" FROM dbo.Character LEFT JOIN dbo.ClanMember ON ClanMember.CID = Character.CID LEFT JOIN dbo.Clan ON Clan.CLID = ClanMember.CLID WHERE Character.Name = :playername';
    $query = $GunZ->prepare($q);
    $query->bindParam(':playername', $player, PDO::PARAM_STR);
    $query->execute();
    
    if($a = $query->fetch(PDO::FETCH_ASSOC))
    {
        $font = 'C://WINDOWS/Fonts/arial.ttf';
        $allowerdExts = array('png' => 'png','jpg' => 'jpeg','jpeg' => 'jpeg','gif' => 'gif');
        $Path = SITE_URL.'public/images/IGN.png';
        
        $Signature = new Signature($a['Name'].' Signature', $allowerdExts);
        $Signature->setBackground($Path);
        
        $NearWhite = hex2RGB('#EFFFFF');
        
        $Signature->AddText(13, 0, 251, 33, $NearWhite, $font, $a['Name'], true);
        $Signature->AddText(13, 0, 251, 52, $NearWhite, $font, $a['Level'], true);
        $Signature->AddText(13, 0, 251, 67, $NearWhite, $font, $a['Rank'], true);
        $Signature->AddText(13, 0, 251, 85, $NearWhite, $font, $a['ClanName'], true);
        $Signature->AddText(13, 0, 251, 100, $NearWhite, $font, 'Online', true);
        
        if($a['ClanName'] === 'No Clan')
            $Signature->AddText(20, -45, 425, 35, array(255, 255, 255), $font, 'No Clan');
        else
        {   
            if($a['Emblem'] === '0')
                $ImagePath = $GlobalPaths['BASE_CLAN_EMBLEM_DIR'].$GlobalPaths['DEFAULT_CLAN_NO_EMBLEM'];
            else
            {
                //$ImagePath = $_SERVER['DOCUMENT_ROOT'].'Web'.mb_substr($a['Emblem'], 1, mb_strlen($a['Emblem'])-1);
                $ImagePath = $GlobalPaths['BASE_CLAN_EMBLEM_DIR'].$a['Emblem'];
                
                if(!file_exists($ImagePath))
                    $ImagePath = $GlobalPaths['BASE_CLAN_EMBLEM_DIR'].$GlobalPaths['DEFAULT_CLAN_NO_EMBLEM'];
            }
            $Signature->AddImage($ImagePath, 415, 15, 0, 0, 100, 100);
        }
        
        if($a['Rank'] < 4)
        {
            $image = '';
            switch($a['Rank'])
            {
                case 1:
                    $image = '1stplaceD1';
                    break;
                case 2:
                    $image = '2ndplaceD1';
                    break;
                case 3:
                    $image = '3rdplaceD1';
                    break;
            }
            
            if(!empty($image))
            {
               $ImagePath =  SITE_URL.'public/img/'.$image.'.png';
               $Signature->AddImage($ImagePath, 345, 0, 0, 0, 70, 70);
            }
        }
        
        $Signature->MakeImage();
    }
    else
    {
        echo 'Player does not exist';
    }
}
else
{
    echo 'Please specific the name of the player, e.g: signature/Activity';
}