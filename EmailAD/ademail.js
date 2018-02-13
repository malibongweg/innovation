var tref;

window.addEvent('domready',function() {
	
    $('srch').value = '';
    $('srch').focus();
    
 var numbers = [8, 9, 46, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 110, 190];
    $$('.numeric').each(function(item) {
        item.addEvent('keydown', function(key) {
            for ( i = 0; i < numbers.length; i++) {
                if (numbers[i] == key.code) {
                    return true;
                }
            }
            return false;
        });
    });

$('finish-but').addEvent('click', function() {
       // $('main-dv').setStyle('display', 'none');
        window.location.reload();
    });
    
   $('show-email').addEvent('click', function(){
       var requestor = document.getElementById("req-staff").value;
        document.getElementById('getpin').disabled = true;
         document.getElementById('finish-but').disabled = true;
       
       showEmail(requestor);
   });
 

    $('getpin').addEvent('click',function(){
    var stdno = document.getElementById("uid").value;
    var requestor = document.getElementById("req-staff").value;
   // var op = $('uname').get('value');
    //var cel = $('cell').get('value');
   
    //alert('here');
   // alert(stdno );
   showPass(stdno,requestor);
    });   
  $('getUser').addEvent('click',function() {
    
            //var uid = parseInt($('userList').getSelected().get('value')); 
            var op = $('srch').get('value');
            $('req-staff').value = '';
            $('req-email').value = '';
            document.getElementById('getpin').disabled = true;
         document.getElementById('finish-but').disabled = true;
           
           
    //var cel = $('cell').get('value');
    //alert('here');
    //alert(stdno + " " + op);
   displayInfo(op);
             
    });
});
function srchUser() {
    //$('ajax-loader').setStyle('display', 'block'); 
    var url = 'index.php?option=com_jumi&fileid=187&user='+$('srch').value+'&func=srch&dt='+new Date().getTime();
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


function displayInfo(op) {
        var x = new Request({
        url: 'index.php?option=com_jumi&fileid=199&uid1='+op+'&func=srch&dt='+new Date().getTime(),
        method: 'get',
        onComplete: function(response) { 
            //alert(response);            
            var row = json_parse(response,function(data,text) {
            if (typeof text == 'string') {
                 var r = text.split(';');
                 $('uid').value = r['0'];
                 $('name').value = r['1'];
                 $('username').value = r['2'];
                 $('email').value = r['3'];
                 
            }
        });
        $('user-details').setStyle('display','block');
       // $('security-questions').setStyle('display','block');
        }
    }).send();
}
function showPass(stdno, requestor){
   
    var x = new Request({
            url: 'index.php?option=com_jumi&fileid=199&stdno='+stdno+'&req='+requestor+'&func=getpass',
            method: 'get',
            noCache: true,
            onComplete: function(response) {
            //$('search-pin').setStyle('display','none');
            //alert(response);
                    if (response == 0) {
                            alert('Could not locate password');
                             $('req-staff').value = '';
                    } else {
                    alert('Password sent to email address...');
                    $('req-staff').value = '';
                    $('req-email').value = '';
                   
                    //alert('Your Temporal ADS Password is: '+response);
                     $('user-details').setStyle('display','none');
                     $('srch').value = '';
                     $('srch').focus();	
                    //$('its-lookup').set('value','');
                    //$('its-lookup').focus();
                    }
            }
    }).send();
    
  
    
}

  function showEmail(requestor){      
      
       var x = new Request({
            url: 'index.php?option=com_jumi&fileid=199&reqs='+requestor+'&func=getemail',
            method: 'get',
            noCache: true,
            onComplete: function(response) {
            //$('search-pin').setStyle('display','none');
            //alert(response);
                    if (response == 0) {
                            alert('Could not find email');
                            // $('req-staff').value = '';
                    } else {
                        document.getElementById('getpin').disabled = false;
                        document.getElementById('finish-but').disabled = false;
                    //alert('Password sent to email address...');
                    $('req-email').value = response;
                    //alert('Your Temporal ADS Password is: '+response);
                     //$('user-details').setStyle('display','none');
                    // $('srch').value = '';
                    // $('srch').focus();	
                    //$('its-lookup').set('value','');
                    //$('its-lookup').focus();
                    }
            }
    }).send();
        
    }