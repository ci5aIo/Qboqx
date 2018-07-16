<?php
/**
 * Adapted from Pages function library
 */

/**
 * Prepare the add/edit form variables
 * Experimental - Altered pages_prepare_form_vars() for QuebX
 *
 * @param ElggObject $page
 * @return array
 */

//namespace quebx;
 
 function quebx_prepare_form_vars($item = null, $parent_guid = 0) {

	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'write_access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $item,
		'parent_guid' => $parent_guid,
	);

	if ($item) {
		foreach (array_keys($values) as $field) {
			if (isset($item->$field)) {
				$values[$field] = $item->$field;
			}
		}
	}

	if (elgg_is_sticky_form('quebx')) {
		$sticky_values = elgg_get_sticky_values('quebx');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('quebx');

	return $values;
}
/**
 * Get first level subcategories for a given container
 * 
 * @param int $container_guid Container GUID or an array of container GUIDs
 * @param array $params Additional parameters to be passed to the getter function
 * @return array Array of categories
 */
function quebx_get_subcategories($container_guid = null, $params = array()) {

	$defaults = array(
		'types' => 'object',
		'subtypes' => HYPECATEGORIES_SUBTYPE,
//		'order_by_metadata' => array('name' => 'priority', 'direction' => 'ASC', 'as' => 'integer'),
		'limit' => 9999
	);

	$params = array_merge($defaults, $params);

	if (!$container_guid) {
		$site = elgg_get_site_entity();
		$container_guid = $site->guid;
	}

	$params['container_guids'] = $container_guid;

	//return elgg_get_entities($params);
	return elgg_get_entities_from_metadata($params);
}
function quebx_get_object_by_title($subtype, $title)
{
    global $CONFIG;
    
    $query ="SELECT e.* from {$CONFIG->dbprefix}entities e ".
            "JOIN {$CONFIG->dbprefix}objects_entity o ON e.guid=o.guid ".
            "WHERE e.subtype={$subtype} ".
            "AND o.title=\"{$title}\" ".
            "LIMIT 0, 1";
    $row = get_data_row($query);
    if ($row)
        return new ElggObject($row);
    else
        return false;
}

function quebx_create_group($options){
    
    	$group = new ElggGroup($options['group_guid']); // load if present, if not create a new group
    	if ($options['group_guid'] && !$group->canEdit()) {
    		register_error(elgg_echo("groups:cantedit"));
    		return true;
    	}
    	
    	$group->name    = $options['group_name'];
    	$group->subtype = $options['group_type'];
    	$group->aspect  = $options['aspect'];
    	
        Switch ($options['aspect']) {
        	case 'support':
        		$relationship = 'support_group_of';
        		break;
        	case 'supplier':
        		$relationship = 'supplier_of';
        		break;
        }
    	
    	// Set group tool options
    	$tool_options = elgg_get_config('group_tool_options');
    	if ($tool_options) {
    		foreach ($tool_options as $group_option) {
    			$option_toggle_name = $group_option->name . "_enable";
    			$option_default = $group_option->default_on ? 'yes' : 'no';
    			$group->$option_toggle_name = $option_toggle_name ?: $option_default;
    		}
    	}
    	
    	// Group membership - should these be treated with same constants as access permissions?
    	$is_public_membership = ($options['membership']) == ACCESS_PUBLIC;
    	$group->membership = $is_public_membership ? ACCESS_PUBLIC : ACCESS_PRIVATE;
    	
    	$group->setContentAccessMode(get_input('content_access_mode'));
    	
    	$group->save();
        $group_guid = $group->getguid();
        $owner_guid = (int) $options['owner_guid'] ?: elgg_get_logged_in_user_guid();

		elgg_create_river_item(array(
			'view' => 'river/group/create',
			'action_type' => 'create',
			'subject_guid' => $owner_guid,
			'object_guid' => $group_guid,
		));
		return $group;
}
/* function quebx_buildTree
 * @param array $elements
 * @param array $options['parent_id_column_name', 'children_key_name', 'id_column_name'] 
 * @param int $parentId
 * @return array
 * 
 * The algorithm is pretty simple:
   1. Take the array of all elements and the id of the current parent (initially 0/nothing/null/whatever).
   2. Loop through all elements.
   3. If the parent_id of an element matches the current parent id you got in 1., the element is a 
      child of the parent. Put it in your list of current children (here: $branch).
   4. Call the function recursively with the id of the element you have just identified in 3., 
      i.e. find all children of that element, and add them as children element.
   5. Return your list of found children.
 * One execution of this function returns a list of elements which are children of the given parent id. 
   Call it with quebx_buildTree($myArray, 1), it will return a list of elements which have the parent id 1. 
 * Initially this function is called with the parent id being 0, so elements without parent id are returned, 
   which are root nodes. The function calls itself recursively to find children of children.
 * source: https://stackoverflow.com/a/8587437/476
 * 
 * @Note 2018-02-24 - SAJ - List option not working as expected
 */

function quebx_buildTree(array $elements, 
		                       $parentId = 0, 
		                       $return   = 'array',
		                       $level    = 1,
		                       $listing)
    {
    $branch = array();
    foreach ($elements as $element) {
    	if ($element['container_guid'] == $parentId) {
    		$list[$element['guid']] = $element['title'];
    		$children = quebx_buildTree($elements, $element['guid'], $return, ++$level);
		    if ($children) {
		    	$element['contents'] = $children;
		    	$list[$element['guid']]['contents'] = $children;
		    }
		    $level = 1;
		    $branch[] = $element;
		}
	}
    Switch ($return){
    	case 'list':                //@DRAFT 2018-02-24 - SAJ - List option not working as expected
    		return $list;
    	case 'array':
    		return $branch;
    }
    
}
/*
 *  $data, $index, $parent_id, $ul_class, $li_class, $collapsible, $collapse_level
 * 
 */
function quebx_display_child_nodes($options){
	$data           = $options['data'];
	$index          = $options['index'];
	$parent_id      = $options['parent_id'];
	$ul_class       = $options['ul_class'];
	$li_class       = $options['li_class'];
	$collapsible    = $options['collapsible'];
	$collapse_level = $options['collapse_level'];
	$level          = $options['level'];
	$links          = $options['links'];
	static $children;
	$toggle_children = elgg_view('output/url',['text'=>'+',
	            		                           'title'=>'expand',
	            		                           'class'=>'elgg-button-submit-element hierarchy-expand-button']);
	if (isset($ul_class)){$list_class = "class = $ul_class";}
	if ($collapsible && $level >= $collapse_level){
		$state = "data-state = 'collapsed'";}
	if (isset($li_class)){$list_item_class = "class = $li_class";}
    if (isset($index[$parent_id])) {
    	$children .= "<ul $list_class data-level=$level $state>";
        foreach ($index[$parent_id] as $id) {
        	unset($child_toggle, $has_children);
        	$has_children = count($index[$id])>0;
        	if ($collapsible && $has_children){
        		$child_toggle    = $toggle_children;}
        	if ($collapsible && !$has_children){
        		if (isset($li_class)){
        			$list_item_class = "class = $li_class node-no-children";}
        		else {$list_item_class = "class = node-no-children";}
        	}
        	$title    = $data[$id]['title'];
        	$item_name = $links ? 
        	             elgg_view('output/url',['text'=>$title,'href'=>get_entity_url($id)]) : 
        	             $title;
            $children .= "<li $list_item_class>$child_toggle $item_name";
            $options['parent_id'] = $id;
            $options['level']     = ++$level;
            quebx_display_child_nodes($options);
            $level = 0;
        }
        $children .= "</li></ul>";
    }
    return $children;
}