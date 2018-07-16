<?php
/**
 * QuebX location form
 **/

$access_id      = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
//$guid           = elgg_extract('guid', $vars, null);
//$element_type   = get_input('element_type');
$container_guid = get_input('container_guid');
$container      = get_entity($container_guid);
$location       = elgg_extract('location', $vars, $container->location);
if (is_numeric($location)){
  $selected_place = get_entity($location);
}
$submit_label   = elgg_echo('Set');
$owner          = elgg_get_page_owner_entity();
$owner_guid     = $owner->guid;
if (!$owner_guid) {
	$owner_guid = elgg_get_logged_in_user_guid();
}
if ($selected_place) {
	$url = $selected_place->getURL();
}
elgg_register_library('elgg:places', elgg_get_plugins_path() . 'places/lib/places.php');
elgg_load_library('elgg:places');
elgg_load_css('jquery.treeview');

//$places = elgg_get_entities(array('type'=>'object','subtype'=>'place', 'owner_guid' => $owner_guid, ));

//echo elgg_view('input/hidden', array('name' => 'element_type'  , 'value' => $element_type  ));
echo elgg_view('input/hidden', array('name' => 'access_id'     , 'value' => $access_id     ));
//echo elgg_view('input/hidden', array('name' => 'item_guid'     , 'value' => $guid          ));
echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));
echo elgg_view('input/hidden', array('name' => 'owner_guid'    , 'value' => $owner_guid    ));

//echo elgg_dump($selected_place);
echo '<div>';
// Build the location tree
	$top_places = new ElggBatch('elgg_get_entities', array(
		'type' => 'object',
		'subtype' => 'place_top',
		'owner_guid' => $owner_guid,
		'limit' => false,
	));

	$places = array();
	$depths = array();

	foreach ($top_places as $place) {
		$places[] = array(
			'guid' => $place->getGUID(),
			'title' => $place->title,
			'url' => $place->getURL(),
			'depth' => 0,
		);
		
		$depths[$place->guid] = 0;
		$array = 0;

		$stack = array();
		array_push($stack, $place);
		while (count($stack) > 0) {
			$parent = array_pop($stack);
			$children = new ElggBatch('elgg_get_entities_from_metadata', array(
				'type' => 'object',
				'subtype' => 'place',
				'metadata_name' => 'parent_guid',
				'metadata_value' => $parent->getGUID(),
				'limit' => false,
			));

			foreach ($children as $child) {
				if ($child->getGUID() == $location){
					$places[] = array(
						'guid' => $child->getGUID(),
						'title' => $child->title,
						'url' => $child->getURL(),
						'parent_guid' => $parent->getGUID(),
						'depth' => $depths[$parent->guid] + 1,
						'checked'=> 'checked',
					);
				}
				else {
					$places[] = array(
						'guid' => $child->getGUID(),
						'title' => $child->title,
						'url' => $child->getURL(),
						'parent_guid' => $parent->getGUID(),
						'depth' => $depths[$parent->guid] + 1,
					);
				}
				$depths[$child->guid] = $depths[$parent->guid] + 1;
				array_push($stack, $child);
/*			$stack_level = count($stack) + 1;
echo '$array:'.$array.'<br>';
//echo '$parent: '.elgg_dump($parent).'<br>';
echo '$child->getGUID(): '.$child->getGUID().'<br>';
				if ($child->getGUID() == $location){
echo '$child->getGUID() == $location @ '.$stack_level.'<br>';
					$places[$array]['checked'] = 'checked';
				}
			
				$array = $array + 1;
*/			}
		}
//			echo elgg_dump($places);
	}
//		if (in_array($collection->string, $itemcollections)) {
//			$checkbox_options['checked'] = 'checked';
//		}
//echo elgg_dump($parent);	
if ($places) {
	foreach ($places as $place) {
			$link = elgg_view('output/url', array(
		      'text' => $place['title'],
		      'href' =>  'places/view/'.$place['guid']));
			$input = elgg_view('input/checkbox', array(
					   'id'=>$place['guid'], 
					   'value'=>$place['guid'], 
					   ));
			$input_radio = '<input type="radio"
			                       name="place" 
			                       value='  . $place['guid'];
	               if ($place['checked']){
			$input_radio .= ' checked='. $place['checked']; 	
			            }
			$input_radio .= '>'.$place['title'];


		elgg_register_menu_item('places_checkboxes', array(
			'name' => $place['guid'],
			'text' => $input.$place['title'],
			'href' => false,
			'parent_name' => $place['parent_guid'],
		));
		elgg_register_menu_item('places_radio', array(
			'name' => $place['guid'],
			'text' => $input_radio,
			'href' => false,
			'parent_name' => $place['parent_guid'],
		));
	}
}
/*
$content = elgg_view_menu('places_checkboxes', array('class' => 'places-nav'));
if (!$content) {
	$content = '<p>' . elgg_echo('places:none') . '</p>';
}
*/
//echo elgg_view_module('aside', $title, $content);

$content_location = elgg_view_form('places/add/element', array("action" => "action/places/set?element_type=location&item_guid=$item_guid"));

$content_radio    = elgg_view_menu('places_radio', array('class' => 'places-nav'));
if (!$content_radio) {
	$content_radio = '<p>' . elgg_echo('places:none') . '</p>';
}

$content  = $content_location;
$content .= $content_radio;
echo elgg_view_module('aside', $title, $content);
//echo elgg_view_module('aside', $title, $content_radio);

?>
<script>
require(['jquery', 'jquery.treeview'], function($) {
	$(function() {
		$(".places-nav").treeview({
			persist: "location",
			collapsed: false,
			unique: true
		});

<?php if ($selected_place) { ?>
		// if in a place, we need to manually select the correct menu item
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
<?php
echo elgg_view('input/submit', array('value' => $submit_label)).'</p>';

echo '</div>';