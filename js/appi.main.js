var $document = $(document);
var $window = $(window);

$document.ready(function() {

	$.buttonScroll();

	$window.scroll(function() {
		$.buttonCheckScroll();
	});

	$.setClientUTC();
});

$.buttonScroll = function() {
	$('#scrollup img').click( function() {
		$('html, body').animate({scrollTop: 0}, '500', 'swing');
		return false;
	});
};

$.buttonCheckScroll = function() {
	if ($document.scrollTop() > 0 ) {
		$('#scrollup').fadeIn('fast');
	} else {
		$('#scrollup').fadeOut('fast');
	}
};

$.isEmpty = function(el) {
	return !$.trim(el.html())
};

$.setClientUTC = function() {
	$.ajax({
		type: 'GET',
		url: 'loader.php',
		data: 'ajax_p=utc&utc='+$.setClientUTC(),
    });
};

$.setClientUTC = function() {
	var offset = new Date().getTimezoneOffset();
	offset = (offset / -1) / 60;
	return offset;
}