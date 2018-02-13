<?php
//header('Content-Type: text/plain; charset=ISO-8859-1');
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$doc = & JFactory::getDocument();
$doc->addScript("scripts/ServiceSystem/Product/product.js");
$doc->addScript("scripts/fav_apps.js");
$doc->addScript("scripts/json.js");

?>
<!--Define app name here-->
<form id="app-details">
<input type="hidden" id="app-name" value="Service System - Product Data" />
<input type="hidden" id="app-url" value="<?php $uri = parse_url(JURI::current()); echo $uri['path'];  ?>" />
<input type="hidden" id="app-uid" value="<?php echo $user->id; ?>" />
</form>
			<div class="art-block">
            <div class="art-block-tl"></div>

            <div class="art-block-tr"></div>
            <div class="art-block-bl"></div>
            <div class="art-block-br"></div>
            <div class="art-block-tc"></div>
            <div class="art-block-bc"></div>
            <div class="art-block-cl"></div>
            <div class="art-block-cr"></div>
            <div class="art-block-cc"></div>
            <div class="art-block-body" id="bh">

            <div class="art-blockheader">
            <div class="l"></div>
            <div class="r"></div>
            <h3 class="t" >
			 <a href="#" id="fav-def"><img src="images/default.png" width="16" height="16" style="vertical-align: middle" title="Set as default." /></a>
			<a href="#" id="fav-app"><img src="images/fav.png" width="16" height="16" style="vertical-align: middle" title="Add to favorite." /></a>
			<a href="#" class="modalizer" id="bug-app"><img src="images/radar.png" width="16" height="16" style="vertical-align: middle" title="Application Tracker." /></a>
      Service System - Product Data</h3>
        </div>
            <div class="art-blockcontent" >
            <div class="art-blockcontent-body">
                
<!----------------------------------------------New product----------------------------------------------------------------------------------------------------------------->                
  <div class="main-div" id="cards" style="display: block" align="right">

        <input type="button" id="add-new" class="button art-button" value="Add New Product" />
         
	<div style="clear: both"></div>
    
</div>              
<!----------------------------------------------Product Data----------------------------------------------------------------------------------------------------------------->
<div class="main-div" id="md">
<div class="main-header"><span id="frame-title" style="font-weight: bold">Product Data</span></div>
	<div style="position: relative; clear: both; margin: 10px 0 0 0"></div>
	
		<div id="header-details" style="position: relative; display: block">
				<div style="position: relative; width: auto; height: auto; margin: 0 16px 0 0">
						<table width="100%" border="0" style="table-layout: fixed">
						<tr>
							<th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">PRODUCT ID</th>
							<th style="width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">UNIT TYPE</th>
							<th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">MODEL</th>
							<th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">SERIAL #</th>
                                                        <th style="width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px; text-align: left">ADDRESS</th>
                                                        <th style="width: 5%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px">&nbsp;</th>
						</tr>
						</table>
				</div>
							<div id="ajax-product" style="position: relative; width: auto; height: auto; display: none">
								<img src="/images/kit-ajax.gif" width="16" height="11" border="0" alt="" style="vertical-align: middle">&nbsp;Loading data...
							</div>

                      <div id="product-form" style="position: relative; width: auto; height: 500px;  display: block; overflow: scroll"></div>
	    </div>
</div>
<!----------------------------------------------Product Data Form----------------------------------------------------------------------------------------------------------------->
        <div class="main-div" id="product-forms">
                    <div class="main-header"><strong>Product Data</span></strong></div>
                    <div id="productData" style="position: relative; width: auto; height: auto; display: block">
                        <div style="position: relative; clear: both; margin: 10px 0 0 0"></div>    
                        <form id="productForm">
                            <input type="hidden" id="product-action" />
                                    <input type="hidden" size="35" name="prdID" id="prdID" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/>
                                    <br />
                                <label class="input_label">Unit Type:</label>
                                    <input type="text" size="35" name="unitType" id="unitType" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Model:</label>
                                    <input type="text" size="35" name="model" id="model" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Serial #:</label>
                                    <input type="text" size="35" name="serialNo" id="serialNo" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                <label class="input_label">Site Address:</label>
                                    <input type="text" size="35" name="address" id="address" class="input_field" onKeyUp="javascript: this.value=this.value.toUpperCase();"/><br />
                                     <div align="center" style="position: relative;"><br/>
                                         <div id="add_rec" style="display: none"> <input type="submit" value="Save New" id="product-save" class="button art-button"/></div>&nbsp;
                                         <div id="edit_rec" style="display: none" align="center"><input type="button" value="Edit Product" id="product-edit" class="button art-button"/>&nbsp;
                                        <input type="button" id="delete-record" class="button art-button" value="Delete Record" />
                                        </div>
                                         <br/>
                                        <input type="button" value="Close" id="product-cancel-update" class="button art-button"/>
                                     </div>    
                        </form>
                    </div>                                        
                </div>
<!----------------------------------------------End of Product Data Form---------------------------------------------------------------------------------------------------------->
                
</div> </div></div></div>
