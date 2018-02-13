var t;

window.addEvent('domready',function(){

$('keywords').addEvent('keyup',function(){
	clearTimeout(t);
	t = setTimeout('search_database()',1000);
});

$('keywords').addEvent('click',function(e){
	clearTimeout(t);
	$('keywords').set('value','');
});


});

function search_database(){
	$('showBusy').setStyle('display','block');
	var fnd = false;
	var qid = '';
	clearTimeout(t);
	var st =$$('input[name=stypes]:checked').get('value');
	var x = new Request({
	url: 'index.php?option=com_jumi&fileid=28&action=search_knowledge&keywords='+encodeURI($('keywords').get('value'))+'&stype='+st+'&dt='+new Date().getTime(),
	method: 'get',
	onComplete: function(response) {
			var row = json_parse(response,function(data,text) {
						if (typeof text == 'string') {
							if (parseInt(text) == -1)
							{
								$('keywords').set('value','No results found...');
							} else {
								qid = qid + data+';';
								fnd = true;
							}
						}
			});
			$('showBusy').setStyle('display','none');
					if (fnd == true)
					{
							if (confirm('Results found. Display?'))
								{
									$('knowledge-display').set('href','index.php?option=com_jumi&fileid=30&qid='+qid+'&tmpl=component&dt='+new Date().getTime());
									$('knowledge-display').click();
								}
					}
	}
	}).send();
}