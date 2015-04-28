/**
// * Init vars
// */
var SEARCH_WORD			= '<?=$search_word;?>';

var highLightWord		= function()
{
	var search_word_reg	= eval('/(' + SEARCH_WORD.split('').join('|') + ')/g');
	jQuery('.department_name,.department_address').each(function(){
		$high_light_words	= jQuery(this).html() . replace(search_word_reg, '<font class="high-light">$1</font>');
		jQuery(this).html( $high_light_words );
	});
}

/**
 * init do function
 */
jQuery(document).ready(function(){
	highLightWord();
});
