<!doctype html>
 <head>
 <script src="jquery.min.js" type="text/javascript"></script>
  <title>Document</title>
 </head>
 <body>
  
	<script type="text/javascript">
		$.ajax({
		url: 'https://opa.cput.ac.za/scripts/rest/meal-balance/?stdno=210086424',
		type: 'get',
		dataType: 'json',
		success : function(data){
			console.log(data);
			for(var i=0;i<data.Records.length;++i){
				$('#alerts').append(data.Records[i].USERNO+'<br />'+data.Records[i].BALANCE+"<br /><br />");
			}
		}
	});

	</script>

<div id="alerts">
</div>

 </body>
</html>
