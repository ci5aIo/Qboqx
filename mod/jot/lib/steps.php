<?php
/**
 * Steps function library
 */

/**
 * Prepare the add/edit form variables
 *
 * @param ElggObject $Step
 * @return array
 */
function steps_prepare_form_vars($Step = null, 
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
		case 'Escallation':
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
					'step_type' => '',
					'step_number' => '',
					'observer' => '',
					'aspect' => 'Step',
			        'referrer' => $referrer,
					'access_id' => ACCESS_DEFAULT,
					'write_access_id' => ACCESS_DEFAULT,
					'tags' => '',
					'container_guid' => elgg_get_page_owner_guid(),
					'guid' => null,
					'entity' => $Step,
					'item_guid' => $item_guid,
					'asset' => get_entity($item_guid)->asset,
				);
			break;
		
	}
	
    if ($Step) {
		foreach (array_keys($values) as $field) {
			if (isset($Step->$field)) {
				$values[$field] = $Step->$field;
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
 * Recurses the Step tree and adds the breadcrumbs for all ancestors
 *
 * @param ElggObject $Step Step entity
 */
function Steps_prepare_parent_breadcrumbs($Step) {
	if ($Step && $Step->parent_guid) {
		$parents = array();
		$parent = get_entity($Step->parent_guid);
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
 * @param ElggEntity $container Container entity for the Steps
 */
function Steps_register_navigation_tree($container) {
	if (!$container) {
		return;
	}

	$top_Steps = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'step_top',
		'container_guid' => $container->getGUID(),
	));

	foreach ($top_Steps as $Step) {
		elgg_register_menu_item('step_nav', array(
			'name' => $Step->getGUID(),
			'text' => $Step->title,
			'href' => $Step->getURL(),
		));

		$stack = array();
		array_push($stack, $Step);
		while (count($stack) > 0) {
			$parent = array_pop($stack);
			$children = elgg_get_entities_from_metadata(array(
				'type' => 'object',
				'subtype' => 'step',
				'metadata_name' => 'parent_guid',
				'metadata_value' => $parent->getGUID(),
			));
			
			foreach ($children as $child) {
				elgg_register_menu_item('steps_nav', array(
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

function Steps_save_Step($title, $description, $status, $number, $asset, $tags, $access){

	$tagarray = string_to_tag_array($tags);

	$Step = new ElggObject();
	$Step->type = 'object';
	$Step->subtype = 'step';
	$Step->title = $title;
	$Step->description = $description;
	$Step->status = $status;
	$Step->Step_number = $number;
    $Step->asset = $asset;
	$Step->access_id = $access;
	$Step->owner_guid = elgg_get_logged_in_user_guid();
    $Step->tags = $tagarray;

	$guid = $Step->save();
	if (!$guid) {return false;}

	// Now save description as an annotation
	$Step->annotate('step', $description, $access);
	
	return true;
}

function Step_prepare_brief_view_vars($Step = null) {
$asset = get_entity($Step->asset);
$asset_link = elgg_view('output/url', array(
   'href' => "market/view/$asset->guid",
   'text' => $asset->title,
));

	// input names => defaults
	$fields = array(
       'Title'         => $Step->title,
       'Description'   => $Step->description,
       'Status'        => $Step->status,
       'Step #'        => $Step->number,
       'Asset'         => $asset_link,
       'Tags'          => $Step->tags,
	);

	if ($Step) {
		foreach (array_keys($fields) as $field) {
			if (isset($Step->$field)) {
				$values[$field] = $Step->$field;
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

