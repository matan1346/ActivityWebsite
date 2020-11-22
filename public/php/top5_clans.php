<?php
require '../../includes/config.php';
require '../../includes/classes/mssql.class.php';
require '../../includes/classes/rankings.class.php';


$GunZ = new sqlGunz($_Data['Host'],$_Data['User'],$_Data['Pass'],$_Data['Base']);

$top = 5;

$Clans = new Rankings('clan', $GunZ, -1, $top);


$getClans = $Clans->execute()->getData();


$toPrint = '<tbody>';


$num_clans = sizeof($getClans);

$restOfRank = $top-$num_clans;

for($i = 0;$i < $num_clans;$i++)
    $toPrint .= '
        <tr>
            <td>'.($i+1).'</td>
            <td>'.$getClans[$i]['Name'].'</td>
            <td>'.$getClans[$i]['TotalPoint'].'</td>
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