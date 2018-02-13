var page = 0;
var bidTimer = 0;
window.addEvent('domready', function(){

	$('auction-spinner').setStyle('display','block');
	$('foward-button').addEvent('click',function(){
		page = page + 12;
		loadItems();
		formEvent();
		disableAuction();
		setConfig();
	});
	
	$('back-button').addEvent('click',function(){
		page = page - 12;
		loadItems();
		formEvent();
		disableAuction();
		setConfig();
	});
	
	$('foward-button2').addEvent('click',function(){
		page = page + 12;
		loadItems();
		formEvent();
		disableAuction();
		setConfig();
	});
	
	$('back-button2').addEvent('click',function(){
		page = page - 12;
		loadItems();
		formEvent();
		disableAuction();
		setConfig();
	});
	
	setConfig();
	setInterval('setConfig()',10000);
});

function setConfig(){
	loadItems();
	formEvent();
	var x = new Request({
		url: '/scripts/auction/db.php?action=bidTime',
		method: 'get',
		async: false,
		onComplete: function(response){
			var data = json_parse(response);
			if (parseInt(data.status) == 0){
				disableAuction();
				$('bidding-timer').set('html','AUCTION STARTS AT '+data.start);
			} else if  (parseInt(data.status) == 1) {
				enableAuction();
				$('bidding-timer').set('html','AUCTION ENDS AT '+data.stop);
			} else {
				disableAuction();
				$('bidding-timer').set('html','NO MORE BIDDING ALLOWED.');
			}
		}
	}).send();
}

function disableAuction(){
		$$('input[type=submit]').each(function(el,idx){
			if (el.id.substring(0, 14) == 'submit-bid-id-'){
				el.setStyle('display','none');
			}
		});
}

function enableAuction(){
	$$('input[type=submit]').each(function(el,idx){
		if (el.id.substring(0, 14) == 'submit-bid-id-'){
			el.setStyle('display','inline');
		}
	});
}

function formEvent(){
	var frm = $$('form');//document.forms;
	for (i=0;i<frm.length;i++){
		frm[i].addEvent('submit',function(e){
			e.stop();
			if (confirm('Are you sure?')){
			var x = new Request({
				url: '/scripts/auction/db.php?action=saveBid',
				method: 'post',
				data: this,
				onComplete: function(response){
					var data = json_parse(response);
					if (parseInt(data.high) == 1){
						alert('Only one item per user allowed.\nYou are currently the highest bidder on item# '+data.item);
						return false;
					}
					$$('span').each(function(el,idx){
						if (el.id.length > 0){
							if (el.id == 'bid-id-'+data.id){
								el.set('html',data.amount);
							}
						}
					});
					loadItems();
				}
			}).send();
			}
		});
	}
}

function loadItems(){
	var uname = $('uname').get('value');
	var x = new Request({
		url: '/scripts/auction/db.php?action=loadPage&page='+page+'&login='+uname,
		method: 'get',
		noCache: true,
		async: false,
		onComplete: function(response){
			var data = json_parse(response);
			///Disable/Enable Nav Buttons//////
			if (data.Back == 'disabled'){
				$('back-button').set('disabled',true);
			} else {
				$('back-button').set('disabled',false);
			}
			if (data.Foward == 'disabled'){
				$('foward-button').set('disabled',true);
			} else {
				$('foward-button').set('disabled',false);
			}
				///Process Data////
				var html = '';
				for (var i=0;i<data.Records.length;i++){
					html = html + '<div class="round-borders" style="width: 170px; height: 200px;  border: 1px dashed #989898; margin: 10px; overflow:hidden; display: inline-block ">';
					html = html + '<form>';
					html = html + '<input type="hidden" name="id" value="'+data.Records[i].id+'"/>';
					html = html + '<input type="hidden" name="uid" value="'+$('uid').get('value')+'"/>';
					html = html + '<input type="hidden" name="username" value="'+$('username').get('value')+'"/>';
					html = html + '<input type="hidden" name="uname" value="'+$('uname').get('value')+'"/>';
					html = html + '<div class="bid-header2" style="background-color: #37598F; color: #ffffff; font-size: 13px; padding: 5px; text-align: center; min-height: 40px; margin-bottom: 5px">';
					html = html + data.Records[i].item_code+'<br />'+data.Records[i].description;
					html = html + '</div>';
					html = html + '<div class="bid-header" style="font-weight: bold; background-color: #ff8484; color: #000000; padding: 5px; text-align: center;  margin: 0 5px 5px 5px">';
					html = html + 'RESERVE PRICE<br />R'+data.Records[i].reserved;
					html = html + '</div>';
					html = html + '<div class="bid-header" id="my-bid" style="font-weight: bold; border: 1px solid #c8c8c8; background-color: #ffffff; color: #000000; padding: 5px; text-align: center;  margin: 0 5px 0 5px">';
					html = html + 'CURRENT BID<br />';
						if (data.Records[i].item_code == data.item){
							html = html + '<span style="background-color: #66cc00" id="bid-id-'+data.Records[i].id+'">R'+data.Records[i].bid_amount+'</span>';
						} else {
							html = html + '<span id="bid-id-'+data.Records[i].id+'">R'+data.Records[i].bid_amount+'</span>';
						} 
						
					html = html + '</div>';
					html = html + '<div  style="background-color: #ffffff; color: #000000; text-align: center;  margin: 15px 1px 0 1px">';
					if (data.Records[i].item_code == data.item){
						html = html + '<input class="myButton" type="submit" id="submit-bid-id-'+data.Records[i].id+'" value="Bid Excepted"  style="width: 155px !important" />';
					} else {
						html = html + '<input class="myButton" type="submit" id="submit-bid-id-'+data.Records[i].id+'" value="Bid (R50.00 Increment)"  style="width: 155px !important" />';
					}
					html = html + '</div></form></div>';
				}
			$('auction-container').set('html',html);
			$('auction-spinner').setStyle('display','none');
		}
	}).send();
}