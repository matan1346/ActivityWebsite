<?php
require_once 'includes/functions.php';

IsAccessAllowed();

$query_str = 'SELECT COUNT(1) AS Rows FROM Character';
$query = $GunZ->Query('Select num of rows', $query_str);
$query->execute();

$getNumRows = $query->fetch(PDO::FETCH_ASSOC);


$per_page = 20;

$max_pages = ceil($getNumRows['Rows']/$per_page);

$page_number = 1;
if(isset($_GET['name']))
    $page_number = intval($_GET['name']);

if($page_number < 1 || $page_number > $max_pages)
    $page_number = 1;

$myData = getRankingPage('player', $getNumRows['Rows'], $per_page, $page_number);
array_walk($myData, 'multiNameColors', 'Name,ClanName');

$each_side_page = 4;

//$Navigate->SetData(array('Per_Page' => $per_page));
$Navigate->SetData(getRankingNavigatePages($each_side_page, $page_number, $max_pages, $per_page));


$first_top = ($page_number-1)*$per_page;

$Navigate->SetData(array('players' => $myData,'title' => SITE_TITLE.' - Players - Top '.($first_top+1).'-'.($first_top+$per_page)));