 var jid; 
// var sid;
window.addEvent('domready',function() {
  
  new DatePicker($('service_date'), {
	pickerClass: 'datepicker',
	timePicker: false,
	format: '%Y-%m-%d',
    positionOffset: {x: 5, y: 0},
	toggleElements: '.date_toggler1',
    useFadeInOut: !Browser.ie
});
       listScheduleLog();
       $('schedule-form').addEvent('submit',function(e) {
		e.stop();
		var action = $('service-action').get('value');
                $('md').setStyle('display','none');
                $('bh').setStyle('display','block');
		addService(this,action);
	});
         $('ups-schedule').addEvent('click',function(e) {	
                $('md1').setStyle('display','block');
                $('md').setStyle('display','none');		
                $('update-service').setStyle('display','none');		
	});
        
       // $('emailUPS').addEvent('click',function(e) {	
                //window.location.reload();	
                //emailUps();		
	//});
        
    $('cancel-service').addEvent('click',function() {                  
        window.location.reload();
    });  
    $('update-service').addEvent('click',function() {  
        var date = $('service_date').get('value');
        var company = $('company').getSelected().get('value');
        var product = $('product').getSelected().get('value');
        var contactno = $('contact-no').get('value');
        var status = $('status').getSelected().get('value');
        updateService(jid, date, company, product, contactno, status);
    }); 
    
});
function listScheduleLog() {    
	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var html = '<table width="100%" border="0" style="table-layout: fixed">';
	$('ajax-service').setStyle('display','block');
	var x = new Request ({
		url: 'index.php?option=com_jumi&fileid=229&action=list_service',
			noCache: true,
			method: 'get',
			timeout: 5000,
			onTimeout: function() {
				$('service-form').set('html','Time-out error...');
				$('ajax-service').setStyle('display','none');
				x.cancel();
			},
				onComplete: function(response) {                                    
                                    //alert(response);
						var row = json_parse(response,function(data,text) {
						if (typeof text == 'string') {
							if (parseInt(text) == -1)
							{
								$('ajax-service').setStyle('display','none');
								$('service-form').set('html','No data available.');
								fnd = false;
							}else {
								fnd = true;
								var rec = text.split(';');
								var m = cnt % 2;
								if (m == 1)
								{
									var color = color1;
								} else {
									var color = color2;
								}
								html = html + '<tr>';								
								html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
								html = html + rec[0] + '</td>';
								html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
								html = html + rec[1] + '</td>';
								html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
								html = html + rec[2] + '</td>';
								html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
								html = html + rec[5] + '</td>';								
                                                                html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
								html = html + rec[6] + '</td>';
                                                                html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
								html = html + rec[3] + '</td>';
                                                                html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="job_no" id="job-no" onclick="editService(this.value)" value="';
								html = html + rec[0] + '"</td>';
								html = html + '</tr>';

								++cnt;
							}
					}
				});
				html = html + "</table>";
				if (fnd == true)
				{
					$('service-form').set('html',html);
					$('ajax-service').setStyle('display','none');
					$('header-details').setStyle('display','block');
				}
			}
	}).send();
}
function addService(frm,action) {    
        var x = new Request({
                url: 'index.php?option=com_jumi&fileid=229&action=add_service',
                        method: 'post',
                        noCache: true,
                        data: frm,
                    onComplete: function(response){
                        //alert(response);
                         if (response == 1)
                            {                              
                                    alert('Service schedule data updated successfully.');
                                window.location.reload();                              
                          
                        }
                        else if (response == -1)
                        {
                                alert('Error adding service schedule.');
                                 window.location.reload();
                        }   
                        listScheduleLog();
            }
        }).send();
}
function editService(id) {   
        $('md').setStyle('display','none');
        $('schedule-service').setStyle('display','none');
	$('md1').setStyle('display','block');
	var x = new Request({
            url: 'index.php?option=com_jumi&fileid=229&action=edit_service&id='+encodeURI(id),
            method: 'get',
            noCache: true,
            onComplete: function(response){
                //alert(response);             
                    var r = response.split(';');              
                    $('service_date').set('value',r[1]);
                    $('company').getElements('option').each(function(el){
					if (parseInt(el.value) == parseInt(r[5]))
					{
						el.selected = true;
					}
				});                               
                    $('product').getElements('option').each(function(el){
					if (parseInt(el.value) == parseInt(r[6]))
					{
						el.selected = true;
					}
				});
                    $('contact-no').set('value',r[2]);
                    
                     $('status').getElements('option').each(function(el){
					if (el.value == r[3])
					{
						el.selected = true;
					}
				});
                   jid = r[0];                   
            }
	}).send();
}

function updateService(jid, date, company, product, contactno, status)
{
	var username = $('uname').get('value');
	
	var x = new Request({
            url: 'index.php?option=com_jumi&fileid=229&action=update_service&jid='+jid+'&date='+date+'&company='+company+'&product='+product+'&contactno='+contactno+'&status='+status+'&username='+username,
            method: 'get',
            noCache: true,
            onComplete: function(response){
                //alert(response);             
                     if (response == 1)
                            {                              
                                alert('Service updated successfully.');
                                window.location.reload();
                        }
                        else if (response == -1)
                        {
                                alert('Error updating service.');
                                 window.location.reload();
                             }
                             listScheduleLog();
            }
	}).send();
}
function emailUps(){
    //alert("Email comes here");
    
    var x = new Request({
            url: 'index.php?option=com_jumi&fileid=229&action=mailsUser',
            method: 'get',
            noCache: true,
            onComplete: function(response){
                //alert(response);  
                return false;
                     if (response == 1)
                            {                              
                                alert('Service updated successfully.');
                             //   window.location.reload();
                        }
                        else if (response == -1)
                        {
                                alert('Error updating service.');
                              //   window.location.reload();
                             }
                          //   listScheduleLog();
            }
	}).send();
}