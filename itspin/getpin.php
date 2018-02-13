<html>
<head>
  <script type="text/javascript" src="mootools-core-1.4.5.js"></script>
    <script type="text/javascript" src="json.js"></script>
    <style>
        body {
            color:#333333;
            font-family:Arial;
            font-size: 14px;
        }

        h2 {
            color: #333;
            font-size: 14px;
        }

        div{
            font-size: 14px;
        }

        span{
            font-size: 14px;
        }

        #show-pin{
            font-size: 14px;
        }

        input[type="submit"], button[type="submit"],a {
            background: #333333 none repeat scroll 0 0;
            border: 0 none;
            color: #ffffff;
            font-weight: bold;
            padding: 5px 10px;
            text-transform: uppercase;
            text-decoration: none;
        }

        input:required {
            box-shadow:none;
        }

    </style>
</head>


<body>
    <form id='pin-form'>
           <div>
               Enter your Student Number<br/>
               <input name="student_no" id='stdno' type="text" size="40" required/>
           </div>

            <div>
                <span style="color: red; display: none;" id="stno-text"></span>
            </div>

            <p style="clear: both"></p>

            <div>
                Enter your ID / Passport Number<br/>
                <input name="id_no" id='digits' type="text" size="40" required style="text-transform:uppercase;"/>
            </div>

            <div>
                <span style="color: red; display: none;" id="idno-text"></span>
            </div>

            <p style="clear: both"></p>

            <div>
                Enter your First & Last Name<br/>
                <input name="f_name" id='f-name' type="text" size="40" required style="text-transform:uppercase;"/>
            </div>

            <div>
                <span style="color: red; display: none;" id="fname-text"></span>
            </div>

            <p style="clear: both"></p>

           <div>
               <input type="submit" name="SubMitBtn" value="Get my Pin Now"/>
           </div>

        <p style="clear: both"></p>
        <div align="center" id="show-pin" style="display:none; width: 300px; padding: 3px; background-color: #c9c9c9; border: 1px solid #9d9d9d; align: center; font-size: 20px; font-weight: bold;">

        </div>
        <div id="notes">
            <br/>
            <b>IMPORTANT NOTES</b><br/>
            This form only works with:
            <ul>
                <li>A valid student number, and</li>
                <li>A valid ID / Passport number, and</li>
                <li>A valid First & Last Name you filled in on your initial application form.</li>
                <li>If you have multiple names, please only use the first name.</li>
            </ul>
            <span style="color: red; font-weight: bold;">*All fields MUST be filled in correctly!</span>
        </div>
    </form>
</body>

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
                /*html_text = html_text + "Invalid student number";
                $('stno-text').set('html',html_text);
                $('stno-text').setStyle('display','block');
                $('stdno').focus();
                html_text = " ";
                return false;*/
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
                            var stno = r[0];
                            var id_pass_no = r[1];
                             var fname = r[2];
                            var pin = r[3];

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
                                    html = html + '<p style="font-size: 13px; color:red;">';
                                        html = html + 'Please request your PIN' + '&nbsp;<a href="https://paris.cput.ac.za/pls/prodi03/w99pkg.mi_login" target="_blank">' +' HERE</a>' + '<br/><br/>and come back to this page to retrieve your PIN.';

                                    html = html + '</p>';
                                    $('show-pin').set('html',html);
                                    $('show-pin').setStyle('display','block');
                                    html_text = " ";
                                    html = " ";
                                    $('stno-text').set('value','');
                                    $('idno-text').set('value','');
                                    $('fname-text').set('value','');
                                    //window.location.href = "http://www.google.com";
                                    //window.location.reload();
                                   // $('school-name').set('value', r[0]);
                                    $('notes').setStyle('display','none');
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
                                    $('notes').setStyle('display','none');
                                }

                            }

                        }
                    });

                }
            }).send();
        });
    });
</script>

</body>
</html>

