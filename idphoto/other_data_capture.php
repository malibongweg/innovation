<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo = &JFactory::getDBO();
?>
<html>
<head>
<style type="text/css">
	.input { font-size: 10px }	
</style>
</head>
<body>

<form id="oth-form">
<label style="float: left; width: 120px; margin-top: 5px" />Card#</label>
<input type="text" size="9" name="card_num" id="card-num" style="background-color: #c0c0c0" readonly  />
<br style="clear: both"/>

<label style="float: left; width: 120px; margin-top: 5px" />Surname:</label>
<input type="text" size="25" id="oth-surname" name="oth_surname"  maxlength="25" onKeyUp="javascript: this.value=this.value.toUpperCase();" />
<br style="clear: both"/>

<label style="float: left; width: 120px; margin-top: 5px" />Initials:</label>
<input type="text" size="5" id="oth-initials" name="oth_initials"  maxlength="5" onKeyUp="javascript: this.value=this.value.toUpperCase();" />
<br style="clear: both"/>

<label style="float: left; width: 120px; margin-top: 5px" />Auxillary Data:</label>
<input type="text" size="25" id="oth-aux" name="oth_aux"  maxlength="25" onKeyUp="javascript: this.value=this.value.toUpperCase();" />
<br style="clear: both"/>

<label style="float: left; width: 120px; margin-top: 5px" />Expire:</label>
<select name="expire_month" size="1">
	<option value="JANUARY">JANUARY</option>
	<option value="FEBRUARY">FEBRUARY</option>
	<option value="MARCH">MARCH</option>
	<option value="APRIL">APRIL</option>
	<option value="MAY">MAY</option>
	<option value="JUNE">JUNE</option>
	<option value="JULY">JULY</option>
	<option value="AUGUST">AUGUST</option>
	<option value="SEPTEMBER">SEPTEMBER</option>
	<option value="OCTOBER">OCTOBER</option>
	<option value="NOVEMBER">NOVEMBER</option>
	<option value="DECEMBER">DECEMBER</option>
</select>&nbsp;<span style="font-weight: bold">[Expire year : <?php echo date("Y"); ?>]</span>
<br style="clear: both"/>

<label style="float: left; width: 120px; margin-top: 5px" />Card Type:</label>
<select name="card_type" id="card-type" size="1">
	<option value="C">CONTRACTOR</option>
	<option value="V">VISITOR</option>
	<option value="W">WALKTHROUGH</option>
	<option value="P">PENSIONER</option>
	<!--option value="I">INTERN</option-->
	<option value="X">CLEANER</option>
</select>
<br style="clear: both"/>

<label style="float: left; width: 120px; margin-top: 5px" />Barcode:</label>
<input type="text" size="13" id="oth-barcode" name="oth_barcode"  readonly  style="background-color: #c0c0c0" />
<br style="clear: both"/>

<label style="float: left; width: 120px; margin-top: 5px" />&nbsp;</label>
<input type="submit" value="Save Record" />&nbsp;
<input type="button" id="oth-cancel" value="Cancel" />

</form>






</body>
</html>
<script type="text/javascript">
window.addEvent('domready',function(){
	window.parent.$j.colorbox.resize({ 'height': 350, 'width': 500 });

	$('oth-cancel').addEvent('click',function(){
		window.parent.$j.colorbox.close();
	});

	$('oth-form').addEvent('submit',function(e){
			e.stop();
			var x = new Request({
				method: 'post',
				url: 'index.php?option=com_jumi&fileid=126&action=save_oth',
				noCache: true,
				data: this,
				onComplete: function(response){
					if (parseInt(response) == -1)	{
						alert('Error saving record....Please report to CTS department.');
						return false;
					} else {
						alert('Record saved.');
						window.parent.$('id-srch').set('value',$('card-num').get('value'));
						window.parent.searchEntry();
						window.parent.$j.colorbox.close();
					}
				}
			}).send();
	});

	getCardNo();

});

function getCardNo(){
	var x = new Request ({
			url: 'index.php?option=com_jumi&fileid=126&action=new_card',
			noCache: true,
			method: 'get',
			onComplete: function(response){
				if (parseInt(response) == -1)	{
					alert('Error connecting to ITS...Contact CTS department.');
					return false;
				} else {
						var r = response.split(';');
						$('card-num').set('value',r[0]);
						$('oth-barcode').set('value',r[1]);
				}
				$('oth-surname').focus();
			}
	}).send();
}
</script>
