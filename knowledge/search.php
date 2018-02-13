<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/json.js");
$doc->addScript("scripts/knowledge/knowledge.js");
?>
<a href="#" id="knowledge-display" class="modalizer" ></a>
<div class="rnd-borders" style="width: auto; height: auto; text-align: center; padding: 3px 3px; background-color: #f6f6f6; border: 1px solid #bcbcbc">
<input type="text" id="keywords" name="keywords" class="input_field" style="width: 80%" /><br />
<div id="showBusy" style="width: auto; height: auto; position: relevant; text-align: left; display: none" >
<img src="images/kit-ajax.gif" width="16" height="16" border="0" alt="">
</div>
<input type="radio" name="stypes" value="0" checked />Contains any word<br />
<input type="radio" name="stypes" value="1" />Contains all words<br />
<div id="know-result" style="width: 100%; height: auto; display: none">
</div>
</div>