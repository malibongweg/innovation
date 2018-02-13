window.addEvent('domready',function() { 
     $('productForm').addEvent('submit',function(e) {         
		e.stop();
		var action = $('product-action').get('value');
               // document.getElementById('product-save').style.visibility = 'visible';
		saveProduct(this,action);
	});    
        $('product-cancel-update').addEvent('click',function() { 
        $('product-forms').setStyle('display','none');
        $('md').setStyle('display','block'); 
        $('cards').setStyle('display','block');
    });
    listProduct();
    $('add-new').addEvent('click', function() {
        $('edit_rec').setStyle('display','none');
        $('add_rec').setStyle('display','block');
            openForm();

        });
    $('delete-record').addEvent('click', function() {
            var prodid = $('prdID').get('value');                        
            deleteRecord(prodid);
        });  
    $('product-edit').addEvent('click', function() {
            var proded = $('prdID').get('value'); 
            var uType = $('unitType').get('value');  
            var mod = $('model').get('value');  
            var serNo = $('serialNo').get('value');  
            var addr = $('address').get('value');
            recordEdit(proded,uType,mod,serNo,addr);
        }); 
});
function saveProduct(frm,action) { 
        var x = new Request({
                url: 'index.php?option=com_jumi&fileid=233&action=save_product',
                        method: 'post',
                        noCache: true,
                        data: frm,
                    onComplete: function(response){
                    // alert(response);
                    if (parseInt(response) == -1){
                                alert('Product saved successfully.');
                                window.location.reload();
                        }
                        else if (parseInt(response) == 0)
                        {
                                alert('Product not saved.');
                                window.location.reload();
                        } 
                        window.location.reload();
            }
        }).send();
}
///////////////////////////////////////////////List Product////////////////////////////////////////////////////////////////////////////////
function listProduct() {
    $('product-forms').setStyle('display','none');
    	var color1 = '#a5badc';
	var color2 = '#FFFFFF';
	var cnt = 1;
	var fnd = true;
	var html = '<table width="100%" border="0" style="table-layout: fixed">';
	$('ajax-product').setStyle('display','block');
	var x = new Request ({
		url: 'index.php?option=com_jumi&fileid=233&action=display_productData',
			noCache: true,
			method: 'get',
			timeout: 5000,
			onTimeout: function() {
				$('product-form').set('html','Time-out error...');
				$('ajax-product').setStyle('display','none');
				x.cancel();
			},
                onComplete: function(response) {
		var row = json_parse(response,function(data,text) {
		if (typeof text == 'string') {
		if (parseInt(text) == -1)
		{
		$('ajax-product').setStyle('display','none');
		$('product-form').set('html','No data available.');
		    fnd = false;
		}else {
                    fnd = true;
		var rec = text.split(';');
		var m = cnt % 2;
		if (m == 1)
		{
                	var color = color1;
		} else {
			var color = color2;
			}                              
		html = html + '<tr>';							
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[0] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 10%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000">';
		html = html + rec[1] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[2] + '</td>';
		html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[3] + '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 15%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000; text-align: left">';
		html = html + rec[4] + '</td>';
                html = html + '<td style="overflow: hidden; font-size: 10px; width: 5%; height: 11px; border: 1px solid '+color+'; background-color: '+color+'; color: #000000"><input type="radio" name="videntity" id="product-id" onclick="editProduct(this.value)" value="';
		html = html + rec[0] + '"</td>';
		html = html + '</tr>';
		++cnt;
			}
			}
		});
		html = html + "</table>";
		if (fnd == true)
		{
		$('product-form').set('html',html);
		$('ajax-product').setStyle('display','none');
		$('header-details').setStyle('display','block');
		}
	}
	}).send();
}
function openForm() {
        document.getElementById('unitType').value = "";
        document.getElementById('model').value = "";
        document.getElementById('serialNo').value = "";
        document.getElementById('address').value = "";
        $('cards').setStyle('display','none');
	$('md').setStyle('display','none');
	$('product-forms').setStyle('display','block');
	
}
///////////////////////////////////////Populated delete form//////////////////////////////////////////////////////////////////////////////////
function editProduct(id) {
        $('cards').setStyle('display','none');
	$('md').setStyle('display','none');
	$('product-forms').setStyle('display','block');
        $('edit_rec').setStyle('display','block');
         $('add_rec').setStyle('display','none');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=233&action=edit_product&id='+encodeURI(id),
			method: 'get',
			noCache: true,
			onComplete: function(response){
				var r = response.split(';');
				$('prdID').set('value',r[0]);
				$('unitType').set('value',r[1]);
                                $('model').set('value',r[2]);
				$('serialNo').set('value',r[3]);
                                $('address').set('value',r[4]);					
		}
	}).send();
}
/////////////////////////////////////////////// delete product data//////////////////////////////////////////////////////////////////////////////////
function deleteRecord(prodid) {
   // var card;
        $('cards').setStyle('display','none');
	$('md').setStyle('display','none');
	$('product-forms').setStyle('display','block');
        $('edit_rec').setStyle('display','block');
        $('add_rec').setStyle('display','none');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=233&action=delete_product&prod_id='+prodid,
			method: 'get',
                        noCache: true,                        
                    onComplete: function(response){
                    // alert(response);
                        if (parseInt(response) == -1)
                        {
                                alert('Error deleting product data...');
                        } else if (parseInt(response) == 1) {
                                alert('Product data successfully deleted.');
                                window.location.reload();
                        }
                        window.location.reload();
            }
        }).send();
}
/////////////////////////////////////////////// edit product data//////////////////////////////////////////////////////////////////////////////////
function recordEdit(proded,uType,mod,serNo,addr) {
        $('cards').setStyle('display','none');
	$('md').setStyle('display','none');
	$('product-forms').setStyle('display','block');
	var x = new Request({
		url: 'index.php?option=com_jumi&fileid=233&action=product_edit&prod_id='+proded+'&uType='+uType+'&mod='+mod+'&serNo='+serNo+'&addr='+addr,
			method: 'get',
                        noCache: true,                        
                    onComplete: function(response){
                     //alert(response);
                        if (parseInt(response) == -1)
                        {
                                alert('Error updating product data...');
                        } else if (parseInt(response) == 1) {
                                alert('Product data successfully updated.');
                                window.location.reload();
                        }
                        window.location.reload();
            }
        }).send();
}
