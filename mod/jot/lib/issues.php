<?php
/**
 * Issues function library
 */

/**
 * Prepare the add/edit form variables
 *
 * @param ElggObject $issue
 * @return array
 */
function issues_prepare_form_vars($issue = null, 
                                  $item_guid = 0, 
                                  $referrer = null, 
                                  $description = null) {
	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'start_date' => '',
		'end_date' => '',
		'issue_type' => '',
		'issue_number' => '',
		'status' => '',
		'assigned_to' => '',
		'percent_done' => '',
		'work_remaining' => '',
		'aspect' => 'issue',
        'referrer' => $referrer,
        'description' => $description,
		'access_id' => ACCESS_DEFAULT,
		'write_access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $issue,
		'item_guid' => $item_guid,
	);

	if ($issue) {
		foreach (array_keys($values) as $field) {
			if (isset($issue->$field)) {
				$values[$field] = $issue->$field;
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
 * Recurses the issue tree and adds the breadcrumbs for all ancestors
 *
 * @param ElggObject $issue Issue entity
 */
function issues_prepare_parent_breadcrumbs($issue) {
	if ($issue && $issue->parent_guid) {
		$parents = array();
		$parent = get_entity($issue->parent_guid);
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
 * @param ElggEntity $container Container entity for the issues
 */
function issues_register_navigation_tree($container) {
	if (!$container) {
		return;
	}

	$top_issues = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'issue_top',
		'container_guid' => $container->getGUID(),
	));

	foreach ($top_issues as $issue) {
		elgg_register_menu_item('issues_nav', array(
			'name' => $issue->getGUID(),
			'text' => $issue->title,
			'href' => $issue->getURL(),
		));

		$stack = array();
		array_push($stack, $issue);
		while (count($stack) > 0) {
			$parent = array_pop($stack);
			$children = elgg_get_entities_from_metadata(array(
				'type' => 'object',
				'subtype' => 'issue',
				'metadata_name' => 'parent_guid',
				'metadata_value' => $parent->getGUID(),
			));
			
			foreach ($children as $child) {
				elgg_register_menu_item('issues_nav', array(
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

function issues_save_issue($title, $description, $status, $number, $asset, $tags, $access){

	$tagarray = string_to_tag_array($tags);

	$issue = new ElggObject();
	$issue->type = 'object';
	$issue->subtype = 'issue';
	$issue->title = $title;
	$issue->description = $description;
	$issue->status = $status;
	$issue->issue_number = $number;
    $issue->asset = $asset;
	$issue->access_id = $access;
	$issue->owner_guid = elgg_get_logged_in_user_guid();
    $issue->tags = $tagarray;

	$guid = $issue->save();
	if (!$guid) {return false;}

	// Now save description as an annotation
	$issue->annotate('issue', $description, $access);
	
	return true;
}
function issue_prepare_brief_view_vars($issue = null) {

	// input names => defaults
	$fields = array(
       'Title'       => $issue->title,
       'Description' => $issue->description,
       'Status'      => $issue->status,
       'Issue #'     => $issue->number,
       'Asset'       => get_entity($issue->asset)->title,
       'Tags'        => $issue->tags,
	);

	if ($issue) {
		foreach (array_keys($fields) as $field) {
			if (isset($issue->$field)) {
				$values[$field] = $issue->$field;
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

