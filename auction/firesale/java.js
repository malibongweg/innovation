var userAllowed = true;

window.addEvent('domready', function(){

	var userid = $('uid').get('value');
	checkUser(userid);

	if (userAllowed){
		loadProducts();
	}

});

function checkUser(uid){

	var x = new Request({
		url: '/scripts/auction/firesale/db.php?action=checkUser&uid='+uid,
		method: 'get',
		noCache: true,
		async: false,
		onComplete: function(response){
			var data = json_parse(response);
			if (parseInt(data.Record[0].cnt) == 0){
				userAllowed = false;
			}
		}
	}).send();

	if (userAllowed == false){
		var x = new Request({
		url: '/scripts/auction/firesale/db.php?action=displayPreviousBid&uid='+uid,
		method: 'get',
		noCache: true,
		async: false,
		onComplete: function(response){
			var data = json_parse(response);
			html = '<b>No more bidding allowed. You have a saved bid.</b><br /><br />';
			html = html + 'Date: '+data.Result.bid_time+'<br />';
			html = html + 'Description: '+data.Result.desc+'<br />';
			html = html + 'Price: '+data.Result.price+'<br /><br />';
			html = html + 'You will be contacted about payment and collection.<br />';
			html = html + 'Thank you....';
			$('div-products').set('html',html);
		}
	}).send();
	}
}

function loadProducts(){
	var x = new Request({
		url: '/scripts/auction/firesale/db.php?action=loadProducts',
		method: 'get',
		noCache: true,
		onComplete: function(response){
			var data = json_parse(response);
			var html = '<ul class="products">';
			for (var i=0;i<data.Records.length ;i++){
				if (data.Records[i].amount_left > 0){
				html = html + '<li style="padding: 5px; margin: 5px; background-color: #e5e5e5; border: 1px solid #c0c0c0">';
				html = html + '<h5>' + data.Records[i].desc + '</h5>';
				html = html + '<p style="font-weight: bold; font-size: 14px">Price: R' + data.Records[i].price + '</p>';
				//html = html + '<p>Amount L' + data.Records[i].amount_left + '</p>';
				if (userAllowed){
					html = html + '<input type="button" id="bid-button-'+data.Records[i].id+'" value="Bid Now" onclick="javascript: bidNow('+data.Records[i].id+');" />';
				}
				html = html + '</li>';
				} else {
					html = html + '<li>No more stock left...</li>';
				}
			}
			html = html + '</ul>';
			$('div-products').set('html',html);
		}
	}).send();

}

function bidNow(id){
	if (confirm('This action cannot be reversed. Are you sure?')){
		var userid = $('uid').get('value');
		var x = new Request({
		url: '/scripts/auction/firesale/db.php?action=saveBid&uid='+userid+'&biditem='+id,
		method: 'get',
		noCache: true,
		onComplete: function(response){
			var data = json_parse(response);
			if (parseInt(data.Result.code) == 1){
				alert('Bid saved.');
				window.location.reload();
			} else {
				alert('Error saving your bid. Please try again.');
				window.location.reload();
			}
		}
		}).send();
	}
}
