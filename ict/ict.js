function isDigit ( evt ) {
	var ie4up = (document.all) ? 1 : 0;
    var keyCode = evt.which ? evt.which : evt.keyCode;
    digit = !isNaN ( parseInt ( String.fromCharCode ( keyCode ) ) ) ||
       ( keyCode == 190 || keyCode == 110 ) ||
       ( keyCode >= 8 && keyCode <= 46 &&
       keyCode !=16 && keyCode !=32 );
    digit = ie4up ? digit || ( keyCode >= 96 && keyCode <=105 ) : digit;
    return ( digit );
}

function validateAcquisitionsStock(form) {

if (form.action.value == 1)
{
	location.href="index.php?option=com_jumi&view=application&fileid=5&Itemid=116&action=1";
	return false;
}
else
{
			var frmval = form;

			if (frmval.id.length >= 2)
			{
				for(var i = 0; i < frmval.id.length; i++) 
				{
					if(frmval.id[i].checked) 
					{
						var str = frmval.id[i].value;
					} 
				}
						if (typeof(str) != 'undefined') 
						{
							if (form.action.value != 'new') 
								{
									
										location.href="index.php?option=com_jumi&view=application&fileid=5&Itemid=116&action=2&id="+str;
										return false;
									
								}
						} 
						else 
						{ 
							alert("Please select record to edit."); return false; 
						}	
			}
			else
			{
				var str = frmval.id.value;
				if (typeof(str) != 'undefined') 
						{
							if (form.action.value != 'new') 
								{
									
										location.href="index.php?option=com_jumi&view=application&fileid=5&Itemid=116&action=2&id="+str;
										return false;
																		
								}
						} 
						else 
						{ 
							alert("Please select record to edit."); return false; 
						}
			}
}
}

function validateAcquisitions(form) {

if (form.action.value == 1)
{
	location.href="index.php?option=com_jumi&view=application&fileid=4&Itemid=115&action=1";
	return false;
}
else
{
			var frmval = form;

			if (frmval.id.length >= 2)
			{
				for(var i = 0; i < frmval.id.length; i++) 
				{
					if(frmval.id[i].checked) 
					{
						var str = frmval.id[i].value;
					} 
				}
						if (typeof(str) != 'undefined') 
						{
							if (form.action.value != 'new') 
								{
									
										location.href="index.php?option=com_jumi&view=application&fileid=4&Itemid=115&action=2&id="+str;
										return false;
									
								}
						} 
						else 
						{ 
							alert("Please select record to edit."); return false; 
						}	
			}
			else
			{
				var str = frmval.id.value;
				if (typeof(str) != 'undefined') 
						{
							if (form.action.value != 'new') 
								{
									
										location.href="index.php?option=com_jumi&view=application&fileid=4&Itemid=115&action=2&id="+str;
										return false;
																		
								}
						} 
						else 
						{ 
							alert("Please select record to edit."); return false; 
						}
			}
}
}


 
function isNotEmpty(elem) {
    var str = elem.value;
    if (str == null || str.length == 0) 
	{
        alert("Please fill in the required field(s).");
        return false;
    } else 
	{
        return true;
    }
}
function validateAcquisitionFields(form) {
    if (isNotEmpty(form.scode))  {
		if (isNotEmpty(form.sdesc))  {
			form.submit;
			return true;
		 }
	}
		form.scode.style.backgroundColor =  '#FFFF00';
		form.sdesc.style.backgroundColor =  '#FFFF00';
 		form.scode.focus();
        return false;  
  }