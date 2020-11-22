<?php
session_start();
//session_regenerate_id(true);

$array = array();

if(isset($_POST['clan_id'],$_POST['page']))
{
    require_once '../../includes/config.php';
    require_once '../../includes/globalvars.php';
    require_once '../../includes/functions.php';
    require_once '../../includes/classes/mssql.class.php';
    require_once '../../includes/classes/user.class.php';
    require_once '../../includes/classes/session.class.php';
    //sleep(2);
    
    $GunZ = new sqlGunz($_Data['Host'],$_Data['User'],$_Data['Pass'],$_Data['Base']);
    
    $Session = new Session($GunZ);
    $Session->SetUserSession();
    $User = $Session->getUser();
    
    //var_dump($User);
    
    $clan_id = intval($_POST['clan_id']);
    $page = intval($_POST['page']);
    
    //print_r($User->getUserField('Clans'));
    
    $myClans = $User->getUserField('Clans');
    
    if(array_key_exists($clan_id,$myClans))
    {
        $Role = getHighestGradeOfClanWithSameUser($myClans[$clan_id]);
        
        $array = getClanMates($GunZ, $clan_id,$Role, $page);
        
        //multiNameColors($array, 1, 'ClanDetails/Name,ClanDetails/MasterName');
        array_walk($array['Mates'], 'multiNameColors', 'Name');
        $array['ClanName'] = $myClans[$clan_id][0]['Name'];
        $array['RolesState'] = getClanChangeRoles($Role);
        /*
        //print_r()
        foreach($array['Mates'] as $key => $Mate)
        {//if (Clans[0]['Role'] == 1 and MateDetails['RoleNum'] !=1) or (Clans[0]['Role'] == 3 and MateDetails['RoleNum'] > 3) or (Clans[0]['Role'] != 9)
            //echo $myClans[$clan_id]['Role'].' : '.$Mate['RoleNum'].'<br />';
            if(($myClans[$clan_id]['Role'] == 1 && $Mate['RoleNum'] == 1) || ($myClans[$clan_id]['Role'] == 3 && $Mate['RoleNum'] <= 3) || ($myClans[$clan_id]['Role'] == 9))
                $array['Mates'][$key]['RoleNum'] = -1;
        }
        */
    }
}
echo json_encode($array);