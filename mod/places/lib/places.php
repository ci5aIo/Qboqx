<?php
/**
 * places function library
 */

/**
 * Prepare the add/edit form variables
 *
 * @param ElggObject     $place
 * @param int            $parent_guid
 * @param ElggAnnotation $revision
 * @return array
 */
function places_prepare_form_vars($place = null, $parent_guid = 0, $revision = null) {

	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'address'         => '',
		'city'            => '',
		'state'           => '',
		'zip'             => '',
		'access_id' => ACCESS_DEFAULT,
		'write_access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $place,
		'parent_guid' => $parent_guid,
	);

	if ($place) {
		foreach (array_keys($values) as $field) {
			if (isset($place->$field)) {
				$values[$field] = $place->$field;
			}
		}
	}

	if (elgg_is_sticky_form('place')) {
		$sticky_values = elgg_get_sticky_values('place');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('place');

	// load the revision annotation if requested
	if ($revision instanceof ElggAnnotation && $revision->entity_guid == $place->getGUID()) {
		$values['description'] = $revision->value;
	}

	return $values;
}

/**
 * Recurses the places tree and adds the breadcrumbs for all ancestors
 *
 * @param ElggObject $place Page entity
 */
function places_prepare_parent_breadcrumbs($place) {
	if ($place && $place->parent_guid) {
		$parents = array();
		$parent = get_entity($place->parent_guid);
		while ($parent) {
			array_push($parents, $parent);
			$parent = get_entity($parent->parent_guid);
		}
		while ($parents) {
			$parent = array_pop($parents);
			elgg_push_breadcrumb($parent->title, $parent->getURL());
		}
	}
}

/**
 * Produce the navigation tree
 *
 * @param ElggEntity $container Container entity for the places
 *
 * @return array
 */
function places_get_navigation_tree($container) {
	if (!elgg_instanceof($container)) {
		return;
	}

	$top_places = new ElggBatch('elgg_get_entities', array(
		'type' => 'object',
		'subtype' => 'place_top',
		'container_guid' => $container->getGUID(),
		'limit' => false,
	));

	/* @var ElggBatch $top_places Batch of top level places */

	$tree = array();
	$depths = array();

	foreach ($top_places as $place) {
		$tree[] = array(
			'guid' => $place->getGUID(),
			'title' => $place->title,
			'url' => $place->getURL(),
			'depth' => 0,
		);
		$depths[$place->guid] = 0;

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
				$tree[] = array(
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
	return $tree;
}

/**
 * Register the navigation menu
 *
 * @param ElggEntity $container Container entity for the places
 */
function places_register_navigation_tree($container) {
	$places = places_get_navigation_tree($container);
	if ($places) {
		foreach ($places as $place) {
			elgg_register_menu_item('places_nav', array(
				'name' => $place['guid'],
				'text' => $place['title'],
				'href' => $place['url'],
				'parent_name' => $place['parent_guid'],
			));
		}
	}
}
function place_prepare_brief_view_vars($subtype = null,
                                       $entity  = null,
                                       $section = null) {
$asset = get_entity($entity->asset);
$container = get_entity($entity->container_guid);
$asset_link = elgg_view('output/url', array(
   'href' => "market/view/$asset->guid",
   'text' => $asset->title,
));
$container_link = elgg_view('output/url', array(
   'href' => "place/view/$container->guid",
   'text' => $container->title,
));
switch ($subtype){
	case 'place_top':
		switch ($entity->state){
			case  1: $state = 'Discover'; break;
			case  2: $state = 'Resolve' ; break;
			case  3: $state = 'Assigned'; $assigned_to = $entity->assigned_to; break;
			case  4: $state = 'Accept'  ; $assigned_to = $entity->assigned_to; break;
			case  5: $state = 'Complete'; $assigned_to = $entity->assigned_to; break;
			default: $state = 'Discover'; $assigned_to = $entity->assigned_to; break;
		}
		switch ($section) {
			case 'Summary':
				$fields = array(
		       'Title'         => $entity->title,
		       'Asset'         => $asset_link,
		       'Description'   => $entity->description,
		       'State'         => $state,
		       'Assigned to'   => $assigned_to,
			);
			    break;
			case 'Details':
			    break;
			case 'Resolution':
			    break;
			case 'Ownership':
			    break;
			case 'Discussion':
			    break;
			case 'Gallery':
			    break;
			default:
				$fields = array(
		       'Title'         => $entity->title,
		       'Description'   => $entity->description,
		       'Status'        => $entity->status,
		       'Observation #' => $entity->number,
		       'Asset'         => $asset_link,
		       'Tags'          => $entity->tags,
			);
				break;
		}
		break;
	case 'place':
		$fields = array(
	       'Title'       => $entity->title,
	       'Description' => $entity->description,
	       'Status'      => $entity->status,
	       'Issue #'     => $entity->number,
	       'Asset'       => $asset_link,
	       'Tags'        => $entity->tags,
		);
		break;
	}
	if ($entity) {
		foreach (array_keys($fields) as $field) {
			if (isset($entity->$field)) {
				$values[$field] = $entity->$field;
			}
		}
	}

	if (elgg_is_sticky_form('place')) {
		$sticky_values = elgg_get_sticky_values('place');
		foreach ($sticky_values as $key => $value) {
			$fields[$key] = $value;
		}
	}

	elgg_clear_sticky_form('place');
	
	aasort($fields, 'Title');

	return $fields;
}

function place_tabs($vars, $selected = 'Summary') {
	$selected  = $vars['this_section'];
	$this_guid = $vars['guid'];
	$aspect    = $vars['aspect'];
	$place     = get_entity($this_guid);
	$title     = $place->title;
	$url       = elgg_get_site_url() . "places/view";
		
	$sections  = array();

	Switch ($aspect){
		case 'facility':
			$sections[] = 'Summary';
			$sections[] = 'Details';
			$sections[] = 'Maintenance';
			$sections[] = 'Documentation';
			$sections[] = 'Ownership';
			$sections[] = 'Discussion';
			$sections[] = 'Gallery';
			$sections[] = 'Reports';
			break;
		case 'destination':
			$sections[] = 'Summary';
			$sections[] = 'Details';
//			$sections[] = 'Resolution';
			$sections[] = 'Ownership';
			$sections[] = 'Discussion';
			$sections[] = 'Gallery';
			$sections[] = 'Reports';
			break;
		default:
			$sections[] = 'Summary';
			$sections[] = 'Details';
			$sections[] = 'Maintenance';
			$sections[] = 'Ownership';
			$sections[] = 'Discussion';
			$sections[] = 'Gallery';
			$sections[] = 'Reports';
			break;
	}

	$tabs = array();
	
	foreach ($sections as $section) {
		$tabs[] = array(
			'title' => elgg_echo("$section"),
 		    'url'   => "$url/$this_guid/$title/$section",
			'selected' => $section == $selected,
		);
	}
	return $tabs;
}

function aasort (&$array, $key) {
	$sorter=array();
	$ret=array();
	reset($array);
	foreach ($array as $ii => $va) {
		$sorter[$ii]=$va[$key];
	}
	asort($sorter);
	foreach ($sorter as $ii => $va) {
		$ret[$ii]=$array[$ii];
	}
	$array=$ret;
}