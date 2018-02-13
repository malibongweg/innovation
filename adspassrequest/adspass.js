var tref;

window.addEvent('domready',function() {
	
    $('srch').value = '';
    $('srch').focus();

$('finish-but').addEvent('click', function() {
        //$('main-dv').setStyle('display', 'none');
        window.location.reload();
    });
    
    /*$('srch').addEvent('keydown',function() {
            $('list-users').setStyle('display','none');
            $('user-details').setStyle('display','none');
            clearTimeout(tref);
            tref = setTimeout('srchUser()',500);
    });*/

    $('getpin').addEvent('click',function(){
    var stdno = $('srch').get('value');
    var op = $('uname').get('value');
    var cel = $('cell').get('value');
    //alert('here');
    //alert(stdno + " " + op);
    showPass(stdno,op,cel);
    });
    
    $('getUser').addEvent('click',function() {
            //var uid = parseInt($('userList').getSelected().get('value')); 
            //var uid = parseInt($('srch').get('value')); 
            var uid = $('srch').get('value'); 
           // alert(uid.length);
            if (uid.length < 9) {
                    alert('Please enter a valid student number.');
                     $('user-details').setStyle('display','none');
                     $('security-questions').setStyle('display','none');
                    $('srch').value = '';
                    $('srch').focus();		
            }
            //if (typeof uid == 'number' && uid > 0) {
            else{
                    //$('list-users').setStyle('display','none');
                    //$('srch').value = ''; 

                    displayInfo(uid);
            } 
    });
});

function srchUser() {
    //$('ajax-loader').setStyle('display', 'block'); 
    var url = 'index.php?option=com_jumi&fileid=197&user='+$('srch').value+'&func=srch&dt='+new Date().getTime();
    var x = new Request({
        url: url,
        method: 'get',
        onComplete: function(response) {
                $('userList').empty();
                var row = json_parse(response,function(data,text) {
                if (typeof text == 'string') {
                        var r = text.split(';');
                        new Element('option',{ 'value':r[0],'text':r[1]+' ['+r[2]+']'}).inject($('userList'));
                }
            });
            $('ajax-loader').setStyle('display','none');
            $('list-users').setStyle('display','block');
        }
  }).send();
}


function displayInfo(uid) {
        var x = new Request({
        url: 'index.php?option=com_jumi&fileid=197&uid='+uid+'&func=info&dt='+new Date().getTime(),
        method: 'get',
        onComplete: function(response) { 
            //alert(response);            
            var row = json_parse(response,function(data,text) {
            if (typeof text == 'string') {
                 var r = text.split(';');
                 $('uid').value = r['0'];
                 $('name').value = r['1'] + ' ' + r['2'];
                 $('idnum').value = r['3'];
                 $('cell').value = r['8'];
                 $('email').value = r['0'] + '@cput.ac.za';
                 $('school').value = r['4'];
                 $('barcode').value = r['6'];

            }
        });
        $('user-details').setStyle('display','block');
        $('security-questions').setStyle('display','block');
        }
    }).send();
}

function showPass(stdno,op,cel){
    if (stdno.length < 9) {
            alert('Enter valid student number');
            $('its-lookup').set('value','');
            $('its-lookup').focus();
            return false;
    }

    var x = new Request({
            url: 'index.php?option=com_jumi&fileid=197&stdno='+stdno+'&op='+op+'&cell='+cel+'&func=getpass',
            method: 'get',
            noCache: true,
            onComplete: function(response) {
            //$('search-pin').setStyle('display','none');
                    if (response.length == 0) {
                            alert('Could not locate pin.');
                    } else {
                    alert('Password smsed to student and sent to email address...');
                    //alert('Your Temporal ADS Password is: '+response);
                     $('user-details').setStyle('display','none');
                     $('security-questions').setStyle('display','none');
                      $('srch').value = '';
                    $('srch').focus();	
                    //$('its-lookup').set('value','');
                    //$('its-lookup').focus();
                    }
            }
    }).send();
}

