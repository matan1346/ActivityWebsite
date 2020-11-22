<?php
session_start();
//session_regenerate_id(true);
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 24*60*60));
$array = array();

if(isset($_POST['search_name'],$_POST['page']))
{
    require_once '../../includes/config.php';
    require_once '../../includes/globalvars.php';
    require_once '../../includes/functions.php';
    require_once '../../includes/classes/mssql.class.php';
    require_once '../../includes/classes/user.class.php';
    require_once '../../includes/classes/session.class.php';
    require_once '../../includes/classes/rankings.class.php';
    //sleep(2);
    
    $GunZ = new sqlGunz($_Data['Host'],$_Data['User'],$_Data['Pass'],$_Data['Base']);
    
    $Session = new Session($GunZ);
    $Session->SetUserSession();
    $User = $Session->getUser();
    
    //var_dump($User);
    
    $search_player = $_POST['search_name'];
    
    
    $statement = $GunZ->WhereStatement(array('Chars.Name LIKE :player_name'));
    $bind = array(':player_name' => '%'.$search_player.'%');
    
    $query_str = 'SELECT COUNT(1) AS Rows FROM Character AS Chars WHERE '.$statement;
    $query = $GunZ->prepare($query_str);
    $query->bindValue(':player_name',$search_player,PDO::PARAM_STR);
    $query->execute();
    
    $getNumRows = $query->fetch(PDO::FETCH_ASSOC);
    
    
    $per_page = 20;
    
    $max_pages = ceil($getNumRows['Rows']/$per_page);
    
    $page_number = 1;
    if(isset($_POST['page']))
        $page_number = intval($_POST['page']);
    
    if($page_number < 1 || $page_number > $max_pages)
        $page_number = 1;
    
    
    $where = array('Statement' => 'AND '.$statement,'Bind' => $bind);
    
    $array['Players'] = getRankingPage('player', $getNumRows['Rows'], $per_page, $page_number,$where);
    //print_r($User->getUserField('Clans'));
    
    array_walk($array['Players'], 'multiNameColors', 'Name,ClanName');
        
    //$array = getClanMates($GunZ, $clan_id,$myClans[$clan_id]['Role'], $page);

}
echo json_encode($array);