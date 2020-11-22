<?php
require '../../includes/config.php';
require '../../includes/classes/mssql.class.php';
require '../../includes/classes/rankings.class.php';


$GunZ = new sqlGunz($_Data['Host'],$_Data['User'],$_Data['Pass'],$_Data['Base']);

$top = 5;

$Players = new Rankings('player', $GunZ, -1, $top);


$getPlayers = $Players->execute()->getData();


$toPrint = '<tbody>';


$num_players = sizeof($getPlayers);

$restOfRank = $top-$num_players;

for($i = 0;$i < $num_players;$i++)
    $toPrint .= '
        <tr>
            <td>'.($i+1).'</td>
            <td>'.$getPlayers[$i]['Name'].'</td>
            <td>'.$getPlayers[$i]['Level'].'</td>
        </tr>';
for($i = ($top-$restOfRank);$i < $top;$i++)
    $toPrint .= '
        <tr>
            <td>'.($i+1).'</td>
            <td>--</td>
            <td>--</td>
        </tr>';
$toPrint .= '</tbody>';
echo $toPrint;