<?php
/*
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
exit();*/
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();//page compressed
include_once('class/class.main.php');
$link = strtolower($_REQUEST['link']);
$connect = new db();
$connect->db_connect();
$con =  new fucn();
$imgrotate = new fucn();
//echo "SELECT s.id,s.source,s.link,s.label,p.nicename,v.link as vlink,g.productId,g.state FROM businessProfile AS p LEFT JOIN businessvanitylink AS v ON v.placeId = p.profilePlaceId LEFT JOIN businessList AS l ON l.id = p.profilePlaceId LEFT JOIN businessUserGroup AS g ON g.gId = l.userGroupId LEFT JOIN businessshorturl as s ON s.placeId = p.profilePlaceId WHERE s.link = '{$link}' OR v.link = '{$link}' OR p.nicename = '{$link}'";

$result = mysql_query("SELECT s.id,s.source,s.link,s.label,p.nicename,v.link as vlink,g.productId,g.state FROM businessProfile AS p LEFT JOIN businessvanitylink AS v ON v.placeId = p.profilePlaceId LEFT JOIN businessList AS l ON l.id = p.profilePlaceId LEFT JOIN businessUserGroup AS g ON g.gId = l.userGroupId LEFT JOIN businessshorturl as s ON s.placeId = p.profilePlaceId WHERE s.link = '{$link}' OR v.link = '{$link}' OR p.nicename = '{$link}'") or die(mysql_error());
//$row = mysql_fetch_object($result);
//print_r($row);
//die();

if(mysql_num_rows($result)){
	$row = mysql_fetch_object($result);
	if($row->state == 'canceled' || $row->state == 'unpaid'){
		header("HTTP/1.0 404 Not Found");
		header('Location: http://tabluu.com');
		exit;
	}else{
		if($row->link == $link){
			if(trim($row->label) != '')
				echo $goingto = 'rateone.html?p='. $row->nicename .'&s='.$row->source.'&label='.$row->id; //urlencode 
			else
				echo $goingto = 'rateone.html?p='. $row->nicename .'&s='.$row->source;
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: {$goingto}");
			exit;
		}else if($row->vlink == $link){
			if($row->productId == $connect->basicID || $row->productId == $connect->basic24 || $row->productId == $connect->basic12 || $row->productId == $connect->freever){
				//$goingto = 'https://www.tabluu.com/'.$row->nicename.'.html'; 
				$goingto = ''.$row->nicename.'.html'; 
				header("HTTP/1.1 301 Moved Permanently");
				header("Location: {$goingto}");
				exit();
			}else{
				$nice = $row->nicename;
				include_once('pinme.php');
			}
		}else if($row->nicename == $link){
			$nice = $row->nicename;
			include_once('pinme.php');
		}
	}
}else{
	$goingto = 'https://tabluu.com'; 
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: {$goingto}");
	exit();
}	
$connect->db_connect();
//header("Location: {$goingto}");
//exit();
?>


?>
