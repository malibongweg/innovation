window.addEvent('domready',function() {

	$('srch-card').addEvent('click',function(){
		$('srch-card').set('value','');
	});

	$('srch-button').addEvent('click',function(){
		var uid = $('srch-card').get('value');
		search_user(uid);
	}).send();

});

function search_user(uid){
	$('show-busy').setStyle('display','block');
		var x = new Request({
        url : 'index.php?option=com_jumi&fileid=126&action=check_reset_card&uid='+uid,
        method : 'get',
        noCache : true,
        async : false,
        onComplete : function(response) {
			$('show-busy').setStyle('display','none');
			var data = response.split(';');
			if (parseInt(data[0]) == 0){
				alert('Could not locate user.');
				$('srch-card').set('value','');
				$('srch-card').focus();
			} else if (parseInt(data[2]) > 0){
				if (confirm(data[3]+'\n'+'User card re-print blocked.'+'\n'+'Cards# printed: '+data[1]+'\n'+'Unblock?')) {
					var x = new Request({
						url : 'index.php?option=com_jumi&fileid=126&action=reset_card&uid='+uid,
						method : 'get',
						noCache : true,
						async : false,
						onComplete : function(response) {
							alert('Card '+uid+' re-print unblocked.');
							$('srch-card').set('value','');
							$('srch-card').focus();	
						}
					}).send();
				}			
			} else {
				alert('Card not blocked....');
				$('srch-card').set('value','');
				$('srch-card').focus();
			}
		}
	}).send();

}