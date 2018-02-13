var tref;
var html;

window.addEvent('domready',function() {

    $('srch').value = '';
    $('srch').focus();


    $('getUser').addEvent('click',function() {
        var uid = $('srch').get('value');
        if (uid.length < 9) {
            alert('Please enter a valid student number.');
            $('user-details').setStyle('display','none');
            $('security-questions').setStyle('display','none');
            $('srch').value = '';
            $('srch').focus();
        }
        else{
            displayInfo(uid);
        }
    });

    $('getpin').addEvent('click',function(){
        var stdno = $('srch').get('value');
        var op = $('uname').get('value');
        showPin(stdno,op);
    });

});

function srchUser() {
    var url = 'index.php?option=com_jumi&fileid=192&user='+$('srch').value+'&func=srch&dt='+new Date().getTime();
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
    var stdno = $('srch').get('value');
    var op = $('uname').get('value');


        var x = new Request({
        url: 'index.php?option=com_jumi&fileid=192&uid='+uid+'&func=info&dt='+new Date().getTime(),
        method: 'get',
        onComplete: function(response) {
            var row = json_parse(response,function(data,text) {
            if (typeof text == 'string') {
                //$data = $row->IADSTNO.";".$row->IADNAMES.";".$row->IADSURN.";".$idno.";".$row->IBCNAME.';'.$row->GAMNAME.';'.$row->iaidesc;
                 var r = text.split(';');
                 $('uid').value = r['0'];
                 $('name').value = r['1'] + ' ' + r['2'];
                 $('idnum').value = r['3'];
                 $('email').value = r['0'] + '@cput.ac.za';
                 $('school').value = r['4'];
                 $('language').value = r['5'];
                 $('qual').value = r['6'];
                 $('address').value = r['7'] + ', ' + r['8'] + ', ' + r['9'];
                 $('fees').value = r['10'];

                showPin(stdno,op);
            }
        });
        $('user-details').setStyle('display','block');
        $('security-questions').setStyle('display','block');
        }
    }).send();
}

function showPin(stdno,op){
    if (stdno.length < 9) {
            alert('Enter valid student number');
            return false;
    }

    var x = new Request({
            url: 'index.php?option=com_jumi&fileid=192&stdno='+stdno+'&op='+op+'&func=getpin',
            method: 'get',
            noCache: true,
            onComplete: function(response) {
                    if (response.length == 0) {
                            alert('Could not locate pin.');
                    } else {
                            //alert('Pin for student number: '+stdno+' is: '+response);

                            html = "<p style='color: red'>" + "<span style='color: #0000ff; font-weight: bold;'>NB:</span> Please do not give the student PIN if student did not answer at least 3 questions correctly. "+ "<br/><br/>" + "The student must contact the ITS System Administrator or go to iEnabler to Request/Change Pin if answers are incorrect." + "</p>";
                            html = html + "<br/>" + "<b>Pin for student number " + stdno + " is</b>: " + response;
                            $('show-pin').set('html',html);
                            /*$('user-details').setStyle('display','none');
                            $('security-questions').setStyle('display','none');
                            $('srch').value = '';
                            $('srch').focus();*/
                           // window.location.reload();
                    }
            }
    }).send();
}

