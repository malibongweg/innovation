window.addEvent('domready',function() {	
    $('srch').value = '';
    $('srch').focus();
    //END OF AD NOT STAFF INFO JQUERY

    /*$('staff-data').addEvent('click', function() {
        $('exclusion-tab').setStyle('display', 'none');
        $('bh').setStyle('display', 'none');
        $('staff-tab').setStyle('display', 'block');

    });

    $('staff-exclusions').addEvent('click', function() {
        $('user-details').setStyle('display', 'none');
        $('staff-tab').setStyle('display', 'none');
        $('bh').setStyle('display', 'none');
        $('exclusion-tab').setStyle('display', 'block');
    });*/

 

   /* $('none-staff-data').addEvent('click', function() {
        $('staff-tab').setStyle('display', 'none');
        $('exclusion-tab').setStyle('display', 'none');
    });*/

/*$('finish-but').addEvent('click', function() {
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

    $('capture-ex').addEvent('click',function() {

        //var uid = parseInt($('userList').getSelected().get('value'));
        var op_ex = $('srch-ex').get('value');
       // $('req-staff').value = '';
        //$('req-email').value = '';
       // document.getElementById('getpin').disabled = true;
       // document.getElementById('finish-but').disabled = true;


        //var cel = $('cell').get('value');
        //alert('here');
        //alert(stdno + " " + op);
        displayEx(op_ex);

    });

});*/




/*function srchUser() {
    //$('ajax-loader').setStyle('display', 'block'); 
    var url = 'index.php?option=com_jumi&fileid=203&user='+$('srch').value+'&func=srch&dt='+new Date().getTime();
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
        url: 'index.php?option=com_jumi&fileid=203&uid1='+op+'&func=srch&dt='+new Date().getTime(),
        method: 'get',
        onComplete: function(response) { 
           if (response == 0){
           alert("Please update your contract details at HR!");
               window.location.reload();
           }else if (response == 1){
               //alert(response);
               alert ("Exclusion Please Capture!");
               window.location.reload();
               }
          else{
            
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
        }
    }).send();
}
function displayEx(op_ex) {
        var x = new Request({
        url: 'index.php?option=com_jumi&fileid=203&uid1='+op_ex+'&func=srchEx&dt='+new Date().getTime(),
        method: 'get',
        onComplete: function(response) { 
           if (response == 1){
           alert("Added Successfully!");
               window.location.reload();
           }
        }
    }).send();
}

function showPass(stdno, requestor){
   

    var x = new Request({
            url: 'index.php?option=com_jumi&fileid=203&stdno='+stdno+'&req='+requestor+'&func=getpass',
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
            url: 'index.php?option=com_jumi&fileid=203&reqs='+requestor+'&func=getemail',
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
        
        }*/
        
        });	