window.addEvent('domready',function(){


	loadADData();


	$$('form').addEvent('submit',function(e) {
		e.stop();
		var fv=this.toQueryString().parseQueryString();
		if (fv.qual.length == 0 ||fv.ot.length == 0 || fv.tmpl.length == 0){
			 alert('Fill in required field(s).');
		} else {
			$$('input[type=submit]').each(function(e,i){
				e.disabled = true;
			});
			var x = new Request({
			url: '/scripts/ad_templates/db.php?action=save_template',
				noCache: true,
				data: this,
				async: false,
				method: 'post',
				onComplete: function(response) {
					var xx = new Request({
					url: '/scripts/ad_templates/db.php?action=proc_pending',
					noCache: true,
					async: true,
					method: 'get',
					onComplete: function(response) {
						console.log('Proc Pending complete.');
					}
					}).send();

					alert('Template updated. Winow will reload.');
					location.reload();
			}
			}).send();
			
		}
		
	});

	$('proc-pending').addEvent('click',function(){
		processPending();
	});

});


function processPending(){
	$('proc-pending').setStyle('display','none');
	$('ad-busy').setStyle('display','block');
	var x = new Request({
		url: '/scripts/ad_templates/db.php?action=proc_pending',
		method: 'get',
		noCache: true,
		async: false,
		onComplete: function(response){
			$('ad-busy').setStyle('display','none');
			$('proc-pending').setStyle('display','block');
			location.reload();
		}
	}).send();
		
}

function loadADData(){
	$('proc-pending').setStyle('display','none');
	$('ad-busy').setStyle('display','block');
		var x = new Request({
		url: '/scripts/ad_templates/db.php?action=get_errors',
		method: 'get',
		noCache: true,
		async: false,
		onComplete: function(response){
				var data = json_parse(response);
				if (data.Count != 0){
					var html = '';
					html = '<table width="100%"><tr>';
					html = html + '<td width="15%" style="padding: 3px; background-color: #dddddd">ITS QUAL</td>';
					html = html + '<td width="10%" style="padding: 3px; background-color: #dddddd">ITS OT</td>';
					html = html + '<td width="10%" style="padding: 3px; background-color: #dddddd">ITS CNT</td>';
					html = html + '<td width="15%" style="padding: 3px; background-color: #dddddd">TEMPL QUAL</td>';
					html = html + '<td width="20%" style="padding: 3px; background-color: #dddddd">TEMPL OT</td>';
					html = html + '<td width="20%" style="padding: 3px; background-color: #dddddd">TEMPLATE</td>';
					html = html + '<td width="10%" style="padding: 3px; background-color: #dddddd">&nbsp;</td>';
					html = html + '</tr></table>';
					//$('ad-corrections').set('html',html);
					for (var i=0;i<data.Records.length;i++){
						html = html + '<form class="update-template">';
						html = html + '<table width="100%"><tr>';
						html = html + '<td width="15%" style="padding: 3px; font-size: 9px" title="FAC: '+data.Records[i].GAENAME+"\nDEPT: "+data.Records[i].GACNAME+'">['+data.Records[i].IAGQUAL+'] '+data.Records[i].IAIDESC+'</td>';
						html = html + '<td width="10%" style="padding: 3px; text-align: center">'+data.Records[i].IAGOT+'</td>';
						html = html + '<td width="10%" style="padding: 3px; text-align: center">'+data.Records[i].CNT+'</td>';
						//$('ad-corrections').set('html',html);

							var x = new Request({
							url: '/scripts/ad_templates/db.php?action=get_template&qual='+data.Records[i].IAGQUAL+'&ot='+ data.Records[i].IAGOT,
							method: 'get',
							noCache: true,
							async: false,
							onComplete: function(response){
								var tdata = json_parse(response);
								if (tdata.Count == 0){
									html = html + '<td width="15%" style="padding: 3px; text-align: center; background-color: #f46e4d"><input type="text" size="8" name="qual" onkeyup="this.value = this.value.toUpperCase();" /></td>';
									html = html + '<td width="20%" style="padding: 3px; text-align: center; background-color: #f46e4d"><input type="text" size="2" name="ot" onkeyup="this.value = this.value.toUpperCase();" /></td>';
									html = html + '<td width="20%" style="padding: 3px; text-align: center; background-color: #f46e4d"><input type="text" size="5" name="tmpl" onkeyup="this.value = this.value.toUpperCase();" /></td>';
									html = html + '<td width="10%" style="padding: 3px"><input type="submit" value="Add" /></td>';
								} else {
									var qual = false; var qual_name = '';
									var ot = false; var ot_name = '';
									var tmpl = '';
									
									for (var x=0;x<tdata.Records.length;x++){
										if (tdata.Records[x].QUAL == tdata.Qualification && qual == false) {  qual = true; qual_name = tdata.Records[x].QUAL; tmpl = tdata.Records[x].TEMPLATE; }
										if (tdata.Records[x].OFF_TYPE == tdata.Offering && ot == false) {  ot = true; ot_name = tdata.Records[x].OFF_TYPE; }
									}
									if (qual == true){
										html = html + '<td width="15%" style="padding: 3px; background-color: #77fd88"><input type="text" size="8" name="qual_dis" value="'+qual_name+'" disabled /><input type="hidden" size="8" name="qual" value="'+qual_name+'" /></td>';
									} else {
										html = html + '<td width="15%" style="padding: 3px; text-align: center; background-color: #f46e4d"><input type="text" size="8" name="qual" value="'+qual_name+'" onkeyup="this.value = this.value.toUpperCase();" /></td>';
									}	
									if (ot == true)	{
										html = html + '<td width="20%" style="padding: 3px; background-color: #77fd88;text-align: center"><input type="text" size="2" name="ot_dis" value="'+ot_name+'" disabled /><input type="hidden" size="2" name="ot" value="'+ot_name+'" /></td>';
									} else {
										html = html + '<td width="20%" style="padding: 3px; text-align: center; background-color: #f46e4d"><input type="text" size="2" name="ot" value="'+ot_name+'" onkeyup="this.value = this.value.toUpperCase();" /></td>';
									}
									if (qual == true){
										html = html + '<td width="20%" style="padding: 3px; background-color: #77fd88;text-align: center"><input type="text" size="5" name="tmpl_dis" value="'+tmpl+'" disabled /><input type="hidden" size="5" name="tmpl" value="'+tmpl+'" /></td>';
									} else {
										html = html + '<td width="20%" style="padding: 3px; text-align: center; background-color: #f46e4d"><input type="text" size="5" name="tmpl" /></td>';
									}
									
									if (qual == false || ot == false){
										html = html + '<td width="10%" style="padding: 3px;padding: 3px"><input type="submit" value="Add" /></td>';
									} else {
										html = html + '<td width="10%" style="padding: 3px;padding: 3px">Pending...</td>';
									}
									
											
								}
							}}).send();

						html = html + '</tr></table></form>';	
						$('ad-corrections').set('html',html);
					}
					
		
				} else { html = 'No more records to process...'; }
				
			$('ad-corrections').set('html',html);
		}
	}).send();
	$('ad-busy').setStyle('display','none');
	$('proc-pending').setStyle('display','block');
}