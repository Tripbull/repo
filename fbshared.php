<?php
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();//page compressed
include_once('class/class.main.php');
$connect = new db();
$connect->db_connect();
$imgrotate = new fucn();
$nice = strtolower($_REQUEST['link']);
$splitID = explode('-',$nice);
$sql = "SELECT s.pathimg,s.ave,s.comment, p.profilePlaceId, p.businessName, p.nicename, p.category, p.longitude, p.latitude, p.address, p.city, p.country, p.zip, p.contactNo, p.facebookURL, p.websiteURL, p.showmap, p.booknow,p.email as pemail, l.subscribe, u.productId,u.state,c.fbpost FROM businessProfile AS p
LEFT JOIN businessList AS l ON l.id = p.profilePlaceId
LEFT JOIN businessUserGroup AS u ON u.gId = l.userGroupId
LEFT JOIN businessCustom AS c ON c.customPlaceId = p.profilePlaceId
LEFT JOIN sharedlink_{$splitID[1]} AS s ON s.link = '{$nice}'
WHERE p.profilePlaceId =  {$splitID[1]}
LIMIT 1";
$result1 = mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_object($result1);
$placeId = $row->profilePlaceId;
$photoDomain = '';//'http://www.tabluu.com/';
$path = '../'.$connect->path;
$businessTitle = $row->businessName .', '.$row->address.' '.$row->city.', '.$row->zip.' '.$row->country. ' @ Tabluu';
$domainpath = '';
if($row->state == 'canceled' || $row->state == 'unpaid' || $row->ave == null){
	header("HTTP/1.0 404 Not Found");
	header('Location: http://www.tabluu.com');
	exit;
}
$fbpost = json_decode($row->fbpost);
	$str = (!empty($fbpost->fbpost) ? $fbpost->fbpost : '<comment> <brand> gets a <rating> out of <max_rating> rating from me. <tabluu_url> <address>, <tel>.');
	$rev = (!empty($fbpost->postdesc) ? $fbpost->postdesc : 'My review of <brand>');
	if($row->fbpost){
		if(empty($fbpost->fbpost) && empty($fbpost->postdesc))
			echo $str =  $row->fbpost;
	}
	$rev = str_replace("<brand>",$row->businessName,$rev); 
	$str = str_replace("<brand>",$row->businessName,$str); 
	$str = str_replace("<rating>",round($row->ave,1),$str);
	$str = str_replace("<max_rating>",5,$str);
	$str = str_replace("<address>",$row->address,$str); 
	if(trim($row->comment) == ''){
		$str = str_replace('<comment> ','',$str);
	}else
		$str = str_replace("<comment>",$row->comment,$str); 
		
	if(trim($row->contactNo) == ''){
		$str = str_replace(', <tel>','',$str);
	}else
		$str = str_replace("<tel>",$row->contactNo,$str);
	$desc_meta = $str;
	$description = $str;
	$desc_meta = str_replace("<tabluu_url>",'',$desc_meta);
	$description = str_replace("<tabluu_url>",'<a href="https://www.tabluu.com/'.$row->nicename.'.html" target="_blank">https://www.tabluu.com/'.$row->nicename.'.html</a>',$description);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/">
<head>

<?php
echo '<title>'. $row->businessName .', '.$row->address.' '.$row->city.', '.$row->zip.' '.$row->country. ' @ Tabluu</title>';
	if($row->state == 'active' && $row->subscribe > 0)
		echo '<meta name="robots" content="index, follow" />';
	else 
		echo '<meta name="robots" content="noindex, nofollow" />';	
	$srcimg = $row->pathimg;
	list($width, $height) = getimagesize($srcimg);	
$istest = true;
if($istest){
   $curDomain = 'https://www.tabluu.com/';
}else
	$curDomain = '../';	
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta property="og:description" content="<?php echo $desc_meta?>" />
<meta property="og:title" content="<?php echo $rev?>" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="tabluu.com" />
<meta property="og:url" content="<?=$curDomain.'user/'.$nice?>" />
<meta property="og:image:url" content="<?=$curDomain.'app/'.$srcimg;?>" />
<meta property="og:image:width" content="<?=$width?>" />
<meta property="og:image:height" content="<?=$height?>" />
<meta property="fb:app_id" content="682746285089153" />
<link href="<?=$path?>css/fbshared.css" media="screen" rel="stylesheet" type="text/css" />
<link href="<?=$path?>js/source/jquery.fancybox.css?v=2.1.5" media="screen" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=$path?>js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?=$path?>js/fbshared.js"></script>
<script type="text/javascript" src="<?=$path?>js/css3-mediaqueries.js"></script>
<!--<script src="//load.sumome.com/" data-sumo-site-id="9e98d0a1ee03ad7942ebac5144759f147aafe068a407e46486c26b9a207c4300" async="async"></script>-->
</head>
<body>
<div id="fb-root"></div>
<?php
if($width > 820)
	$width = 820;
?>
<input type="hidden" value="<?=$row->nicename?>" id="nice" name="nice" />
<div id="vdesktop">
	<div class="header">
		<div class="HeaderContainer">
			<div class="d-logo"><a href="/" rel="follow" class="Pinme"><img alt="www.tabluu.com" src="<?=$path?>images/white-logo-tabluu-page.png" /></a></div>
		</div>
	</div>
</div>
<div id="vmobile">
	<div class="header">
		<div class="logo"><a href="/"><img src="<?=$path?>images/white-logo-tabluu-page.png" > </a></div>
		<a href="https://www.tabluu.com/<?=$row->nicename?>.html" rel="follow"><div class="topleftmenu"> <span class="mobile_search"></span></div></a>
	</div>	 
	<div id="topmenu">
		<ul>
			<li class="borderright" id="showcase"><a href="https://www.tabluu.com/<?=$row->nicename?>.html" rel="follow">Showcase</a></li>
			<li class="activeMenu" id="top-reviews"><a href="https://www.tabluu.com/<?=$row->nicename?>.html" rel="follow">Reviews</a></li>
		</ul>
	</div>
</div>
<!--<div style="position:fixed;top:0;left:0;background-color:#000;height:100%;width:100%"> </div>-->
<div class="overlay"> </div>
<div class="ColumnContainer">
	<div class="wrapheader">
	    <div class="MerchantHead" style="min-height:<?=$height+45?>px;max-width:<?=$width+380?>px;">
			<a href="https://www.tabluu.com/<?=$row->nicename?>.html" rel="follow"><div class="xclose"></div></a>
			<div class="clear"></div>
			<div style="margin:0 auto;width:100%;max-width:<?=$width+390?>px;">
			  <div class="left text-center" style="max-width:<?=$width?>px;">
				<a href="https://www.tabluu.com/<?=$row->nicename?>.html"><img src="<?=$path.$srcimg;?>" width="<?=$width?>" height="<?=$height?>"  alt="selfie photo" /></a>
			  </div>
			 <div class="right">
				<?php
				echo "<p>{$description}</p>";
				?>
				<div class="fb-comments" data-href="<?=$curDomain.'user/'.$nice;?>" mobile="true" data-numposts="5" data-colorscheme="light"></div>
			  </div> 
		</div>
		</div>
	</div>
</div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3&appId=104158746362545";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script> 
</body>
</html>
<?php
	function encodequote($str){
		$str = str_replace('<double>','"',str_replace("<quote>","'",$str));
		$str = str_replace("<comma>",',',$str);
		return $str;
	}
?>