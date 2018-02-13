<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
jimport('joomla.environment.browser');
$dbo = &JFactory::getDBO();
$user = &JFactory::getUser();
$doc = &JFactory::getDocument();
$doc -> addCustomTag("<meta http-equiv='X-UA-Compatible' content='IE=8' />");
$doc -> addScript("scripts/fav_apps.js");
$doc -> addScript("scripts/json.js");
$doc -> addScript("scripts/idphoto/jquery.js");
$doc -> addStyleSheet("scripts/idphoto/css.css");
$doc -> addStyleSheet("scripts/idphoto/uvumi-crop.css");
$doc -> addScript("scripts/idphoto/jquery.webcam.js");
$doc -> addScript("scripts/idphoto/camera.js");
$doc -> addScript("scripts/idphoto/UvumiCrop.js");
$doc -> addScript("scripts/idphoto/java.js");
?>
<style type="text/css">
	.noedit {
		disabled: true;
		color: #6c6c6c;
		text-align: center;
		margin-bottom: 5px
	}
</style>
<input type="hidden" id="system-mode" />
<input type="hidden" id="system-log" />
<a href="#" id="lnk-encode" class="modalizer" > </a>
<a href="#" id="lnk-other" class="modalizer" > </a>
<a href="#" id="lnk-card" class="modalizer"></a>
<input type="hidden" id="uid" value="<?php echo $user -> username; ?>" />
<input type="hidden" id="sysid" value="<?php echo $user -> id; ?>" />
<input type="hidden" id="rootdir" value="<?php echo $_SERVER['DOCUMENT_ROOT']; ?>" />
<input type="hidden" id="sysdate" value="<?php echo date("d-m-Y"); ?>" />
<input type="hidden" id="crd-type" />
<!--Define app name here-->
<form id="app-details">
	<input type="hidden" id="app-name" value="Student History Report" />
	<input type="hidden" id="app-url" value="<?php $uri = parse_url(JURI::current());
		echo $uri['path'];
  ?>" />
	<input type="hidden" id="app-uid" value="<?php echo $user -> id; ?>" />
</form>
<div style="position: absolute; top: 5px; left: 5px; width: 100%">
	<div class="art-block">
		<div class="art-block-tl"></div>

		<div class="art-block-tr"></div>
		<div class="art-block-bl"></div>
		<div class="art-block-br"></div>
		<div class="art-block-tc"></div>
		<div class="art-block-bc"></div>
		<div class="art-block-cl"></div>
		<div class="art-block-cr"></div>
		<div class="art-block-cc"></div>
		<div class="art-block-body">

			<div class="art-blockheader">
				<div class="l"></div>
				<div class="r"></div>
				<h3 class="t" ><div style="float: right; width: auto; height: auto; text-align: right" id="status"></div><a href="#" id="fav-def">
				    <img src="images/default.png" width="16" height="16" style="vertical-align: middle" title="Set as default." /></a>
				    <a href="#" id="fav-app">
				    <img src="images/fav.png" width="16" height="16" style="vertical-align: middle" title="Add to favorite." /></a>
				    <a href="#" class="modalizer" id="bug-app">
				        <img src="images/radar.png" width="16" height="16" style="vertical-align: middle" title="Application Tracker." /></a>
				         <span id="photo-title">ID Photo Application</span></h3>
			</div>
			<div class="art-blockcontent">
				<div class="art-blockcontent-body" >

					<!--div id="myPreview" style="z-index: 3500; background-color: #3e3e3e"></div-->

					<div id="camera-frame" style="float: left; width: auto; height: auto">
						<div id="camera"></div>
						<div id="canvas">
							<img id="captured-pic" width="320" height="240" border="0" src="/scripts/idphoto/img/blank.jpg">
						</div>
					</div>
				</div>

				<div id="show-busy" style="position: absolute; top: 30px; left: 70%; height: 50px; width: 100px; border: 2px solid #d4d4d4; background-color: #ffffff; text-align: center; padding: 5px; display: none">
					<img src="/scripts/idphoto/img/ajax.gif" width="32" height="32" border="0" alt="">
					<br style="clear: both" />
					<span style="font-weight: bold">Busy...</span>
				</div>

				<div id="card-frame" style="float: right; width: 40%; height: auto; border: 2px solid #000000; text-align: center">
					<img src="/scripts/idphoto/img/staff.jpg" style="width: 100%" height="80" border="0" id="card-header">
					<p style="font-weight: bold">
						IDENTIFICATION CARD #<span id="photo-num" style="font-weight: bold"></span>
					</p>
					<p style="font-weight: bold; color:#070eaa; font-size: 12px" id="card-type">
						&nbsp;
					</p>

					<input type="hidden" id="userid" />
					<input type="text" name="id_srch" id="id-srch" size="9" class="numeric" maxlength="9"/>
					<input type="button" class="button" id="srch-button" value="?" />
					&nbsp;
					<input type="button" id="take-picture" style="background-image: url('/scripts/idphoto/cam.png'); background-repeat: no-repeat; border: none; width: 38px; height: 27px; display: none" />
					<br />
					<input type="text" name="id_name" id="id-name" size="30" class="noedit" />
					<br />
					<input type="text" name="id_aux" id="id-aux" size="30" class="noedit" />
					<br />
					<input type="text" name="id_expire" id="id-expire" size="15" class="noedit" />

					<!--div id="myPreview" style="z-index: 3500; background-color: #3e3e3e"></div-->

					<div id="fakeFrame" style="z-index: 3500; border: 2px solid #555555; position: relative; width: 100px; height: 105px; margin-top: 5px; background-color: #000000; margin-left: auto; margin-right: auto; margin-bottom: 5px">
						<img id="fake-pic" width="100" height="105" border="0" />
					</div>

					<input type="text" name="id_barcode" id="id-barcode" size="13" class="noedit" /><br style="clear: both">
					<input type="checkbox" name="photo_rfid" id="rfid" checked /><span style="font-weight: bold">Ask RFID?</span>
					<div style="display: none" id="stf-intern"><input type="checkbox" id="stf-intern-box" value="1" />Intern</div>
					<br />
					<input type="button" class="button" id="print-button" value="Print Card"/>
					&nbsp;
					<input type="button" class="button" id="encode-button" value="Encode Only" />
					<br />
					<br />

				</div>

				<div style="float: right; width: 40%; height: auto; text-align: center; margin-top: 5px">
					<input type="button" class="button" value="New Other Card" id="new-other" style="display: none; disabled: false" />
					<input type="button" class="button" value="New Barcode" id="new-barcode" style="display: none" />
				</div>

			</div>
		</div>
	</div>
</div>
<?php
$browser = &JBrowser::getInstance();
$browserType = $browser -> getBrowser();
$browserVersion = $browser -> getMajor();

if (trim($browserType) == 'msie') {
	echo "<script type='text/javascript'>";
	echo "alert('This application was designed for Firefox browser due to HTML5 components and silent printing from browser.');";
	echo "window.location.href='http://opa.cput.ac.za'";
	echo "</script>";
}
?>
