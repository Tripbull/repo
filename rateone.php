<?php
session_start();
$ur_session = rand(0, 15);
$_SESSION['session']=$ur_session;
$nice = $_REQUEST['p'];
include_once('class/class.main.php');
$connect = new db();
$connect->db_connect();
$sql = "SELECT p.profilePlaceId, p.businessName, p.nicename, p.category, p.address, p.city, p.country, p.zip, p.contactNo FROM businessProfile AS p WHERE p.nicename =  '$nice' LIMIT 1";
$result = mysql_query($sql);
$row = mysql_fetch_object($result);
$address = $row->businessName .', '. $row->address .', '. $row->city .', '.$row->country;
$_SESSION['address']=$address;
$connect->db_disconnect();
?>
<!DOCTYPE html>
<html> 
<head>
	<title>Please rate <?php echo $address ?></title>
    <meta name="robots" content="index, follow"/>
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<!--[if lt IE 9]>
    <script src="js/excanvas.js"></script>
	<![endif]-->
	<link type="text/css" rel="stylesheet" href="css/jquery.mobile-1.4.2.min.css" />
	<link type="text/css" rel="stylesheet" href="css/dialog.css" type="text/css">
	<link type="text/css" rel="stylesheet" href="css/dialogcomment.css" type="text/css">
	<link type="text/css" rel="stylesheet" href="css/magnific-popup.css"/>
	<link type="text/css" rel="stylesheet" href="js/source/jquery.fancybox.css?v=2.1.5" media="screen" />
	<link type="text/css" rel="stylesheet" href="css/style.css" />
	<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="js/jquery.mobile-1.4.2.min.js"></script>
	<script type="text/javascript" src="js/source/jquery.fancybox.pack.js?v=2.1.5"></script>
	<script type="text/javascript" src="js/scriptcam.js"></script>
	<script type="text/javascript" src="js/jquery.form.min.js"></script>
	<script type="text/javascript" src="js/json3.min.js"></script>
	<script type="text/javascript" src="js/jquery.md5.js"></script>
	<script type="text/javascript" src="js/dialog.js"></script>
	<script type="text/javascript" src="js/dialogcomment.js"></script>
	<script type="text/javascript" src="js/jquery.magnific-popup.js"></script>
	<script type="text/javascript" src="js/rate.js"></script>
	<script type="text/javascript" src="js/webcam.js"></script>
	<script type="text/javascript" src="js/exif.js"></script>
	<link rel="Shortcut Icon" href="http://www.tabluu.com/blog/wp-content/themes/Tabluu%20Theme%20V1/images/favicon.ico" type="image/x-icon">
</head>
<body>
	<div class="hide top-button-selfie"><div style="text-align:center;"><div style="display: inline-block;vertical-align: middle;height: 50px;margin:10px 0px 0px auto;"><p style="margin:0px !important;font-size:12px !important;">powered by</p><img src="images/tabluu-logo-mono-small.png" height="63%" style="margin-top:-2px;"></div><span style="display: inline-block;margin-left: auto;margin-right: 20px;" class="btn-take-isselfie">Your Selfie Now!</span></div></div>
	<div style="position:absolute;opacity:0;overflow:hidden;">
		<div style="position:absolute;font-family:myriadpro;">.</div>
		<div style="position:absolute;font-family:Lato-Light;">.</div>
		<div style="position:absolute;font-family:myriadproit;">.</div>
		<canvas id="canvas-image" style="position:absolute;"></canvas>
		<canvas id="canvas-image-test" style="position:absolute;"></canvas>
		<canvas id="canvas-resize" style="position:absolute;"></canvas>
	</div>
	<div id="fb-root"></div>
	<div class="rate" id="rateone" data-dom-cache="false" data-role="page" data-prefetch="false">
		<div class="content-wrap">
			<div role="main" class="ui-content">
				<div class="ratewrap">
					<div class="hide isselfie">
					<div class="rate-logo">
						<img src="images/" alt="" class="loc-logo" />
					</div>
					
					<div class="rate-question">
						<p class="ratetxt"></p>
					</div>
					<div class="wrap-star">
						<div class="rate-wrapstar">
							<div class="rate-star starRate1">
								<img src="images/template/blankstar.png" width="" class="imgrate1" alt="" />
								<span class="vpoor"></span>
							</div>
							<div class="rate-star starRate2">
								<img src="images/template/blankstar.png" alt="" class="imgrate2" />
								<span class="poor"></span>
							</div>
							<div class="rate-star starRate3">
								<img src="images/template/blankstar.png" alt="" class="imgrate3" />
								<span class="fair"></span>
							</div>
							<div class="rate-star starRate4">
								<img src="images/template/blankstar.png" alt="" class="imgrate4" />
								<span class="good"></span>
							</div>
							<div class="rate-star starRate5">
								<img src="images/template/blankstar.png" alt="" class="imgrate5" />
								<span class="exc"></span>
							</div>
						</div>
					</div>
					<div class="loc-address"><p class="addressname"></p></div>
					<div class="loc-login"><img src="images/template/tabluu-std-logo.png" class="ratelogo" alt="" style="width:85px !important;height:auto !important;"/></div>
					<div>
				</div>	
				<input type="hidden" id="nicename" name="nicename" value="<?php echo $_REQUEST['p']?>" />
				<form id="frmtakeselfie" style="visibility:hidden;height:0px" action="setPhoto.php" method="post" enctype="multipart/form-data" >
					<input type="file" name="fileselfie" style="visibility:hidden;height:0px" id="fileselfie" accept="image/*" capture="camera" />
					<input type="hidden" value="" name="selfieId" id="selfieId" />
				</form>
				<div class="hide">
					<div id="data">
						<div class="cam-frame">
							<div id="screen"></div>
							<canvas id="canvas" style="position:absolute;z-index:-1;" width="640" height="480"></canvas>
							<div class="snapshotbutton">
								<div class="snapshot hide">
									<a href="#" data-rel="back" class="cancelsnap">cancel</a>
									<div class="btnseparator"></div>
									<a href="#" data-rel="back" class="takesnap">snap</a>
								</div>
								<div class="usesnap hide">
									<a href="#" data-rel="back" class="cancelsnap">discard</a>
									<div class="btnseparator"></div>
									<a href="#" data-rel="back" class="use">use</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /content -->
		</div>
	</div>
</body>
</html>