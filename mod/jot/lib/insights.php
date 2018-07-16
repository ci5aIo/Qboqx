<?php
/**
 * insights function library
 */

/**
 * Prepare the add/edit form variables
 *
 * @param ElggObject $insight
 * @return array
 */
function insights_prepare_form_vars($insight = null, 
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
					'insight_type' => '',
					'number' => '',
					'observer' => elgg_get_logged_in_user_guid(),
					'aspect' => 'insight',
			        'referrer' => $referrer,
					'access_id' => ACCESS_DEFAULT,
					'write_access_id' => ACCESS_DEFAULT,
					'tags' => '',
					'container_guid' => elgg_get_page_owner_guid(),
					'guid' => null,
					'entity' => $insight,
					'item_guid' => $item_guid,
					'asset' => get_entity($item_guid)->asset,
				);
			break;
		
	}
	
    if ($insight) {
		foreach (array_keys($values) as $field) {
			if (isset($insight->$field)) {
				$values[$field] = $insight->$field;
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
 * Register the navigation menu
 * 
 * @param ElggEntity $container Container entity for the insights
 */
function insights_register_navigation_tree($container) {
	if (!$container) {
		return;
	}

	$top_insights = elgg_get_entities(array(
		'type'           => 'object',
		'subtype'        => 'insight_top',
		'container_guid' => $container->getGUID(),
	));

	foreach ($top_insights as $insight) {
		elgg_register_menu_item('insight_nav', array(
			'name' => $insight->getGUID(),
			'text' => $insight->title,
			'href' => $insight->getURL(),
		));

		$stack = array();
		array_push($stack, $insight);
		while (count($stack) > 0) {
			$parent = array_pop($stack);
			$children = elgg_get_entities_from_metadata(array(
				'type'           => 'object',
				'subtype'        => 'insight',
				'metadata_name'  => 'parent_guid',
				'metadata_value' => $parent->getGUID(),
			));
			
			foreach ($children as $child) {
				elgg_register_menu_item('insights_nav', array(
					'name'        => $child->getGUID(),
					'text'        => $child->title,
					'href'        => $child->getURL(),
					'parent_name' => $parent->getGUID(),
				));
				array_push($stack, $child);
			}
		}
	}
}

function insights_save_insight($title, $description, $status, $number, $asset, $tags, $access){

	$tagarray = string_to_tag_array($tags);

	$insight = new ElggObject();
	$insight->type = 'object';
	$insight->subtype = 'insight';
	$insight->title = $title;
	$insight->description = $description;
	$insight->status = $status;
	$insight->insight_number = $number;
    $insight->asset = $asset;
	$insight->access_id = $access;
	$insight->owner_guid = elgg_get_logged_in_user_guid();
    $insight->tags = $tagarray;

	$guid = $insight->save();
	if (!$guid) {return false;}

	// Now save description as an annotation
	$insight->annotate('insight', $description, $access);
	
	return true;
}

function insight_prepare_brief_view_vars($insight = null,
                                             $section     = null) {
$asset = get_entity($insight->asset);
$asset_link = elgg_view('output/url', array(
   'href' => "market/view/$asset->guid",
   'text' => $asset->title,
));

	// input names => defaults
	switch ($section) {
		case 'Summary':
			$fields = array(
	       'Title'         => $insight->title,
	       'Description'   => $insight->description,
	       'Status'        => $insight->status,
	       'insight #'     => $insight->number,
		);
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
			$fields = array(
	       'Title'         => $insight->title,
	       'Description'   => $insight->description,
	       'Status'        => $insight->status,
	       'insight #'     => $insight->number,
	       'Asset'         => $asset_link,
	       'Tags'          => $insight->tags,
		);
			break;
		
	}

	if ($insight) {
		foreach (array_keys($fields) as $field) {
			if (isset($insight->$field)) {
				$values[$field] = $insight->$field;
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

