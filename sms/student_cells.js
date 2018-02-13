var tobj; 
var html;
window.addEvent('domready',function() {
	$('criteria').addEvent('submit',function(e) {
		new Event(e).stop();

		var url1 = 'index.php?option=com_jumi&fileid=8&view=application';
		var url2 = 'index.php?option=com_jumi&fileid=10&view=application';
                var url3 = 'index.php?option=com_jumi&fileid=166&view=application';
		var data = '&bc='+$('subj-bc').value+'&subj='+$('selected-subj').value+'&qual='+$('subj-qual').value+
		'&ot='+$('subj-offer').value+'&cyr='+$('cyr').value+'&tmpl=component';
        
                var celldata = '&subj='+$('selected-subj').value+'&qual='+$('subj-qual').value+
		'&ot='+$('subj-offer').value+'&cyr='+$('cyr').value+'&tmpl=component';
		
		if ($('action').value == 'marksheet') {
		$('show-sheet').set('href',url1+data);
		$('show-sheet').click();
		} else if ($('action').value == 'classlist') {	
		$('show-classlist').set('href',url2+data);
		$('show-classlist').click();
		} else if ($('action').value == 'celllist') {	
		$('show-celllist').set('href',url3+celldata);
		$('show-celllist').click();
		}
	});
        
        /*$('show').addEvent('click', function(){
           alert('dsf');
           var url7 = 'index.php?option=com_jumi&fileid=166&function=table';
           var celldata = '&subj='+$('selected-subj').value+'&qual='+$('subj-qual').value+'&ot='+$('subj-offer').value+'&cyr='+$('cyr').value+'&tmpl=component';
           var x = new Request({
                       url: url7+celldata,
                       method: 'get',
                       onComplete: function(response){
                           var row = json_parse(response,function(data,text) {
					
                                if(parseInt(response)==0)
                                {
                                        $('view-data').set('html','No data to display.');
                                }

                                if (typeof text == 'string') {
                                                var rec = text.split(';');

                                                html = html + '<tr>';
                                                html = html + '<td style="overflow: hidden; width: 15%; height: 11px; border: 1px solid #FFFFFF; background-color: #FFFFFF; color: #000000">'+rec[0]+'</td>';
                                                html = html + '<td style="overflow: hidden; width: 15%; height: 11px; border: 1px solid #FFFFFF; background-color: #FFFFFF; color: #000000">'+rec[1]+'</td>';
                                                html = html + '<td style="overflow: hidden; width: 15%; font-size: 10px; height: 11px; border: 1px solid #FFFFFF; background-color: #FFFFFF; color: #000000">'+rec[2]+'</td>';                                        
                                                html = html + '</tr>';
                                }
                        }
                        html = html + '</table>';
                        $('view-data').set('html',html);
               }
           }).send();
       });  */
       
       $('show').addEvent('click',function() {
            //alert('test');
            var url7 = 'index.php?option=com_jumi&fileid=168&function=table';
            var celldata = '&subj='+$('selected-subj').value+'&qual='+$('subj-qual').value+'&ot='+$('subj-offer').value+'&cyr='+$('cyr').value+'&tmpl=component';
            var html = '<table border="0" width="100%" style="table-layout: fixed">';
            var x = new Request({
		url: url7 + celldata,
		method: 'get',
		onComplete: function(response) {
				//alert('test')
                      /*  var row = json_parse(response,function(data,text) {
					
                                if(parseInt(response)==0)
                                {
                                        $('view-data').set('html','No data to display.');
                                }

                                if (typeof text == 'string') {
                                                var rec = text.split(';');

                                                html = html + '<tr>';
                                                html = html + '<td style="overflow: hidden; width: 15%; height: 11px; border: 1px solid #FFFFFF; background-color: #FFFFFF; color: #000000">'+rec[0]+'</td>';
                                                html = html + '<td style="overflow: hidden; width: 15%; height: 11px; border: 1px solid #FFFFFF; background-color: #FFFFFF; color: #000000">'+rec[1]+'</td>';
                                                html = html + '<td style="overflow: hidden; width: 15%; font-size: 10px; height: 11px; border: 1px solid #FFFFFF; background-color: #FFFFFF; color: #000000">'+rec[2]+'</td>';                                        
                                                html = html + '</tr>';
                                }
                        });      
                        html = html + '</table>';
				
				$('view-data').set('html',html);*/
		}
	}).send();
	});

	$('show').addEvent('click',function() {

            var url7 = 'index.php?option=com_jumi&fileid=168&function=table';
            var celldata = '&subj='+$('selected-subj').value+'&qual='+$('subj-qual').value+'&ot='+$('subj-offer').value+'&cyr='+$('cyr').value+'&tmpl=component';
        
			
		$('show-celllist').set('href',url7+celldata);
		$('show-celllist').click();
		
	});
  
       /*$('show').addEvent('click',function() {
            //alert('test');
            var url7 = 'index.php?option=com_jumi&fileid=168&function=table';
            var celldata = '&subj='+$('selected-subj').value+'&qual='+$('subj-qual').value+'&ot='+$('subj-offer').value+'&cyr='+$('cyr').value+'&tmpl=component';
            var html = '<table border="0" width="100%" style="table-layout: fixed">';
            var x = new Request({
		url: url7 + celldata,
		method: 'get',
		onComplete: function(response) {
				//alert('test')
                       var row = json_parse(response,function(data,text) {
					
                                if(parseInt(response)==0)
                                {
                                        $('view-data').set('html','No data to display.');
                                }

                                if (typeof text == 'string') {
                                                var rec = text.split(';');

                                                html = html + '<tr>';
                                                html = html + '<td style="overflow: hidden; width: 15%; height: 11px; border: 1px solid #FFFFFF; background-color: #FFFFFF; color: #000000">'+rec[0]+'</td>';
                                                html = html + '<td style="overflow: hidden; width: 15%; height: 11px; border: 1px solid #FFFFFF; background-color: #FFFFFF; color: #000000">'+rec[1]+'</td>';
                                                html = html + '<td style="overflow: hidden; width: 15%; font-size: 10px; height: 11px; border: 1px solid #FFFFFF; background-color: #FFFFFF; color: #000000">'+rec[2]+'</td>';                                        
                                                html = html + '</tr>';
                                }
                        });      
                        html = html + '</table>';
				
				$('view-data').set('html',html);
		}
	}).send();
	});*/
        
    
        
	$('subj-search').addEvent('keyup',function() {
            //testing();
		$('div-subject').setStyle('display','none');
		$('loader-subject').setStyle('display','none');
		$('level-3').setStyle('display','none');
                $('level-4').setStyle('display','none');
		//$('level-5').setStyle('display','none');
		$('subj-search').value = $('subj-search').value.toUpperCase();
		clearTimeout(tobj);
		if ($('subj-search').value.length > 0) {
			tobj = setTimeout('search_subjects()',1000);
		}
	});
	
	$('subj-search').addEvent('focus',function() {
		$('subj-search').value = '';
	});
	
	$('list-subj').addEvent('click',function() {
		var subject = $('list-subj').getSelected().get('value');
		$('subj-search').value = $('list-subj').getSelected().get('text');
		$('selected-subj').value = subject;
		$('div-subject').setStyle('display','none');
		$$('selected-subj').value = $('list-subj').getSelected().get('text');	
		//Get qualification
		$('level-3').setStyle('display','block');
		$('subj-qual').empty();
		$('loader-subject-all').setStyle('display','block');
		
			var x = new Request({
					url: 'index.php?option=com_jumi&fileid=166&function=qualification&subj='+subject+'&cyr='+$('cyr').value+'&dt='+new Date().getTime(),
					method: 'get',
					onComplete: function(response) { 
							$('subj-qual').empty();
							new Element('option',{ 'value':'ALL','text':'ALL'}).inject($('subj-qual'));
							var row = json_parse(response,function(data,text) {
								if (typeof text == 'string') {
									new Element('option',{ 'value':text,'text':text}).inject($('subj-qual'));
								}
							});
					$('loader-subject-all').setStyle('display','none');
					}
			}).send();
			getOffering(subject);
	});	
	
	
});


function getOffering(subject) {
	
    $('level-4').setStyle('display','block');
    //$('level-5').setStyle('display','block');
    $('subj-offer').empty();
    $('loader-subject-all').setStyle('display','block');
    var x = new Request({
    url: 'index.php?option=com_jumi&fileid=166&function=offering&subj='+subject+'&cyr='+$('cyr').value+'&dt='+new Date().getTime(),
    method: 'get',
    onComplete: function(response) {
    $('subj-offer').empty();
                    var row = json_parse(response,function(data,text) {
                            if (typeof text == 'string') {
                                    new Element('option',{ 'value':text,'text':text}).inject($('subj-offer'));
                            }
                    });
    }
    }).send();	
    $('level-5').setStyle('display','block');
    $('show').setStyle('display','block');
}

function disableExport(){
	var sm = $('system-mode').get('value');
	var sl = $('system-log').get('value');
	if (parseInt(sm) == 1) {
		if (parseInt(sl) == 1){
			$('go').set('value','Export Function Disabled');
			$('go').set('disabled',true);
		}
	}
}

function testing()
{
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=166&function=test',
		method: 'get',
		onComplete: function(response) {
		
		}
	}).send();
}


function search_subjects() {
	$('loader-subject').setStyle('display','block');
	var srch = $('subj-search').value;
	var cyr = $('cyr').getSelected().get('value');
	var its = new Request({
		url: 'index.php?option=com_jumi&fileid=166&function=subjSearch&srch='+srch+'&cyr='+cyr+'&dt='+new Date().getTime(),
		method: 'get',
		timeout: 35000,
		onTimeout: function() {
			$('loader-subject').setStyle('display','none');
			$('subj-search').set('value','Error connecting to ITS');
		its.cancel();
		},
		onComplete: function(response) {
                   
			$('list-subj').empty();
			if (parseInt(response) == 99999) {
				$('subj-search').set('value','Error connecting to ITS');
				$('div-subject').setStyle('display','block');
				$('loader-subject').setStyle('display','none');
				return false;
			}
					var row = json_parse(response,function(data,text) {
						if (typeof text == 'string') {
							new Element('option',{ 'value':data,'text':'['+data+'] '+text}).inject($('list-subj'));
							}
						});
					
		$('div-subject').setStyle('display','block');
		$('loader-subject').setStyle('display','none');
		}
	}).send();
}
