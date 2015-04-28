
var doSearch			= function()
{
	jQuery.post('/find/log',jQuery('#search_form').serialize(),function(){
		jQuery('#search_form').get(0).submit();
	},'json');
}