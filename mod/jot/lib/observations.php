<?php
/**
 * Observations function library
 */

/**
 * Prepare the add/edit form variables
 *
 * @param ElggObject $observation
 * @return array
 */
function observations_prepare_form_vars($observation = null, 
		                                $item_guid   = 0, 
		                                $referrer    = null, 
		                                $description = null,
										$section     = null) {
	// input names => defaults
	switch ($section) {
		case 'Summary':
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
			$values = array(
					'title' => '',
			        'description' => $description,
					'observation_type' => '',
					'number' => '',
					'observer' => elgg_get_logged_in_user_guid(),
					'moment' => $observation->time_created,
					'aspect' => 'observation',
			        'referrer' => $referrer,
					'access_id' => ACCESS_DEFAULT,
					'write_access_id' => ACCESS_DEFAULT,
					'tags' => '',
					'container_guid' => elgg_get_page_owner_guid(),
					'guid' => null,
					'entity' => $observation,
					'item_guid' => $item_guid,
					'asset' => get_entity($item_guid)->asset,
				);
			break;
		
	}
	
    if ($observation) {
		foreach (array_keys($values) as $field) {
			if (isset($observation->$field)) {
				$values[$field] = $observation->$field;
			}
		}
	}

	if (elgg_is_sticky_form('jot')) {
		$sticky_values = elgg_get_sticky_values('jot');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

//	elgg_clear_sticky_form('jot');

	return $values;
}

/**
 * Recurses the observation tree and adds the breadcrumbs for all ancestors
 *
 * @param ElggObject $observation observation entity
 */
function observations_prepare_parent_breadcrumbs($observation) {
	if ($observation && $observation->parent_guid) {
		$parents = array();
		$parent = get_entity($observation->parent_guid);
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
 * Register the navigation menu
 * 
 * @param ElggEntity $container Container entity for the observations
 */
function observations_register_navigation_tree($container) {
	if (!$container) {
		return;
	}

	$top_observations = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'observation_top',
		'container_guid' => $container->getGUID(),
	));

	foreach ($top_observations as $observation) {
		elgg_register_menu_item('observation_nav', array(
			'name' => $observation->getGUID(),
			'text' => $observation->title,
			'href' => $observation->getURL(),
		));

		$stack = array();
		array_push($stack, $observation);
		while (count($stack) > 0) {
			$parent = array_pop($stack);
			$children = elgg_get_entities_from_metadata(array(
				'type' => 'object',
				'subtype' => 'observation',
				'metadata_name' => 'parent_guid',
				'metadata_value' => $parent->getGUID(),
			));
			
			foreach ($children as $child) {
				elgg_register_menu_item('observations_nav', array(
					'name' => $child->getGUID(),
					'text' => $child->title,
					'href' => $child->getURL(),
					'parent_name' => $parent->getGUID(),
				));
				array_push($stack, $child);
			}
		}
	}
}

function observations_save_observation($title, $description, $status, $number, $asset, $tags, $access){

	$tagarray = string_to_tag_array($tags);

	$observation = new ElggObject();
	$observation->type = 'object';
	$observation->subtype = 'observation';
	$observation->title = $title;
	$observation->description = $description;
	$observation->status = $status;
	$observation->observation_number = $number;
    $observation->asset = $asset;
	$observation->access_id = $access;
	$observation->owner_guid = elgg_get_logged_in_user_guid();
    $observation->tags = $tagarray;

	$guid = $observation->save();
	if (!$guid) {return false;}

	// Now save description as an annotation
	$observation->annotate('observation', $description, $access);
	
	return true;
}

function observation_prepare_brief_view_vars($observation = null,
                                             $section     = null) {
$asset = get_entity($observation->asset);
$asset_link = elgg_view('output/url', array(
   'href' => "market/view/$asset->guid",
   'text' => $asset->title,
));

	// input names => defaults
	switch ($section) {
		case 'Summary':
			$fields = array(
	       'Title'         => $observation->title,
	       'Description'   => $observation->description,
	       'Status'        => $observation->status,
	       'Observation #' => $observation->number,
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
	       'Title'         => $observation->title,
	       'Description'   => $observation->description,
	       'Status'        => $observation->status,
	       'Observation #' => $observation->number,
	       'Asset'         => $asset_link,
	       'Tags'          => $observation->tags,
		);
			break;
		
	}

	if ($observation) {
		foreach (array_keys($fields) as $field) {
			if (isset($observation->$field)) {
				$values[$field] = $observation->$field;
			}
		}
	}

	if (elgg_is_sticky_form('jot')) {
		$sticky_values = elgg_get_sticky_values('jot');
		foreach ($sticky_values as $key => $value) {
			$fields[$key] = $value;
		}
	}

	elgg_clear_sticky_form('jot');

	return $fields;
}

