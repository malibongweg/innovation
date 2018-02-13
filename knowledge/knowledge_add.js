var t;


window.addEvent('domready',function(){

	$('srch').focus();
	$('srch').addEvent('focus',function(){
		$('ajax-x').setStyle('display','none');
	});

	$('display-entries').addEvent('click',function(){
		listEntries();
	});

	$('save-button').addEvent('click',function(){
		saveRecord();
	});

	$('keywords-button').addEvent('click',function(){
		extractKeywords();
	});

	$('possible-answer').addEvent('click',function(){
		displayQuestion();
	});

	$('answer').addEvent('dblclick',function(){
		setNewAns();
	});

	$('new-button').addEvent('click',function(){
		newQuestion();
	});

	$('possible-answer').addEvent('dblclick',function(){
		delAnswer();
	});

	$('back-button').addEvent('click',function(){
		window.location = 'index.php?option=com_jumi&view=application&fileid=29';
	});

	var h = window.getSize().y - 380;
	$('page-header').setStyles({'height':parseInt(h) +'px'});
	$('list-records').setStyles({'height':parseInt(h-100)+'px'});

	listEntries();

});


function listEntries(){
	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var err = false;
	$('list-records').set('html','');
	var stype = $$('input[name=stype]:checked').get('value');
	var html = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="table-layout: fixed">';
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=28&action=search_knowledge&keywords='+encodeURI($('srch').get('value'))+'&stype='+stype+'&dt='+new Date().getTime(),
		method: 'get',
		timeout: 5000,
		onTimeout: function(){
			$('list-records').set('html','Timeout Error.');
			x.cancel();
		},
		onComplete: function(response){
			var row = json_parse(response,function(data,text) {
						if (typeof text == 'string') {
							if (parseInt(text) == -1)
							{
								$('list-records').set('html','No records to display.');
								err = true;
							} else {
								var sections = text.split(';');
								var m = cnt % 2;
							if (m == 0)
							{
								var color = color1;
							} else {
								var color = color2;
							}
								html = html + '<tr>';
								html = html + '<td style="overflow: hidden; width: 5%; height: 11px; border: 1px solid #ffffff; padding: 1px; background-color: '+color+'; color: #000000">';
								html = html + '<input type="radio" name="rec_id" id="rec-id" value="'+data+'" onclick="editRecord();" />';
								html = html + '</td>';
								html = html + '<td style="overflow: hidden; width: 60%; height: 11px; border: 1px solid #ffffff; padding: 1px; background-color: '+color+'; color: #000000">';
								html = html + sections[0];
								html = html + '</td>';
								html = html + '<td style="overflow: hidden; width: 15%; height: 11px; border: 1px solid  #ffffff; padding: 1px;background-color: '+color+'; color: #000000">';
								html = html + sections[1];
								html = html + '</td>';
								html = html + '<td style="overflow: hidden; width: 20%; height: 11px; border: 1px solid #ffffff; padding: 1px; background-color: '+color+'; color: #000000">';
								html = html + sections[2];
								html = html + '</td>';
								html = html + '</tr>';
								++cnt;
							}
						}
			});
			html = html + '</table>';
			if (err == false)
			{
				$('list-records').set('html',html);
			}
		}
	}).send();
}

function editRecord(){
	getQuestion();
	var cnt = 0;
	var dta = '';
	$('page-header').setStyle('display','none');
	$('entry-form').setStyle('display','block');
}

function setNewAns(){
	$('qid').set('value','999999');
	$('answer').set('value','Enter your answer here...');
	$('ans-state').set('html','<b>NEW ANSWER</b>');
}

function getQuestion(){
	var q = $$('input[name=rec_id]:checked').get('value');
	getPossibles(q);
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=28&action=get_q&id='+q+'&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response){
				var row = json_parse(response,function(data,text) {
					if (typeof text == 'string') {
						if (parseInt(text) == -1)
						{
							$('question').set('html','No entries found...'); return false;
						} else {
							var rec = text.split(';');
							$('record-id').set('value',rec[0]);
							$('question').set('html',rec[1]);
							$('db-keywords').set('value',rec[2]);
							$('answer').set('value','');
						}
					}
			});
		}
	}).send();
}

function getPossibles(id){
		$('possible-answer').empty();
		var x = new Request({
		url: 'index.php?option=com_jumi&fileid=28&action=get_possibles&id='+id+'&dt='+new Date().getTime(),
		method: 'get',
		onComplete: function(response){
				var row = json_parse(response,function(data,text) {
					if (typeof text == 'string') {
						if (parseInt(text) == -1)
						{
							new Element('option',{ 'value':'-1','text':'No records found.'}).inject($('possible-answer')); return false;
						} else {
							new Element('option',{ 'value':data,'text':text}).inject($('possible-answer')); return false;
						}
					}
			});
		}
	}).send();

}

function delAnswer(){
	var id = $('possible-answer').getSelected().get('value');
	if (id > 0)
	{
		if (confirm('Are you sure?'))
		{
				var x = new Request({
				url: 'index.php?option=com_jumi&fileid=28&action=del_answer&id='+id+'&dt='+new Date().getTime(),
				method: 'get',
				onComplete: function(response){
					$('answer').set('value','');
					getPossibles($('record-id').get('value'));
				}
			}).send();
		}
	}
}

function displayQuestion(){
	var id = $('possible-answer').getSelected().get('value');
	if (id > 0)
	{
				var x = new Request({
				url: 'index.php?option=com_jumi&fileid=28&action=display_q&id='+id+'&dt='+new Date().getTime(),
				method: 'get',
				onComplete: function(response){
						var row = json_parse(response,function(data,text) {
							if (typeof text == 'string') {
								if (parseInt(text) == -1)
								{
									$('answer').set('value','Error retrieving question.'); return false;
								} else {
									$('answer').set('value',text);
									$('qid').set('value',data);
								}
							}
					});
					$('ans-state').set('html','<b>EDIT ANSWER</b>');
				}
			}).send();

	}
}

function newQuestion(){
	var q = prompt('Question:','');
		if (q !=null && q !='')
		{
			var x = new Request({
				url: 'index.php?option=com_jumi&fileid=28&action=save_new&q='+encodeURI(q)+'&dt='+new Date().getTime(),
				method: 'get',
				onComplete: function(response){
						var row = json_parse(response,function(data,text) {
							if (typeof text == 'string') {
								if (parseInt(text) == -1)
								{
									alert('Error updating record.');
								} else {
									alert('Record updated.');
								}
							}
					});
					listEntries();
				}
			}).send();
		}
}

function saveRecord(){
	if ($('db-keywords').get('value').length < 4)
	{
		alert('Ivalid keyword length.');
		return false;
	} else {
		extractKeywords();
			if ($('answer').get('value').length > 5)
			{
				saveAnswers($('qid').get('value'));
				getPossibles($('record-id').get('value'));
			} else {
				alert('Answer to short.');
			}
		var dta = 'id='+$('record-id').get('value')+'&k='+encodeURI($('db-keywords').get('value'));
		var x = new Request({
			url: 'index.php?option=com_jumi&fileid=28&action=update_q&dt='+new Date().getTime(),
			method: 'post',
			data: dta,
			onComplete: function(response){
				$('page-header').setStyle('display','block');
				$('entry-form').setStyle('display','none');
				listEntries();
			}
			
		}).send();
	  
	}
}

function saveAnswers(id){
	var ada = 'qid='+$('qid').get('value')+'&txt='+encodeURI($('answer').get('value'))+'&recid='+$('record-id').get('value');
	var x = new Request({
			url: 'index.php?option=com_jumi&fileid=28&action=update_a&dt='+new Date().getTime(),
			method: 'post',
			data: ada,
			onComplete: function(response){
				if (parseInt(response) == -1)
				{
					alert('Error updating record.')
				} 
				getPossibles($('record-id').get('value'));
			}
			
		}).send();
}

function extractKeywords(){
	var kWords = '';
	var q = $('question').get('html').toLowerCase();
	var qParts = q.split(' ');
	for (i=0;i < qParts.length ;++i )
	{
		if (qParts[i].length > 3)
		{
				kWords = kWords + qParts[i]+ ' ';
		}
	}
	$('db-keywords').set('value',kWords);
}