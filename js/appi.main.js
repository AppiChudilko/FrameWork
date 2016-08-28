var $document = $(document);
var $window = $(window);
var $hrActiveMargin, hrActiveWidth;
var $offsetInit;

$document.ready(function() {
	$.setClientUTC();
	$.buttonScroll();
	$.hrDefault();
	$.checkHrOffset();
	$.checkHrHover();
});

$window.resize(function() {
	$.checkHrOffset();
});

$window.scroll(function() {
	$.buttonCheckScroll();
});

$.checkHrOffset = function() {
	$offsetInit = $('#first-input').offset();
};

$.hrDefault = function() {
	$('.hr-active').css('width', $('#first-input').width()+12);
	$hrActiveMargin = $('.hr-active').css('marginLeft');
	$hrActiveWidth =  $('#first-input').width()+12;
};

$.footerBottom = function() {
	var docHeight = $(window).height();
	var footerHeight = $('footer').height();
	var footerTop = $('footer').position().top + footerHeight;

	if (footerTop < docHeight) {
		$('footer').css('margin-top', (docHeight - footerTop)-20 + 'px');
	}
};

$.checkHrHover = function() {
	$('.content input').mouseenter(function(){
        var $offset = $('.content input:hover').offset();
        $('.hr-active').css('marginLeft', $offset.left-$offsetInit.left);
        $('.hr-active').css('width', $('.content input:hover').width()+12);
    }).mouseleave(function(){
        $('.hr-active').css('marginLeft', $hrActiveMargin);
        $('.hr-active').css('width', $hrActiveWidth);
    });
};

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

$.isEmpty = function($el) {
	return !$.trim($el.html())
};

$.setClientUTC = function() {
	$.ajax({
		type: 'GET',
		url: 'loader.php',
		data: 'ajax_p=utc&utc='+$.getClientUTC(),
    });
};

$.getClientUTC = function() {
	var offset = new Date().getTimezoneOffset();
	offset = (offset / -1) / 60;
	return offset;
}

$.nl2br = function ($str, $is_xhtml) {   
    var $breakTag = ($is_xhtml || typeof $is_xhtml === 'undefined') ? '<br />' : '<br>';    
    return ($str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ $breakTag +'$2');
}