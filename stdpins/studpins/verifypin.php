<html>
<head>
  <script type="text/javascript" src="mootools-core-1.4.5.js"></script>
    <script type="text/javascript" src="json.js"></script>
    <style>
        body {
            color:#333333;
            font-family:OpenSansRegular, 'Helvetica Neue', Arial, sans-serif;
        }

        h2 {
            color: #333;
            font-size: 1.333em;
        }

        td{
            font-size: 1em;
        }
        </style>
</head>
</body>

<h2>CPUT Student iEnabler Pin Request</h2>

<form id='pin-form'>
    <table border="0" width="35%">
       <tr>
           <td width="20%">Enter your Student Number:</td>
           <td width="15%"><input name="student_no" id='stdno' type="text" size="40" required/></td>
       </tr>

        <tr>
            <td>&nbsp;</td>
            <td><span style="color: red; display: none; font-size: 80%;" id="stno-text"></span></td>
        </tr>

        <tr>
            <td>Enter your ID/Passport Number:</td>
            <td><input name="id_no" id='digits' type="text" size="40" required/></td>
        </tr>
        <!--<tr>
            <td colspan="2"><br/></td>
        </tr>-->
        <tr>
            <td>&nbsp;</td>
            <td><span style="color: red; display: none; font-size: 80%;" id="idno-text"></span></td>
        </tr>
        <tr>
            <td>Enter your First & Last Name:</td>
            <td><input name="f_name" id='f-name' type="text" size="40" required style="text-transform:uppercase;"/></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><span style="color: red; display: none; font-size: 80%;" id="fname-text"></span></td>
        </tr>
       <tr>
           <td>&nbsp;</td>
           <td><input type="submit" name="SubMitBtn" value="Get my Pin Now"/></td>
       </tr>
        </table>
    <p style="clear: both"></p>
    <div align="center" id="show-pin" style="display:none; width: 400px; padding: 3px; background-color: #c9c9c9; border: 1px solid #9d9d9d">

    </div>
</form>


<script type='text/javascript'>
    var html;
    var html_text;
    var pin;
    window.addEvent('domready',function() {
        html = " ";
        html_text = " ";
        $('stdno').set('value','');
        $('digits').set('value','');
        $('f-name').set('value','');
        $('stdno').focus();

        $('pin-form').addEvent('submit',function(e) {
            e.stop();
            var login = $('stdno').get('value');
            var pwd =  $('digits').get('value');
            var name = $('f-name').get('value');

            $('stno-text').setStyle('display','none');
            $('idno-text').setStyle('display','none');
            $('fname-text').setStyle('display','none');
            $('show-pin').setStyle('display','none');

            if (login.length != 9){
                alert('Please enter a valid student number!');
                $('stdno').focus();
                return false;
            }

            var x = new Request({
                url: 'db.php',
                data: this,
                method: 'post',
                onComplete: function(response) {
                   // alert(response);
                    var row = json_parse(response,function(data,text){
                        if (typeof text == 'string') {
                            var r = text.split(';');

//$data[] = $std . ';' . $id_pass . ';' . $name . ';' . $pin;

                            var stno = r[0]; //0/1 

                            var id_pass_no = r[1];     //0/1
                         var fname = r[2];             // 0/1
                            var pin = r[3];                    //Pin / 4

                            if(r[3] == 4)
                            {
                                if(r[0] == 0)
                                {
                                    html_text = html_text + "Invalid student number";
                                    $('stno-text').set('html',html_text);
                                    $('stno-text').setStyle('display','block');
                                    $('stdno').focus();
                                    html_text = " ";
                                }

                                if(r[1] == '0')
                                {
                                    html_text = html_text + "Invalid ID/Passport number";
                                    $('idno-text').set('html',html_text);
                                    $('idno-text').setStyle('display','block');
                                    $('digits').focus();
                                    html_text = " ";
                                }

                                if(r[2] == '0')
                                {
                                    html_text = html_text + "Invalid Name";
                                    $('fname-text').set('html',html_text);
                                    $('fname-text').setStyle('display','block');
                                    $('f-name').focus();
                                    html_text = " ";
                                }
                            }
                            else{
                                if(r[3].length <= 2) {

                                    html = html + '<p>';
                                    html = html + "Pin not available";
                                    html = html + '</p>';
                                    $('show-pin').set('html',html);
                                    $('show-pin').setStyle('display','block');
                                    html_text = " ";
                                    html = " ";
                                }
                                else{
                                //alert(pin);
                                html = html + '<p>';
                                html = html + "Your pin is: "+ r[3];
                                html = html + '</p>';
                                $('show-pin').set('html',html);
                                $('show-pin').setStyle('display','block');
                                html_text = " ";
                                html = " ";
                                }

                            }

                        }
                    });

                }
            }).send();
        });
    });

 /*   var html;
    var html_text;
	window.addEvent('domready',function() {
        html = " ";
        html_text = " ";
		$('stdno').set('value','');
		$('digits').set('value','');
        $('f-name').set('value','');
		$('stdno').focus();
				
		$('pin-form').addEvent('submit',function(e) {
			e.stop();
			var login = $('stdno').get('value');
			var pwd =  $('digits').get('value');
            var name = $('f-name').get('value');
			
			if (login.length != 9){
                alert('Please enter a valid student number!');
				$('stdno').focus();
				return false;
			}

                var x = new Request({
                    url: 'db.php',
                    data: this,
                    method: 'post',
                    onComplete: function(response) {
                       // alert(response);
                        if (response == 0) {
                            alert('Could not locate pin.');
                        }
                        else if(response == 1)
                        {
                            $('fname-text').setStyle('display','none');
                            $('idno-text').setStyle('display','none');
                            //$('fname-text').set('html','');
                            //$('idno-text').set('html','');
                            //alert('Invalid student number');
                            html_text = html_text + "Invalid student number";
                            $('stno-text').set('html',html_text);
                            $('stno-text').setStyle('display','block');
                            $('stdno').focus();
                            html_text = " ";
                        }

                        else if(response == 2)
                        {
                            $('stno-text').setStyle('display','none');
                            $('fname-text').setStyle('display','none');
                            //$('stno-text').set('html','');
                            //$('fname-text').set('html','');
                            //alert('Invalid id/passport number');
                            html_text = html_text + "Invalid ID/Passport number";
                            $('idno-text').set('html',html_text);
                            $('idno-text').setStyle('display','block');
                            $('digits').focus();
                            html_text = " ";
                        }

                        else if(response == 3)
                        {
                            $('stno-text').setStyle('display','none');
                            $('idno-text').setStyle('display','none');
                            //$('stno-text').set('html','');
                            //$('idno-text').set('html','');
                            //alert('Invalid fullname');
                            html_text = html_text + "Invalid Name";
                            $('fname-text').set('html',html_text);
                            $('fname-text').setStyle('display','block');
                            $('f-name').focus();
                            html_text = " ";
                        }
                        else {
                            $('stno-text').setStyle('display','none');
                            $('idno-text').setStyle('display','none');
                            $('fname-text').setStyle('display','none');

                            //alert('Pin for student number: '+stdno+' is: '+response);
                            html = html + '<p>';
                            html = html + "Your pin is: "+ response;
                            html = html + '</p>';
                            $('show-pin').set('html',html);
                            $('show-pin').setStyle('display','block');
                            html_text = " ";
                        }
                    }
                }).send();


			});
	});*/
  
</script>

</body>
</html>

