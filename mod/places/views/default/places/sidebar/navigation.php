<?php
/**
 * Navigation menu for a user's or a group's places
 *
 * @uses $vars['place'] place object if manually setting selected item
 */

// add the jquery treeview files for navigation
elgg_load_css('jquery.treeview');


$selected_place = elgg_extract('place', $vars, false);
if ($selected_place) {
	$url = $selected_place->getURL();
}

$title = elgg_echo('places:navigation');

places_register_navigation_tree(elgg_get_page_owner_entity());

$content = elgg_view_menu('places_nav', array('class' => 'places-nav'));
if (!$content) {
	$content = '<p>' . elgg_echo('places:none') . '</p>';
}

echo elgg_view_module('aside', $title, $content);

?>
<script>
require(['jquery', 'jquery.treeview'], function($) {
	$(function() {
		$(".places-nav").treeview({
			persist: "location",
			collapsed: true,
			unique: true
		});

<?php if ($selected_place) { ?>
		// if on a history place, we need to manually select the correct menu item
		// code taken from the jquery.treeview library
		var current = $(".places-nav a[href='<?php echo $url; ?>']");
		var items = current.addClass("selected").parents("ul, li").add( current.next() ).show();
		var CLASSES = $.treeview.classes;
		items.filter("li")
			.swapClass( CLASSES.collapsable, CLASSES.expandable )
			.swapClass( CLASSES.lastCollapsable, CLASSES.lastExpandable )
				.find(">.hitarea")
					.swapClass( CLASSES.collapsableHitarea, CLASSES.expandableHitarea )
					.swapClass( CLASSES.lastCollapsableHitarea, CLASSES.lastExpandableHitarea );
<?php } ?>

	});

});

</script>
