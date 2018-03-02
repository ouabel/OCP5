$(document).ready(function(){

	$('.panel').mouseover(function(e) {
		$('.panel-primary').attr('class', 'panel panel-default');
		$(this).attr('class', 'panel panel-primary');
	});

});