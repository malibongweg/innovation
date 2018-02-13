window.addEvent('domready',function() {
	navigateOption();
	
});

function navigateOption() {
	var n = $('nav').getSelected().get('value');
	switch (parseInt(n))
	{
	case 1: $('ldap-lookup').setStyle('display','block'); $('ldap-write').setStyle('display','none'); break;
	case 2: $('ldap-lookup').setStyle('display','none'); $('ldap-write').setStyle('display','block'); break;
	}
}