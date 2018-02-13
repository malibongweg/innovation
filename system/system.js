window.addEvent('domready',function() {
	
	var colorInput = document.id('txt-color');
		var cpicker = new ColorPicker(colorInput,{cellWidth: 8, cellHeight: 12})

	
	loadMessages();

	checkSelect();
	
	
	$('new-rec').addEvent('click',function() {
		$('rec-action').set('value','new');
		$('browse-frame').setStyle('display','none');
		$('form-frame').setStyle('display','block');
		$('txt-msg').set('value','');
		$('txt-color').set('value','');
		$('txt-color').setStyle('background-color','#FFFFFF');
		$('txt-active').set('checked',false);
		$('rec-action').set('value','new');
		$('txt-msg').focus();
	});
	
	$('save-button').addEvent('click',function() {
			var x = new Request({
				url: 'index.php?option=com_jumi&fileid=14&action=save_msg&dt='+new Date().getTime(),
				method: 'get',
				data: $('frm-msg'),
				onComplete: function (){
					$('form-frame').setStyle('display','none');
					loadMessages();
					$('browse-frame').setStyle('display','block');
				}
			}).send();
	});
	
	$('cancel-button').addEvent('click',function() {
		$('form-frame').setStyle('display','none');
		loadMessages();
		$('browse-frame').setStyle('display','block');
	});
	
	$('edit-rec').addEvent('click',function() {
		var s = 0;
		s = $$('input[type=radio]:checked').get('value');		
		if (s == 0) { alert('Select entry to edit.'); }
		else 	{
				$('rec-action').set('value','edit');
				$('browse-frame').setStyle('display','none');
				$('form-frame').setStyle('display','block');
				var x = new Request({
					url: 'index.php?option=com_jumi&fileid=14&action=get_message&id='+s+'&dt='+new Date().getTime(),
					method: 'get',
					onComplete: function(response) {
							var row = json_parse(response,function(data,text) {
								if (typeof text == 'string') {
									switch(data) {
										case 'id': $('rec-id').set('value',text); break;
										case 'msg': $('txt-msg').set('value',text); break;
										case 'color': $('txt-color').set('value',text); break;
										case 'active': if (parseInt(text) == 0) $('txt-active').set('checked',false); else $('txt-active').set('checked',true); break;
									}
								}
							});
					}
				}).send();
				
				$('txt-color').setStyle('background-color',$('txt-color').get('value'));
				$('txt-msg').focus();
			}
	});

});

function checkSelect() {
	$$('input[type=checkbox]').each(function(e) {
		if (e.get('id') != 'txt-active') {
			e.addEvent('click',function() {
				if (this.checked == true) { this.checked = false; }
				else { this.checked = true; }
			});
		}
	});
}

function loadMessages() {
	$('loader').set('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=14&action=scroll_msg&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response) {
			$('loader').set('display','none');
			$('msg-data').set('html',response);
			checkSelect();
		}
	}).send();
}