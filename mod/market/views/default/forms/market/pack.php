<?php
/**
 * QuebX container form
 **/

$access_id          = elgg_extract('access_id', $vars, ACCESS_PUBLIC);
$guid               = elgg_extract('guid', $vars, null);
$selected_container = elgg_extract('location', $vars, false);
$element_type       = get_input('element_type');
$container_guid     = get_input('container_guid');
$submit_label       = elgg_echo('Pack');
$owner              = elgg_get_page_owner_entity();
$owner_guid         = $owner->guid;
if ($selected_container) {
	$url = $selected_container->getURL();
}
if (!$owner_guid) {
	$owner_guid = elgg_get_logged_in_user_guid();
}
$container     = get_entity($container_guid);
/*
elgg_register_library('elgg:containers', elgg_get_plugins_path() . 'market/lib/market.php');
elgg_load_library('elgg:containers');
*/
elgg_load_css('jquery.treeview');


$containers = elgg_get_entities(array('type'=>'object','subtype'=>'market', 'owner_guid' => $owner_guid, ));

echo elgg_view('input/hidden', array('name' => 'element_type'  , 'value' => $element_type  ));
echo elgg_view('input/hidden', array('name' => 'access_id'     , 'value' => $access_id     ));
echo elgg_view('input/hidden', array('name' => 'item_guid'     , 'value' => $guid          ));
echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));
echo elgg_view('input/hidden', array('name' => 'owner_guid'    , 'value' => $owner_guid    ));

echo '<div>';
// Build the location tree
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
	usort($containers, function($a, $b) {
		return strnatcmp(strtolower($a->title), strtolower($b->title));	
	});
//	usort($containers, "title");
	foreach ($containers as $container) {
			$link = elgg_view('output/url', array(
		      'text' => $container['title'],
		      'href' =>  'market/view/'.$container['guid']));
			$input = elgg_view('input/checkbox', array(
					   'id'=>$container['guid'], 
					   'value'=>$container['guid'], 
					   ));
			$input_radio = '<input type="radio" name="container" value='. $container['guid'].'>'.$container['title'];


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
		));
	}
}

$content = elgg_view_menu('containers_checkboxes', array('class' => 'containers-nav'));
if (!$content) {
	$content = '<p>' . elgg_echo('containers:none') . '</p>';
}

//echo elgg_view_module('aside', $title, $content);

$content_location = elgg_view_form('market/add/element', array("action" => "action/market/pack?element_type=contents&item_guid=$item_guid"));

$content_radio = elgg_view_menu('containers_radio', array('class' => 'containers-nav'));
if (!$content_radio) {
	$content_radio = '<p>' . elgg_echo('containers:none') . '</p>';
}

$content  = $content_location;
//$content .= "<div class=\"elgg-col elgg-col-1of3\">";
$content .= $content_radio;
//$content .= "</div>";
echo elgg_view_module('aside', $title, $content);
//echo elgg_view_module('aside', $title, $content_radio);

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