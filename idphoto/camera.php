<link rel="stylesheet" href="/scripts/idphoto/css.css" type="text/css" />
<script type="text/javascript" src="/scripts/idphoto/jquery.js"></script>
<script type="text/javascript" src="/scripts/idphoto/jquery.webcam.js.js"></script>
<script type="text/javascript" src="/scripts/idphoto/camera.js"></script>

<input type="hidden" id="uid" value="<?php echo $user->username; ?>" />
<input type="hidden" id="sysid" value="<?php echo $user->id; ?>" />
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Student History Report" />
<input type="hidden" id="app-url" value="<?php $uri = parse_url(JURI::current()); echo $uri['path'];  ?>" />
<input type="hidden" id="app-uid" value="<?php echo $user->id; ?>" />
</form>
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
            <h3 class="t" >
			 <a href="#" id="fav-def"><img src="images/default.png" width="16" height="16" style="vertical-align: middle" title="Set as default." /></a>
			<a href="#" id="fav-app"><img src="images/fav.png" width="16" height="16" style="vertical-align: middle" title="Add to favorite." /></a>
			<a href="#" class="modalizer" id="bug-app"><img src="images/radar.png" width="16" height="16" style="vertical-align: middle" title="Application Tracker." /></a>
      ID Photo Application</h3>
        </div>
            <div class="art-blockcontent">
            <div class="art-blockcontent-body" >



<div id="camera">
<!--object id="XwebcamXobjectX" type="application/x-shockwave-flash" data="/scripts/idphoto/jscam_canvas_only.swf" height="240" width="320"><param name="movie" value="/scripts/idphoto/jscam_canvas_only.swf"><param name="FlashVars" value="mode=callback&quality=85"><param name="allowScriptAccess" value="always"></object-->
</div>

<p><canvas id="canvas" height="240" width="320"></canvas></p>
<div id="status" style="width: 100%; height: 30px; border: 1px solid black"></div>

</div></div></div></div>
