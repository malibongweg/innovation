<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

$user =& JFactory::getUser();
$groups = $user->get('groups');

if(array_key_exists("acquisitions",$groups)) {

$dbo =& JFactory::getDBO();
$doc = & JFactory::getDocument();
$doc->addScript('scripts/ict/ict.js');
$doc->addScript('scripts/ict/json.js');
$doc->addScript("scripts/datetimepicker_css.js");
$doc->addScript("scripts/validnum.js");
JHTML::_('behavior.mootools');

if (isset($_POST['dbaction'])) {
	if ($_POST['dbaction'] == 1) {
		$sql = sprintf("insert into ict_acquisitions (capture_date,call_id,caller_id,call_details,call_loggedby,acquisition_type,cost_centre,approval_email,status=1) values
		('%s',%d,'%s','%s','%s',%d,'%s','%s')",$_POST['cap_date'],$_POST['call_id'],$_POST['caller_id'],
		$_POST['call_details'],$_POST['logged_by'],$_POST['ac_type'],$_POST['cost_centre'],$_POST['app_email']);
		echo $sql;
		$dbo->setQuery($sql);
		$result = $dbo->query();
	} else if ($_POST['dbaction'] == 2) {
		$sql = sprintf("update ict_acquisitions set capture_date='%s',call_id=%d,caller_id='%s',call_details='%s',call_loggedby='%s',acquisition_type=%d,
		cost_centre='%s',approval_email='%s',status=1 where id=%d",$_POST['cap_date'],$_POST['call_id'],$_POST['caller_id'],
		$_POST['call_details'],$_POST['logged_by'],$_POST['ac_type'],$_POST['cost_centre'],$_POST['app_email'],$_POST['id']);
		$dbo->setQuery($sql);
		$result = $dbo->query();
	}
}
?>
<script type='text/javascript'>

if(typeof HTMLElement!='undefined'&&!HTMLElement.prototype.click) {
HTMLElement.prototype.click=function() {
var evt = this.ownerDocument.createEvent('MouseEvents');
evt.initMouseEvent('click', true, true, this.ownerDocument.defaultView, 1, 0, 0, 0, 0, false, false, false, false, 0, null);
this.dispatchEvent(evt);
}}

function setMessages(msg) {
	$('bh').set('html',msg);
}

function disableButtons(form) {
	$each($$('input[type=button]'),function(el) {
		el.disabled = true;
	});
	$each($$('input[type=submit]'),function(el) {
		el.disabled = true;
	});
}

function enableButtons(form) {
	$each($$('input[type=button]'),function(el) {
		el.disabled = false;
	});
	$each($$('input[type=submit]'),function(el) {
		el.disabled = false;
	});
}

function loadMainScreen() {
	if ($('action')) {
		if ($('action').value == 1) {
			var url = 'index.php?option=com_jumi&view=application&fileid=6&request=7&id='+$('id').value;
				var a = new Request({ url: url,
				method: 'get',
				async: false,
				onComplete: function (response) {
				}
			}).send();
		}
	}

	$('ajax-container').innerHTML = '<img src="images/ajax-loader.gif" width="150" height="24"  />';
				var url = 'index.php?option=com_jumi&view=application&fileid=7&screen=1';
				var a = new Request({ url: url,
				method: 'get',
				async: false,
				onComplete: function (response) {
				$('ajax-container').set('html','');
				$('content').set('html', response);
				}
			}).send();

			if ($('button_cancel')) {
			$('button_cancel').addEvent('click',assignCancelButton,false);
		}
}
function deleteItem(value) {
	if (confirm('Are you sure?')) {
		var ditem = $('ac_items_list').getSelected().get('value');
		var url = 'index.php?option=com_jumi&view=application&fileid=6&request=6&id='+value+'&sc='+ditem;
		var a = new Request({ url: url,
				method: 'get',
				async: false,
				onComplete: function (response) {
				$('items-ajax').set('html','ADD ITEMS');
					$('ac_items').set('html',response);
				}
			}).send();
	loadItemsScreen(value);
	getItems(value);
	}
}

function saveAddItems(value) {
	var id = $('newid').value;
	$('items-ajax').innerHTML = '<img src="images/ajax-loader.gif" width="150" height="24"  />';
	var url = 'index.php?option=com_jumi&view=application&fileid=6&request=5&id='+$('newid').value+
		'&qty='+$('qty').value+'&sc='+$('stock_select').getSelected().get('value')+'&sd='+$('stock_select').getSelected().get('text');
	url = encodeURI(url);
		var a = new Request({ url: url,
				method: 'get',
				async: false,
				onComplete: function (response) {
				$('items-ajax').set('html','ADD ITEMS');
					$('ac_items').set('html',response);
				}
			}).send();
	loadItemsScreen(id);
	getItems(id);
}

function loadAddItemsScreen(value) {
$('items-ajax').innerHTML = '<img src="images/ajax-loader.gif" width="150" height="24"  />';
	var url = 'index.php?option=com_jumi&view=application&fileid=7&screen=5&id='+value;
				var a = new Request({ url: url,
				method: 'get',
				async: false,
				onComplete: function (response) {
				$('items-ajax').set('html','ADD ITEMS');
					$('ac_items').set('html',response);
				}
			}).send();
			$('qty').focus();

}

function loadItemsScreen(value) {
	var url = 'index.php?option=com_jumi&view=application&fileid=7&screen=4&id='+value;
						var a = new Request({ url: url,
						method: 'get',
						async: false,
						onComplete: function (response) {
						$('ac_items').set('html', response);
						$('ajax-container').set('html','');
						}
					}).send();
}

function getItems(value) {
	$('items-ajax').innerHTML = '<img src="images/ajax-loader.gif" width="150" height="24"  />';
	var url = 'index.php?option=com_jumi&view=application&fileid=6&request=4&id='+value;
				var a = new Request({ url: url,
				method: 'get',
				async: false,
				onComplete: function (response) {
				$('items-ajax').set('html','ATTACHED ITEMS');
					$('ac_items_list').empty();
					var resp = json_parse(response,function(data,text) {
					if (text != '[object Object]') {
						new Element('option',{ 'value':data,'text':text}).inject($('ac_items_list'));;
					}
					});
				}
			}).send();

}

function getCallDetails(value) {
	$('ajax-container').innerHTML = '<img src="images/ajax-loader.gif" width="150" height="24"  />';
				setMessages('Getting Call Details...Please Wait.');
				disableButtons($('generic_new'));
				var url = 'index.php?option=com_jumi&view=application&fileid=6&request=3&id='+value;
				var a = new Request({ url: url,
				method: 'get',
				async: false,
				onComplete: function (response) {
				$('ajax-container').set('html','');
					var resp = json_parse(response,function(data,text) {
									if (data == 'details') { $('call_details').value = text; }
									if (data == 'loggedby') { $('logged_by').value = text; }
									if (data == 'callerid') { $('caller_id').value = text; }
						});
				}
			}).send();
			setMessages('New Acquisitions');
			enableButtons($('generic_new'));
			$('ac_type').focus();
}

function displayNewForm() {
				$('ajax-container').innerHTML = '<img src="images/ajax-loader.gif" width="150" height="24"  />';
				setMessages('Connecting to Auxliary Systems...Please Wait.');
				//$('bh').set('html','Connecting to Auxliary Systems...Please Wait.');
				disableButtons($('generic_form'));
				var url = 'index.php?option=com_jumi&view=application&fileid=7&screen=2';
				var a = new Request({ url: url,
				method: 'get',
				async: false,
				onComplete: function (response) {
				//$('ajax-container').set('html','');
				$('content').set('html', response);
				}
			}).send();
					var url = 'index.php?option=com_jumi&view=application&fileid=7&screen=4&id='+$('id').value;
						var a = new Request({ url: url,
						method: 'get',
						async: false,
						onComplete: function (response) {
						$('ac_items').set('html', response);
						$('ajax-container').set('html','');

						}
					}).send();
			getItems($('id').value);
}

function displayEditForm() {
				var str = checkSelection();
				if (str.length == 0) {
					alert('Please select entry to Cancel.');
				} else {
				$('ajax-container').innerHTML = '<img src="images/ajax-loader.gif" width="150" height="24"  />';
				setMessages('Connecting to Auxliary Systems...Please Wait.');
				//$('bh').set('html','Connecting to Auxliary Systems...Please Wait.');
				disableButtons($('generic_form'));
				var url = 'index.php?option=com_jumi&view=application&fileid=7&screen=3&id='+str;
				var a = new Request({ url: url,
				method: 'get',
				async: false,
				onComplete: function (response) {
					$('content').set('html',response);
				}
			}).send();
			loadItemsScreen(str);
			getItems(str);
		}
}

function checkSelection() {
	var str = '';
	var form = $('generic_form');
		if (form.id.length >= 2) {
			for (i=0;i<form.id.length;++i) {
				if (form.id[i].checked) {
					str = form.id[i].value;
				}
			}
		}
		else {
			str = form.id.value;
		}
	return str;
}

function assignCancelButton () {
	disableButtons($('generic_form'));
					var str = checkSelection();
					if (str.length == 0) {
						alert('Please select entry to Cancel.');
					} else {
						var code = 0;
						var url = '';
						url = 'index.php?option=com_jumi&view=application&fileid=6&request=1&id='+str;
						$('ajax-container').innerHTML = '<img src="images/ajax-loader.gif" width="150" height="24"  />';
							var a = new Request({ url: url,
							method: 'get',
							async: false,
							onComplete: function (response) {
								$('ajax-container').innerHTML = '';
								var resp = json_parse(response,function(data,text) {
									if (data == 'code') { code = text; }
								});
								$('ajax-container').innerHTML = '';
									if (parseInt(code) > 1) {
										alert('Cancellation not allowed at this stage. Contact Administrator.');
									} else {
										if (confirm('Are you sure?')) {
											$('ajax-container').innerHTML = '<img src="images/ajax-loader.gif" width="150" height="24"  />';
											url = 'index.php?option=com_jumi&view=application&fileid=6&request=2&id='+str;
											var a = new Request({ url: url,
											method: 'get',
											async: false,
											onComplete: function (response) {
												$('ajax-container').innerHTML = '';
												alert('Cancelled.');
														//$('content').set('html','');
														url =  'index.php?option=com_jumi&view=application&fileid=7&screen=1';
														var a = new Request({ url: url,
														method: 'get',
														async: true,
														onComplete: function (response) {
															$('content').set('html',response);
															$('button_cancel').addEvent('click',assignCancelButton,false);
														}
														}).send();
											}
											}).send();
										}
									}
							}
							}).send();
					}
					enableButtons($('generic_form'));
}

</script>


<?php

		if (isset($_GET['action'])) {
			if ($_GET['action'] == 3) {
				$sql = sprintf("insert into ict_acquisition_stock (stock_code,stock_description,price)
				values ('%s','%s',%0.2f)",$_POST['scode'],$_POST['sdesc'],$_POST['sprice']);
				$dbo->setQuery($sql);
				$result = $dbo->query();
				echo "<script type='text/javascript'>";
					echo "alert('Record Saved.');";
					echo "window.location.href='index.php?option=com_jumi&view=application&fileid=5&Itemid=116&srch=".$_POST['scode']."'";
				echo "</script>";

			}
			else if ($_GET['action'] == 4) {
				$sql = sprintf("update ict_acquisition_stock set stock_code='%s',stock_description='%s',price=%0.2f
				where id = %d",$_POST['scode'],$_POST['sdesc'],$_POST['price'],$_POST['id']);
				$dbo->setQuery($sql);
				$result = $dbo->query();
				echo "<script type='text/javascript'>";
					echo "alert('Record Saved.');";
					echo "window.location.href='index.php?option=com_jumi&view=application&fileid=5&Itemid=116&srch=".$_POST['scode']."'";
				echo "</script>";

			}
		}




		if (isset($_GET['action'])) {
			if ($_GET['action'] == 1) {
				?>
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
							 <h3 class="t">
							New Stock Record</h3>
					</div>
				</div>
				</div>
				<form name='generic_new' method='post' action='index.php?option=com_jumi&view=application&fileid=5&Itemid=116&action=3' onSubmit='return validateAcquisitionFields(this)'>
				<table border='0' width='100%'>
					<tr>
						<td width='20%'>Stock Code:</td>
						<td width='80%'><input type='text' name='scode' id='scode' size='10' maxlength='15' onKeyUp='this.value=this.value.toUpperCase();'></td>
					</tr>
					<tr>
						<td width='20%'>Stock Description:</td>
						<td width='80%'><input type='text' name='sdesc' id='sdesc' size='80' maxlength='255' onKeyUp='this.value=this.value.toUpperCase();'></td>
					</tr>
					<tr>
						<td width='20%'>Stock Price:</td>
						<td width='80%'><input type='text' name='sprice' id='sprice' size='8' maxlength='255' onKeyUp = "if ( !isDigit ( event ) ) {
						alert ( 'Oops! numbers only please.' ); this.value=''; return false }"></td>
					</tr>
					<tr>
					<td width='20%'>&nbsp;</td>
					<td width='80%'>
						<input type='submit' value='Save' class='button art-button'>&nbsp;
						<input type='button' value='Cancel' class='button art-button' onclick="if (confirm('Are you sure?')) { window.location.href='index.php?option=com_jumi&view=application&fileid=5&Itemid=116'; }">
					</td>
					</tr>
				</table>
				</form>
				<script type='text/javascript'> $('scode').focus(); </script>
				<?php
			}
		 else if ($_GET['action'] == 2) {
			 ?>
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
							 <h3 class="t">
							Edit Stock Record</h3>
					</div>
				</div>
				</div>
				<form name='generic_edit' method='post' action='index.php?option=com_jumi&view=application&fileid=5&Itemid=116&action=4' onSubmit='return validateAcquisitionFields(this)'>
				<?php
						$sql = sprintf("select stock_code,stock_description,price,active from ict_acquisition_stock
										where id = %d",$_GET['id']);
							$dbo->setQuery($sql);
							$result = $dbo->query();
							$row = mysqli_fetch_object($result);
				?>
				<input type='hidden' name='id' id='id' value='<?php echo $_GET['id']; ?>'>
				<table border='0' width='100%'>
					<tr>
						<td width='20%'>Stock Code:</td>
						<td width='80%'><input type='text' name='scode' id='scode' size='10' value='<?php echo $row->stock_code; ?>' maxlength='15' onKeyUp='this.value=this.value.toUpperCase();'></td>
					</tr>
					<tr>
						<td width='20%'>Stock Description:</td>
						<td width='80%'><input type='text' name='sdesc' id='sdesc' size='80' value='<?php echo $row->stock_description; ?>' maxlength='255' onKeyUp='this.value=this.value.toUpperCase();'></td>
					</tr>
					<tr>
						<td width='20%'>Stock Price:</td>
						<td width='80%'><input type='text' name='sprice' id='sprice' size='8' value='<?php echo $row->price; ?>' maxlength='255' onKeyUp = "if ( !isDigit ( event ) ) {
						alert ( 'Oops! numbers only please.' ); this.value=''; return false }"></td>
					</tr>
					<tr>
						<td width='20%'>Status:</td>
						<td width='80%'><input type='radio' name='sactive' id='sactive' value='1' <?php if ($row->active ==1) { echo 'checked'; } ?>>Active
						<input type='radio' name='sactive' id='sactive' value='0' <?php if ($row->active ==0) { echo 'checked'; } ?>>Not Active
						</td>
					</tr>
					<tr>
					<td width='20%'>&nbsp;</td>
					<td width='80%'>
						<input type='submit' value='Save' class='button art-button'>&nbsp;
						<input type='button' value='Cancel' class='button art-button' onclick="if (confirm('Are you sure?')) { window.location.href='index.php?option=com_jumi&view=application&fileid=5&Itemid=116'; }">
					</td>
					</tr>
				</table>
				</form>
				<script type='text/javascript'> $('scode').focus(); </script>
				<?php
		}
		}
		else {
?>

<script type='text/javascript'>
window.addEvent('domready',function() {
		loadMainScreen();
});


</script>



<?php
		}
}
	?>
<div id = 'ajax-container' style='position: fixed; top: 0; left: 0; width: 150px; height: 25px'>
</div>
<div id='content' style='width: 100%; height: auto'>
</div>
