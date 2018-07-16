<?php
/**
 * Internal JS so we can pause PHP variables for user customizations
 * 
 */
 
// Get plugin settings
$menu_style = elgg_get_plugin_setting('menu_style', 'landr'); 
 
?> 
 
<script type="text/javascript"> 
$( document ).ready(function() { 
    
    $('#login-dropdown, .elgg-module .elgg-head, .elgg-menu-item-thread').remove();
    
    <?php if ($menu_style == 'horizontal') { ?>
        $('.elgg-page-header .elgg-menu-site').appendTo($('.landr-menu-horizontal')); 
    <?php } else { ?>
        $('.elgg-page-header .elgg-menu-site').remove(); 
    <?php } ?>
    
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
</script>