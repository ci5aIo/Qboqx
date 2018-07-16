<?php

function shelf_count_items (){
    $owner_guid = elgg_get_logged_in_user_guid();
    $file = new ElggFile;
    $file->owner_guid = $owner_guid;
    $file->setFilename("shelf.json");
    if ($file->exists()) {
    	$file->open('read');
    	$json = $file->grabFile();
    	$file->close();
    }
    
    $data = json_decode($json, true);
    
    $count = count($data);
    return $count;
}

/**
 * Determine whether a container is open
 *
 * @param int    $entity_guid     Entity guid
 * @param int    $owner_guid      Defaults to logged in user.
 *
 * @return bool
 */
function shelf_container_is_open($entity_guid, $owner_guid = null) {
	global $CONFIG;

	if (!$owner_guid && !($owner_guid = elgg_get_logged_in_user_guid())) {
		return false;
	}

	$entity_guid = sanitize_int($entity_guid);
	$owner_guid = sanitize_int($owner_guid);

	$sql = "SELECT t1.id FROM {$CONFIG->dbprefix}metadata t1" .
			" JOIN {$CONFIG->dbprefix}metastrings t2 ON t1.name_id = t2.id" .
			" JOIN {$CONFIG->dbprefix}metastrings t3 ON t1.value_id = t3.id" .
			" WHERE t1.owner_guid = $owner_guid AND t1.entity_guid = $entity_guid" .
			" AND t2.string = 'container_state'" .
			" AND t3.string = 'open'";

	if (get_data_row($sql)) {
		return true;
	}

	return false;
}

/**
 * Determine which container is open
 *
 * @param int    $entity_guid     Entity guid
 *
 * @return guid
 */
function shelf_get_open_container($owner_guid = null) {
	global $CONFIG;

	if (!$owner_guid && !($owner_guid = elgg_get_logged_in_user_guid())) {
		return false;
	}

	$owner_guid = sanitize_int($owner_guid);

	$sql = "SELECT t1.guid FROM {$CONFIG->dbprefix}entities t1" .
	        " JOIN {$CONFIG->dbprefix}metadata              t2 ON t1.guid     = t2.entity_guid" .
			" JOIN {$CONFIG->dbprefix}metastrings           t3 ON t2.name_id  = t3.id" .
			" JOIN {$CONFIG->dbprefix}metastrings           t4 ON t2.value_id = t4.id" .
			" WHERE t1.owner_guid = $owner_guid" .
			"   AND t1.type       = 'object'" .
			"   AND t3.string     = 'container_state'" .
			"   AND t4.string     = 'open'";

	if (get_data_row($sql)) {
		$entity_data = get_data_row($sql);
		return $entity_data->guid ;
	}

	return false;
}


/**
 * Returns a string of rendered entities.
 *
 * Displays list of entities with formatting specified by the entity view.
 *
 * @tip Pagination is handled automatically.
 *
 * @internal This also provides the views for elgg_view_annotation().
 *
 * @internal If the initial COUNT query returns 0, the $getter will not be called again.
 *
 * @param array    $options Any options from $getter options plus:
 *                   full_view => BOOL Display full view of entities (default: false)
 *                   list_type => STR 'list' or 'gallery'
 *                   list_type_toggle => BOOL Display gallery / list switch
 *                   pagination => BOOL Display pagination links
 *                   no_results => STR Message to display when there are no entities
 *
 * @param callback $getter  The entity getter function to use to fetch the entities.
 * @param callback $viewer  The function to use to view the entity list.
 *
 * @return string
 * @since 1.7
 * @see elgg_get_entities()
 * @see elgg_view_entity_list()
 */
function shelf_list_entities(array $options = array(), $getter = 'elgg_get_entities',
	$viewer = 'shelf_view_select_list') {

	global $autofeed;
	$autofeed = true;

	$offset_key = isset($options['offset_key']) ? $options['offset_key'] : 'offset';

	$defaults = array(
		'offset' => (int) max(get_input($offset_key, 0), 0),
		'limit' => (int) max(get_input('limit', 10), 0),
		'full_view' => false,
		'list_type_toggle' => false,
		'pagination' => true,
		'no_results' => '',
	);

	$options = array_merge($defaults, $options);
	
    	$file        = new ElggFile;
        $file->owner_guid = elgg_get_logged_in_user_guid();
        $file->setFilename("shelf.json");
        if ($file->exists()) {
        	$file->open('read');
        	$json = $file->grabFile();
        	$file->close();
        }
        $data = json_decode($json, true);
        foreach($data as $key=>$contents){
            foreach($contents as $position=>$value){
                while (list($position, $value) = each($contents)){                                                                  
                    if ($position == 'guid'){                     
                        $guids[] = $value;
                        continue;             
                    }
                }
            }
        }

    $options['guids'] = $guids;
	$options['count'] = true;
	$count = call_user_func($getter, $options);

	if ($count > 0) {
		$options['count'] = false;
		$entities = call_user_func($getter, $options);
	} else {
		$entities = array();
	}

	$options['count'] = $count;

	return call_user_func($viewer, $entities, $options);
}

/**
 * Returns a rendered list of entities with pagination. This function should be
 * called by wrapper functions.
 *
 * @see elgg_list_entities()
 * @see list_user_friends_objects()
 * @see elgg_list_entities_from_metadata()
 * @see elgg_list_entities_from_relationships()
 * @see elgg_list_entities_from_annotations()
 *
 * @param array $entities Array of entities
 * @param array $vars     Display variables
 *      'count'            The total number of entities across all pages
 *      'offset'           The current indexing offset
 *      'limit'            The number of entities to display per page
 *      'full_view'        Display the full view of the entities?
 *      'list_class'       CSS class applied to the list
 *      'item_class'       CSS class applied to the list items
 *      'pagination'       Display pagination?
 *      'list_type'        List type: 'list' (default), 'gallery'
 *      'list_type_toggle' Display the list type toggle?
 *      'no_results'       Message to display if no results
 *
 * @return string The rendered list of entities
 * @access private
 */
function shelf_view_select_list($entities, $vars = array(), $offset = 0, $limit = 10, $full_view = true,
$list_type_toggle = true, $pagination = true) {

	if (!is_int($offset)) {
		$offset = (int)get_input('offset', 0);
	}

	// list type can be passed as request parameter
	$list_type = elgg_extract('list_type', $vars, 'list');
//	$list_type = get_input('list_type', 'list');

	if (is_array($vars)) {
		// new function
		$defaults = array(
			'items' => $entities,
			'list_class' => 'elgg-list-entity',
			'full_view' => true,
			'pagination' => true,
			'list_type' => $list_type,
			'list_type_toggle' => false,
			'offset' => $offset,
			'limit' => null,
		);

		$vars = array_merge($defaults, $vars);

	} else {
		// old function parameters
		elgg_deprecated_notice("Please update your use of elgg_view_entity_list()", 1.8);

		$vars = array(
			'items' => $entities,
			'count' => (int) $vars, // the old count parameter
			'offset' => $offset,
			'limit' => (int) $limit,
			'full_view' => $full_view,
			'pagination' => $pagination,
			'list_type' => $list_type,
			'list_type_toggle' => $list_type_toggle,
			'list_class' => 'elgg-list-entity',
		);
	}

	if (!$vars["limit"] && !$vars["offset"]) {
		// no need for pagination if listing is unlimited
		$vars["pagination"] = false;
	}
return elgg_view('page/components/list_select', $vars);
/*	Switch($list_type){
	    case 'gallery':
	        return elgg_view('page/components/gallery', $vars);
	        break;
	    case 'list':
	        return elgg_view('page/components/list', $vars);
	    default:
	        return elgg_view('page/components/gallery', $vars);
	        break;
	}*/	
}