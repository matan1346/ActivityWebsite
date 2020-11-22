<?php
require_once 'includes/functions.php';
//require_once 'includes/classes/form.class.php';
//require_once 'includes/classes/login.class.php';
//require_once 'includes/classes/transfer.class.php';

IsAccessAllowed();
 
$IsConnected = false;
if($Session->isConnected())
{
    $IsConnected = true;
    
    if(isset($_POST['EditClan']))
    {
        $clan_id = intval($_POST['ClanOption']);
        $SelectedMates = $_POST['MarkMate'];
        $roleOption = intval($_POST['role']);
        
        $myClans = $User->getUserField('Clans');
        if(array_key_exists($clan_id, $myClans))
        {
            $Role = getHighestGradeOfClanWithSameUser($myClans[$clan_id]);
            $getRole = getClanChangeRoles($Role);
            
            if(array_key_exists($roleOption, $Clan_Grades) && array_key_exists($Clan_Grades[$roleOption], $getRole))
            {
                if($getRole[$Clan_Grades[$roleOption]] === true)
                {
                    $sizeMates = sizeof($SelectedMates);
                    if($sizeMates > 0)
                    {
                        if($roleOption == 1 && $sizeMates > 1)
                            $MessageSystem = "You must choose 1 mate only for leader.";
                        else
                        {
                            $statementMates = array();
                            for($i = 0;$i < $sizeMates;$i++)
                            {
                                $SelectedMates[$i] = intval($SelectedMates[$i]);
                                $statementMates[] = "CID = '".$SelectedMates[$i]."'";
                            }
                            $selectMatesData = $GunZ->SelectData('ClanMember',array('Grade','CID'),array(array('CLID' => ':clan_id'),$statementMates),array(':clan_id' => $clan_id));
                            
                            $effected = 0;
                            for($i = 0,$sizeMatesFromDB = sizeof($selectMatesData);$i < $sizeMatesFromDB;$i++)
                            {
                                $clan_member_grade = $selectMatesData[$i]['Grade'];
                                $clan_member_id = $selectMatesData[$i]['CID'];
                                
                                if(isSelectedMateIsAllowed($Role, $clan_member_grade) && $clan_member_grade != $roleOption)
                                {
                                    if($roleOption == 10)//Kick
                                        $GunZ->DeleteData('ClanMember',array(array('CLID' => ':clan_id'),array('CID' => ':clan_member_id')),array(':clan_id' => $clan_id,':clan_member_id' => $clan_member_id));
                                    else// if($clan_member_grade != $roleOption)
                                    {
                                        if($roleOption == 1)
                                        {
                                            $GunZ->UpdateData('ClanMember',array('Grade' => 3),array(array('CLID' => ':clan_id'),array('Grade' => 1)),array(':clan_id' => $clan_id));
                                            $GunZ->UpdateData('Clan',array('MasterCID' => ':new_leader_player_id'),array('CLID' => ':clan_id'),array(':clan_id' => $clan_id,':new_leader_player_id' => $clan_member_id));
                                        }
                                        $GunZ->UpdateData('ClanMember',array('Grade' => ':new_grade'),array(array('CLID' => ':clan_id'),array('CID' => ':clan_member_id')),array(':clan_id' => $clan_id,':clan_member_id' => $clan_member_id,':new_grade' => $roleOption));
                                    }
                                    $effected++;
                                }
                            }//The changes have been successfuly saved. 2 members has been affected.
                            if($effected > 0)
                            {
                                $text1 = 'member';
                                if($sizeMatesFromDB > 1)
                                    $text1 .= 's';
                                $text2 = 'has';
                                if($effected > 1)
                                    $text2 = 'have';
                                $MessageSystem = "The changes have been successfuly saved.\n$effected of $sizeMatesFromDB $text1 $text2 been affected.";
                            }
                            else
                                $MessageSystem = "There is no member to update.";
                        }
                    }
                    else
                        $MessageSystem = "Please choose mate before edit.";
                }
                else
                    $MessageSystem = "This option is unavailable for you.";
            }
            else
                $MessageSystem = "This option (role) does not exist.";
            
            
            
            
            
            
            
        }
        else 
            $MessageSystem = "This clan is not from the selectlist.\n Please Select a valid clan.";
    }
        
    
    $myClans = $User->getUserField('Clans');
    
    $getData['Pages'] = array();
    $Mates = array();
    $clanRolesState = array();
    if(sizeof($myClans) > 0)
    {
        $clan_id = key($myClans);
    
        $Role = getHighestGradeOfClanWithSameUser($myClans[$clan_id]);
    
        $getData = getClanMates($GunZ,$clan_id, $Role);
        $Mates = $getData['Mates'];
        
        array_walk($Mates, 'multiNameColors', 'Name');
        
        $clanRolesState = getClanChangeRoles($Role);
        
        $Navigate->SetData(array('clan_id' => $clan_id));
    }
    
    
    $Navigate->SetData(array('ClanManageSystemMsg' => $MessageSystem));
    
    $Navigate->SetData(array('Clans' => $myClans,'RolesState' => $clanRolesState,'Mates' => $Mates,'MatesPages' => $getData['Pages']));
}

$Navigate->SetData(array('IsConnected' => $IsConnected));