<?php
/**
 * Causes function library
 */

/**
 * Prepare the add/edit form variables
 *
 * @param ElggObject $cause
 * @return array
 */
function causes_prepare_form_vars($cause = null, 
                                 $item_guid   = 0, 
                                 $referrer    = null, 
                                 $description = null) {
	// input names => defaults
	switch ($section) {
		case 'Summary':
		    break;
		case 'Details':
		    break;
		case 'Escallation':
		    break;
		case 'Ownership':
		    break;
		case 'Discussion':
		    break;
		case 'Gallery':
		    break;
		case 'Reports':
		    break;
		default:
				$values = array(
				'title' => '',
		        'description' => $description,
				'cause_number' => $cause->number,
				'observer' => '',
				'aspect' => 'cause',
		        'referrer' => $referrer,
				'access_id' => ACCESS_DEFAULT,
				'write_access_id' => ACCESS_DEFAULT,
				'tags' => '',
				'container_guid' => elgg_get_page_owner_guid(),
				'guid' => null,
				'entity' => $cause,
				'item_guid' => $item_guid,
				'asset' => get_entity($item_guid)->asset,
				'root_cause' => $cause->root_cause,
			);
			break;
		
	}
    if ($cause) {
		foreach (array_keys($values) as $field) {
			if (isset($cause->$field)) {
				$values[$field] = $cause->$field;
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
 * Recurses the cause tree and adds the breadcrumbs for all ancestors
 *
 * @param ElggObject $cause cause entity
 */
function causes_prepare_parent_breadcrumbs($cause) {
	if ($cause && $cause->parent_guid) {
		$parents = array();
		$parent = get_entity($cause->parent_guid);
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
 * @param ElggEntity $container Container entity for the causes
 */
function causes_register_navigation_tree($container) {
	if (!$container) {
		return;
	}

	$top_causes = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'cause_top',
		'container_guid' => $container->getGUID(),
	));

	foreach ($top_causes as $cause) {
		elgg_register_menu_item('cause_nav', array(
			'name' => $cause->getGUID(),
			'text' => $cause->title,
			'href' => $cause->getURL(),
		));

		$stack = array();
		array_push($stack, $cause);
		while (count($stack) > 0) {
			$parent = array_pop($stack);
			$children = elgg_get_entities_from_metadata(array(
				'type' => 'object',
				'subtype' => 'cause',
				'metadata_name' => 'parent_guid',
				'metadata_value' => $parent->getGUID(),
			));
			
			foreach ($children as $child) {
				elgg_register_menu_item('causes_nav', array(
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

function causes_save_cause($title, $description, $status, $number, $asset, $tags, $access){

	$tagarray = string_to_tag_array($tags);

	$cause = new ElggObject();
	$cause->type = 'object';
	$cause->subtype = 'cause';
	$cause->title = $title;
	$cause->description = $description;
	$cause->status = $status;
	$cause->cause_number = $number;
    $cause->asset = $asset;
	$cause->access_id = $access;
	$cause->owner_guid = elgg_get_logged_in_user_guid();
    $cause->tags = $tagarray;

	$guid = $cause->save();
	if (!$guid) {return false;}

	// Now save description as an annotation
	$cause->annotate('cause', $description, $access);
	
	return true;
}

function cause_prepare_brief_view_vars($cause = null) {

	// input names => defaults
	$fields = array(
       'Title'       => $cause->title,
       'Description' => $cause->description,
       'Status'      => $cause->status,
       'Cause #'     => $cause->number,
       'Asset'       => $cause->asset,
//       'Tags'        => $cause->tags,
       'Root'        => $cause->root_cause,
	);

	if ($cause) {
		foreach (array_keys($fields) as $field) {
			if (isset($cause->$field)) {
				$values[$field] = $cause->$field;
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

