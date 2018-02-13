<!doctype html>
 <head>
 <script src="jquery.min.js" type="text/javascript"></script>
  <title>Document</title>
 </head>
 <body>
  
	<script type="text/javascript">
		$.ajax({
		url: 'https://opa.cput.ac.za/scripts/rest/cts-alerts/',
		type: 'get',
		dataType: 'json',
		success : function(data){
			if (data =='NULL'){
			} else {

			}
			console.log(data);
			if (data['Result'] == 'OK')	{
				for(var i=0;i<data.Records.length;++i){
					$('#alerts').append(data.Records[i].failure_date+'<br />'+data.Records[i].failure_message+"<br /><br />");
				}
			}
		}
	});

	</script>

<div id="alerts">
</div>

 </body>
</html>
