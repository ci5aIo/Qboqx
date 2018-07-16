define(['require', 'jquery', 'elgg'], function(require, $, elgg) {
	var top = $('.landr-slider-content').offset().top - parseFloat($('.landr-slider-content').css('marginTop').replace(/auto/, 500));

	$(window).scroll(function (event) {
		var y = $(this).scrollTop();
		if (y >= top) {
			$('.elgg-page-header').addClass('fixed');
			$('body').css('margin-top','90px');
		} else {
			$('.elgg-page-header').removeClass('fixed');
			$('body').css('margin-top','0');
		}
	});
});