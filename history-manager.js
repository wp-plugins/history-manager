var $j = jQuery.noConflict();
$j(document).ready(function() {

	$j('.history_manager h3:not(.open)').parent().find('ul').height('0px');

	$j('.history_manager h3').click(function() {
		if ($j(this).hasClass('open')) {
			$j(this).parent().find('ul').animate({'height': '0px'},250);
		} else {
			var s = $j(this).parent().find('ul')[0].scrollHeight;
			$j(this).parent().find('ul').animate({'height': s+'px'},250);
		}
		$j(this).toggleClass('open');
	});

});
