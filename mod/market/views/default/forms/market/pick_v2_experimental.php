market\views\default\forms\market\pick.php
<?php
/**
 * QuebX pick item form
 * E X P E R I M E N T A L
 * Creating an item selection using the menu filter.  
 * Currently very rough. Clicking a tab opens a new page.  Not the desired action.
 * Try to redesign using javascript and layering.
 * Used by:
 	*  Edit receipt (jot\views\default\forms\transfers\edit.php)
 	*  
 	*  
 	*  
 **/

$access_id          = elgg_extract('access_id', $vars, ACCESS_PUBLIC);
$guid               = elgg_extract('guid', $vars, null);
$selected_item      = elgg_extract('item', $vars, false);
$element_type       = elgg_extract('element_type', $vars, null);
$container_guid     = elgg_extract('container_guid', $vars, null);
$content_options    = elgg_extract('content_options', $vars, null);
$submit_label       = elgg_echo('Pick');
$owner              = elgg_get_page_owner_entity();
$owner_guid         = $owner->guid;
if ($selected_item) {
	$url = $selected_item->getURL();
}
if (!$owner_guid) {
	$owner_guid = elgg_get_logged_in_user_guid();
}
$container     = get_entity($container_guid);
$container_type = $container->getsubtype();
/*
elgg_register_library('elgg:containers', elgg_get_plugins_path() . 'market/lib/market.php');
elgg_load_library('elgg:containers');
echo elgg_dump($vars);
*/
elgg_load_css('jquery.treeview');

$containers = elgg_get_entities(array('type'=>'object','subtype'=>'market', 'owner_guid' => $owner_guid, ));

$linked_items = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'receipt_item',
	'relationship_guid' => $container_guid,
	'inverse_relationship' => true,
	'limit' => false,
));

if (!empty($linked_items[0])){
	$linked_item = $linked_items[0];
}

echo elgg_view('input/hidden', array('name' => 'element_type'  , 'value' => $element_type  ));
echo elgg_view('input/hidden', array('name' => 'access_id'     , 'value' => $access_id     ));
echo elgg_view('input/hidden', array('name' => 'item_guid'     , 'value' => $linked_item->guid));
echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));
echo elgg_view('input/hidden', array('name' => 'container_type', 'value' => $container_type));
echo elgg_view('input/hidden', array('name' => 'owner_guid'    , 'value' => $owner_guid    ));

//echo '$container->show_on_timeline:'.$container->show_on_timeline.'<br>';
echo $content_options;
echo '<div>';
// Build the item tree
	$top_containers = new ElggBatch('elgg_get_entities', array(
		'type' => 'object',
		'subtype' => 'market',
		'owner_guid' => $owner_guid,
		'limit' => false,
	));

	$containers = array();
	$depths = array();

	foreach ($top_containers as $container) {
		$containers[] = array(
			'guid' => $container->getGUID(),
			'title' => $container->title,
			'url' => $container->getURL(),
			'depth' => 0,
		);
		$depths[$container->guid] = 0;

		$stack = array();
		array_push($stack, $container);
		while (count($stack) > 0) {
			$parent = array_pop($stack);
			$children = new ElggBatch('elgg_get_entities_from_metadata', array(
				'type' => 'object',
				'subtype' => 'market',
				'metadata_name' => 'parent_guid',
				'metadata_value' => $parent->getGUID(),
				'limit' => false,
			));

			foreach ($children as $child) {
				$containers[] = array(
					'guid' => $child->getGUID(),
					'title' => $child->title,
					'url' => $child->getURL(),
					'parent_guid' => $parent->getGUID(),
					'depth' => $depths[$parent->guid] + 1,
				);
				$depths[$child->guid] = $depths[$parent->guid] + 1;
				array_push($stack, $child);
			}
		}
	}


if ($containers) {
	aasort($containers,"title");
	$sort_order = (int) '';

	foreach ($containers as $container) {
			$sort_order = $sort_order + 1;
			$link = elgg_view('output/url', array(
			      'text' => $container['title'],
			      'href' =>  'market/view/'.$container['guid']));
				$input = elgg_view('input/checkbox', array(
						   'id'=>$container['guid'], 
						   'value'=>$container['guid'], 
						   ));
				if ($container['guid'] == $linked_item['guid']){
					$input_radio = '<input type="radio" checked="checked" name="item_guid" value='. $container['guid'].'>'.$container['title'];
				}
				else {
					$input_radio = '<input type="radio" name="item_guid" value='. $container['guid'].'>'.$container['title'];				
				}
			elgg_register_menu_item('containers_checkboxes', array(
				'name' => $container['guid'],
				'text' => $input.$container['title'],
				'href' => false,
				'parent_name' => $container['parent_guid'],
			));
			
			elgg_register_menu_item('containers_radio', array(
				'name' => $container['guid'],
				'text' => $input_radio,
				'href' => false,
				'parent_name' => $container['parent_guid'],
				'sort_order' => $sort_order,
			));
		}
}
/*
$content = elgg_view_menu('containers_checkboxes', array('class' => 'containers-nav'));
if (!$content) {
	$content = '<p>' . elgg_echo('containers:none') . '</p>';
}
*/
//echo elgg_view_module('aside', $title, $content);

//$content_location = elgg_view_form('market/add/element', array("action" => "action/market/pick?element_type=item&item_guid=$item_guid&container_guid=$container_guid"));

$content_radio = elgg_view_menu('containers_radio', array('class' => 'containers-nav', 'sort_by' => 'sort_order'));
if (!$content_radio) {
	$content_radio = '<p>' . elgg_echo('containers:none') . '</p>';
}

//$content  = $content_location;
//$content .= "<div class=\"elgg-col elgg-col-1of3\">";
$content = 'E X P E R I M E N T A L<br>Link to item<br>';
$content .= elgg_view('market/menu', array('category' => $selected_category));
$content .= 'copied from market\pages\market\category.php';
$options = array(
		'types' => 'object',
		'subtypes' => 'market',
		'limit' => $num_items,
		'pagination' => true, // default = false
		'list_type_toggle' => true, // default = false
		'view_type' => 'list',   // custom option
		'metadata_name' => 'marketcategory',
		//* hypelist options *//
		'pagination_type' => 'infinite',
		'reversed' => true,
		'position' => 'both',
);

// Get a list of market posts in a specific category
if (!empty($category)) {
	$options['metadata_value'] = $selected_category;
	$content .= elgg_list_entities_from_metadata($options);
} else {
	$content .= elgg_list_entities($options);
}

$content .= $content_radio;

echo elgg_view_module('aside', $title, $content);

?>
<script>
require(['jquery', 'jquery.treeview'], function($) {
	$(function() {
		$(".containers-nav").treeview({
			persist: "location",
			collapsed: false,
			unique: true
		});

<?php if ($selected_container) { ?>
		// if in a container, we need to manually select the correct menu item
		// code taken from the jquery.treeview library
		var current = $(".containers-nav a[href='<?php echo $url; ?>']");
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
<?php

echo elgg_view('input/submit', array('value' => $submit_label)).'</p>';

echo '</div>';