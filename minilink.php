<?php
include_once('class/class.main.php');
$link = strtolower($_REQUEST['link']);
$connect = new db();
$connect->db_connect();
$con =  new fucn();
$result = mysql_query("SELECT s.id,s.source,s.link,s.label,p.nicename FROM businessshorturl as s LEFT JOIN businessProfile AS p ON p.profilePlaceId = s.placeId WHERE link = '{$link}'");
header("HTTP/1.1 301 Moved Permanently");
if(mysql_num_rows($result)){
	$row = mysql_fetch_object($result);
	if(trim($row->label) != '')
		echo $goingto = 'app/rateone.html?p='. $row->nicename .'&s='.$row->source.'&label='.$row->id; //urlencode 
	else
		echo $goingto = 'app/rateone.html?p='. $row->nicename .'&s='.$row->source;
}else
	$goingto = 'https://www.tabluu.com'; 
$connect->db_connect();
header("Location: {$goingto}");
exit();
?>
