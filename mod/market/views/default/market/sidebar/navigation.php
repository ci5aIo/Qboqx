<?php
/**
 * Navigation menu for a user's or a group's pages
 *
 * @uses $vars['page'] Page object if manually setting selected item
 */

 $tree_root = elgg_get_page_owner_entity();
 if(empty($tree_root)) {$tree_root = elgg_get_logged_in_user_entity();}
 $category = elgg_extract('marketcategory', $vars, 'stuff');
 
// add the jquery treeview files for navigation
elgg_load_css('jquery.treeview');

$selected_page = elgg_extract('market', $vars, false);
if ($selected_page) {
	$url = $selected_page->getURL();
}

$title = elgg_echo("market:mine");
//$title = elgg_echo("market:mine:$category");

quebx_register_navigation_tree($tree_root);

$content = elgg_view_menu('items_nav', array('class' => 'market-nav', 'show_section_headers' => false));
if (!$content) {
	$content = '<p>' . elgg_echo('pages:none') . '</p>';
}

echo elgg_view_module('aside', $title, $content);

?>
<script>
require(['jquery', 'jquery.treeview'], function($) {
	$(function() {
		$(".market-nav").treeview({
			persist: "location",
			collapsed: true,
			unique: true
		});

<?php if ($selected_page) { ?>
		// if on a history page, we need to manually select the correct menu item
		// code taken from the jquery.treeview library
		var current = $(".market-nav a[href='<?php echo $url; ?>']");
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
