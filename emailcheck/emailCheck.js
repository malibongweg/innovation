window.addEvent('domready',function() {   
    var he = $('myCheck').checked = false;
     $('staff-check').setStyle('display','none');
    listStaffQuery();
    listAdStaffInfo();
     $('srch-details-form').addEvent('submit',function(e){
		e.stop();
		console.log(this.toQueryString());
		filterDisplays(this.toQueryString());
                filterDisplays2(this.toQueryString());
	});
    $('staff-cancel').addEvent('click',function() {       
        window.location.reload();           
    });   
    $('myCheck').addEvent('click',function() {
		displayLog();
	});
          
});

///////////////////////////////////////////////List Staff Query////////////////////////////////////////////////////////////////////////////////
function listStaffQuery() {
    var card;
    $('staff-query').setStyle('display','none');
    	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var html = '<table width="100%" border="0" style="table-layout: fixed">';
	$('ajax-staff').setStyle('display','block');
	var x = new Request ({
		url: 'index.php?option=com_jumi&fileid=225&action=display_staffQuery',
			noCache: true,
			method: 'get',
			timeout: 5000,
			onTimeout: function() {
				$('staff-query').set('html','Time-out error...');
				$('ajax-staff').setStyle('display','none');
				x.cancel();
			},
                onComplete: function(response) {
		var row = json_parse(response,function(data,text) {
		if (typeof text == 'string') {
		if (parseInt(text) == -1)
		{
		$('staff-query').setStyle('display','none');
		$('ajax-staff').set('html','No data available.');
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
                var fullname = rec[1] + ' ' + rec[2];
		html = html + '<tr>';							
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[0] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + fullname + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[3]+ '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[4] + '</td>';
		html = html + '</tr>';
		++cnt;
			}
			}
		});
		html = html + "</table>";
		if (fnd == true)
		{
		$('ajax-staff').set('html',html);
		$('staff-query').setStyle('display','none');
		$('header-details').setStyle('display','block');
		}
	}
	}).send();
 }
 ////////////////////////////////////////////////////////////////////List AD Staff Info/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 function listAdStaffInfo() {
    var card;
    $('staff-adinfo').setStyle('display','none');
    	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var html = '<table width="100%" border="0" style="table-layout: fixed">';
	$('ajax-adstaff').setStyle('display','block');
	var x = new Request ({
		url: 'index.php?option=com_jumi&fileid=225&action=display_staffAdInfo',
			noCache: true,
			method: 'get',
			timeout: 5000,
			onTimeout: function() {
				$('staff-adinfo').set('html','Time-out error...');
				$('ajax-adstaff').setStyle('display','none');
				x.cancel();
			},
                onComplete: function(response) {
		var row = json_parse(response,function(data,text) {
		if (typeof text == 'string') {
		if (parseInt(text) == -1)
		{
		$('ajax-adstaff').setStyle('display','none');
		$('staff-adinfo').set('html','No data available.');
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
                var email = rec[5] + '@CPUT.AC.ZA'; 
                var fullname = rec[1] + ' ' + rec[2];
		html = html + '<tr>';							
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[0] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + fullname + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[3]+ '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[4]+ '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + email + '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="videntity" id="staff-id" onclick="displayRec(this.value)" value="';
		html = html + rec[0] + '"</td>';
		html = html + '</tr>';
		++cnt;
			}
			}
		});
		html = html + "</table>";
		if (fnd == true)
		{
		$('ajax-adstaff').set('html',html);
		$('staff-adinfo').setStyle('display','none');
		$('header-details').setStyle('display','block');
		}
	}
	}).send();
 }
////////////////////////////////////////////////////////Filter Staff Query///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function filterDisplays(data) {
    var card;
	var q = data.split('&');
	var qq = q[0].split('=');
	var qqq = q[1].split('=');
	var scond = qqq[1];
	var id = qq[1];
	$('ajax-staff').setStyle('display','block');	
        var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var html = '<table width="100%" border="0" style="table-layout: fixed">';
	$('ajax-staff').setStyle('display','block');
        
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=225&action=list_staffQuery&id='+id+'&scond='+scond,
		method: 'get',
		timeout: 10000,
		onTimeout: function() {
			x.cancel();
			$('ajax-staff').setStyle('display','none');
			clearTimeout(t);
			alert('Time-out error from database. Please try again.');
		},
		onComplete: function(response) {
                    //alert(response);
		var row = json_parse(response,function(data,text) {
		if (typeof text == 'string') {
		if (parseInt(text) == -1)
		{
		$('ajax-staff').setStyle('display','none');
		$('staff-query').set('html','No data available.');
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
                var fullname = rec[1] + ' ' + rec[2];        
		html = html + '<tr>';							
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[0] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + fullname + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[3]+ '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[4] + '</td>';
		html = html + '</tr>';
		++cnt;
			}
			}
		});
		html = html + "</table>";
                if (fnd == true)
		{
		$('ajax-staff').set('html',html);
		$('staff-query').setStyle('display','none');
		$('header-details').setStyle('display','block');
		}
	}
	}).send();
}
////////////////////////////////////////////////////////Filter Staff AD Info///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function filterDisplays2(data) {
    var card;
	var q = data.split('&');
	var qq = q[0].split('=');
	var qqq = q[1].split('=');
	var scond = qqq[1];
	var id = qq[1];
	$('ajax-adstaff').setStyle('display','none');	
        var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var html = '<table width="100%" border="0" style="table-layout: fixed">';
	$('ajax-adstaff').setStyle('display','block');
        
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=225&action=list_staffAD&id='+id+'&scond='+scond,
		method: 'get',
		timeout: 10000,
		onTimeout: function() {
			x.cancel();
			$('ajax-staff').setStyle('display','none');
			clearTimeout(t);
			alert('Time-out error from database. Please try again.');
		},
		onComplete: function(response) {
                var row = json_parse(response,function(data,text) {
		if (typeof text == 'string') {
		if (parseInt(text) == -1)
		{
		$('ajax-adstaff').setStyle('display','none');
		$('staff-adinfo').set('html','No data available.');
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
                var email = rec[5] + '@CPUT.AC.ZA'; 
                var fullname = rec[1] + ' ' + rec[2]; 
		html = html + '<tr>';							
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[0] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + fullname + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[3]+ '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[4]+ '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + email + '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="videntity" id="staff-id" onclick="displayRec(this.value)" value="';
		html = html + rec[0] + '"</td>';
		html = html + '</tr>';
		++cnt;
			}
			}
		});
		html = html + "</table>";
		if (fnd == true)
		{
		$('ajax-adstaff').set('html',html);
		$('staff-adinfo').setStyle('display','none');
		$('header-details').setStyle('display','block');
		}
	}
	}).send();
}
///////////////////////////////////////Populated form//////////////////////////////////////////////////////////////////////////////////
function displayRec(id) {
    //var card;
    $('cards').setStyle('display','none');
    $('md').setStyle('display','none');
    $('staff-adinfo').setStyle('display','none');
    $('staff-ad').setStyle('display','none');
    $('staff-check').setStyle('display','block');
    
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=225&action=displayRec&id='+encodeURI(id),
			method: 'get',
			noCache: true,
			onComplete: function(response){
//echo $row->STAFFNO.';'. $row->FIRSTNAME . ';' . $row->SURNAME . ';' . $row->PEXTENTION  . ';' . $row->USERNAME . ';' . $row->DEPARTMENT . ';' . $row->UNIFLOW_COSTCENTER . ';' . $row->PCAMPUS;           
				var r = response.split(';');
                                var fullname = r[1] + ' ' + r[2];
                                var email = r[4] + '@CPUT.AC.ZA' 
				$('staffNo').set('value',r[0]);
				$('fName').set('value',fullname);
                                $('sExt').set('value',r[3]);
				$('sEmail').set('value',email);
				$('sDept').set('value',r[5]);
				$('sCode').set('value',r[6]);
                                $('sCampus').set('value',r[7]);
                        }
	}).send();
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function displayLog() {
    var he = $('myCheck').checked;
   // alert (he);
    //return false;
   // var hb = $('myCheck').checked = false;
     var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var html = '<table width="100%" border="0" style="table-layout: fixed">';
	$('ajax-staff').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=225&action=display_activeStaff&he='+he,
                        noCache: true,
			method: 'get',
			timeout: 5000,
			onTimeout: function() {
				$('staff-query').set('html','Time-out error...');
				$('ajax-staff').setStyle('display','none');
				x.cancel();
			},
                onComplete: function(response) {
		var row = json_parse(response,function(data,text) {
		if (typeof text == 'string') {
		if (parseInt(text) == -1)
		{
		$('staff-query').setStyle('display','none');
		$('ajax-staff').set('html','No data available.');
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
                var fullname = rec[1] + ' ' + rec[2];
		html = html + '<tr>';							
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[0] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 20%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + fullname + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[3]+ '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[4] + '</td>';
		html = html + '</tr>';
		++cnt;
			}
			}
		});
		html = html + "</table>";
		if (fnd == true)
		{
		$('ajax-staff').set('html',html);
		$('staff-query').setStyle('display','none');
		$('header-details').setStyle('display','block');
		}
	}
	}).send();
			
}



