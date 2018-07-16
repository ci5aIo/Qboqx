<?php
/**
 * Exerpt from /engine/lib/navigation.php
 * Functions for managing menus and other navigational elements
 */

/**
 * Convenience function for registering a button to title menu
 *
 * The URL must be $handler/$name/$guid where $guid is the guid of the page owner.
 * The label of the button is "$handler:$name" so that must be defined in a
 * language file.
 *
 * This is used primarily to support adding an add content button
 *
 * @param string $handler The handler to use or null to autodetect from context
 * @param string $name    Name of the button
 * @return void
 * @since 1.8.0
 */

//namespace quebx;
 
 
 function quebx_register_title_button($handler = null, $name = 'add') {
	if (elgg_is_logged_in()) {

		if (!$handler) {
			$handler = elgg_get_context();
		}

		$owner = elgg_get_page_owner_entity();
		if (!$owner) {
			// no owns the page so this is probably an all site list page
			$owner = elgg_get_logged_in_user_entity();
		}
		if ($owner && $owner->canWriteToContainer()) {
			$guid = $owner->getGUID();
			elgg_register_menu_item('title', array(
				'name' => $name,
				'href' => "$handler/$name/$guid",
				'text' => elgg_echo("$handler:$name"),
				'link_class' => 'elgg-button elgg-button-action',
			));
		}
	}
}

elgg_register_event_handler('init', 'system', 'elgg_nav_init');

function quebx_task_tabs($vars, $selected = 'summary') {
	$selected = $vars['this_section'];
	$title     = $vars['title'];
	$this_guid = $vars['guid'];

	//set the url
	$url = elgg_get_site_url() . "task/view";
	
	$sections = array();
	$sections[] = 'Profile';
	$sections[] = 'Summary';
	$sections[] = 'Details';
	$sections[] = 'Documentation';
	$sections[] = 'Ownership';
	$sections[] = 'Discussion';
	$sections[] = 'Gallery';
	$sections[] = 'Dependencies';
	$sections[] = 'Reports';
	$sections[] = 'Timeline';
	
	$tabs = array();
	
	foreach ($sections as $section) {
		$tabs[] = array(
			'title' => elgg_echo("$section"),
			'url' => "$url/$this_guid/$section",
			'selected' => $section == $selected,
		);
	}
	return $tabs;
}
/**
 * Get occupied subcategories
 * 
 */
function quebx_get_occupied_subcategories($category) {

/**v1*****************************/
    if (!empty($category)){
        $container = $category;
    }
    else {
        $container = elgg_get_site_entity();
    }

    $count = hypeJunction\Categories\get_subcategories($container->guid, array(
    	'count' => true,
    		));
    
    $categories = hypeJunction\Categories\get_subcategories($container->guid);

    // get all items
    $items = elgg_get_entities(array('type'=>'object',
                                     'subtype'=> 'market',
                                     'limit' => 0,
                             ));
    // determine the unique categories that the items occupy
    $categorized_items = array();
    foreach($items as $item){
        $item_categories= hypeJunction\Categories\get_entity_categories($item->guid);
        $item_category_guids[] = $item_categories[0]['guid'];
    }
    foreach($item_category_guids as $key=>$value){
        if ($value == ''){
            unset($item_category_guids[$key]);
        }
    }
    $occupied_categories = array_unique($item_category_guids);
    foreach($occupied_categories as $key => $leaf){  
        $tree[] = hypeJunction\Categories\get_hierarchy($leaf, true, true);
    }
    foreach($tree as $branch){
//         $key = array_search($container->guid, $branch);
//         if ($key){
//             $root[] = $branch[$key];
//             continue;
//         }
         $root[] = $branch[0];
    }
    $roots = array_unique($root);
    
    $dbprefix = elgg_get_config('dbprefix');
    $options = array(
      'guids'    => $roots,
      'joins' => array("INNER JOIN {$dbprefix}objects_entity o ON (e.guid = o.guid)"),
      'order_by' => 'o.title'
    );
  
    $categories  = elgg_get_entities_from_metadata($options);
    
    return $categories;

}

function quebx_get_occupied_subcategories_v2($root, $owner) {
/*v2**/

$dbprefix = elgg_get_config('dbprefix');
if (!empty($root)){
        $container = get_entity($root);
    }
    else {
        $container = elgg_get_site_entity();
    }
$options = array('count' => true,
                 'container_guids' => (HYPECATEGORIES_GROUP_CATEGORIES && elgg_instanceof($page_owner, 'group')) ? $page_owner->guid : null,);
if (!empty($owner)){
    $options['wheres'] = array('e.owner_guid = '.$owner);
}
$categories = hypeJunction\Categories\get_subcategories($container->guid);
    foreach ($categories as $subcategory){
        $subcategories[] = array('guid'  => (int) $subcategory->guid,
                                 'items' => (int) quebx_get_filed_items($subcategory->guid, $options),);
    }
    if (!empty($subcategories)){
        foreach ($subcategories as $key=>$subcategory){
            if ($subcategory['items'] == 0){
                unset($subcategories[$key]);
            }
        }
    }
    return $subcategories;
}


function quebx_get_occupied_albums($root, $owner) {
/*v1**/

$dbprefix = elgg_get_config('dbprefix');
if (!empty($root)){
        $container = get_entity($root);
    }
    else {
        $container = elgg_get_site_entity();
    }
$options = array('count' => true,
                 'container_guids' => (HYPECATEGORIES_GROUP_CATEGORIES && elgg_instanceof($page_owner, 'group')) ? $page_owner->guid : null,);
if (!empty($owner)){
    $options['wheres'] = array('e.owner_guid = '.$owner);
}

//$albums = hypeJunction\Categories\get_subcategories($container->guid);
$albums = elgg_get_entities(array(
			'types' => 'object',
			'subtypes' => array(hjAlbum),
			'full_view' => false,
			'list_type' => get_input('list_type', 'gallery'),
			'list_type_toggle' => true,
			'gallery_class' => 'gallery-photostream',
			'pagination' => true,
			'limit' => get_input('limit', 20),
			'offset' => get_input('offset-albums', 0),
			'offset_key' => 'offset-albums',
            'count'      => true,
		));
    foreach ($albums as $subcategory){
        $subcategories[] = array('guid'  => (int) $subcategory->guid,
                                 'items' => (int) hypeJunction\Gallery\get_files($options),);
    }
    if (!empty($subcategories)){
        foreach ($subcategories as $key=>$subcategory){
            if ($subcategory['items'] == 0){
                unset($subcategories[$key]);
            }
        }
    }
    return $subcategories;
}
/**
 * Produce the navigation tree
 *
 * @param ElggEntity $container Container entity for the pages
 *
 * @return array
 */
function quebx_get_navigation_tree($container) {
	if (!elgg_instanceof($container)) {
		return;
	}

	$top_pages = new ElggBatch('elgg_get_entities', array(
		'type' => 'object',
		'subtype' => 'market',
		'container_guid' => $container->getGUID(),
		'limit' => false,
	));

	/* @var ElggBatch $top_pages Batch of top level pages */

	$tree   = array();
	$depths = array();
	$stack  = array();

	foreach ($top_pages as $page) {
		$tree[] = array(
			'guid' => $page->getGUID(),
			'title' => $page->title,
			'url' => $page->getURL(),
			'depth' => 0,
		);
		$depths[$page->guid] = 0;

		array_push($stack, $page);
		while (count($stack) > 0) {
			$parent = array_pop($stack);
			$item_guid = $parent->getGUID();
			$documents = new ElggBatch('elgg_get_entities_from_relationship', array(
				'type' => 'object',
				'relationship' => 'document',
				'relationship_guid' => $item_guid,
				'inverse_relationship' => true,
				'limit' => false,
			));
			$tasks = new ElggBatch('elgg_get_entities_from_relationship', array(
				'type' => 'object',
				'relationship' => 'task',
				'relationship_guid' => $item_guid,
				'inverse_relationship' => true,
				'limit' => false,
				));
			$components = new ElggBatch('elgg_get_entities_from_relationship', array(
				'type' => 'object',
				'relationship' => 'component',
				'relationship_guid' => $item_guid,
			    'inverse_relationship' => true,
				'limit' => false,
			));
			$accessories = new ElggBatch('elgg_get_entities_from_relationship', array(
				'type' => 'object',
				'relationship' => 'accessory',
				'relationship_guid' => $item_guid,
			    'inverse_relationship' => true,
				'limit' => false,
			));
			$parent_items = new ElggBatch('elgg_get_entities_from_relationship', array(
				'type' => 'object',
				'relationship' => 'accessory',
				'relationship_guid' => $item_guid,
			    'inverse_relationship' => false,
				'limit' => false,
			));
			$containers = new ElggBatch('elgg_get_entities_from_relationship', array(
				'type' => 'object',
				'relationship' => 'component',
				'relationship_guid' => $item_guid,
			    'inverse_relationship' => false,
				'limit' => false,
			));
			$issues = new ElggBatch('elgg_get_entities_from_relationship', array(
				'type' => 'object',
				'relationship' => 'issue',
				'relationship_guid' => $item_guid,
				'inverse_relationship' => true,
				'limit' => false,
			));
			$observations = new ElggBatch('elgg_get_entities_from_relationship', array(
				'type' => 'object',
				'relationship' => 'observation',
				'relationship_guid' => $item_guid,
				'inverse_relationship' => true,
				'limit' => false,
			));
			
/*
 * Build the tree 
 */	
			if($components){
				$header = $tree[] = array(
						'title'  => 'Components',
						'depth'  => 1,
						'header' => true,
						);
//				array_push($stack, $header);
				foreach ($components as $i) {
					$tree[] = array(
						'guid' => $i->getGUID(),
						'title' => $i->title,
						'url' => $i->getURL(),
						'parent_guid' => $item_guid,
						'header'      => false,
						'depth' => 2,
					    );
					$depths[$i->guid] = 2;
					array_push($stack, $i);
				}
			}
			if($accessories){
				$header = $tree[] = array(
						'title'  => 'Accessories',
						'depth'  => 1,
						'header' => true,
						);
//				array_push($stack, $header);
				foreach ($accessories as $i) {
					$tree[] = array(
						'guid' => $i->getGUID(),
						'title' => $i->title,
						'url' => $i->getURL(),
						'parent_guid' => $item_guid,
						'header'      => false,
						'depth' => 2,
					    );
					$depths[$i->guid] = 2;
					array_push($stack, $i);
				}
			}
 			if($documents){
				$header = $tree[] = array(
						'section'  => 'Documents',
						'depth'  => $depths[$parent->guid] + 1,
						'header' => true,
						);
//				array_push($stack, $header);
				foreach ($documents as $i) {
					$tree[] = array(
						'guid'        => $i->getGUID(),
						'title'       => $i->title,
						'url'         => $i->getURL(),
						'parent_guid' => $item_guid,
						'section'     => 'Documents',
						'header'      => false,
					    'depth'       => $depths[$parent->guid] + 2,
					    );
					$depths[$i->guid] = 2;
					array_push($stack, $i);
				}
			}
			
			if($observations){
				$header = $tree[] = array(
						'section'       => 'Observations',
						'depth'       => $depths[$parent->guid] + 1,
						'header'      => true,
						);
//				array_push($stack, $header);
				foreach ($observations as $i) {
					$tree[] = array(
						'guid'        => $i->getGUID(),
						'title'       => $i->title,
						'url'         => $i->getURL(),
						'parent_guid' => $item_guid,
						'section'     => 'Observations',
						'header'      => false,
					    'depth'       => $depths[$parent->guid] + 2,
					    );
					$depths[$i->guid] = $depths[$parent->guid] + 2;
					array_push($stack, $i);
				}
			}
		}
	}
	return $tree;
}

/**
 * Register the navigation menu
 *
 * @param ElggEntity $container Container entity for the pages
 */
function quebx_register_navigation_tree($container) {
	$pages = quebx_get_navigation_tree($container);
	if ($pages) {
		foreach ($pages as $page) {
			if ($page->header == true){
//				elgg_echo ($page->title);
		     }
			else {
				elgg_register_menu_item('items_nav', array(
					'name' => $page['guid'],
					'text' => $page['title'],
					'href' => $page['url'],
					'parent_name' => $page['parent_guid'],
				));
			}
		}
	}
}

function quebx_get_lineage($category_guid, $params = array()){
    $downsream_guids[] = $category_guid;
    $downstream = hypeJunction\Categories\get_subcategories($category_guid);
    foreach ($downstream as $category){
        $downstream_guids[] = $category->guid;
    }
}
/**
 * Get entities filed under this category
 *
 * @param int $category_guid GUID of the category
 * @param array $params Additional parameters to be passed to the getter function
 * @return array Array of filed items
 */

function quebx_get_filed_items ($category_guid, $params = array()) {

	$defaults = array(
		'types' => elgg_get_config('taxonomy_types'),
		'subtypes' => elgg_get_config('taxonomy_subtypes'),
		'relationship' => HYPECATEGORIES_RELATIONSHIP,
		'inverse_relationship' => true
	);

	$params = array_merge($defaults, $params);

	$params['relationship_guid'] = $category_guid;

	return elgg_get_entities_from_relationship($params);
}