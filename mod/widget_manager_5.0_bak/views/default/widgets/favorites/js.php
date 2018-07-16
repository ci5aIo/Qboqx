<?php
?>

function widget_favorite_init() {
	$(".elgg-menu-item-widget-favorites a").on("click", function(elem) {
		$elem = $(this);
		
		elgg.action($elem.attr("href"), { data: { title: document.title } , success: function(data) {
			$elem.replaceWith(data.output);
		}});

		return false;
	});
	
	$(".widgets-favorite-entity-delete").on("click", function() {
		var $elem = $(this);
		elgg.action($elem.attr("href"), { success: function() {
			$elem.parent().hide();
		}});
		return false;
	});
}

elgg.register_hook_handler('init', 'system', widget_favorite_init);